<?php

namespace App\Http\Controllers;

use App\Models\BdOpportunity;
use App\Models\BdPartner;
use App\Models\User;
use App\Services\BdService;
use Illuminate\Http\Request;

class BdController extends Controller
{
    public function index(Request $request)
    {
        $partners = BdPartner::with('assignee')->withCount('opportunities')
            ->orderByDesc('created_at')->paginate(12, ['*'], 'partners_page');

        $opportunities = BdOpportunity::with(['partner', 'assignee', 'lead'])
            ->when($request->status, fn ($q) => $q->where('status', $request->status))
            ->orderByDesc('created_at')->paginate(12, ['*'], 'opportunities_page');

        $stats = [
            'partners' => BdPartner::count(),
            'opportunities' => BdOpportunity::whereNotIn('status', ['converted', 'lost'])->count(),
            'converted' => BdOpportunity::where('status', 'converted')->count(),
            'pipeline_value' => (float) BdOpportunity::whereNotIn('status', ['converted', 'lost'])->sum('estimated_value'),
        ];

        return view('bd.index', compact('partners', 'opportunities', 'stats'));
    }

    public function createPartner()
    {
        $users = User::whereHas('employee')->orderBy('name')->get(['id', 'name']);

        return view('bd.partners.create', compact('users'));
    }

    public function storePartner(Request $request, BdService $bd)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'company' => 'nullable|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:50',
            'partner_type' => 'required|in:agency,vendor,referrer,strategic,other',
            'country' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:5000',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        $partner = $bd->createPartner($validated);

        return redirect()->route('bd.partners.show', $partner)->with('success', 'تم تسجيل الشريك.');
    }

    public function showPartner(BdPartner $partner)
    {
        $partner->load(['assignee', 'opportunities.assignee', 'opportunities.lead']);
        $users = User::whereHas('employee')->orderBy('name')->get(['id', 'name']);

        return view('bd.partners.show', compact('partner', 'users'));
    }

    public function storeOpportunity(Request $request, BdService $bd)
    {
        $validated = $request->validate([
            'partner_id' => 'nullable|exists:bd_partners,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:10000',
            'estimated_value' => 'nullable|numeric|min:0',
            'expected_close_date' => 'nullable|date',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        $opp = $bd->createOpportunity($validated);

        return redirect()->route('bd.index')->with('success', 'تمت إضافة الفرصة '.$opp->reference_code);
    }

    public function convertOpportunity(BdOpportunity $opportunity, BdService $bd)
    {
        $lead = $bd->convertOpportunityToLead($opportunity);

        return redirect()->route('leads.show', $lead)->with('success', 'تم تحويل الفرصة إلى Lead.');
    }

    public function updateOpportunityStatus(Request $request, BdOpportunity $opportunity)
    {
        $validated = $request->validate([
            'status' => 'required|in:prospecting,contacted,qualified,lost',
            'lost_reason' => 'nullable|string|max:500',
        ]);

        $opportunity->update($validated);

        return back()->with('success', 'تم تحديث حالة الفرصة.');
    }
}
