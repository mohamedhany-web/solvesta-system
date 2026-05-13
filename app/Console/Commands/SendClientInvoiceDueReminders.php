<?php

namespace App\Console\Commands;

use App\Models\ClientNotification;
use App\Models\FinancialInvoice;
use App\Models\Invoice;
use App\Services\ClientPortalNotifier;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendClientInvoiceDueReminders extends Command
{
    protected $signature = 'client-portal:invoice-due-reminders';

    protected $description = 'Create client notifications for invoices due within 7 days';

    public function handle(): int
    {
        $until = Carbon::now()->addDays(7)->startOfDay();

        Invoice::query()
            ->where('status', '!=', 'paid')
            ->whereNotNull('due_date')
            ->whereBetween('due_date', [Carbon::now()->startOfDay(), $until])
            ->whereNotNull('client_id')
            ->each(function (Invoice $inv) {
                $this->notifyOnce(
                    (int) $inv->client_id,
                    'invoice_due_soon',
                    'استحقاق فاتورة قريب',
                    'الفاتورة '.$inv->invoice_number.' مستحقة بتاريخ '.$inv->due_date->format('Y/m/d').'.',
                    url('/client/invoices'),
                    ['invoice_id' => $inv->id]
                );
            });

        FinancialInvoice::query()
            ->where('payment_status', '!=', 'paid')
            ->whereNotNull('due_date')
            ->whereBetween('due_date', [Carbon::now()->startOfDay(), $until])
            ->whereNotNull('client_id')
            ->each(function (FinancialInvoice $inv) {
                $this->notifyOnce(
                    (int) $inv->client_id,
                    'financial_invoice_due_soon',
                    'استحقاق فاتورة مالية قريب',
                    'الفاتورة المالية '.$inv->invoice_number.' مستحقة بتاريخ '.$inv->due_date->format('Y/m/d').'.',
                    url('/client/invoices'),
                    ['financial_invoice_id' => $inv->id]
                );
            });

        $this->info('Done.');

        return self::SUCCESS;
    }

    private function notifyOnce(int $clientId, string $type, string $title, string $body, string $url, array $meta): void
    {
        $q = ClientNotification::where('client_id', $clientId)
            ->where('type', $type)
            ->where('created_at', '>=', now()->subDay());

        if (isset($meta['invoice_id'])) {
            $q->where('meta->invoice_id', $meta['invoice_id']);
        }
        if (isset($meta['financial_invoice_id'])) {
            $q->where('meta->financial_invoice_id', $meta['financial_invoice_id']);
        }

        if ($q->exists()) {
            return;
        }

        ClientPortalNotifier::notify($clientId, $type, $title, $body, $url, $meta);
    }
}
