<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Client;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\Accounting\AccountingPostingService;

class ExpenseController extends Controller
{
    public function index()
    {
        $expenses = Expense::with(['vendor', 'creator', 'approver'])
            ->when(request('search'), function ($query) {
                $query->where('description', 'like', '%' . request('search') . '%')
                    ->orWhere('expense_number', 'like', '%' . request('search') . '%');
            })
            ->when(request('status'), function ($query) {
                $query->where('status', request('status'));
            })
            ->when(request('category'), function ($query) {
                $query->where('expense_category', request('category'));
            })
            ->orderBy('expense_date', 'desc')
            ->paginate(15);
        
        $stats = [
            'total' => Expense::count(),
            'total_amount' => Expense::sum('amount'),
            'pending' => Expense::where('status', 'pending')->count(),
            'approved' => Expense::where('status', 'approved')->count(),
        ];

        return view('expenses.index', compact('expenses', 'stats'));
    }

    public function create()
    {
        $vendors = Client::all();
        return view('expenses.create', compact('vendors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'expense_category' => 'required|in:office_supplies,utilities,rent,salaries,marketing,travel,maintenance,software,professional_fees,insurance,taxes,other',
            'vendor_id' => 'nullable|exists:clients,id',
            'expense_date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'description' => 'required|string',
            'payment_method' => 'required|in:cash,bank_transfer,check,credit_card',
        ], [
            'expense_category.required' => 'يجب اختيار فئة المصروف',
            'expense_category.in' => 'فئة المصروف غير صحيحة',
            'payment_method.required' => 'يجب اختيار طريقة الدفع',
            'payment_method.in' => 'طريقة الدفع غير صحيحة',
        ]);

        $validated['expense_number'] = 'EXP-' . time();
        $validated['created_by'] = auth()->id();
        $validated['status'] = 'pending';

        Expense::create($validated);

        return redirect()->route('expenses.index')->with('success', 'تم إضافة المصروف بنجاح');
    }

    public function show(Expense $expense)
    {
        $expense->load(['vendor', 'creator', 'approver']);
        return view('expenses.show', compact('expense'));
    }

    public function edit(Expense $expense)
    {
        $vendors = Client::all();
        return view('expenses.edit', compact('expense', 'vendors'));
    }

    public function update(Request $request, Expense $expense)
    {
        $validated = $request->validate([
            'expense_category' => 'required|in:office_supplies,utilities,rent,salaries,marketing,travel,maintenance,software,professional_fees,insurance,taxes,other',
            'vendor_id' => 'nullable|exists:clients,id',
            'expense_date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'description' => 'required|string',
            'payment_method' => 'required|in:cash,bank_transfer,check,credit_card',
            'status' => 'required|in:pending,approved,paid,rejected',
        ], [
            'expense_category.required' => 'يجب اختيار فئة المصروف',
            'expense_category.in' => 'فئة المصروف غير صحيحة',
            'payment_method.required' => 'يجب اختيار طريقة الدفع',
            'payment_method.in' => 'طريقة الدفع غير صحيحة',
        ]);

        if ($request->status == 'approved' && $expense->status != 'approved') {
            $validated['approved_by'] = auth()->id();
            $validated['approved_at'] = now();
        }

        $previousStatus = $expense->status;
        $expense->update($validated);

        // عند الموافقة، ترحيل قيد المصروف
        if ($expense->status === 'approved' && $previousStatus !== 'approved') {
            try {
                app(AccountingPostingService::class)->postExpenseApproved($expense);
            } catch (\Throwable $e) {
                // يمكن لاحقاً تسجيل الخطأ
            }
        }

        return redirect()->route('expenses.index')->with('success', 'تم تحديث المصروف بنجاح');
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();
        return redirect()->route('expenses.index')->with('success', 'تم حذف المصروف بنجاح');
    }
}
