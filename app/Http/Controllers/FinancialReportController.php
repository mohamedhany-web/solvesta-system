<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Account;
use App\Models\JournalEntry;
use App\Models\JournalEntryLine;
use Carbon\Carbon;

class FinancialReportController extends Controller
{
    public function index()
    {
        return view('accounting.reports.index');
    }
    
    public function balanceSheet(Request $request)
    {
        $date = $request->get('date', Carbon::now()->format('Y-m-d'));
        $reportDate = Carbon::parse($date);
        
        // الأصول - فقط الحسابات الرئيسية (بدون parent_id)
        $assets = Account::where('type', 'asset')
            ->where('is_active', true)
            ->whereNull('parent_id')
            ->with('children')
            ->orderBy('code')
            ->get();
            
        // حساب إجمالي الأصول مع الحسابات الفرعية
        $totalAssets = 0;
        foreach ($assets as $asset) {
            $asset->total_balance = $asset->balance + $asset->children->sum('balance');
            $totalAssets += $asset->total_balance;
        }
        
        // الخصوم - فقط الحسابات الرئيسية
        $liabilities = Account::where('type', 'liability')
            ->where('is_active', true)
            ->whereNull('parent_id')
            ->with('children')
            ->orderBy('code')
            ->get();
            
        $totalLiabilities = 0;
        foreach ($liabilities as $liability) {
            $liability->total_balance = $liability->balance + $liability->children->sum('balance');
            $totalLiabilities += $liability->total_balance;
        }
        
        // حقوق الملكية - فقط الحسابات الرئيسية
        $equity = Account::where('type', 'equity')
            ->where('is_active', true)
            ->whereNull('parent_id')
            ->with('children')
            ->orderBy('code')
            ->get();
            
        $totalEquity = 0;
        foreach ($equity as $equityItem) {
            $equityItem->total_balance = $equityItem->balance + $equityItem->children->sum('balance');
            $totalEquity += $equityItem->total_balance;
        }
        
        // الأرباح المحتجزة (الإيرادات - المصروفات) للفترة حتى التاريخ المحدد
        $totalRevenue = Account::where('type', 'revenue')
            ->where('is_active', true)
            ->sum('balance');
        $totalExpenses = Account::where('type', 'expense')
            ->where('is_active', true)
            ->sum('balance');
        $retainedEarnings = $totalRevenue - $totalExpenses;
        
        $totalEquity += $retainedEarnings;
        $totalLiabilitiesEquity = $totalLiabilities + $totalEquity;
        
        return view('accounting.reports.balance-sheet', compact(
            'date',
            'reportDate',
            'assets',
            'totalAssets',
            'liabilities',
            'totalLiabilities',
            'equity',
            'totalEquity',
            'retainedEarnings',
            'totalLiabilitiesEquity'
        ));
    }
    
    public function incomeStatement(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfYear()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));
        
        $reportStartDate = Carbon::parse($startDate);
        $reportEndDate = Carbon::parse($endDate);
        
        // الإيرادات - فقط الحسابات الرئيسية
        $revenues = Account::where('type', 'revenue')
            ->where('is_active', true)
            ->whereNull('parent_id')
            ->with('children')
            ->orderBy('code')
            ->get();
            
        $totalRevenue = 0;
        foreach ($revenues as $revenue) {
            $revenue->total_balance = $revenue->balance + $revenue->children->sum('balance');
            $totalRevenue += $revenue->total_balance;
        }
        
        // المصروفات - فقط الحسابات الرئيسية
        $expenses = Account::where('type', 'expense')
            ->where('is_active', true)
            ->whereNull('parent_id')
            ->with('children')
            ->orderBy('code')
            ->get();
            
        $totalExpenses = 0;
        foreach ($expenses as $expense) {
            $expense->total_balance = $expense->balance + $expense->children->sum('balance');
            $totalExpenses += $expense->total_balance;
        }
        
        // صافي الدخل
        $netIncome = $totalRevenue - $totalExpenses;
        
        // حساب هامش الربح
        $profitMargin = $totalRevenue > 0 ? ($netIncome / $totalRevenue) * 100 : 0;
        
        return view('accounting.reports.income-statement', compact(
            'startDate',
            'endDate',
            'reportStartDate',
            'reportEndDate',
            'revenues',
            'totalRevenue',
            'expenses',
            'totalExpenses',
            'netIncome',
            'profitMargin'
        ));
    }
    
    public function cashFlow(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));
        
        $reportStartDate = Carbon::parse($startDate);
        $reportEndDate = Carbon::parse($endDate);
        
        // التدفقات النقدية من الأنشطة التشغيلية
        $operatingCashFlow = $this->calculateOperatingCashFlow($startDate, $endDate);
        
        // التدفقات النقدية من الأنشطة الاستثمارية
        $investingCashFlow = $this->calculateInvestingCashFlow($startDate, $endDate);
        
        // التدفقات النقدية من الأنشطة التمويلية
        $financingCashFlow = $this->calculateFinancingCashFlow($startDate, $endDate);
        
        $netCashFlow = $operatingCashFlow + $investingCashFlow + $financingCashFlow;
        
        // الرصيد النقدي في بداية الفترة
        $cashAccounts = Account::where('type', 'asset')
            ->whereIn('name', ['النقدية', 'البنوك', 'الخزينة', 'نقدية', 'بنوك', 'خزينة'])
            ->get();
            
        $beginningCash = $cashAccounts->sum('balance') - $netCashFlow;
        $endingCash = $cashAccounts->sum('balance');
        
        return view('accounting.reports.cash-flow', compact(
            'startDate',
            'endDate',
            'reportStartDate',
            'reportEndDate',
            'operatingCashFlow',
            'investingCashFlow',
            'financingCashFlow',
            'netCashFlow',
            'beginningCash',
            'endingCash'
        ));
    }
    
    public function trialBalance(Request $request)
    {
        $date = $request->get('date', Carbon::now()->format('Y-m-d'));
        
        $accounts = Account::where('is_active', true)
            ->orderBy('type')
            ->orderBy('code')
            ->get();
            
        $totalDebit = 0;
        $totalCredit = 0;
        
        foreach ($accounts as $account) {
            if ($account->type === 'asset' || $account->type === 'expense') {
                if ($account->balance >= 0) {
                    $account->debit_balance = $account->balance;
                    $account->credit_balance = 0;
                } else {
                    $account->debit_balance = 0;
                    $account->credit_balance = abs($account->balance);
                }
            } else {
                if ($account->balance >= 0) {
                    $account->debit_balance = 0;
                    $account->credit_balance = $account->balance;
                } else {
                    $account->debit_balance = abs($account->balance);
                    $account->credit_balance = 0;
                }
            }
            
            $totalDebit += $account->debit_balance;
            $totalCredit += $account->credit_balance;
        }
        
        return view('accounting.reports.trial-balance', compact(
            'date',
            'accounts',
            'totalDebit',
            'totalCredit'
        ));
    }
    
    private function calculateOperatingCashFlow($startDate, $endDate)
    {
        // حساب التدفقات النقدية من الأنشطة التشغيلية
        $revenueAccounts = Account::where('type', 'revenue')->pluck('id');
        $expenseAccounts = Account::where('type', 'expense')->pluck('id');
        
        $revenueCash = JournalEntryLine::whereIn('account_id', $revenueAccounts)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('debit');
            
        $expenseCash = JournalEntryLine::whereIn('account_id', $expenseAccounts)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('credit');
            
        return $revenueCash - $expenseCash;
    }
    
    private function calculateInvestingCashFlow($startDate, $endDate)
    {
        // حساب التدفقات النقدية من الأنشطة الاستثمارية
        $assetAccounts = Account::where('type', 'asset')
            ->whereIn('name', ['المعدات', 'الأراضي', 'المباني', 'المركبات'])
            ->pluck('id');
            
        return JournalEntryLine::whereIn('account_id', $assetAccounts)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('debit');
    }
    
    private function calculateFinancingCashFlow($startDate, $endDate)
    {
        // حساب التدفقات النقدية من الأنشطة التمويلية
        $liabilityAccounts = Account::where('type', 'liability')->pluck('id');
        $equityAccounts = Account::where('type', 'equity')->pluck('id');
        
        $allAccounts = $liabilityAccounts->merge($equityAccounts);
        
        return JournalEntryLine::whereIn('account_id', $allAccounts)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('credit');
    }
}
