<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Services\SalesCommissionService;
use App\Services\SalesRepScope;
use Illuminate\Http\Request;

class SalesRepAccountController extends Controller
{
    public function __construct(protected SalesCommissionService $commission) {}

    public function index()
    {
        $user = auth()->user();
        $employee = $user->employee;

        if (! $employee) {
            abort(404, 'لا يوجد ملف موظف مرتبط بحسابك. تواصل مع الموارد البشرية.');
        }

        $commissionSummary = $this->commission->summaryForUser($user);

        return view('sales.account', [
            'user' => $user,
            'employee' => $employee,
            'salary' => $employee->effectiveSalary(),
            'contract' => $employee->activeEmploymentContract(),
            'commissionSummary' => $commissionSummary,
            'payoutMethods' => Employee::payoutMethodLabels(),
            'isRestricted' => SalesRepScope::isRestricted($user),
        ]);
    }

    public function updatePayout(Request $request)
    {
        $user = auth()->user();
        $employee = $user->employee;

        if (! $employee) {
            abort(404, 'لا يوجد ملف موظف مرتبط بحسابك.');
        }

        $validated = $request->validate([
            'preferred_payout_method' => 'nullable|in:bank,vodafone_cash,instapay',
            'bank_name' => 'nullable|string|max:255',
            'bank_account_number' => 'nullable|string|max:64',
            'bank_account_holder' => 'nullable|string|max:255',
            'vodafone_cash_number' => 'nullable|string|max:20',
            'instapay_identifier' => 'nullable|string|max:128',
            'payout_notes' => 'nullable|string|max:1000',
        ]);

        $employee->update($validated);

        return back()->with('success', 'تم حفظ بيانات التحويل بنجاح.');
    }
}
