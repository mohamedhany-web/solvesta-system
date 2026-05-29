<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\JournalEntryLine;
use App\Services\AccountingDashboardService;
use Illuminate\Http\Request;

class AccountingController extends Controller
{
    public function index(AccountingDashboardService $dashboard)
    {
        return view('accounting.index', $dashboard->build());
    }

    public function guide()
    {
        return view('accounting.guide');
    }

    public function accounts()
    {
        $types = ['asset', 'liability', 'equity', 'revenue', 'expense'];
        $service = app(AccountingDashboardService::class);

        $grouped = Account::with('parent')
            ->orderBy('code')
            ->get()
            ->each(fn (Account $a) => $a->balance = $service->accountBalance($a))
            ->groupBy('type');

        $accounts = [];
        foreach ($types as $type) {
            $accounts[$type] = $grouped->get($type, collect());
        }

        return view('accounting.accounts', compact('accounts'));
    }

    public function createAccount(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:20|unique:accounts',
            'type' => 'required|in:asset,liability,equity,revenue,expense',
            'parent_id' => 'nullable|exists:accounts,id',
            'description' => 'nullable|string',
            'balance' => 'nullable|numeric|min:0',
        ]);

        Account::create([
            'name' => $request->name,
            'code' => $request->code,
            'type' => $request->type,
            'parent_id' => $request->parent_id,
            'description' => $request->description,
            'balance' => 0,
            'is_active' => true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تم إنشاء الحساب بنجاح',
        ]);
    }

    public function editAccount(Account $account)
    {
        return response()->json([
            'success' => true,
            'account' => $account,
        ]);
    }

    public function updateAccount(Request $request, Account $account)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:20|unique:accounts,code,'.$account->id,
            'type' => 'required|in:asset,liability,equity,revenue,expense',
            'parent_id' => 'nullable|exists:accounts,id',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $hasTransactions = JournalEntryLine::where('account_id', $account->id)->exists();

        $account->update([
            'name' => $request->name,
            'code' => $request->code,
            'type' => $request->type,
            'parent_id' => $request->parent_id,
            'description' => $request->description,
            'is_active' => $request->boolean('is_active'),
            'balance' => $hasTransactions ? $account->balance : 0,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث الحساب بنجاح',
        ]);
    }

    public function deleteAccount(Account $account)
    {
        $hasTransactions = JournalEntryLine::where('account_id', $account->id)->exists();

        if ($hasTransactions) {
            return response()->json([
                'success' => false,
                'message' => 'لا يمكن حذف الحساب لوجود حركات محاسبية مرتبطة به',
            ], 400);
        }

        $account->delete();

        return response()->json([
            'success' => true,
            'message' => 'تم حذف الحساب بنجاح',
        ]);
    }
}
