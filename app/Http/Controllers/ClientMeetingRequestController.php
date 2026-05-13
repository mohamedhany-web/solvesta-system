<?php

namespace App\Http\Controllers;

use App\Models\ClientMeetingRequest;
use App\Models\ClientPortalFeedback;
use App\Services\ClientPortalAdminAlertService;
use Illuminate\Http\Request;

class ClientMeetingRequestController extends Controller
{
    public function index(Request $request)
    {
        $account = $request->user('client');
        $client = $account?->client;
        if (! $client) {
            abort(403, 'لا يوجد عميل مرتبط بهذا الحساب');
        }

        $meetingRequests = ClientMeetingRequest::where('client_id', $client->id)
            ->orderByDesc('created_at')
            ->paginate(12);

        return view('client-portal.meeting-requests.index', compact('client', 'meetingRequests'));
    }

    public function create(Request $request)
    {
        $account = $request->user('client');
        $client = $account?->client;
        if (! $client) {
            abort(403, 'لا يوجد عميل مرتبط بهذا الحساب');
        }

        return view('client-portal.meeting-requests.create', compact('client'));
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
            'preferred_at' => 'required|date|after:now',
            'alternative_times' => 'nullable|string|max:5000',
            'participants_count' => 'nullable|integer|min:1|max:500',
            'meeting_format' => 'required|in:online,in_person,either',
        ]);

        $referenceCode = $this->makeReferenceCode();

        $meetingRequest = ClientMeetingRequest::create([
            'reference_code' => $referenceCode,
            'client_id' => $client->id,
            'title' => $validated['title'],
            'description' => $validated['description'],
            'preferred_at' => $validated['preferred_at'],
            'alternative_times' => $validated['alternative_times'] ?? null,
            'participants_count' => $validated['participants_count'] ?? null,
            'meeting_format' => $validated['meeting_format'],
            'status' => 'pending',
        ]);

        app(ClientPortalAdminAlertService::class)->notifyMeetingRequestCreated($client, $account, $meetingRequest);

        return redirect()
            ->route('client.meeting-requests.show', $meetingRequest)
            ->with('success', 'تم إرسال طلب الاجتماع. رقم المتابعة: '.$meetingRequest->reference_code);
    }

    public function show(Request $request, ClientMeetingRequest $meetingRequest)
    {
        $this->authorizeClientMeetingRequest($request, $meetingRequest);
        $client = $request->user('client')?->client;
        $existingFeedback = null;
        if ($client) {
            $existingFeedback = ClientPortalFeedback::where('client_id', $client->id)
                ->where('feedbackable_type', ClientMeetingRequest::class)
                ->where('feedbackable_id', $meetingRequest->id)
                ->first();
        }

        return view('client-portal.meeting-requests.show', [
            'client' => $client,
            'meetingRequest' => $meetingRequest,
            'existingFeedback' => $existingFeedback,
        ]);
    }

    public function storeFeedback(Request $request, ClientMeetingRequest $meetingRequest)
    {
        $this->authorizeClientMeetingRequest($request, $meetingRequest);
        $client = $request->user('client')?->client;
        if (! $client) {
            abort(403);
        }
        if ($meetingRequest->status !== 'completed') {
            abort(403, 'التقييم متاح بعد اكتمال الاجتماع');
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:2000',
        ]);

        ClientPortalFeedback::updateOrCreate(
            [
                'client_id' => $client->id,
                'feedbackable_type' => ClientMeetingRequest::class,
                'feedbackable_id' => $meetingRequest->id,
            ],
            [
                'rating' => $validated['rating'],
                'comment' => $validated['comment'] ?? null,
            ]
        );

        return redirect()
            ->route('client.meeting-requests.show', $meetingRequest)
            ->with('success', 'شكراً لتقييمك');
    }

    private function authorizeClientMeetingRequest(Request $request, ClientMeetingRequest $meetingRequest): void
    {
        $client = $request->user('client')?->client;
        if (! $client || (int) $meetingRequest->client_id !== (int) $client->id) {
            abort(403);
        }
    }

    private function makeReferenceCode(): string
    {
        do {
            $code = 'MR-'.now()->format('Y').'-'.strtoupper(substr(bin2hex(random_bytes(3)), 0, 6));
        } while (ClientMeetingRequest::where('reference_code', $code)->exists());

        return $code;
    }
}
