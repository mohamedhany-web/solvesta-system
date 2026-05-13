<?php

namespace App\Observers;

use App\Models\FinancialInvoice;
use App\Services\ClientPortalNotifier;

class FinancialInvoiceObserver
{
    public function created(FinancialInvoice $invoice): void
    {
        if (! $invoice->client_id) {
            return;
        }

        ClientPortalNotifier::notify(
            (int) $invoice->client_id,
            'financial_invoice_created',
            'فاتورة مالية جديدة',
            'تم إصدار فاتورة مالية رقم '.$invoice->invoice_number.'.',
            url('/client/invoices'),
            ['financial_invoice_id' => $invoice->id]
        );
    }
}
