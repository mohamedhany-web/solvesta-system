<?php

namespace App\Http\Controllers\Support;

use App\Http\Controllers\Controller;
use App\Models\ContactRequest;
use Illuminate\Http\Request;

class ContactRequestController extends Controller
{
    public function index(Request $request)
    {
        $query = ContactRequest::query()->latest();

        if ($type = $request->string('type')->toString()) {
            if (in_array($type, ['contact', 'consultation'], true)) {
                $query->where('type', $type);
            }
        }

        if ($status = $request->string('status')->toString()) {
            if (in_array($status, ['new', 'in_progress', 'closed'], true)) {
                $query->where('status', $status);
            }
        }

        $requests = $query->paginate(20)->withQueryString();

        $stats = [
            'new' => ContactRequest::where('status', 'new')->count(),
            'in_progress' => ContactRequest::where('status', 'in_progress')->count(),
            'closed' => ContactRequest::where('status', 'closed')->count(),
        ];

        return view('support.contact-requests.index', compact('requests', 'stats'));
    }

    public function show(ContactRequest $contactRequest)
    {
        return view('support.contact-requests.show', compact('contactRequest'));
    }

    public function updateStatus(Request $request, ContactRequest $contactRequest)
    {
        $data = $request->validate([
            'status' => 'required|in:new,in_progress,closed',
        ]);

        $contactRequest->update($data);

        return back()->with('success', 'تم تحديث الحالة.');
    }
}

