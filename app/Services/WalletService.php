<?php

namespace App\Services;

use App\Models\FinancialInvoice;
use App\Models\Payment;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\DB;

class WalletService
{
    public function recordInvoicePayment(FinancialInvoice $invoice, array $data): Payment
    {
        return DB::transaction(function () use ($invoice, $data) {
            $wallet = Wallet::where('id', $data['wallet_id'])->where('is_active', true)->lockForUpdate()->firstOrFail();

            $amount = round((float) $data['amount'], 2);
            if ($amount <= 0) {
                throw new \InvalidArgumentException('مبلغ الدفعة يجب أن يكون أكبر من صفر.');
            }

            $balanceDue = round((float) $invoice->balance_due, 2);
            if ($amount > $balanceDue + 0.01) {
                throw new \InvalidArgumentException('مبلغ الدفعة أكبر من المتبقي على الفاتورة.');
            }

            $payment = Payment::create([
                'payment_number' => $this->generatePaymentNumber(),
                'payment_type' => 'invoice',
                'payment_date' => $data['payment_date'] ?? now()->toDateString(),
                'amount' => $amount,
                'payment_method' => $data['payment_method'] ?? 'cash',
                'reference_number' => $data['reference_number'] ?? null,
                'invoice_id' => $invoice->id,
                'client_id' => $invoice->client_id,
                'wallet_id' => $wallet->id,
                'description' => $data['description'] ?? ('تحصيل فاتورة '.$invoice->invoice_number),
                'notes' => $data['notes'] ?? null,
                'status' => 'completed',
                'created_by' => auth()->id(),
            ]);

            $wallet->current_balance = round((float) $wallet->current_balance + $amount, 2);
            $wallet->save();

            WalletTransaction::create([
                'wallet_id' => $wallet->id,
                'direction' => 'in',
                'amount' => $amount,
                'balance_after' => $wallet->current_balance,
                'reference' => $payment->payment_number,
                'category' => 'invoice_payment',
                'source_type' => Payment::class,
                'source_id' => $payment->id,
                'financial_invoice_id' => $invoice->id,
                'payment_id' => $payment->id,
                'description' => $payment->description,
                'transaction_date' => $payment->payment_date,
                'created_by' => auth()->id(),
            ]);

            $invoice->paid_amount = round((float) $invoice->paid_amount + $amount, 2);
            $invoice->balance_due = round((float) $invoice->total_amount - (float) $invoice->paid_amount, 2);

            if ($invoice->balance_due <= 0.01) {
                $invoice->balance_due = 0;
                $invoice->status = 'paid';
                $invoice->payment_status = 'paid';
            } else {
                $invoice->status = 'partial';
                $invoice->payment_status = 'partial';
            }

            $invoice->save();

            return $payment->load('wallet');
        });
    }

    public function recordManualTransaction(Wallet $wallet, array $data): WalletTransaction
    {
        return DB::transaction(function () use ($wallet, $data) {
            $wallet = Wallet::where('id', $wallet->id)->lockForUpdate()->firstOrFail();
            $amount = round((float) $data['amount'], 2);
            $direction = $data['direction'] === 'out' ? 'out' : 'in';

            if ($amount <= 0) {
                throw new \InvalidArgumentException('المبلغ يجب أن يكون أكبر من صفر.');
            }

            if ($direction === 'out' && $amount > (float) $wallet->current_balance) {
                throw new \InvalidArgumentException('رصيد المحفظة غير كافٍ.');
            }

            $wallet->current_balance = $direction === 'in'
                ? round((float) $wallet->current_balance + $amount, 2)
                : round((float) $wallet->current_balance - $amount, 2);
            $wallet->save();

            return WalletTransaction::create([
                'wallet_id' => $wallet->id,
                'direction' => $direction,
                'amount' => $amount,
                'balance_after' => $wallet->current_balance,
                'reference' => $data['reference'] ?? null,
                'category' => $data['category'] ?? 'manual',
                'description' => $data['description'] ?? null,
                'transaction_date' => $data['transaction_date'] ?? now()->toDateString(),
                'created_by' => auth()->id(),
            ]);
        });
    }

    protected function generatePaymentNumber(): string
    {
        $prefix = 'PAY-'.now()->format('Ym').'-';
        $last = Payment::where('payment_number', 'like', $prefix.'%')
            ->orderByDesc('id')
            ->value('payment_number');

        $seq = 1;
        if ($last && preg_match('/-(\d+)$/', $last, $m)) {
            $seq = (int) $m[1] + 1;
        }

        return $prefix.str_pad((string) $seq, 4, '0', STR_PAD_LEFT);
    }
}
