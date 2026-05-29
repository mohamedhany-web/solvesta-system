<?php

namespace App\Services;

use App\Models\Account;
use App\Models\Expense;
use App\Models\FinancialInvoice;
use App\Models\Invoice;
use App\Models\JournalEntry;
use App\Models\JournalEntryLine;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class AccountingDashboardService
{
    protected array $postedStatuses = ['approved', 'posted'];

    public function build(): array
    {
        $now = Carbon::now();
        $monthStart = $now->copy()->startOfMonth();
        $monthEnd = $now->copy()->endOfMonth();

        $hasLedger = JournalEntryLine::whereHas(
            'journalEntry',
            fn ($q) => $q->whereIn('status', $this->postedStatuses)
        )->exists();

        $totalAssets = $this->sumTypeBalance('asset');
        $totalLiabilities = $this->sumTypeBalance('liability');
        $totalEquity = $this->sumTypeBalance('equity');
        $totalRevenue = $this->sumTypeBalance('revenue');
        $totalExpenses = $this->sumTypeBalance('expense');

        $monthlyRevenue = $this->monthlyRevenue($monthStart, $monthEnd);
        $monthlyExpenses = $this->monthlyExpenses($monthStart, $monthEnd);

        $netIncome = $totalRevenue - $totalExpenses;
        $cashBalance = $this->cashBalance();

        $pendingInvoices = $this->pendingInvoicesTotal();
        $pendingPayments = (float) Payment::where('status', 'pending')->sum('amount');

        $recentEntries = JournalEntry::with(['lines.account'])
            ->orderByDesc('date')
            ->orderByDesc('created_at')
            ->take(10)
            ->get()
            ->map(function ($entry) {
                $entry->status_color = match ($entry->status) {
                    'draft' => 'bg-gray-100 text-gray-800',
                    'posted' => 'bg-green-100 text-green-800',
                    'approved' => 'bg-blue-100 text-blue-800',
                    default => 'bg-gray-100 text-gray-800',
                };
                $entry->status_in_arabic = match ($entry->status) {
                    'draft' => 'مسودة',
                    'posted' => 'مرحّل',
                    'approved' => 'معتمد',
                    default => $entry->status,
                };
                $entry->total_debit = $entry->lines->sum('debit');

                return $entry;
            });

        $types = ['asset', 'liability', 'equity', 'revenue', 'expense'];
        $activeAccounts = [];
        foreach ($types as $type) {
            $activeAccounts[$type] = Account::where('is_active', true)
                ->where('type', $type)
                ->orderBy('code')
                ->get()
                ->map(fn (Account $a) => tap($a, fn ($acc) => $acc->computed_balance = $this->accountBalance($acc)));
        }

        return [
            'hasLedger' => $hasLedger,
            'totalAssets' => $totalAssets,
            'totalLiabilities' => $totalLiabilities,
            'totalEquity' => $totalEquity,
            'totalRevenue' => $totalRevenue,
            'totalExpenses' => $totalExpenses,
            'netIncome' => $netIncome,
            'cashBalance' => $cashBalance,
            'balanceSheetTotal' => $totalAssets,
            'balanceSheetLiabilitiesEquity' => $totalLiabilities + $totalEquity + $netIncome,
            'monthlyRevenue' => $monthlyRevenue,
            'monthlyExpenses' => $monthlyExpenses,
            'monthlyNet' => $monthlyRevenue - $monthlyExpenses,
            'pendingInvoices' => $pendingInvoices,
            'pendingPayments' => $pendingPayments,
            'recentEntries' => $recentEntries,
            'activeAccounts' => collect($activeAccounts),
            'monthlyTrend' => $this->monthlyTrend(6),
            'stats' => [
                'accounts_count' => Account::where('is_active', true)->count(),
                'journal_entries_count' => JournalEntry::count(),
                'posted_entries_count' => JournalEntry::whereIn('status', $this->postedStatuses)->count(),
                'unpaid_invoices_count' => $this->unpaidInvoicesCount(),
                'pending_payments_count' => Payment::where('status', 'pending')->count(),
                'expenses_month_count' => Expense::whereBetween('expense_date', [$monthStart, $monthEnd])
                    ->whereIn('status', ['approved', 'paid'])
                    ->count(),
            ],
        ];
    }

    public function accountBalance(Account $account): float
    {
        $query = JournalEntryLine::query()
            ->where('account_id', $account->id)
            ->whereHas('journalEntry', fn ($q) => $q->whereIn('status', $this->postedStatuses));

        $debit = (float) (clone $query)->sum('debit');
        $credit = (float) (clone $query)->sum('credit');

        if ($debit > 0 || $credit > 0) {
            return in_array($account->type, ['asset', 'expense'], true)
                ? $debit - $credit
                : $credit - $debit;
        }

        return 0.0;
    }

    protected function sumTypeBalance(string $type): float
    {
        return (float) Account::where('type', $type)
            ->where('is_active', true)
            ->get()
            ->sum(fn (Account $a) => $this->accountBalance($a));
    }

    protected function monthlyRevenue(Carbon $start, Carbon $end): float
    {
        $fromLedger = $this->sumLinesForTypeInPeriod('revenue', $start, $end, 'credit');

        $fromInvoices = (float) Invoice::where('status', 'paid')
            ->whereBetween('paid_date', [$start, $end])
            ->sum('paid_amount');

        $fromFinancial = (float) FinancialInvoice::where('status', 'paid')
            ->whereBetween('invoice_date', [$start, $end])
            ->sum('paid_amount');

        $fromPaymentsIn = (float) Payment::whereIn('status', ['completed', 'paid', 'approved'])
            ->whereBetween('payment_date', [$start, $end])
            ->where('payment_type', 'invoice')
            ->sum('amount');

        if ($fromLedger > 0) {
            return $fromLedger;
        }

        return $fromInvoices + $fromFinancial + $fromPaymentsIn;
    }

    protected function monthlyExpenses(Carbon $start, Carbon $end): float
    {
        $fromLedger = $this->sumLinesForTypeInPeriod('expense', $start, $end, 'debit');

        $fromExpenses = (float) Expense::whereIn('status', ['approved', 'paid'])
            ->whereBetween('expense_date', [$start, $end])
            ->sum('amount');

        $fromPaymentsOut = (float) Payment::whereIn('status', ['completed', 'paid', 'approved'])
            ->whereBetween('payment_date', [$start, $end])
            ->whereIn('payment_type', ['salary', 'expense'])
            ->sum('amount');

        if ($fromLedger > 0) {
            return $fromLedger;
        }

        return $fromExpenses + $fromPaymentsOut;
    }

    protected function sumLinesForTypeInPeriod(string $type, Carbon $start, Carbon $end, string $side): float
    {
        $accountIds = Account::where('type', $type)->pluck('id');
        if ($accountIds->isEmpty()) {
            return 0.0;
        }

        return (float) JournalEntryLine::query()
            ->whereIn('account_id', $accountIds)
            ->whereHas('journalEntry', function ($q) use ($start, $end) {
                $q->whereIn('status', $this->postedStatuses)
                    ->whereBetween('date', [$start->toDateString(), $end->toDateString()]);
            })
            ->sum($side);
    }

    protected function cashBalance(): float
    {
        $cashAccounts = Account::where('type', 'asset')
            ->where('is_active', true)
            ->where(function ($q) {
                $q->where('code', 'like', '11%')
                    ->orWhere('code', 'like', '12%')
                    ->orWhere('name', 'like', '%نقد%')
                    ->orWhere('name', 'like', '%بنك%')
                    ->orWhere('name', 'like', '%خزينة%');
            })
            ->get();

        if ($cashAccounts->isEmpty()) {
            return max(0, $this->sumTypeBalance('asset') - $this->sumTypeBalance('liability'));
        }

        return (float) $cashAccounts->sum(fn (Account $a) => $this->accountBalance($a));
    }

    protected function pendingInvoicesTotal(): float
    {
        $crm = (float) Invoice::whereNotIn('status', ['paid', 'cancelled'])
            ->sum('balance_amount');

        $financial = (float) FinancialInvoice::whereNotIn('status', ['paid', 'cancelled'])
            ->sum('balance_due');

        return $crm + $financial;
    }

    protected function unpaidInvoicesCount(): int
    {
        return Invoice::whereNotIn('status', ['paid', 'cancelled'])->count()
            + FinancialInvoice::whereNotIn('status', ['paid', 'cancelled'])->count();
    }

    protected function monthlyTrend(int $months): Collection
    {
        $trend = collect();

        for ($i = $months - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $start = $date->copy()->startOfMonth();
            $end = $date->copy()->endOfMonth();

            $trend->push([
                'label' => $date->locale('ar')->translatedFormat('M Y'),
                'revenue' => $this->monthlyRevenue($start, $end),
                'expenses' => $this->monthlyExpenses($start, $end),
            ]);
        }

        return $trend;
    }
}
