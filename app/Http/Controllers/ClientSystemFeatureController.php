<?php

namespace App\Http\Controllers;

use App\Models\ClientSystemFeature;
use App\Models\ClientSystemProject;
use App\Services\ClientPortalAdminAlertService;
use Illuminate\Http\Request;

class ClientSystemFeatureController extends Controller
{
    public function index(Request $request)
    {
        $client = $this->requireClient($request);

        $projects = ClientSystemProject::where('client_id', $client->id)
            ->withCount('features')
            ->with(['features' => fn ($q) => $q->orderByDesc('created_at')->limit(5)])
            ->orderByDesc('updated_at')
            ->get();

        $recentFeatures = ClientSystemFeature::whereHas('project', fn ($q) => $q->where('client_id', $client->id))
            ->with('project')
            ->orderByDesc('created_at')
            ->limit(20)
            ->get();

        return view('client-portal.system-features.index', compact('client', 'projects', 'recentFeatures'));
    }

    public function create(Request $request)
    {
        $client = $this->requireClient($request);

        $projects = ClientSystemProject::where('client_id', $client->id)
            ->where('status', '!=', 'archived')
            ->orderBy('name')
            ->get();

        return view('client-portal.system-features.create', compact('client', 'projects'));
    }

    public function store(Request $request)
    {
        $account = $request->user('client');
        $client = $this->requireClient($request);

        $validated = $request->validate([
            'project_id' => 'nullable|exists:client_system_projects,id',
            'new_project_name' => 'nullable|string|max:255',
            'type' => 'required|in:feature,bug,improvement',
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:15000',
            'priority' => 'nullable|in:low,medium,high,urgent',
        ]);

        $project = null;
        if (! empty($validated['project_id'])) {
            $project = ClientSystemProject::where('client_id', $client->id)->findOrFail($validated['project_id']);
        } elseif (! empty($validated['new_project_name'])) {
            $project = ClientSystemProject::create([
                'reference_code' => ClientSystemProject::generateReferenceCode(),
                'client_id' => $client->id,
                'name' => $validated['new_project_name'],
                'status' => 'active',
                'created_by_client_account_id' => $account->id,
            ]);
        } else {
            return back()->withErrors(['project_id' => 'اختر مشروعاً موجوداً أو أدخل اسم مشروع جديد.'])->withInput();
        }

        $feature = ClientSystemFeature::create([
            'reference_code' => ClientSystemFeature::generateReferenceCode(),
            'client_system_project_id' => $project->id,
            'type' => $validated['type'],
            'title' => $validated['title'],
            'description' => $validated['description'],
            'status' => 'submitted',
            'priority' => $validated['priority'] ?? 'medium',
            'submitted_by_client_account_id' => $account->id,
        ]);

        $project->touch();

        app(ClientPortalAdminAlertService::class)->notifySystemFeatureCreated($client, $account, $feature);

        return redirect()
            ->route('client.system-features.show', $feature)
            ->with('success', 'تم إرسال الطلب بنجاح. رقم المتابعة: '.$feature->reference_code);
    }

    public function show(Request $request, ClientSystemFeature $systemFeature)
    {
        $client = $this->requireClient($request);
        $this->authorizeFeature($client, $systemFeature);

        $systemFeature->load([
            'project',
            'clientVisibleUpdates.author',
            'clientVisibleUpdates.clientAuthor',
        ]);

        return view('client-portal.system-features.show', [
            'client' => $client,
            'feature' => $systemFeature,
        ]);
    }

    protected function requireClient(Request $request)
    {
        $client = $request->user('client')?->client;
        if (! $client) {
            abort(403, 'لا يوجد عميل مرتبط بهذا الحساب');
        }

        return $client;
    }

    protected function authorizeFeature($client, ClientSystemFeature $feature): void
    {
        $feature->loadMissing('project');
        if ((int) $feature->project->client_id !== (int) $client->id) {
            abort(403);
        }
    }
}
