<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClientSystemFeature;
use App\Models\ClientSystemFeatureUpdate;
use App\Models\User;
use App\Services\ClientPortalNotifier;
use Illuminate\Http\Request;

class ClientSystemFeatureController extends Controller
{
    public function show(ClientSystemFeature $clientSystemFeature)
    {
        $clientSystemFeature->load([
            'project.client',
            'assignee',
            'submitter',
            'updates.author',
            'updates.clientAuthor',
        ]);
        $users = User::orderBy('name')->get(['id', 'name']);

        return view('admin.client-system-features.show', [
            'feature' => $clientSystemFeature,
            'users' => $users,
        ]);
    }

    public function update(Request $request, ClientSystemFeature $clientSystemFeature)
    {
        if ($request->input('assigned_to') === '') {
            $request->merge(['assigned_to' => null]);
        }

        $validated = $request->validate([
            'status' => 'required|in:submitted,reviewing,approved,in_progress,testing,completed,rejected,cancelled',
            'priority' => 'required|in:low,medium,high,urgent',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        $prevStatus = $clientSystemFeature->status;
        $clientSystemFeature->update($validated);
        $clientSystemFeature->project?->touch();

        if ($prevStatus !== $clientSystemFeature->status) {
            $cid = (int) $clientSystemFeature->project->client_id;
            ClientPortalNotifier::notify(
                $cid,
                'system_feature_updated',
                'تحديث على طلب التطوير',
                $clientSystemFeature->reference_code.' — '.$clientSystemFeature->title.' — الحالة: '.$clientSystemFeature->status_label,
                url('/client/system-features/'.$clientSystemFeature->id),
                ['feature_id' => $clientSystemFeature->id]
            );
        }

        return redirect()
            ->route('client-system-features.show', $clientSystemFeature)
            ->with('success', 'تم تحديث الطلب.');
    }

    public function storeUpdate(Request $request, ClientSystemFeature $clientSystemFeature)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string|max:20000',
            'visibility' => 'required|in:client,internal',
        ]);

        $update = ClientSystemFeatureUpdate::create([
            'client_system_feature_id' => $clientSystemFeature->id,
            'title' => $validated['title'],
            'body' => $validated['body'],
            'visibility' => $validated['visibility'],
            'created_by_user_id' => $request->user()->id,
        ]);

        $clientSystemFeature->project?->touch();

        if ($validated['visibility'] === 'client') {
            $cid = (int) $clientSystemFeature->project->client_id;
            ClientPortalNotifier::notify(
                $cid,
                'system_feature_documentation',
                'تحديث توثيقي على طلبك',
                $clientSystemFeature->reference_code.' — '.$validated['title'],
                url('/client/system-features/'.$clientSystemFeature->id),
                ['feature_id' => $clientSystemFeature->id, 'update_id' => $update->id]
            );
        }

        return redirect()
            ->route('client-system-features.show', $clientSystemFeature)
            ->with('success', 'تم إضافة التوثيق / التحديث.');
    }

    public function destroyUpdate(ClientSystemFeatureUpdate $clientSystemFeatureUpdate)
    {
        $feature = $clientSystemFeatureUpdate->feature;
        $clientSystemFeatureUpdate->delete();

        return redirect()
            ->route('client-system-features.show', $feature)
            ->with('success', 'تم حذف التحديث.');
    }
}
