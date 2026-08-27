<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\User;
use App\Services\CommercialWorkflowService;
use App\Services\SalesActivityAdminAlertService;
use App\Services\SalesRepScope;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = SalesRepScope::scopeLeads(
            Lead::with(['assignee', 'convertedClient', 'convertedSale']),
            $user
        )->orderByDesc('created_at');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('source')) {
            $query->where('source', $request->source);
        }
        if ($request->filled('search')) {
            $s = '%'.$request->search.'%';
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', $s)
                    ->orWhere('company', 'like', $s)
                    ->orWhere('email', 'like', $s)
                    ->orWhere('reference_code', 'like', $s);
            });
        }

        $leads = $query->paginate(20)->withQueryString();

        $statsQuery = SalesRepScope::scopeLeads(Lead::query(), $user);
        $stats = [
            'new' => (clone $statsQuery)->where('status', 'new')->count(),
            'qualified' => (clone $statsQuery)->where('status', 'qualified')->count(),
            'converted' => (clone $statsQuery)->where('status', 'converted')->count(),
        ];

        return view('leads.index', compact('leads', 'stats'));
    }

    public function create()
    {
        $users = SalesRepScope::isRestricted(auth()->user())
            ? collect()
            : User::whereHas('employee')->orderBy('name')->get(['id', 'name']);

        return view('leads.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'company' => 'nullable|string|max:255',
            'service_interest' => 'nullable|string|max:255',
            'source' => 'required|in:ads,social_media,referral,website,bd_outreach,event,other',
            'estimated_budget' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:10000',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        $lead = Lead::create([
            ...$validated,
            'reference_code' => Lead::generateReferenceCode(),
            'status' => 'new',
            'assigned_to' => SalesRepScope::isRestricted(auth()->user())
                ? auth()->id()
                : ($validated['assigned_to'] ?? auth()->id()),
            'created_by' => auth()->id(),
        ]);

        app(SalesActivityAdminAlertService::class)->notifyLeadCreated($lead, auth()->user());

        return redirect()->route('leads.show', $lead)->with('success', 'تم تسجيل الـ Lead بنجاح.');
    }

    public function show(Lead $lead)
    {
        SalesRepScope::authorizeLead(auth()->user(), $lead);

        $lead->load(['assignee', 'creator', 'contactRequest', 'convertedClient', 'convertedSale.client']);
        $users = SalesRepScope::isRestricted(auth()->user())
            ? collect()
            : User::whereHas('employee')->orderBy('name')->get(['id', 'name']);

        return view('leads.show', compact('lead', 'users'));
    }

    public function updateStatus(Request $request, Lead $lead)
    {
        SalesRepScope::authorizeLead(auth()->user(), $lead);
        $validated = $request->validate([
            'status' => 'required|in:new,contacted,qualified,lost,on_hold',
            'lost_reason' => 'nullable|string|max:500',
        ]);

        $oldStatus = $lead->status;
        $lead->update($validated);

        app(SalesActivityAdminAlertService::class)->notifyLeadStatusUpdated($lead, auth()->user(), $oldStatus);

        return back()->with('success', 'تم تحديث حالة الـ Lead.');
    }

    public function convertToSale(Request $request, Lead $lead, CommercialWorkflowService $workflow)
    {
        SalesRepScope::authorizeLead(auth()->user(), $lead);

        $assignedTo = SalesRepScope::isRestricted(auth()->user())
            ? auth()->id()
            : $request->validate(['assigned_to' => 'required|exists:users,id'])['assigned_to'];

        $sale = $workflow->convertLeadToSale($lead, (int) $assignedTo);

        app(SalesActivityAdminAlertService::class)->notifyLeadConverted($lead, $sale, auth()->user());

        return redirect()->route('sales.show', $sale)
            ->with('success', 'تم تحويل الـ Lead إلى فرصة مبيعات. أكمل التأهيل وملخص المتطلبات.');
    }
}
