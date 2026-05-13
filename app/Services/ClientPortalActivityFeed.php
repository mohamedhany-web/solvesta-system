<?php

namespace App\Services;

use App\Models\ClientMeetingRequest;
use App\Models\ClientServiceReport;
use App\Models\ClientSharedDocument;
use App\Models\ClientWebsiteIssue;
use App\Models\Client;
use App\Models\FinancialInvoice;
use App\Models\Invoice;
use App\Models\Ticket;
use Illuminate\Support\Collection;

class ClientPortalActivityFeed
{
    /**
     * @return Collection<int, array{at:\Carbon\Carbon, label:string, url:?string, kind:string}>
     */
    public static function recent(Client $client, int $limit = 15): Collection
    {
        $items = collect();

        ClientServiceReport::where('client_id', $client->id)
            ->latest('created_at')->limit(5)->get()
            ->each(function ($r) use ($items) {
                $items->push([
                    'at' => $r->created_at,
                    'label' => 'تقرير خدمة: '.$r->title,
                    'url' => route('client.service-reports'),
                    'kind' => 'report',
                ]);
            });

        ClientWebsiteIssue::where('client_id', $client->id)
            ->latest('updated_at')->limit(5)->get()
            ->each(function ($w) use ($items) {
                $items->push([
                    'at' => $w->updated_at,
                    'label' => 'بلاغ موقع: '.$w->title.' ('.$w->status_label.')',
                    'url' => route('client.website-issues.show', $w),
                    'kind' => 'issue',
                ]);
            });

        ClientMeetingRequest::where('client_id', $client->id)
            ->latest('updated_at')->limit(5)->get()
            ->each(function ($m) use ($items) {
                $items->push([
                    'at' => $m->updated_at,
                    'label' => 'طلب اجتماع: '.$m->title.' ('.$m->status_label.')',
                    'url' => route('client.meeting-requests.show', $m),
                    'kind' => 'meeting',
                ]);
            });

        ClientSharedDocument::where('client_id', $client->id)
            ->latest('created_at')->limit(5)->get()
            ->each(function ($d) use ($items) {
                $items->push([
                    'at' => $d->created_at,
                    'label' => 'مستند مشترك: '.$d->title,
                    'url' => route('client.documents.index'),
                    'kind' => 'document',
                ]);
            });

        Invoice::where('client_id', $client->id)
            ->latest('updated_at')->limit(4)->get()
            ->each(function ($inv) use ($items) {
                $items->push([
                    'at' => $inv->updated_at,
                    'label' => 'فاتورة '.$inv->invoice_number.' — '.$inv->status,
                    'url' => route('client.invoices'),
                    'kind' => 'invoice',
                ]);
            });

        FinancialInvoice::where('client_id', $client->id)
            ->latest('updated_at')->limit(4)->get()
            ->each(function ($inv) use ($items) {
                $items->push([
                    'at' => $inv->updated_at,
                    'label' => 'فاتورة مالية '.$inv->invoice_number.' — '.$inv->payment_status,
                    'url' => route('client.invoices'),
                    'kind' => 'fin_invoice',
                ]);
            });

        Ticket::where('client_id', $client->id)
            ->latest('updated_at')->limit(5)->get()
            ->each(function ($t) use ($items) {
                $items->push([
                    'at' => $t->updated_at,
                    'label' => 'تذكرة '.$t->ticket_number.' — '.$t->status_name,
                    'url' => route('client.support.tickets.show', $t),
                    'kind' => 'ticket',
                ]);
            });

        return $items->sortByDesc('at')->values()->take($limit);
    }
}
