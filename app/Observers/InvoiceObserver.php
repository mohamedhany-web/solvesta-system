<?php

namespace App\Observers;

use App\Models\Invoice;
use App\Services\ClientPortalNotifier;

class InvoiceObserver
{
    public function created(Invoice $invoice): void
    {
        if (! $invoice->client_id) {
            return;
        }

        ClientPortalNotifier::notify(
            (int) $invoice->client_id,
            'invoice_created',
            'فاتورة جديدة',
            'تم إصدار فاتورة رقم '.$invoice->invoice_number.'.',
            url('/client/invoices'),
            ['invoice_id' => $invoice->id]
        );
    }
}
