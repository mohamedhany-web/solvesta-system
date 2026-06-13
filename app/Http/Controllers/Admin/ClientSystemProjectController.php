<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\ClientSystemProject;
use App\Models\User;
use Illuminate\Http\Request;

class ClientSystemProjectController extends Controller
{
    public function index(Request $request)
    {
        $query = ClientSystemProject::with(['client', 'assignee'])
            ->withCount('features')
            ->orderByDesc('updated_at');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('client_id')) {
            $query->where('client_id', $request->client_id);
        }
        if ($request->filled('search')) {
            $s = '%'.$request->search.'%';
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', $s)
                    ->orWhere('reference_code', 'like', $s)
                    ->orWhere('description', 'like', $s);
            });
        }

        $projects = $query->paginate(20)->withQueryString();
        $clients = Client::orderBy('name')->get(['id', 'name', 'company']);

        return view('admin.client-system-projects.index', compact('projects', 'clients'));
    }

    public function create()
    {
        $clients = Client::orderBy('name')->get(['id', 'name', 'company']);
        $users = User::orderBy('name')->get(['id', 'name']);

        return view('admin.client-system-projects.create', compact('clients', 'users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:5000',
            'status' => 'required|in:active,on_hold,completed,archived',
            'assigned_to' => 'nullable|exists:users,id',
            'admin_notes' => 'nullable|string|max:10000',
        ]);

        $project = ClientSystemProject::create([
            'reference_code' => ClientSystemProject::generateReferenceCode(),
            'client_id' => $validated['client_id'],
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'status' => $validated['status'],
            'assigned_to' => $validated['assigned_to'] ?? null,
            'admin_notes' => $validated['admin_notes'] ?? null,
        ]);

        return redirect()
            ->route('client-system-projects.show', $project)
            ->with('success', 'تم إنشاء مشروع النظام.');
    }

    public function show(ClientSystemProject $clientSystemProject)
    {
        $clientSystemProject->load([
            'client',
            'assignee',
            'features.assignee',
            'features' => fn ($q) => $q->orderByDesc('created_at'),
        ]);
        $users = User::orderBy('name')->get(['id', 'name']);

        return view('admin.client-system-projects.show', [
            'project' => $clientSystemProject,
            'users' => $users,
        ]);
    }

    public function update(Request $request, ClientSystemProject $clientSystemProject)
    {
        if ($request->input('assigned_to') === '') {
            $request->merge(['assigned_to' => null]);
        }

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string|max:5000',
            'status' => 'required|in:active,on_hold,completed,archived',
            'assigned_to' => 'nullable|exists:users,id',
            'admin_notes' => 'nullable|string|max:10000',
        ]);

        $clientSystemProject->update($validated);

        return redirect()
            ->route('client-system-projects.show', $clientSystemProject)
            ->with('success', 'تم تحديث المشروع.');
    }
}
