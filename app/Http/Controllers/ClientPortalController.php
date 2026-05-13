<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\ClientMeetingRequest;
use App\Models\ClientNotification;
use App\Models\ClientServiceReport;
use App\Models\ClientSharedDocument;
use App\Models\ClientWebsiteIssue;
use App\Models\FinancialInvoice;
use App\Models\Invoice;
use App\Models\Project;
use App\Models\Ticket;
use App\Services\ClientPortalActivityFeed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ClientPortalController extends Controller
{
    public function dashboard(Request $request)
    {
        $account = $request->user('client');
        $client = $account?->client;

        if (!$client) {
            abort(403, 'لا يوجد عميل مرتبط بهذا الحساب');
        }

        $projectsCount = Project::where('client_id', $client->id)->count();
        $ticketsOpenCount = Ticket::where('client_id', $client->id)->whereIn('status', ['open', 'in_progress', 'pending_client'])->count();

        $invoicesCount = Invoice::where('client_id', $client->id)->count();
        $invoicesUnpaidAmount = Invoice::where('client_id', $client->id)->where('status', '!=', 'paid')->sum('balance_amount');

        $financialInvoicesCount = FinancialInvoice::where('client_id', $client->id)->count();
        $financialInvoicesUnpaidAmount = FinancialInvoice::where('client_id', $client->id)->where('payment_status', '!=', 'paid')->sum('balance_due');

        $serviceReportsCount = ClientServiceReport::where('client_id', $client->id)->count();

        $websiteIssuesOpenCount = ClientWebsiteIssue::where('client_id', $client->id)
            ->whereIn('status', ['open', 'in_progress'])
            ->count();

        $meetingRequestsActiveCount = ClientMeetingRequest::where('client_id', $client->id)
            ->whereIn('status', ['pending', 'confirmed'])
            ->count();

        $notificationsUnread = ClientNotification::where('client_id', $client->id)->whereNull('read_at')->count();

        $ticketsAwaitingClient = Ticket::where('client_id', $client->id)
            ->where('status', 'pending_client')
            ->count();

        $invoicesUnpaidCount = Invoice::where('client_id', $client->id)
            ->where('status', '!=', 'paid')
            ->where('status', '!=', 'cancelled')
            ->count();

        $financialUnpaidCount = FinancialInvoice::where('client_id', $client->id)
            ->where('payment_status', '!=', 'paid')
            ->count();

        $meetingPendingReview = ClientMeetingRequest::where('client_id', $client->id)
            ->where('status', 'pending')
            ->count();

        $invoicesDueSoon = Invoice::where('client_id', $client->id)
            ->where('status', '!=', 'paid')
            ->whereNotNull('due_date')
            ->whereBetween('due_date', [now()->startOfDay(), now()->addDays(7)->endOfDay()])
            ->orderBy('due_date')
            ->limit(5)
            ->get(['id', 'invoice_number', 'due_date', 'balance_amount', 'payment_link']);

        $financialDueSoon = FinancialInvoice::where('client_id', $client->id)
            ->where('payment_status', '!=', 'paid')
            ->whereNotNull('due_date')
            ->whereBetween('due_date', [now()->startOfDay(), now()->addDays(7)->endOfDay()])
            ->orderBy('due_date')
            ->limit(5)
            ->get(['id', 'invoice_number', 'due_date', 'balance_due', 'payment_link']);

        $activityItems = ClientPortalActivityFeed::recent($client, 12);

        $upcomingMeetings = ClientMeetingRequest::where('client_id', $client->id)
            ->where('status', 'confirmed')
            ->whereNotNull('scheduled_at')
            ->where('scheduled_at', '>=', now()->subDay())
            ->orderBy('scheduled_at')
            ->limit(8)
            ->get();

        $clientAccount = $account;

        return view('client-portal.dashboard', compact(
            'client',
            'clientAccount',
            'projectsCount',
            'ticketsOpenCount',
            'invoicesCount',
            'invoicesUnpaidAmount',
            'financialInvoicesCount',
            'financialInvoicesUnpaidAmount',
            'serviceReportsCount',
            'websiteIssuesOpenCount',
            'meetingRequestsActiveCount',
            'notificationsUnread',
            'ticketsAwaitingClient',
            'invoicesUnpaidCount',
            'financialUnpaidCount',
            'meetingPendingReview',
            'invoicesDueSoon',
            'financialDueSoon',
            'activityItems',
            'upcomingMeetings',
        ));
    }

    public function projects(Request $request)
    {
        $account = $request->user('client');
        $client = $account?->client;
        if (!$client) abort(403, 'لا يوجد عميل مرتبط بهذا الحساب');

        $projects = Project::where('client_id', $client->id)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('client-portal.projects', compact('client', 'projects'));
    }

    public function invoices(Request $request)
    {
        $account = $request->user('client');
        $client = $account?->client;
        if (!$client) abort(403, 'لا يوجد عميل مرتبط بهذا الحساب');

        $invoices = Invoice::where('client_id', $client->id)->orderBy('created_at', 'desc')->paginate(10, ['*'], 'invoices_page');
        $financialInvoices = FinancialInvoice::where('client_id', $client->id)->orderBy('created_at', 'desc')->paginate(10, ['*'], 'fin_invoices_page');

        return view('client-portal.invoices', compact('client', 'invoices', 'financialInvoices'));
    }

    public function serviceReports(Request $request)
    {
        $account = $request->user('client');
        $client = $account?->client;
        if (! $client) {
            abort(403, 'لا يوجد عميل مرتبط بهذا الحساب');
        }

        $reports = ClientServiceReport::where('client_id', $client->id)
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('client-portal.service-reports', compact('client', 'reports'));
    }

    public function downloadServiceReport(Request $request, ClientServiceReport $serviceReport)
    {
        $account = $request->user('client');
        $client = $account?->client;
        if (! $client || $serviceReport->client_id !== $client->id) {
            abort(403);
        }

        if (! Storage::disk('local')->exists($serviceReport->file_path)) {
            abort(404, 'الملف غير متوفر');
        }

        return Storage::disk('local')->download(
            $serviceReport->file_path,
            $serviceReport->original_filename ?? basename($serviceReport->file_path)
        );
    }

    public function notifications(Request $request)
    {
        $client = $this->requireClient($request);
        $notifications = ClientNotification::where('client_id', $client->id)
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('client-portal.notifications', compact('client', 'notifications'));
    }

    public function markNotificationRead(Request $request, ClientNotification $clientNotification)
    {
        $client = $this->requireClient($request);
        if ((int) $clientNotification->client_id !== (int) $client->id) {
            abort(403);
        }
        $clientNotification->markRead();

        return redirect()->back()->with('success', 'تم تعليم الإشعار كمقروء');
    }

    public function markAllNotificationsRead(Request $request)
    {
        $client = $this->requireClient($request);
        ClientNotification::where('client_id', $client->id)->whereNull('read_at')->update(['read_at' => now()]);

        return redirect()->route('client.notifications')->with('success', 'تم تعليم كل الإشعارات كمقروءة');
    }

    public function documents(Request $request)
    {
        $client = $this->requireClient($request);
        $documents = ClientSharedDocument::where('client_id', $client->id)->orderByDesc('created_at')->paginate(20);

        return view('client-portal.documents', compact('client', 'documents'));
    }

    public function downloadSharedDocument(Request $request, ClientSharedDocument $sharedDocument)
    {
        $client = $this->requireClient($request);
        if ((int) $sharedDocument->client_id !== (int) $client->id) {
            abort(403);
        }
        if (! Storage::disk('local')->exists($sharedDocument->file_path)) {
            abort(404);
        }

        return Storage::disk('local')->download(
            $sharedDocument->file_path,
            $sharedDocument->original_filename ?? basename($sharedDocument->file_path)
        );
    }

    public function calendar(Request $request)
    {
        $client = $this->requireClient($request);
        $meetings = ClientMeetingRequest::where('client_id', $client->id)
            ->where('status', 'confirmed')
            ->whereNotNull('scheduled_at')
            ->orderBy('scheduled_at')
            ->paginate(20);

        return view('client-portal.calendar', compact('client', 'meetings'));
    }

    private function requireClient(Request $request): Client
    {
        $account = $request->user('client');
        $client = $account?->client;
        if (! $client) {
            abort(403, 'لا يوجد عميل مرتبط بهذا الحساب');
        }

        return $client;
    }
}

