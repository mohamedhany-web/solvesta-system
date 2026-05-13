<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\ClientWebsiteIssue;
use App\Models\User;
use App\Services\ClientPortalNotifier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ClientWebsiteIssueController extends Controller
{
    public function index(Request $request)
    {
        $query = ClientWebsiteIssue::with(['client', 'resolver', 'assignee'])
            ->orderByDesc('created_at');

        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }
        if ($request->filled('client_id')) {
            $query->where('client_id', $request->integer('client_id'));
        }
        if ($request->filled('search')) {
            $s = '%'.$request->string('search').'%';
            $query->where(function ($q) use ($s) {
                $q->where('title', 'like', $s)
                    ->orWhere('description', 'like', $s)
                    ->orWhere('reference_code', 'like', $s);
            });
        }

        $issues = $query->paginate(20)->withQueryString();
        $clients = Client::query()->orderBy('name')->get(['id', 'name', 'company_name']);

        return view('admin.client-website-issues.index', compact('issues', 'clients'));
    }

    public function show(ClientWebsiteIssue $websiteIssue)
    {
        $websiteIssue->load(['client', 'resolver', 'assignee']);
        $users = User::query()->orderBy('name')->get(['id', 'name']);

        return view('admin.client-website-issues.show', compact('websiteIssue', 'users'));
    }

    public function update(Request $request, ClientWebsiteIssue $websiteIssue)
    {
        if ($request->input('assigned_to') === '') {
            $request->merge(['assigned_to' => null]);
        }

        $validated = $request->validate([
            'status' => 'required|in:open,in_progress,resolved,closed',
            'admin_notes' => 'nullable|string|max:10000',
            'resolution_message' => 'nullable|string|max:10000',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        $updates = [
            'status' => $validated['status'],
            'admin_notes' => $validated['admin_notes'] ?? null,
            'resolution_message' => $validated['resolution_message'] ?? null,
            'assigned_to' => $validated['assigned_to'] ?? null,
        ];

        if (in_array($validated['status'], ['resolved', 'closed'], true)) {
            if (! $websiteIssue->resolved_at) {
                $updates['resolved_at'] = now();
                $updates['resolved_by'] = $request->user()?->id;
            }
        } else {
            $updates['resolved_at'] = null;
            $updates['resolved_by'] = null;
        }

        $prevStatus = $websiteIssue->status;
        $prevResolution = $websiteIssue->resolution_message;

        $websiteIssue->update($updates);
        $websiteIssue->refresh();

        $cid = (int) $websiteIssue->client_id;
        if ($cid && ($prevStatus !== $websiteIssue->status || ($prevResolution ?? '') !== ($websiteIssue->resolution_message ?? ''))) {
            ClientPortalNotifier::notify(
                $cid,
                'website_issue_updated',
                'تحديث على بلاغ الموقع',
                'تم تحديث البلاغ '.$websiteIssue->reference_code.' — الحالة: '.$websiteIssue->status_label,
                url('/client/website-issues/'.$websiteIssue->id),
                ['issue_id' => $websiteIssue->id]
            );
        }

        return redirect()
            ->route('client-website-issues.show', $websiteIssue)
            ->with('success', 'تم تحديث البلاغ');
    }

    public function file(ClientWebsiteIssue $websiteIssue, int $index)
    {
        $attachments = $websiteIssue->attachments ?? [];
        if (! isset($attachments[$index]['path'])) {
            abort(404);
        }
        $path = $attachments[$index]['path'];
        if (! Storage::disk('local')->exists($path)) {
            abort(404);
        }
        $mime = $attachments[$index]['mime'] ?? Storage::disk('local')->mimeType($path) ?: 'image/jpeg';

        return response()->file(Storage::disk('local')->path($path), [
            'Content-Type' => $mime,
        ]);
    }
}
