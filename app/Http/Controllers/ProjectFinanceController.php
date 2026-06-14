<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Services\ProjectFinanceService;
use Illuminate\Http\Request;

class ProjectFinanceController extends Controller
{
    public function storeExpense(Request $request, Project $project, ProjectFinanceService $finance)
    {
        $this->authorize('update', $project);

        $validated = $request->validate([
            'expense_category' => 'required|in:office_supplies,utilities,rent,salaries,marketing,travel,maintenance,software,professional_fees,insurance,taxes,other',
            'amount' => 'required|numeric|min:0',
            'description' => 'required|string|max:500',
            'expense_date' => 'required|date',
            'payment_method' => 'required|in:cash,bank_transfer,check,credit_card',
            'notes' => 'nullable|string|max:1000',
        ]);

        $finance->storeProjectExpense($project, $validated);

        return back()->with('success', 'تم تسجيل مصروف المشروع.');
    }

    public function createDeliveryInvoice(Project $project, ProjectFinanceService $finance)
    {
        $this->authorize('update', $project);

        if (! $finance->canCreateDeliveryInvoice($project)) {
            return back()->withErrors(['error' => 'لا يمكن إصدار فاتورة التسليم — تأكد من اكتمال المراحل ودفع المقدمة.']);
        }

        $invoice = $finance->createDeliveryInvoice($project);

        return redirect()->route('invoices.show', $invoice)
            ->with('success', 'تم إنشاء فاتورة التسليم (50%).');
    }
}
