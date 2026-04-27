<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
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

        Ticket::create($validated);

        return redirect()->route('client.support.tickets')->with('success', 'تم إرسال التذكرة بنجاح');
    }
}

