<?php

namespace App\Services\Accounting;

use App\Models\Account;
use App\Models\Expense;
use App\Models\FinancialInvoice;
use App\Models\Invoice;
use App\Models\JournalEntry;
use App\Models\JournalEntryLine;
use Illuminate\Support\Facades\DB;

class AccountingPostingService
{
    public function postInvoiceSent(Invoice|FinancialInvoice $invoice): void
    {
        $accounts = config('accounting');
        $arAccount = $this->getAccountByCode($accounts['accounts_receivable']);
        $revenueAccount = $this->getAccountByCode($accounts['revenue']);
        $taxPayableAccount = isset($accounts['tax_payable']) ? $this->getAccountByCode($accounts['tax_payable']) : null;

        DB::transaction(function () use ($invoice, $arAccount, $revenueAccount, $taxPayableAccount) {
            $totalAmount = (float) $invoice->total_amount;
            $taxAmount = (float) ($invoice->tax_amount ?? 0);
            $revenueAmount = (float) ($invoice->subtotal ?? $invoice->amount ?? ($totalAmount - $taxAmount));
            $entryDate = $invoice->issue_date
                ?? $invoice->invoice_date
                ?? now()->toDateString();

            $entry = JournalEntry::create([
                'date' => $entryDate instanceof \DateTimeInterface ? $entryDate->format('Y-m-d') : $entryDate,
                'reference' => 'INV-'.$invoice->invoice_number,
                'description' => 'Posting revenue for invoice #'.$invoice->invoice_number,
                'total_debit' => $totalAmount,
                'total_credit' => $totalAmount,
                'status' => 'posted',
                'created_by' => auth()->id(),
            ]);

            $this->createLine($entry->id, $arAccount, 'Accounts Receivable for invoice', $totalAmount, 0);
            $this->createLine($entry->id, $revenueAccount, 'Revenue for invoice', 0, $revenueAmount);

            if ($taxAmount > 0 && $taxPayableAccount) {
                $this->createLine($entry->id, $taxPayableAccount, 'Tax payable for invoice', 0, $taxAmount);
            }
        });
    }

    public function postInvoicePaid(Invoice|FinancialInvoice $invoice, ?float $amount = null, ?string $paymentDate = null): void
    {
        $accounts = config('accounting');
        $cashAccount = $this->getAccountByCode($accounts['cash_or_bank']);
        $arAccount = $this->getAccountByCode($accounts['accounts_receivable']);

        $paidAmount = $amount ?? (float) ($invoice->paid_amount ?? $invoice->total_amount);
        if ($paidAmount <= 0) {
            return;
        }

        $entryDate = $paymentDate
            ?? $invoice->payment_date
            ?? $invoice->paid_date
            ?? now()->toDateString();

        if ($entryDate instanceof \DateTimeInterface) {
            $entryDate = $entryDate->format('Y-m-d');
        }

        DB::transaction(function () use ($invoice, $cashAccount, $arAccount, $paidAmount, $entryDate) {
            $reference = 'INV-PAY-'.$invoice->invoice_number.'-'.now()->format('YmdHis');

            $entry = JournalEntry::create([
                'date' => $entryDate,
                'reference' => $reference,
                'description' => 'Customer payment for invoice #'.$invoice->invoice_number,
                'total_debit' => $paidAmount,
                'total_credit' => $paidAmount,
                'status' => 'posted',
                'created_by' => auth()->id(),
            ]);

            $this->createLine($entry->id, $cashAccount, 'Cash received', $paidAmount, 0);
            $this->createLine($entry->id, $arAccount, 'Reduce AR', 0, $paidAmount);
        });
    }

    public function postIncomingPayment(Invoice|FinancialInvoice|null $invoice, float $amount, ?string $reference = null): void
    {
        $accounts = config('accounting');
        $cashAccount = $this->getAccountByCode($accounts['cash_or_bank']);
        $unappliedReceiptsAccount = $this->getAccountByCode($accounts['unapplied_receipts']);

        DB::transaction(function () use ($invoice, $amount, $cashAccount, $unappliedReceiptsAccount, $reference) {
            $entry = JournalEntry::create([
                'date' => now()->toDateString(),
                'reference' => $reference ?? 'PAYMENT-IN',
                'description' => $invoice ? ('Payment for invoice #'.$invoice->invoice_number) : 'Unapplied incoming payment',
                'total_debit' => $amount,
                'total_credit' => $amount,
                'status' => 'posted',
                'created_by' => auth()->id(),
            ]);

            $this->createLine($entry->id, $cashAccount, 'Incoming payment', $amount, 0);

            $creditAccount = $invoice
                ? $this->getAccountByCode(config('accounting.accounts_receivable'))
                : $unappliedReceiptsAccount;

            $this->createLine(
                $entry->id,
                $creditAccount,
                $invoice ? 'Reduce AR' : 'Unapplied receipts',
                0,
                $amount
            );
        });
    }

    public function postExpenseApproved(Expense $expense): void
    {
        $accounts = config('accounting');
        $expenseAccount = $this->getAccountByCode($accounts['default_expense']);
        $cashAccount = $this->getAccountByCode($accounts['cash_or_bank']);

        DB::transaction(function () use ($expense, $expenseAccount, $cashAccount) {
            $amount = (float) $expense->amount;
            $entry = JournalEntry::create([
                'date' => $expense->expense_date ?? now()->toDateString(),
                'reference' => 'EXP-'.$expense->expense_number,
                'description' => 'Expense approved #'.$expense->description,
                'total_debit' => $amount,
                'total_credit' => $amount,
                'status' => 'posted',
                'created_by' => auth()->id(),
            ]);

            $this->createLine($entry->id, $expenseAccount, 'Expense', $amount, 0);
            $this->createLine($entry->id, $cashAccount, 'Cash/Bank', 0, $amount);
        });
    }

    private function createLine(int $journalEntryId, Account $account, string $description, float $debit, float $credit): void
    {
        JournalEntryLine::create([
            'journal_entry_id' => $journalEntryId,
            'account_id' => $account->id,
            'description' => $description,
            'debit' => $debit,
            'credit' => $credit,
        ]);

        // Keep cached Account.balance in sync with ledger lines (same rules as manual journals).
        if (in_array($account->type, ['asset', 'expense'], true)) {
            $account->increment('balance', $debit - $credit);
        } else {
            $account->increment('balance', $credit - $debit);
        }
    }

    private function getAccountByCode(string $code): Account
    {
        $account = Account::where('code', $code)->first();
        if (! $account) {
            throw new \RuntimeException("Account with code {$code} not found. Configure config/accounting.php correctly.");
        }

        return $account;
    }
}


