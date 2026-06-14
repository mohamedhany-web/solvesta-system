<?php

namespace App\Services;

use App\Models\Client;
use App\Models\ContactRequest;
use App\Models\Contract;
use App\Models\Invoice;
use App\Models\Lead;
use App\Models\Project;
use App\Models\Sale;
use Illuminate\Support\Facades\DB;

class CommercialWorkflowService
{
    public function createLeadFromContactRequest(ContactRequest $contactRequest, ?int $assignedTo = null): Lead
    {
        if ($existing = Lead::where('contact_request_id', $contactRequest->id)->first()) {
            return $existing;
        }

        $lead = Lead::create([
            'reference_code' => Lead::generateReferenceCode(),
            'source' => 'website',
            'status' => 'new',
            'name' => $contactRequest->name,
            'email' => $contactRequest->email,
            'phone' => $contactRequest->phone,
            'company' => $contactRequest->company,
            'service_interest' => $contactRequest->subject,
            'notes' => $contactRequest->message,
            'assigned_to' => $assignedTo ?? auth()->id(),
            'created_by' => auth()->id(),
            'contact_request_id' => $contactRequest->id,
        ]);

        $contactRequest->update(['status' => 'in_progress']);

        return $lead;
    }

    public function convertLeadToSale(Lead $lead, int $assignedTo): Sale
    {
        if ($lead->converted_sale_id) {
            return $lead->convertedSale;
        }

        return DB::transaction(function () use ($lead, $assignedTo) {
            $client = $lead->converted_client_id
                ? Client::find($lead->converted_client_id)
                : Client::create([
                    'name' => $lead->name,
                    'email' => $lead->email,
                    'phone' => $lead->phone,
                    'company_name' => $lead->company,
                    'status' => 'active',
                    'notes' => 'أُنشئ تلقائياً من '.$lead->reference_code,
                ]);

            $sale = Sale::create([
                'lead_id' => $lead->id,
                'lead_source' => $lead->source,
                'client_id' => $client->id,
                'assigned_to' => $assignedTo,
                'product_service' => $lead->service_interest ?: 'طلب عام',
                'estimated_value' => $lead->estimated_budget ?? 0,
                'stage' => 'prospect',
                'qualification_status' => 'pending',
                'probability_percentage' => 25,
                'notes' => $lead->notes,
            ]);

            $lead->update([
                'status' => 'converted',
                'converted_client_id' => $client->id,
                'converted_sale_id' => $sale->id,
            ]);

            return $sale;
        });
    }

    public function qualifySale(Sale $sale, ?string $requirementSummary = null): Sale
    {
        $sale->update([
            'qualification_status' => 'qualified',
            'stage' => $sale->stage === 'lead' ? 'prospect' : $sale->stage,
            'requirement_summary' => $requirementSummary ?? $sale->requirement_summary,
            'probability_percentage' => max($sale->probability_percentage, 40),
        ]);

        return $sale->fresh();
    }

    public function disqualifySale(Sale $sale, string $reason): Sale
    {
        $sale->update([
            'qualification_status' => 'disqualified',
            'stage' => 'closed_lost',
            'lost_reason' => $reason,
            'probability_percentage' => 0,
            'actual_close_date' => now(),
        ]);

        return $sale->fresh();
    }

    public function createContractFromSale(Sale $sale): Contract
    {
        $existing = Contract::where('sale_id', $sale->id)->first();
        if ($existing) {
            return $existing;
        }

        $amount = (float) ($sale->actual_value ?? $sale->estimated_value ?? 0);

        return Contract::create([
            'contract_number' => Contract::generateContractNumber(),
            'title' => 'عقد — '.$sale->product_service,
            'description' => $sale->requirement_summary ?? $sale->notes,
            'client_id' => $sale->client_id,
            'sale_id' => $sale->id,
            'contract_type' => 'service',
            'start_date' => now(),
            'end_date' => now()->addMonths(6),
            'value' => $amount,
            'status' => 'draft',
            'created_by' => auth()->id(),
        ]);
    }

    public function createDepositInvoice(Contract $contract, float $percent = 50): Invoice
    {
        $existing = Invoice::where('contract_id', $contract->id)
            ->where('notes', 'like', '%دفعة مقدمة%')
            ->first();

        if ($existing) {
            return $existing;
        }

        $total = (float) ($contract->value ?? 0);
        $deposit = round($total * ($percent / 100), 2);

        return Invoice::create([
            'invoice_number' => Invoice::generateInvoiceNumber(),
            'invoice_type' => 'deposit',
            'client_id' => $contract->client_id,
            'contract_id' => $contract->id,
            'sale_id' => $contract->sale_id,
            'invoice_number' => Invoice::generateInvoiceNumber(),
            'invoice_date' => now(),
            'due_date' => now()->addDays(14),
            'subtotal' => $deposit,
            'tax_rate' => 0,
            'tax_amount' => 0,
            'discount_amount' => 0,
            'total_amount' => $deposit,
            'paid_amount' => 0,
            'balance_amount' => $deposit,
            'status' => 'sent',
            'notes' => 'دفعة مقدمة ('.$percent.'%) — عقد '.$contract->contract_number,
            'created_by' => auth()->id(),
        ]);
    }

    public function createProjectAfterDepositPaid(Contract $contract): ?Project
    {
        if ($contract->project_id) {
            return $contract->project;
        }

        $depositPaid = Invoice::where('contract_id', $contract->id)
            ->where('notes', 'like', '%دفعة مقدمة%')
            ->where('status', 'paid')
            ->exists();

        if (! $depositPaid) {
            return null;
        }

        return DB::transaction(function () use ($contract) {
            $sale = $contract->sale;

            $project = Project::create([
                'name' => $sale?->product_service ?? $contract->title,
                'description' => $contract->description,
                'client_id' => $contract->client_id,
                'contract_id' => $contract->id,
                'sale_id' => $contract->sale_id,
                'start_date' => now(),
                'end_date' => $contract->end_date,
                'budget' => $contract->value,
                'status' => 'planning',
                'kickoff_status' => 'started',
                'priority' => 'medium',
                'progress_percentage' => 0,
            ]);

            $contract->update(['project_id' => $project->id, 'status' => 'active']);

            Invoice::where('contract_id', $contract->id)
                ->where(function ($q) {
                    $q->where('invoice_type', 'deposit')
                        ->orWhere('notes', 'like', '%دفعة مقدمة%');
                })
                ->update(['project_id' => $project->id]);

            if ($sale) {
                $sale->update([
                    'stage' => 'closed_won',
                    'project_id' => $project->id,
                    'actual_close_date' => now(),
                ]);
            }

            return $project;
        });
    }
}
