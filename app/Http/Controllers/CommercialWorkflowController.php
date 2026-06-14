<?php

namespace App\Http\Controllers;

use App\Models\ContactRequest;
use App\Models\Contract;
use App\Models\Sale;
use App\Services\CommercialWorkflowService;
use App\Services\PmoService;
use Illuminate\Http\Request;

class CommercialWorkflowController extends Controller
{
    public function convertContactToLead(ContactRequest $contactRequest, CommercialWorkflowService $workflow)
    {
        $lead = $workflow->createLeadFromContactRequest($contactRequest);

        return redirect()->route('leads.show', $lead)
            ->with('success', 'تم إنشاء Lead من طلب الموقع.');
    }

    public function qualifySale(Request $request, Sale $sale, CommercialWorkflowService $workflow)
    {
        $validated = $request->validate([
            'requirement_summary' => 'nullable|string|max:20000',
        ]);

        $workflow->qualifySale($sale, $validated['requirement_summary'] ?? null);

        return back()->with('success', 'تم تأهيل الفرصة — جاهزة لمرحلة العرض والتسعير.');
    }

    public function disqualifySale(Request $request, Sale $sale, CommercialWorkflowService $workflow)
    {
        $validated = $request->validate([
            'lost_reason' => 'required|string|max:500',
        ]);

        $workflow->disqualifySale($sale, $validated['lost_reason']);

        return back()->with('success', 'تم تصنيف الفرصة كمفقودة.');
    }

    public function createContract(Sale $sale, CommercialWorkflowService $workflow)
    {
        $accepted = $sale->proposals()->where('status', 'accepted')->exists();
        if (! $accepted) {
            return back()->withErrors(['error' => 'يجب موافقة العميل على العرض (Proposal) قبل إنشاء العقد.']);
        }

        $contract = $workflow->createContractFromSale($sale);

        $sale->update(['stage' => 'negotiation']);

        return redirect()->route('contracts.show', $contract)
            ->with('success', 'تم إنشاء مسودة العقد. بعد التوقيع أنشئ فاتورة الدفعة المقدمة.');
    }

    public function createDepositInvoice(Contract $contract, CommercialWorkflowService $workflow)
    {
        $invoice = $workflow->createDepositInvoice($contract);

        return redirect()->route('invoices.show', $invoice)
            ->with('success', 'تم إنشاء فاتورة الدفعة المقدمة (50%). لا يبدأ المشروع قبل تأكيد الدفع.');
    }

    public function kickoffProject(Contract $contract, CommercialWorkflowService $workflow, PmoService $pmo)
    {
        $project = $workflow->createProjectAfterDepositPaid($contract);

        if (! $project) {
            return back()->withErrors(['error' => 'لا يمكن بدء المشروع — يجب تأكيد دفع الدفعة المقدمة أولاً.']);
        }

        $pmo->seedDefaultMilestones($project);

        return redirect()->route('projects.show', $project)
            ->with('success', 'تم إنشاء المشروع مع مراحل PMO الافتراضية. وزّع المهام على الفريق.');
    }
}
