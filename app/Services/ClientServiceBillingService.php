<?php

namespace App\Services;

use App\Models\ClientService;
use App\Models\FinancialInvoice;
use App\Models\FinancialInvoiceItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ClientServiceBillingService
{
    public function generateMonthlyInvoice(ClientService $service, ?Carbon $billingDate = null): FinancialInvoice
    {
        return DB::transaction(function () use ($service, $billingDate) {
            $service = ClientService::where('id', $service->id)->lockForUpdate()->firstOrFail();

            if ($service->status !== 'active') {
                throw new \InvalidArgumentException('الخدمة غير نشطة.');
            }

            $invoiceDate = $billingDate ?? $service->next_billing_date ?? now();
            if (! $invoiceDate instanceof Carbon) {
                $invoiceDate = Carbon::parse($invoiceDate);
            }

            $periodLabel = $invoiceDate->locale('ar')->translatedFormat('F Y');
            $monthStart = $invoiceDate->copy()->startOfMonth()->toDateString();
            $monthEnd = $invoiceDate->copy()->endOfMonth()->toDateString();

            if (FinancialInvoice::where('client_service_id', $service->id)
                ->whereBetween('invoice_date', [$monthStart, $monthEnd])
                ->exists()) {
                throw new \InvalidArgumentException('تم إصدار فاتورة هذا الشهر لهذه الخدمة مسبقاً.');
            }

            $amount = round((float) $service->monthly_amount, 2);
            $taxRate = (float) ($service->tax_rate ?? 0);
            $taxAmount = round($amount * $taxRate / 100, 2);
            $total = round($amount + $taxAmount, 2);

            $invoice = FinancialInvoice::create([
                'invoice_number' => FinancialInvoice::generateInvoiceNumber(),
                'invoice_type' => 'service',
                'client_id' => $service->client_id,
                'contract_id' => $service->contract_id,
                'client_service_id' => $service->id,
                'invoice_date' => $invoiceDate->toDateString(),
                'due_date' => $invoiceDate->copy()->addDays((int) $service->payment_terms_days)->toDateString(),
                'description' => 'فاتورة خدمة شهرية — '.$service->title,
                'subtotal' => $amount,
                'tax_rate' => $taxRate,
                'tax_amount' => $taxAmount,
                'discount_amount' => 0,
                'total_amount' => $total,
                'paid_amount' => 0,
                'balance_due' => $total,
                'status' => 'sent',
                'payment_status' => 'unpaid',
                'currency' => $service->currency ?? 'EGP',
                'notes' => 'فاتورة تلقائية لخدمة ما بعد البيع — '.$periodLabel,
                'created_by' => auth()->id(),
            ]);

            FinancialInvoiceItem::create([
                'invoice_id' => $invoice->id,
                'item_name' => $service->title,
                'description' => 'اشتراك / خدمة شهرية — '.$periodLabel,
                'quantity' => 1,
                'unit_price' => $amount,
                'amount' => $amount,
            ]);

            $service->next_billing_date = $service->computeNextBillingDate($invoiceDate)->toDateString();
            if ($service->end_date && $service->next_billing_date > $service->end_date) {
                $service->status = 'ended';
            }
            $service->save();

            ClientPortalNotifier::notify(
                $service->client_id,
                'invoice',
                'فاتورة خدمة جديدة',
                'تم إصدار فاتورة '.$periodLabel.' بمبلغ '.number_format($total, 2).' '.$service->currency,
                route('client.invoices'),
                ['invoice_id' => $invoice->id, 'client_service_id' => $service->id]
            );

            return $invoice->load(['client', 'items']);
        });
    }

    public function processDueServices(): int
    {
        $count = 0;
        ClientService::billableToday()->with('client')->orderBy('id')->chunkById(50, function ($services) use (&$count) {
            foreach ($services as $service) {
                try {
                    $this->generateMonthlyInvoice($service);
                    $count++;
                } catch (\Throwable $e) {
                    report($e);
                }
            }
        });

        return $count;
    }
}
