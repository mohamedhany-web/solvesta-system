<?php

namespace App\Http\Controllers;

use App\Models\CostEstimation;
use App\Models\Proposal;
use App\Models\Sale;
use App\Services\PreSalesService;
use Illuminate\Http\Request;

class PreSalesController extends Controller
{
    public function index(Request $request)
    {
        $query = Sale::with(['client', 'salesRep', 'costEstimations', 'proposals'])
            ->where('qualification_status', 'qualified')
            ->whereNotIn('stage', ['closed_won', 'closed_lost'])
            ->orderByDesc('updated_at');

        if ($request->filled('filter')) {
            match ($request->filter) {
                'needs_estimate' => $query->whereDoesntHave('costEstimations'),
                'needs_proposal' => $query->whereHas('costEstimations', fn ($q) => $q->where('status', 'approved'))
                    ->whereDoesntHave('proposals'),
                'pending_client' => $query->whereHas('proposals', fn ($q) => $q->where('status', 'sent')),
                default => null,
            };
        }

        $sales = $query->paginate(15)->withQueryString();

        $stats = [
            'queue' => Sale::where('qualification_status', 'qualified')
                ->whereNotIn('stage', ['closed_won', 'closed_lost'])->count(),
            'needs_estimate' => Sale::where('qualification_status', 'qualified')
                ->whereNotIn('stage', ['closed_won', 'closed_lost'])
                ->whereDoesntHave('costEstimations')->count(),
            'proposals_sent' => Proposal::where('status', 'sent')->count(),
            'proposals_accepted' => Proposal::where('status', 'accepted')->count(),
        ];

        return view('pre-sales.index', compact('sales', 'stats'));
    }

    public function estimate(Sale $sale)
    {
        if ($sale->qualification_status !== 'qualified') {
            return redirect()->route('sales.show', $sale)
                ->withErrors(['error' => 'يجب تأهيل الفرصة قبل تقدير التكلفة.']);
        }

        $sale->load(['client', 'costEstimations' => fn ($q) => $q->latest()]);
        $estimation = $sale->costEstimations->first();
        $defaults = app(PreSalesService::class)->defaultRates();

        return view('pre-sales.estimate', compact('sale', 'estimation', 'defaults'));
    }

    public function storeEstimate(Request $request, Sale $sale, PreSalesService $preSales)
    {
        $validated = $request->validate([
            'screen_count' => 'nullable|integer|min:0|max:500',
            'developers_count' => 'required|integer|min:1|max:50',
            'dev_hours' => 'required|numeric|min:0|max:99999',
            'design_hours' => 'required|numeric|min:0|max:99999',
            'qa_hours' => 'required|numeric|min:0|max:99999',
            'pm_hours' => 'nullable|numeric|min:0|max:99999',
            'hourly_rate_dev' => 'required|numeric|min:0',
            'hourly_rate_design' => 'required|numeric|min:0',
            'hourly_rate_qa' => 'required|numeric|min:0',
            'hourly_rate_pm' => 'required|numeric|min:0',
            'margin_percent' => 'required|numeric|min:0|max:100',
            'duration_weeks' => 'nullable|integer|min:1|max:104',
            'scope_notes' => 'nullable|string|max:10000',
            'technical_notes' => 'nullable|string|max:10000',
            'submit_for_approval' => 'nullable|boolean',
        ]);

        $existing = $sale->costEstimations()->latest()->first();
        $status = $request->boolean('submit_for_approval') ? 'submitted' : 'draft';
        if ($request->boolean('approve_now')) {
            $status = 'approved';
        }

        $estimation = $preSales->storeEstimation($sale, [...$validated, 'status' => $status], $existing);

        if ($status === 'approved') {
            $preSales->approveEstimation($estimation);
        }

        return redirect()->route('pre-sales.estimate', $sale)
            ->with('success', 'تم حفظ تقدير التكلفة.');
    }

    public function approveEstimate(CostEstimation $estimation, PreSalesService $preSales)
    {
        $preSales->approveEstimation($estimation);

        return back()->with('success', 'تم اعتماد التقدير — يمكن إصدار العرض الآن.');
    }

    public function generateProposal(Sale $sale, PreSalesService $preSales)
    {
        $estimation = $sale->costEstimations()
            ->where('status', 'approved')
            ->latest()
            ->first();

        if (! $estimation) {
            return back()->withErrors(['error' => 'يجب اعتماد تقدير التكلفة أولاً.']);
        }

        $proposal = $preSales->generateProposal($sale, $estimation);

        return redirect()->route('pre-sales.proposals.show', $proposal)
            ->with('success', 'تم إنشاء العرض تلقائياً من التقدير.');
    }

    public function showProposal(Proposal $proposal)
    {
        $proposal->load(['sale.client', 'costEstimation', 'creator']);

        return view('pre-sales.proposal', compact('proposal'));
    }

    public function markSent(Proposal $proposal, PreSalesService $preSales)
    {
        $preSales->markProposalSent($proposal);

        return back()->with('success', 'تم تسجيل إرسال العرض للعميل.');
    }

    public function accept(Proposal $proposal, PreSalesService $preSales)
    {
        $preSales->acceptProposal($proposal);

        return back()->with('success', 'موافقة العميل مسجّلة — يمكن إنشاء العقد الآن.');
    }

    public function reject(Request $request, Proposal $proposal, PreSalesService $preSales)
    {
        $validated = $request->validate(['rejection_reason' => 'required|string|max:500']);
        $preSales->rejectProposal($proposal, $validated['rejection_reason']);

        return back()->with('success', 'تم تسجيل رفض العرض.');
    }
}
