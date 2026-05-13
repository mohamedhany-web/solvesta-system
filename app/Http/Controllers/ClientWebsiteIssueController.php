<?php

namespace App\Http\Controllers;

use App\Models\ClientWebsiteIssue;
use App\Services\ClientPortalAdminAlertService;
use Illuminate\Http\Request;

class ClientWebsiteIssueController extends Controller
{
    public function index(Request $request)
    {
        $account = $request->user('client');
        $client = $account?->client;
        if (! $client) {
            abort(403, 'لا يوجد عميل مرتبط بهذا الحساب');
        }

        $issues = ClientWebsiteIssue::where('client_id', $client->id)
            ->orderByDesc('created_at')
            ->paginate(12);

        return view('client-portal.website-issues.index', compact('client', 'issues'));
    }

    public function create(Request $request)
    {
        $account = $request->user('client');
        $client = $account?->client;
        if (! $client) {
            abort(403, 'لا يوجد عميل مرتبط بهذا الحساب');
        }

        return view('client-portal.website-issues.create', compact('client'));
    }

    public function store(Request $request)
    {
        $account = $request->user('client');
        $client = $account?->client;
        if (! $client) {
            abort(403, 'لا يوجد عميل مرتبط بهذا الحساب');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:15000',
            'page_url' => 'nullable|string|max:2000',
            'attachments' => 'nullable|array|max:8',
            'attachments.*' => 'file|image|max:5120',
        ]);

        $referenceCode = $this->makeReferenceCode();

        $issue = ClientWebsiteIssue::create([
            'reference_code' => $referenceCode,
            'client_id' => $client->id,
            'title' => $validated['title'],
            'description' => $validated['description'],
            'page_url' => $validated['page_url'] ?? null,
            'status' => 'open',
            'attachments' => [],
        ]);

        $attachments = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('client-website-issues/'.$issue->id, 'local');
                $attachments[] = [
                    'path' => $path,
                    'original' => $file->getClientOriginalName(),
                    'mime' => $file->getMimeType() ?: 'application/octet-stream',
                ];
            }
        }
        if ($attachments !== []) {
            $issue->update(['attachments' => $attachments]);
        }

        app(ClientPortalAdminAlertService::class)->notifyWebsiteIssueCreated($client, $account, $issue);

        return redirect()
            ->route('client.website-issues.show', $issue)
            ->with('success', 'تم تسجيل البلاغ بنجاح. رقم المتابعة: '.$issue->reference_code);
    }

    public function show(Request $request, ClientWebsiteIssue $websiteIssue)
    {
        $this->authorizeClientIssue($request, $websiteIssue);

        return view('client-portal.website-issues.show', [
            'client' => $request->user('client')?->client,
            'issue' => $websiteIssue,
        ]);
    }

    public function destroy(Request $request, ClientWebsiteIssue $websiteIssue)
    {
        $this->authorizeClientIssue($request, $websiteIssue);

        if ($websiteIssue->status !== 'open') {
            return redirect()
                ->route('client.website-issues.index')
                ->with('error', 'يمكن حذف البلاغ فقط وهو في حالة «مفتوح». للبلاغات قيد المعالجة أو المغلقة تواصل مع الدعم.');
        }

        $this->deleteIssueAttachments($websiteIssue);
        $websiteIssue->delete();

        return redirect()
            ->route('client.website-issues.index')
            ->with('success', 'تم حذف البلاغ بنجاح.');
    }

    public function file(Request $request, ClientWebsiteIssue $websiteIssue, int $index)
    {
        $this->authorizeClientIssue($request, $websiteIssue);

        $attachments = $websiteIssue->attachments ?? [];
        if (! isset($attachments[$index]['path'])) {
            abort(404);
        }

        $path = $attachments[$index]['path'];
        $disk = \Illuminate\Support\Facades\Storage::disk('local');

        if (! $disk->exists($path)) {
            abort(404);
        }

        $mime = $attachments[$index]['mime'] ?? $disk->mimeType($path) ?: 'image/jpeg';
        $downloadName = $attachments[$index]['original'] ?? basename($path);

        return $disk->response($path, $downloadName, [
            'Content-Type' => $mime,
        ], 'inline');
    }

    private function authorizeClientIssue(Request $request, ClientWebsiteIssue $issue): void
    {
        $account = $request->user('client');
        $client = $account?->client;
        if (! $client || $issue->client_id !== $client->id) {
            abort(403);
        }
    }

    private function deleteIssueAttachments(ClientWebsiteIssue $issue): void
    {
        $disk = \Illuminate\Support\Facades\Storage::disk('local');
        foreach ($issue->attachments ?? [] as $att) {
            if (! empty($att['path']) && $disk->exists($att['path'])) {
                $disk->delete($att['path']);
            }
        }
    }

    private function makeReferenceCode(): string
    {
        do {
            $code = 'WEB-'.now()->format('Ymd').'-'.strtoupper(substr(bin2hex(random_bytes(4)), 0, 6));
        } while (ClientWebsiteIssue::where('reference_code', $code)->exists());

        return $code;
    }
}
