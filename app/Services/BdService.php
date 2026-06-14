<?php

namespace App\Services;

use App\Models\BdOpportunity;
use App\Models\BdPartner;
use App\Models\Lead;
use Illuminate\Support\Facades\DB;

class BdService
{
    public function createPartner(array $data): BdPartner
    {
        return BdPartner::create([
            ...$data,
            'reference_code' => BdPartner::generateReferenceCode(),
            'created_by' => auth()->id(),
            'assigned_to' => $data['assigned_to'] ?? auth()->id(),
        ]);
    }

    public function createOpportunity(array $data): BdOpportunity
    {
        return BdOpportunity::create([
            ...$data,
            'reference_code' => BdOpportunity::generateReferenceCode(),
            'created_by' => auth()->id(),
            'assigned_to' => $data['assigned_to'] ?? auth()->id(),
        ]);
    }

    public function convertOpportunityToLead(BdOpportunity $opportunity): Lead
    {
        if ($opportunity->lead_id) {
            return $opportunity->lead;
        }

        return DB::transaction(function () use ($opportunity) {
            $partner = $opportunity->partner;

            $lead = Lead::create([
                'reference_code' => Lead::generateReferenceCode(),
                'source' => 'bd_outreach',
                'status' => 'new',
                'name' => $partner?->name ?? $opportunity->title,
                'email' => $partner?->email,
                'phone' => $partner?->phone,
                'company' => $partner?->company,
                'service_interest' => $opportunity->title,
                'estimated_budget' => $opportunity->estimated_value,
                'notes' => $opportunity->description,
                'assigned_to' => $opportunity->assigned_to ?? auth()->id(),
                'created_by' => auth()->id(),
            ]);

            $opportunity->update(['status' => 'converted', 'lead_id' => $lead->id]);

            return $lead;
        });
    }
}
