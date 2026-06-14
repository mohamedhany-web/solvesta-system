<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\User;
use App\Services\CommercialWorkflowService;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    public function index(Request $request)
    {
        $query = Lead::with(['assignee', 'convertedClient', 'convertedSale'])
            ->orderByDesc('created_at');

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

        $stats = [
            'new' => Lead::where('status', 'new')->count(),
            'qualified' => Lead::where('status', 'qualified')->count(),
            'converted' => Lead::where('status', 'converted')->count(),
        ];

        return view('leads.index', compact('leads', 'stats'));
    }

    public function create()
    {
        $users = User::whereHas('employee')->orderBy('name')->get(['id', 'name']);

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
            'assigned_to' => $validated['assigned_to'] ?? auth()->id(),
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('leads.show', $lead)->with('success', 'تم تسجيل الـ Lead بنجاح.');
    }

    public function show(Lead $lead)
    {
        $lead->load(['assignee', 'creator', 'contactRequest', 'convertedClient', 'convertedSale.client']);
        $users = User::whereHas('employee')->orderBy('name')->get(['id', 'name']);

        return view('leads.show', compact('lead', 'users'));
    }

    public function updateStatus(Request $request, Lead $lead)
    {
        $validated = $request->validate([
            'status' => 'required|in:new,contacted,qualified,lost,on_hold',
            'lost_reason' => 'nullable|string|max:500',
        ]);

        $lead->update($validated);

        return back()->with('success', 'تم تحديث حالة الـ Lead.');
    }

    public function convertToSale(Request $request, Lead $lead, CommercialWorkflowService $workflow)
    {
        $validated = $request->validate([
            'assigned_to' => 'required|exists:users,id',
        ]);

        $sale = $workflow->convertLeadToSale($lead, (int) $validated['assigned_to']);

        return redirect()->route('sales.show', $sale)
            ->with('success', 'تم تحويل الـ Lead إلى فرصة مبيعات. أكمل التأهيل وملخص المتطلبات.');
    }
}
