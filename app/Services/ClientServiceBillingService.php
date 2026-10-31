<?php

namespace App\Services;

use App\Models\ClientService;
use App\Models\ClientSystemFeature;
use App\Models\ClientWebsiteIssue;
use App\Models\FinancialInvoice;
use App\Models\FinancialInvoiceItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class ClientServiceBillingService
{
    /**
     * @param  array<int, array{type: string, id: int, amount: float, description?: string}>  $extraLines
     */
    public function generateMonthlyInvoice(
        ClientService $service,
        ?Carbon $billingDate = null,
        array $extraLines = [],
        bool $advanceBillingDate = true,
        ?string $notes = null
    ): FinancialInvoice {
        return DB::transaction(function () use ($service, $billingDate, $extraLines, $advanceBillingDate, $notes) {
            $service = ClientService::where('id', $service->id)->lockForUpdate()->firstOrFail();

            if ($service->status !== 'active' && $service->status !== 'paused') {
                throw new \InvalidArgumentException('الخدمة غير نشطة.');
            }

            $invoiceDate = $billingDate ?? $service->next_billing_date ?? now();
            if (! $invoiceDate instanceof Carbon) {
                $invoiceDate = Carbon::parse($invoiceDate);
            }

            $periodLabel = $invoiceDate->locale('ar')->translatedFormat('F Y');
            $monthStart = $invoiceDate->copy()->startOfMonth()->toDateString();
            $monthEnd = $invoiceDate->copy()->endOfMonth()->toDateString();

            if ($advanceBillingDate && FinancialInvoice::where('client_service_id', $service->id)
                ->whereBetween('invoice_date', [$monthStart, $monthEnd])
                ->exists()) {
                throw new \InvalidArgumentException('تم إصدار فاتورة هذا الشهر لهذه الخدمة مسبقاً.');
            }

            $subscriptionAmount = round((float) $service->monthly_amount, 2);
            $resolvedExtras = $this->resolveExtraLines($service->client_id, $extraLines);
            $extrasSubtotal = round($resolvedExtras->sum('amount'), 2);
            $subtotal = round($subscriptionAmount + $extrasSubtotal, 2);

            $taxRate = (float) ($service->tax_rate ?? 0);
            if ($taxRate <= 0) {
                $taxRate = (float) \App\Helpers\SettingsHelper::getDefaultTaxRate();
            }
            $taxAmount = round($subtotal * $taxRate / 100, 2);
            $total = round($subtotal + $taxAmount, 2);

            $invoice = FinancialInvoice::create([
                'invoice_number' => FinancialInvoice::generateInvoiceNumber(),
                'invoice_type' => 'service',
                'client_id' => $service->client_id,
                'contract_id' => $service->contract_id,
                'client_service_id' => $service->id,
                'invoice_date' => $invoiceDate->toDateString(),
                'due_date' => $invoiceDate->copy()->addDays((int) ($service->payment_terms_days ?: \App\Helpers\SettingsHelper::getDefaultPaymentPeriod()))->toDateString(),
                'description' => 'فاتورة خدمة شهرية — '.$service->title,
                'subtotal' => $subtotal,
                'tax_rate' => $taxRate,
                'tax_amount' => $taxAmount,
                'discount_amount' => 0,
                'total_amount' => $total,
                'paid_amount' => 0,
                'balance_due' => $total,
                'status' => 'sent',
                'payment_status' => 'unpaid',
                'currency' => $service->currency ?? 'EGP',
                'notes' => $notes ?: ('فاتورة شهرية لخدمة ما بعد البيع — '.$periodLabel),
                'created_by' => auth()->id(),
            ]);

            FinancialInvoiceItem::create([
                'invoice_id' => $invoice->id,
                'item_name' => mb_substr((string) $service->title, 0, 255),
                'description' => 'اشتراك / خدمة شهرية — '.$periodLabel,
                'quantity' => 1,
                'unit_price' => $subscriptionAmount,
                'amount' => $subscriptionAmount,
            ]);

            foreach ($resolvedExtras as $line) {
                FinancialInvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'item_name' => mb_substr((string) $line['item_name'], 0, 255),
                    'description' => $line['description'],
                    'quantity' => 1,
                    'unit_price' => $line['amount'],
                    'amount' => $line['amount'],
                    'billable_type' => $line['billable_type'],
                    'billable_id' => $line['billable_id'],
                ]);
            }

            if ($advanceBillingDate) {
                $service->next_billing_date = $service->computeNextBillingDate($invoiceDate)->toDateString();
                if ($service->end_date && $service->next_billing_date > $service->end_date) {
                    $service->status = 'ended';
                }
                $service->save();
            }

            ClientPortalNotifier::notify(
                $service->client_id,
                'invoice',
                'فاتورة خدمة جديدة',
                'تم إصدار فاتورة '.$periodLabel.' بمبلغ '.number_format($total, 2).' '.$service->currency,
                route('client.invoices'),
                ['invoice_id' => $invoice->id, 'client_service_id' => $service->id]
            );

            return $invoice->load(['client', 'items', 'clientService']);
        });
    }

    /**
     * Unbilled website features/issues for a client.
     *
     * @return array{features: Collection, issues: Collection}
     */
    public function unbilledRequirementsForClient(int $clientId): array
    {
        $billedFeatureIds = FinancialInvoiceItem::query()
            ->where('billable_type', ClientSystemFeature::class)
            ->whereHas('invoice', fn ($q) => $q->where('status', '!=', 'cancelled'))
            ->pluck('billable_id')
            ->all();

        $billedIssueIds = FinancialInvoiceItem::query()
            ->where('billable_type', ClientWebsiteIssue::class)
            ->whereHas('invoice', fn ($q) => $q->where('status', '!=', 'cancelled'))
            ->pluck('billable_id')
            ->all();

        $features = ClientSystemFeature::query()
            ->whereHas('project', fn ($q) => $q->where('client_id', $clientId))
            ->whereNotIn('status', ['rejected', 'cancelled'])
            ->when(! empty($billedFeatureIds), fn ($q) => $q->whereNotIn('id', $billedFeatureIds))
            ->orderByDesc('created_at')
            ->get();

        $issues = ClientWebsiteIssue::query()
            ->where('client_id', $clientId)
            ->whereNotIn('status', ['closed'])
            ->when(! empty($billedIssueIds), fn ($q) => $q->whereNotIn('id', $billedIssueIds))
            ->orderByDesc('created_at')
            ->get();

        return compact('features', 'issues');
    }

    /**
     * @param  array<int, array{type: string, id: int, amount: float|int|string, description?: string}>  $extraLines
     */
    protected function resolveExtraLines(int $clientId, array $extraLines): Collection
    {
        $resolved = collect();

        foreach ($extraLines as $line) {
            $type = $line['type'] ?? '';
            $id = (int) ($line['id'] ?? 0);
            $amount = round((float) ($line['amount'] ?? 0), 2);
            if ($id <= 0 || $amount < 0) {
                continue;
            }

            if ($type === 'feature') {
                $feature = ClientSystemFeature::whereHas('project', fn ($q) => $q->where('client_id', $clientId))
                    ->whereKey($id)
                    ->first();
                if (! $feature) {
                    throw new \InvalidArgumentException('ميزة غير صالحة للعميل المحدد.');
                }
                if ($this->isAlreadyBilled(ClientSystemFeature::class, $feature->id)) {
                    throw new \InvalidArgumentException('تم فوترة الميزة '.$feature->reference_code.' مسبقاً.');
                }
                $label = ($feature->type_label ?? 'ميزة').': '.$feature->title;
                $resolved->push([
                    'item_name' => $feature->reference_code.' — '.$feature->title,
                    'description' => $line['description'] ?? $label,
                    'amount' => $amount,
                    'billable_type' => ClientSystemFeature::class,
                    'billable_id' => $feature->id,
                ]);
            } elseif ($type === 'issue') {
                $issue = ClientWebsiteIssue::where('client_id', $clientId)->whereKey($id)->first();
                if (! $issue) {
                    throw new \InvalidArgumentException('مشكلة موقع غير صالحة للعميل المحدد.');
                }
                if ($this->isAlreadyBilled(ClientWebsiteIssue::class, $issue->id)) {
                    throw new \InvalidArgumentException('تم فوترة المشكلة '.$issue->reference_code.' مسبقاً.');
                }
                $label = 'مشكلة موقع: '.$issue->title;
                $resolved->push([
                    'item_name' => $issue->reference_code.' — '.$issue->title,
                    'description' => $line['description'] ?? $label,
                    'amount' => $amount,
                    'billable_type' => ClientWebsiteIssue::class,
                    'billable_id' => $issue->id,
                ]);
            }
        }

        return $resolved;
    }

    protected function isAlreadyBilled(string $type, int $id): bool
    {
        return FinancialInvoiceItem::query()
            ->where('billable_type', $type)
            ->where('billable_id', $id)
            ->whereHas('invoice', fn ($q) => $q->where('status', '!=', 'cancelled'))
            ->exists();
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
