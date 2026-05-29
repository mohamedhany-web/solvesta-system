<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Project;
use App\Models\Ticket;
use App\Models\User;
use App\Services\ClientPortalNotifier;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class TicketController extends Controller
{
    public function index()
    {
        $projects = Project::query()
            ->with(['client', 'projectManager'])
            ->with(['tickets' => function ($query) {
                $this->applyTicketFilters($query)
                    ->with(['assignedTo', 'createdBy'])
                    ->orderByDesc('updated_at');
            }])
            ->when(request('client_id'), fn ($q) => $q->where('client_id', request('client_id')))
            ->when(request('project_id'), fn ($q) => $q->where('id', request('project_id')))
            ->whereHas('tickets', fn ($q) => $this->applyTicketFilters($q))
            ->orderBy('name')
            ->get();

        $unassignedTickets = Ticket::query()
            ->whereNull('project_id')
            ->with(['client', 'assignedTo', 'createdBy'])
            ->tap(fn ($q) => $this->applyTicketFilters($q))
            ->orderByDesc('updated_at')
            ->get();

        $stats = [
            'total' => Ticket::count(),
            'open' => Ticket::open()->count(),
            'closed' => Ticket::closed()->count(),
            'high_priority' => Ticket::highPriority()->count(),
        ];

        $clients = Client::orderBy('name')->get(['id', 'name']);
        $allProjects = Project::with('client')->orderBy('name')->get(['id', 'name', 'client_id']);

        return view('tickets.index', compact('projects', 'unassignedTickets', 'stats', 'clients', 'allProjects'));
    }

    public function create()
    {
        $clients = Client::orderBy('name')->get();
        $users = User::orderBy('name')->get();
        $projects = Project::with('client')->orderBy('name')->get();

        return view('tickets.create', compact('clients', 'users', 'projects'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'client_id' => 'required|exists:clients,id',
            'project_id' => 'nullable|exists:projects,id',
            'priority' => 'required|in:low,medium,high,critical',
            'category' => 'required|in:technical,billing,general,bug_report,feature_request',
        ]);

        $this->assertProjectBelongsToClient($validated['project_id'] ?? null, (int) $validated['client_id']);

        $validated['ticket_number'] = 'TKT-' . time();
        $validated['created_by'] = auth()->id();
        $validated['status'] = 'open';

        Ticket::create($validated);

        return redirect()->route('tickets.index')->with('success', 'تم إنشاء التذكرة بنجاح');
    }

    public function show(Ticket $ticket)
    {
        $ticket->load(['client', 'project', 'assignedTo', 'createdBy']);

        return view('tickets.show', compact('ticket'));
    }

    public function edit(Ticket $ticket)
    {
        $clients = Client::orderBy('name')->get();
        $users = User::orderBy('name')->get();
        $projects = Project::with('client')->orderBy('name')->get();

        return view('tickets.edit', compact('ticket', 'clients', 'users', 'projects'));
    }

    public function update(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'client_id' => 'required|exists:clients,id',
            'project_id' => 'nullable|exists:projects,id',
            'assigned_to' => 'nullable|exists:users,id',
            'priority' => 'required|in:low,medium,high,critical',
            'category' => 'required|in:technical,billing,general,bug_report,feature_request',
            'status' => 'required|in:open,in_progress,pending_client,resolved,closed',
        ]);

        $this->assertProjectBelongsToClient($validated['project_id'] ?? null, (int) $validated['client_id']);

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

    protected function applyTicketFilters(Builder $query): Builder
    {
        return $query
            ->when(request('search'), function ($q) {
                $term = '%'.request('search').'%';
                $q->where(function ($inner) use ($term) {
                    $inner->where('subject', 'like', $term)
                        ->orWhere('description', 'like', $term)
                        ->orWhere('ticket_number', 'like', $term);
                });
            })
            ->when(request('status'), fn ($q) => $q->where('status', request('status')))
            ->when(request('priority'), fn ($q) => $q->where('priority', request('priority')));
    }

    protected function assertProjectBelongsToClient(?int $projectId, int $clientId): void
    {
        if (! $projectId) {
            return;
        }

        $belongs = Project::where('id', $projectId)->where('client_id', $clientId)->exists();
        if (! $belongs) {
            throw ValidationException::withMessages([
                'project_id' => 'المشروع المحدد لا يتبع هذا العميل.',
            ]);
        }
    }
}
