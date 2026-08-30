<?php

namespace App\Http\Controllers;

use App\Models\MailMessage;
use App\Services\OfficialMailInboxService;
use Illuminate\Http\Request;

class OfficialMailController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $user = auth()->user();
            if (! $user || ! ($user->hasAnyRole(['super_admin', 'admin']) || $user->can('view-settings'))) {
                abort(403, 'غير مصرح لك بصندوق البريد الرسمي.');
            }

            return $next($request);
        });
    }

    public function index(Request $request)
    {
        return $this->mailbox($request);
    }

    public function show(Request $request, MailMessage $mailMessage)
    {
        if ($mailMessage->direction === 'inbound' && ! $mailMessage->is_read) {
            $mailMessage->update(['is_read' => true]);
        }

        $mailMessage->load(['replies', 'replyTo', 'creator']);

        return $this->mailbox($request, $mailMessage);
    }

    protected function mailbox(Request $request, ?MailMessage $selected = null)
    {
        $tab = $request->get('tab', 'inbox');
        $query = MailMessage::query()->latest('received_at');

        if ($tab === 'sent') {
            $query->where('direction', 'outbound');
        } elseif ($tab === 'unread') {
            $query->where('direction', 'inbound')->where('is_read', false);
        } else {
            $query->where('direction', 'inbound');
            $tab = 'inbox';
        }

        if ($request->filled('search')) {
            $s = '%'.$request->search.'%';
            $query->where(function ($q) use ($s) {
                $q->where('subject', 'like', $s)
                    ->orWhere('from_email', 'like', $s)
                    ->orWhere('from_name', 'like', $s)
                    ->orWhere('to_email', 'like', $s);
            });
        }

        $messages = $query->paginate(50)->withQueryString();
        $unreadCount = MailMessage::where('direction', 'inbound')->where('is_read', false)->count();
        $inboxCount = MailMessage::where('direction', 'inbound')->count();
        $sentCount = MailMessage::where('direction', 'outbound')->count();

        return view('official-mail.index', [
            'messages' => $messages,
            'tab' => $tab,
            'unreadCount' => $unreadCount,
            'inboxCount' => $inboxCount,
            'sentCount' => $sentCount,
            'selected' => $selected,
        ]);
    }

    public function sync(OfficialMailInboxService $service)
    {
        try {
            $result = $service->sync();

            return back()->with('success', "تمت المزامنة: {$result['synced']} رسالة جديدة، تحويل: {$result['forwarded']}، إشعارات: {$result['notified']}");
        } catch (\Throwable $e) {
            return back()->with('error', 'فشل المزامنة: '.$e->getMessage());
        }
    }

    public function reply(Request $request, MailMessage $mailMessage, OfficialMailInboxService $service)
    {
        $validated = $request->validate([
            'body' => 'required|string|max:20000',
        ]);

        try {
            $reply = $service->reply($mailMessage, $validated['body'], auth()->user());

            return redirect()->route('official-mail.show', $mailMessage)
                ->with('success', 'تم إرسال الرد بنجاح.');
        } catch (\Throwable $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function compose()
    {
        return view('official-mail.compose');
    }

    public function send(Request $request, OfficialMailInboxService $service)
    {
        $validated = $request->validate([
            'to' => 'required|email|max:255',
            'cc' => 'nullable|string|max:1000',
            'bcc' => 'nullable|string|max:1000',
            'subject' => 'required|string|max:255',
            'body' => 'required|string|max:50000',
            'attachments' => 'nullable|array|max:5',
            'attachments.*' => 'file|max:10240|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png,gif,webp,zip,rar,txt,csv',
        ], [
            'attachments.*.max' => 'حجم كل مرفق يجب ألا يتجاوز 10 ميجابايت.',
            'attachments.*.mimes' => 'نوع ملف غير مدعوم.',
        ]);

        $attachments = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                if (! $file->isValid()) {
                    continue;
                }
                $attachments[] = [
                    'path' => $file->getRealPath(),
                    'name' => $file->getClientOriginalName(),
                ];
            }
        }

        $parseList = function (?string $raw): array {
            if (! $raw) {
                return [];
            }

            return collect(preg_split('/[,;]+/', $raw) ?: [])
                ->map(fn ($e) => trim($e))
                ->filter(fn ($e) => filter_var($e, FILTER_VALIDATE_EMAIL))
                ->values()
                ->all();
        };

        try {
            $mail = $service->compose(
                $validated['to'],
                $validated['subject'],
                $validated['body'],
                auth()->user(),
                $attachments,
                $parseList($validated['cc'] ?? null),
                $parseList($validated['bcc'] ?? null)
            );

            return redirect()->route('official-mail.show', $mail)
                ->with('success', 'تم إرسال الرسالة بنجاح.');
        } catch (\Throwable $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function markRead(MailMessage $mailMessage)
    {
        $mailMessage->update(['is_read' => true]);

        return response()->json(['success' => true]);
    }
}
