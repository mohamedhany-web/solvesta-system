<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Client;
use App\Models\User;
use App\Services\ClientPortalNotifier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TicketController extends Controller
{
    public function index()
    {
        $tickets = Ticket::with(['client', 'assignedTo', 'createdBy'])
            ->when(request('search'), function ($query) {
                $query->where('subject', 'like', '%' . request('search') . '%')
                    ->orWhere('description', 'like', '%' . request('search') . '%')
                    ->orWhere('ticket_number', 'like', '%' . request('search') . '%');
            })
            ->when(request('status'), function ($query) {
                $query->where('status', request('status'));
            })
            ->when(request('priority'), function ($query) {
                $query->where('priority', request('priority'));
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $stats = [
            'total' => Ticket::count(),
            'open' => Ticket::open()->count(),
            'closed' => Ticket::closed()->count(),
            'high_priority' => Ticket::highPriority()->count(),
        ];

        return view('tickets.index', compact('tickets', 'stats'));
    }

    public function create()
    {
        $clients = Client::all();
        $users = User::all();
        return view('tickets.create', compact('clients', 'users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'client_id' => 'required|exists:clients,id',
            'priority' => 'required|in:low,medium,high,critical',
            'category' => 'required|in:technical,billing,general,bug_report,feature_request',
        ]);

        $validated['ticket_number'] = 'TKT-' . time();
        $validated['created_by'] = auth()->id();
        $validated['status'] = 'open';

        Ticket::create($validated);

        return redirect()->route('tickets.index')->with('success', 'تم إنشاء التذكرة بنجاح');
    }

    public function show(Ticket $ticket)
    {
        $ticket->load(['client', 'assignedTo', 'createdBy']);
        return view('tickets.show', compact('ticket'));
    }

    public function edit(Ticket $ticket)
    {
        $clients = Client::all();
        $users = User::all();
        return view('tickets.edit', compact('ticket', 'clients', 'users'));
    }

    public function update(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'client_id' => 'required|exists:clients,id',
            'assigned_to' => 'nullable|exists:users,id',
            'priority' => 'required|in:low,medium,high,critical',
            'category' => 'required|in:technical,billing,general,bug_report,feature_request',
            'status' => 'required|in:open,in_progress,pending_client,resolved,closed',
        ]);

        $oldStatus = $ticket->status;

        $ticket->update($validated);

        if ($ticket->client_id) {
            $cid = (int) $ticket->client_id;
            if ($oldStatus !== $ticket->status) {
                if ($ticket->status === 'pending_client') {
                    ClientPortalNotifier::notify(
                        $cid,
                        'ticket_pending_client',
                        'تذكرة تنتظر ردك',
                        'التذكرة '.$ticket->ticket_number.' بحاجة إلى رد أو معلومات منك.',
                        url('/client/support/tickets/'.$ticket->id),
                        ['ticket_id' => $ticket->id]
                    );
                } elseif (in_array($ticket->status, ['resolved', 'closed'], true)) {
                    ClientPortalNotifier::notify(
                        $cid,
                        'ticket_closed',
                        'تم تحديث حالة التذكرة',
                        'التذكرة '.$ticket->ticket_number.' أصبحت: '.$ticket->status_name,
                        url('/client/support/tickets/'.$ticket->id),
                        ['ticket_id' => $ticket->id]
                    );
                } elseif ($ticket->status === 'in_progress' && $oldStatus === 'open') {
                    ClientPortalNotifier::notify(
                        $cid,
                        'ticket_in_progress',
                        'جاري معالجة تذكرتك',
                        'التذكرة '.$ticket->ticket_number.' قيد المعالجة لدى الفريق.',
                        url('/client/support/tickets/'.$ticket->id),
                        ['ticket_id' => $ticket->id]
                    );
                }
            }
        }

        return redirect()->route('tickets.index')->with('success', 'تم تحديث التذكرة بنجاح');
    }

    public function destroy(Ticket $ticket)
    {
        $ticket->delete();
        return redirect()->route('tickets.index')->with('success', 'تم حذف التذكرة بنجاح');
    }
}
