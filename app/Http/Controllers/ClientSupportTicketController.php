<?php

namespace App\Http\Controllers;

use App\Models\ClientPortalFeedback;
use App\Models\Ticket;
use App\Services\ClientPortalAdminAlertService;
use Illuminate\Http\Request;

class ClientSupportTicketController extends Controller
{
    public function index(Request $request)
    {
        $account = $request->user('client');
        $client = $account?->client;
        if (!$client) abort(403, 'لا يوجد عميل مرتبط بهذا الحساب');

        $tickets = Ticket::with(['assignedTo'])
            ->where('client_id', $client->id)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('client-portal.support.tickets.index', compact('client', 'tickets'));
    }

    public function create(Request $request)
    {
        $account = $request->user('client');
        $client = $account?->client;
        if (!$client) abort(403, 'لا يوجد عميل مرتبط بهذا الحساب');

        return view('client-portal.support.tickets.create', compact('client'));
    }

    public function store(Request $request)
    {
        $account = $request->user('client');
        $client = $account?->client;
        if (!$client) abort(403, 'لا يوجد عميل مرتبط بهذا الحساب');

        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|in:low,medium,high,critical',
            'category' => 'required|in:technical,billing,general,bug_report,feature_request',
        ]);

        $validated['ticket_number'] = 'TKT-' . time();
        $validated['created_by'] = null;
        $validated['client_id'] = $client->id;
        $validated['status'] = 'open';

        $ticket = Ticket::create($validated);

        app(ClientPortalAdminAlertService::class)->notifyTicketCreated($client, $account, $ticket);

        return redirect()->route('client.support.tickets')->with('success', 'تم إرسال التذكرة بنجاح');
    }

    public function show(Request $request, Ticket $ticket)
    {
        $client = $this->requireClient($request);
        if ((int) $ticket->client_id !== (int) $client->id) {
            abort(403);
        }
        $ticket->load(['assignedTo']);
        $existingFeedback = ClientPortalFeedback::where('client_id', $client->id)
            ->where('feedbackable_type', Ticket::class)
            ->where('feedbackable_id', $ticket->id)
            ->first();

        return view('client-portal.support.tickets.show', compact('client', 'ticket', 'existingFeedback'));
    }

    public function storeFeedback(Request $request, Ticket $ticket)
    {
        $client = $this->requireClient($request);
        if ((int) $ticket->client_id !== (int) $client->id) {
            abort(403);
        }
        if (! in_array($ticket->status, ['resolved', 'closed'], true)) {
            abort(403, 'لا يمكن التقييم إلا بعد إغلاق أو حل التذكرة');
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:2000',
        ]);

        ClientPortalFeedback::updateOrCreate(
            [
                'client_id' => $client->id,
                'feedbackable_type' => Ticket::class,
                'feedbackable_id' => $ticket->id,
            ],
            [
                'rating' => $validated['rating'],
                'comment' => $validated['comment'] ?? null,
            ]
        );

        $ticket->update(['rating' => $validated['rating']]);

        return redirect()
            ->route('client.support.tickets.show', $ticket)
            ->with('success', 'شكراً لتقييمك');
    }

    private function requireClient(Request $request): \App\Models\Client
    {
        $account = $request->user('client');
        $client = $account?->client;
        if (! $client) {
            abort(403, 'لا يوجد عميل مرتبط بهذا الحساب');
        }

        return $client;
    }
}

