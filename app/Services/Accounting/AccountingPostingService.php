<?php

namespace App\Services\Accounting;

use App\Models\Invoice;
use App\Models\Expense;
use App\Models\JournalEntry;
use App\Models\JournalEntryLine;
use App\Models\Account;
use Illuminate\Support\Facades\DB;

class AccountingPostingService
{
    public function postInvoiceSent(Invoice $invoice): void
    {
        // الاعتراف بالإيراد مقابل الذمم المدينة
        $accounts = config('accounting');
        $arAccount = $this->getAccountByCode($accounts['accounts_receivable']);
        $revenueAccount = $this->getAccountByCode($accounts['revenue']);
        $taxPayableAccount = isset($accounts['tax_payable']) ? $this->getAccountByCode($accounts['tax_payable']) : null;

        DB::transaction(function () use ($invoice, $arAccount, $revenueAccount, $taxPayableAccount) {
            $totalDebit = $invoice->total_amount;
            $totalCredit = $invoice->total_amount;

            $entry = JournalEntry::create([
                'date' => $invoice->issue_date ?? now()->toDateString(),
                'reference' => 'INV-' . $invoice->invoice_number,
                'description' => 'Posting revenue for invoice #' . $invoice->invoice_number,
                'total_debit' => $totalDebit,
                'total_credit' => $totalCredit,
                'status' => 'posted',
                'created_by' => auth()->id(),
            ]);

            // Dr Accounts Receivable
            JournalEntryLine::create([
                'journal_entry_id' => $entry->id,
                'account_id' => $arAccount->id,
                'description' => 'Accounts Receivable for invoice',
                'debit' => $invoice->total_amount,
                'credit' => 0,
            ]);

            $revenueAmount = $invoice->amount;
            $taxAmount = (float) ($invoice->tax_amount ?? 0);

            // Cr Revenue
            JournalEntryLine::create([
                'journal_entry_id' => $entry->id,
                'account_id' => $revenueAccount->id,
                'description' => 'Revenue for invoice',
                'debit' => 0,
                'credit' => $revenueAmount,
            ]);

            // Cr Tax Payable (if any)
            if ($taxAmount > 0 && $taxPayableAccount) {
                JournalEntryLine::create([
                    'journal_entry_id' => $entry->id,
                    'account_id' => $taxPayableAccount->id,
                    'description' => 'Tax payable for invoice',
                    'debit' => 0,
                    'credit' => $taxAmount,
                ]);
            }
        });
    }

    public function postInvoicePaid(Invoice $invoice, float $amount = null): void
    {
        // استلام نقدي/بنك مقابل الذمم المدينة
        $accounts = config('accounting');
        $cashAccount = $this->getAccountByCode($accounts['cash_or_bank']);
        $arAccount = $this->getAccountByCode($accounts['accounts_receivable']);

        $paidAmount = $amount ?? (float) ($invoice->paid_amount ?? $invoice->total_amount);
        if ($paidAmount <= 0) {
            return;
        }

        DB::transaction(function () use ($invoice, $cashAccount, $arAccount, $paidAmount) {
            $entry = JournalEntry::create([
                'date' => $invoice->payment_date ?? now()->toDateString(),
                'reference' => 'INV-PAY-' . $invoice->invoice_number,
                'description' => 'Customer payment for invoice #' . $invoice->invoice_number,
                'total_debit' => $paidAmount,
                'total_credit' => $paidAmount,
                'status' => 'posted',
                'created_by' => auth()->id(),
            ]);

            // Dr Cash/Bank
            JournalEntryLine::create([
                'journal_entry_id' => $entry->id,
                'account_id' => $cashAccount->id,
                'description' => 'Cash received',
                'debit' => $paidAmount,
                'credit' => 0,
            ]);

            // Cr Accounts Receivable
            JournalEntryLine::create([
                'journal_entry_id' => $entry->id,
                'account_id' => $arAccount->id,
                'description' => 'Reduce AR',
                'debit' => 0,
                'credit' => $paidAmount,
            ]);
        });
    }

    public function postIncomingPayment(?Invoice $invoice, float $amount, string $reference = null): void
    {
        // مدفوعات واردة غير مرتبطة بفاتورة: نقد/بنك مقابل إيرادات أخرى أو أرباح مؤجلة
        $accounts = config('accounting');
        $cashAccount = $this->getAccountByCode($accounts['cash_or_bank']);
        $unappliedReceiptsAccount = $this->getAccountByCode($accounts['unapplied_receipts']);

        DB::transaction(function () use ($invoice, $amount, $cashAccount, $unappliedReceiptsAccount, $reference) {
            $entry = JournalEntry::create([
                'date' => now()->toDateString(),
                'reference' => $reference ?? 'PAYMENT-IN',
                'description' => $invoice ? ('Payment for invoice #' . $invoice->invoice_number) : 'Unapplied incoming payment',
                'total_debit' => $amount,
                'total_credit' => $amount,
                'status' => 'posted',
                'created_by' => auth()->id(),
            ]);

            // Dr Cash/Bank
            JournalEntryLine::create([
                'journal_entry_id' => $entry->id,
                'account_id' => $cashAccount->id,
                'description' => 'Incoming payment',
                'debit' => $amount,
                'credit' => 0,
            ]);

            // Cr Unapplied Receipts (or AR if invoice provided)
            $creditAccount = $invoice ? $this->getAccountByCode(config('accounting.accounts_receivable')) : $unappliedReceiptsAccount;
            JournalEntryLine::create([
                'journal_entry_id' => $entry->id,
                'account_id' => $creditAccount->id,
                'description' => $invoice ? 'Reduce AR' : 'Unapplied receipts',
                'debit' => 0,
                'credit' => $amount,
            ]);
        });
    }

    public function postExpenseApproved(Expense $expense): void
    {
        // مصروف مقابل نقد/بنك (تبسيطاً)
        $accounts = config('accounting');
        $expenseAccount = $this->getAccountByCode($accounts['default_expense']);
        $cashAccount = $this->getAccountByCode($accounts['cash_or_bank']);

        DB::transaction(function () use ($expense, $expenseAccount, $cashAccount) {
            $amount = (float) $expense->amount;
            $entry = JournalEntry::create([
                'date' => $expense->expense_date ?? now()->toDateString(),
                'reference' => 'EXP-' . $expense->expense_number,
                'description' => 'Expense approved #' . $expense->description,
                'total_debit' => $amount,
                'total_credit' => $amount,
                'status' => 'posted',
                'created_by' => auth()->id(),
            ]);

            // Dr Expense
            JournalEntryLine::create([
                'journal_entry_id' => $entry->id,
                'account_id' => $expenseAccount->id,
                'description' => 'Expense',
                'debit' => $amount,
                'credit' => 0,
            ]);

            // Cr Cash/Bank
            JournalEntryLine::create([
                'journal_entry_id' => $entry->id,
                'account_id' => $cashAccount->id,
                'description' => 'Cash/Bank',
                'debit' => 0,
                'credit' => $amount,
            ]);
        });
    }

    private function getAccountByCode(string $code): Account
    {
        $account = Account::where('code', $code)->first();
        if (!$account) {
            throw new \RuntimeException("Account with code {$code} not found. Configure config/accounting.php correctly.");
        }
        return $account;
    }
}


