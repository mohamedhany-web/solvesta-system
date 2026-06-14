<?php

namespace App\Services;

use App\Models\Contract;
use App\Models\Expense;
use App\Models\Invoice;
use App\Models\Project;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ProjectFinanceService
{
    public function tryCreateDeliveryInvoice(Project $project): ?Invoice
    {
        if (! $this->canCreateDeliveryInvoice($project)) {
            return null;
        }

        return $this->createDeliveryInvoice($project);
    }

    public function canCreateDeliveryInvoice(Project $project): bool
    {
        $contract = $project->contract;
        if (! $contract) {
            return false;
        }

        if ($this->getDeliveryInvoice($project, $contract)) {
            return false;
        }

        $depositPaid = Invoice::where('contract_id', $contract->id)
            ->where(function ($q) {
                $q->where('invoice_type', 'deposit')
                    ->orWhere('notes', 'like', '%دفعة مقدمة%');
            })
            ->where('status', 'paid')
            ->exists();

        if (! $depositPaid) {
            return false;
        }

        if ($project->milestones()->exists()) {
            $allDone = $project->milestones()
                ->whereNotIn('status', ['completed', 'cancelled'])
                ->doesntExist();

            return $allDone && $project->progress_percentage >= 100;
        }

        return $project->status === 'completed' || $project->progress_percentage >= 100;
    }

    public function createDeliveryInvoice(Project $project, float $percent = 50): Invoice
    {
        $contract = $project->contract ?? Contract::where('project_id', $project->id)->first();

        if ($existing = $this->getDeliveryInvoice($project, $contract)) {
            return $existing;
        }

        $total = (float) ($contract?->value ?? $project->budget ?? 0);
        $amount = round($total * ($percent / 100), 2);

        return Invoice::create([
            'invoice_number' => Invoice::generateInvoiceNumber(),
            'invoice_type' => 'delivery',
            'client_id' => $project->client_id,
            'contract_id' => $contract?->id,
            'project_id' => $project->id,
            'sale_id' => $project->sale_id,
            'invoice_date' => now(),
            'due_date' => now()->addDays(14),
            'subtotal' => $amount,
            'tax_rate' => 0,
            'tax_amount' => 0,
            'discount_amount' => 0,
            'total_amount' => $amount,
            'paid_amount' => 0,
            'balance_amount' => $amount,
            'status' => 'sent',
            'notes' => 'دفعة عند التسليم ('.$percent.'%) — مشروع '.$project->name,
            'created_by' => auth()->id() ?? 1,
        ]);
    }

    public function getDeliveryInvoice(?Project $project, ?Contract $contract): ?Invoice
    {
        $query = Invoice::query()->where(function ($q) {
            $q->where('invoice_type', 'delivery')
                ->orWhere('notes', 'like', '%عند التسليم%');
        });

        if ($project) {
            $query->where(function ($q) use ($project, $contract) {
                $q->where('project_id', $project->id);
                if ($contract) {
                    $q->orWhere('contract_id', $contract->id);
                }
            });
        }

        return $query->first();
    }

    public function getProjectFinancials(Project $project): array
    {
        $contract = $project->contract;
        $contractValue = (float) ($contract?->value ?? $project->budget ?? 0);

        $invoices = Invoice::where(function ($q) use ($project, $contract) {
            $q->where('project_id', $project->id);
            if ($contract) {
                $q->orWhere('contract_id', $contract->id);
            }
        })->get();

        $deposit = $invoices->first(fn ($i) => $i->invoice_type === 'deposit' || str_contains($i->notes ?? '', 'دفعة مقدمة'));
        $delivery = $invoices->first(fn ($i) => $i->invoice_type === 'delivery' || str_contains($i->notes ?? '', 'عند التسليم'));

        $revenuePaid = (float) $invoices->where('status', 'paid')->sum('paid_amount');
        $outstanding = (float) $invoices->whereNotIn('status', ['paid', 'cancelled'])->sum('balance_amount');
        $expenses = (float) Expense::where('project_id', $project->id)->whereIn('status', ['approved', 'paid'])->sum('amount');
        $expensesPending = (float) Expense::where('project_id', $project->id)->where('status', 'pending')->sum('amount');

        return [
            'contract_value' => $contractValue,
            'revenue_paid' => $revenuePaid,
            'outstanding' => $outstanding,
            'expenses' => $expenses,
            'expenses_pending' => $expensesPending,
            'profit' => $revenuePaid - $expenses,
            'margin_percent' => $revenuePaid > 0 ? round((($revenuePaid - $expenses) / $revenuePaid) * 100, 1) : 0,
            'deposit_invoice' => $deposit,
            'delivery_invoice' => $delivery,
            'can_create_delivery' => $this->canCreateDeliveryInvoice($project),
        ];
    }

    public function getExecutiveFinancialSummary(): array
    {
        $paidRevenue = (float) Invoice::where('status', 'paid')->sum('paid_amount');
        $outstanding = (float) Invoice::whereNotIn('status', ['paid', 'cancelled'])->sum('balance_amount');
        $deliveryPending = Invoice::where('invoice_type', 'delivery')
            ->whereNotIn('status', ['paid', 'cancelled'])->count();
        $deliveryOutstanding = (float) Invoice::where('invoice_type', 'delivery')
            ->whereNotIn('status', ['paid', 'cancelled'])->sum('balance_amount');

        $projectExpenses = (float) Expense::whereNotNull('project_id')
            ->whereIn('status', ['approved', 'paid'])->sum('amount');
        $generalExpenses = (float) Expense::whereNull('project_id')
            ->whereIn('status', ['approved', 'paid'])->sum('amount');

        $activeProjects = Project::whereIn('status', ['planning', 'in_progress', 'completed'])
            ->with('contract')
            ->get();

        $projectRows = $activeProjects->map(function (Project $p) {
            $fin = $this->getProjectFinancials($p);

            return [
                'project' => $p,
                'revenue_paid' => $fin['revenue_paid'],
                'expenses' => $fin['expenses'],
                'profit' => $fin['profit'],
                'outstanding' => $fin['outstanding'],
                'margin_percent' => $fin['margin_percent'],
            ];
        })->sortByDesc('profit')->values();

        return [
            'paid_revenue' => $paidRevenue,
            'outstanding' => $outstanding,
            'delivery_pending_count' => $deliveryPending,
            'delivery_outstanding' => $deliveryOutstanding,
            'project_expenses' => $projectExpenses,
            'general_expenses' => $generalExpenses,
            'estimated_profit' => $paidRevenue - $projectExpenses - $generalExpenses,
            'project_rows' => $projectRows,
            'recent_delivery_invoices' => Invoice::where('invoice_type', 'delivery')
                ->with('client', 'project')
                ->orderByDesc('created_at')
                ->limit(8)
                ->get(),
        ];
    }

    public function storeProjectExpense(Project $project, array $data): Expense
    {
        return Expense::create([
            'expense_number' => 'EXP-'.time().'-'.$project->id,
            'expense_category' => $data['expense_category'],
            'project_id' => $project->id,
            'vendor_id' => $data['vendor_id'] ?? null,
            'expense_date' => $data['expense_date'],
            'amount' => $data['amount'],
            'description' => $data['description'],
            'notes' => $data['notes'] ?? null,
            'payment_method' => $data['payment_method'],
            'created_by' => auth()->id(),
            'status' => 'pending',
        ]);
    }
}
