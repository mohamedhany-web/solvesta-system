<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Account;
use App\Models\JournalEntry;
use App\Models\JournalEntryLine;
use App\Models\FinancialInvoice;
use App\Models\Payment;
use App\Models\Expense;
use Carbon\Carbon;

class AccountingController extends Controller
{
    public function index()
    {
        // إحصائيات المحاسبة
        $totalAssets = Account::where('type', 'asset')->sum('balance');
        $totalLiabilities = Account::where('type', 'liability')->sum('balance');
        $totalEquity = Account::where('type', 'equity')->sum('balance');
        $totalRevenue = Account::where('type', 'revenue')->sum('balance');
        $totalExpenses = Account::where('type', 'expense')->sum('balance');
        
        // الأرباح/الخسائر
        $netIncome = $totalRevenue - $totalExpenses;
        
        // الميزانية العمومية
        $balanceSheetTotal = $totalAssets;
        $balanceSheetLiabilitiesEquity = $totalLiabilities + $totalEquity + $netIncome;
        
        // القيود الأخيرة
        $recentEntries = JournalEntry::with('lines.account')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get()
            ->map(function($entry) {
                $entry->status_color = match($entry->status) {
                    'draft' => 'bg-gray-100 text-gray-800',
                    'posted' => 'bg-green-100 text-green-800',
                    'approved' => 'bg-blue-100 text-blue-800',
                    default => 'bg-gray-100 text-gray-800'
                };
                $entry->status_in_arabic = match($entry->status) {
                    'draft' => 'مسودة',
                    'posted' => 'منشور',
                    'approved' => 'معتمد',
                    default => $entry->status
                };
                $entry->total_debit = $entry->lines->sum('debit');
                return $entry;
            });
        
        // الحسابات النشطة
        $activeAccounts = Account::where('is_active', true)
            ->orderBy('type')
            ->orderBy('name')
            ->get()
            ->groupBy('type');
        
        // الإيرادات والمصروفات الشهرية
        $monthlyRevenue = JournalEntry::whereMonth('date', Carbon::now()->month)
            ->whereYear('date', Carbon::now()->year)
            ->with(['lines' => function($query) {
                $query->whereHas('account', function($q) {
                    $q->where('type', 'revenue');
                });
            }])
            ->get()
            ->sum(function($entry) {
                return $entry->lines->sum('credit');
            });
            
        $monthlyExpenses = JournalEntry::whereMonth('date', Carbon::now()->month)
            ->whereYear('date', Carbon::now()->year)
            ->with(['lines' => function($query) {
                $query->whereHas('account', function($q) {
                    $q->where('type', 'expense');
                });
            }])
            ->get()
            ->sum(function($entry) {
                return $entry->lines->sum('debit');
            });
        
        // الفواتير والمدفوعات المعلقة
        $pendingInvoices = \App\Models\Invoice::where('status', 'pending')->sum('total_amount');
        $pendingPayments = Payment::where('status', 'pending')->sum('amount');
        
        return view('accounting.index', compact(
            'totalAssets',
            'totalLiabilities', 
            'totalEquity',
            'totalRevenue',
            'totalExpenses',
            'netIncome',
            'balanceSheetTotal',
            'balanceSheetLiabilitiesEquity',
            'recentEntries',
            'activeAccounts',
            'monthlyRevenue',
            'monthlyExpenses',
            'pendingInvoices',
            'pendingPayments'
        ));
    }
    
    public function accounts()
    {
        $accounts = Account::with('parent')
            ->orderBy('type')
            ->orderBy('code')
            ->get()
            ->groupBy('type');
            
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
            'balance' => $request->balance ?? 0,
            'is_active' => true,
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'تم إنشاء الحساب بنجاح'
        ]);
    }
    
    public function editAccount(Account $account)
    {
        return response()->json([
            'success' => true,
            'account' => $account
        ]);
    }
    
    public function updateAccount(Request $request, Account $account)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:20|unique:accounts,code,' . $account->id,
            'type' => 'required|in:asset,liability,equity,revenue,expense',
            'parent_id' => 'nullable|exists:accounts,id',
            'description' => 'nullable|string',
            'balance' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
        ]);
        
        $account->update([
            'name' => $request->name,
            'code' => $request->code,
            'type' => $request->type,
            'parent_id' => $request->parent_id,
            'description' => $request->description,
            'balance' => $request->balance ?? $account->balance,
            'is_active' => $request->boolean('is_active'),
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'تم تحديث الحساب بنجاح'
        ]);
    }
    
    public function deleteAccount(Account $account)
    {
        // التحقق من وجود حركات في الحساب
        $hasTransactions = JournalEntryLine::where('account_id', $account->id)->exists();
        
        if ($hasTransactions) {
            return response()->json([
                'success' => false,
                'message' => 'لا يمكن حذف الحساب لوجود حركات محاسبية مرتبطة به'
            ], 400);
        }
        
        $account->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'تم حذف الحساب بنجاح'
        ]);
    }
}