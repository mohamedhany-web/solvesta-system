<?php

namespace App\Services;

use App\Models\Client;
use App\Models\ClientAccount;
use App\Models\ClientMeetingRequest;
use App\Models\ClientWebsiteIssue;
use App\Models\Notification;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class ClientPortalAdminAlertService
{
    public function notifyTicketCreated(Client $client, ClientAccount $account, Ticket $ticket): void
    {
        $title = 'تذكرة جديدة من العميل';
        $message = $client->name.' — '.$ticket->subject;
        $adminUrl = url(route('tickets.show', $ticket, false));

        $this->notifyStaff($title, $message, 'client_new_ticket', [
            'ticket_id' => $ticket->id,
            'client_id' => $client->id,
            'url' => $adminUrl,
        ]);

        $this->sendAlertMail(
            $title,
            $this->buildBody(
                'تذكرة دعم جديدة',
                [
                    'العميل' => $client->name,
                    'الحساب' => e($account->name).' ('.e($account->email).')',
                    'رقم التذكرة' => $ticket->ticket_number,
                    'الموضوع' => e($ticket->subject),
                    'الأولوية' => e($ticket->priority),
                    'التصنيف' => e($ticket->category),
                ],
                $adminUrl
            )
        );
    }

    public function notifyWebsiteIssueCreated(Client $client, ClientAccount $account, ClientWebsiteIssue $issue): void
    {
        $title = 'بلاغ موقع جديد من العميل';
        $message = $client->name.' — '.$issue->title.' ('.$issue->reference_code.')';
        $adminUrl = url(route('client-website-issues.show', $issue, false));

        $this->notifyStaff($title, $message, 'client_new_website_issue', [
            'issue_id' => $issue->id,
            'client_id' => $client->id,
            'url' => $adminUrl,
        ]);

        $this->sendAlertMail(
            $title,
            $this->buildBody(
                'بلاغ موقع جديد',
                [
                    'العميل' => $client->name,
                    'الحساب' => e($account->name).' ('.e($account->email).')',
                    'المرجع' => e($issue->reference_code),
                    'العنوان' => e($issue->title),
                ],
                $adminUrl
            )
        );
    }

    public function notifyMeetingRequestCreated(Client $client, ClientAccount $account, ClientMeetingRequest $meetingRequest): void
    {
        $title = 'طلب اجتماع جديد من العميل';
        $message = $client->name.' — '.$meetingRequest->title.' ('.$meetingRequest->reference_code.')';
        $adminUrl = url(route('client-meeting-requests.show', $meetingRequest, false));

        $this->notifyStaff($title, $message, 'client_new_meeting_request', [
            'meeting_request_id' => $meetingRequest->id,
            'client_id' => $client->id,
            'url' => $adminUrl,
        ]);

        $this->sendAlertMail(
            $title,
            $this->buildBody(
                'طلب اجتماع جديد',
                [
                    'العميل' => $client->name,
                    'الحساب' => e($account->name).' ('.e($account->email).')',
                    'المرجع' => e($meetingRequest->reference_code),
                    'العنوان' => e($meetingRequest->title),
                    'الموعد المفضل' => optional($meetingRequest->preferred_at)->format('Y-m-d H:i'),
                ],
                $adminUrl
            )
        );
    }

    private function notifyStaff(string $title, string $message, string $type, array $data): void
    {
        try {
            $users = User::permission('view-tickets')->get();
        } catch (\Throwable $e) {
            Log::warning('Client portal staff notify: could not resolve users by permission.', ['error' => $e->getMessage()]);
            $users = collect();
        }
        foreach ($users as $user) {
            Notification::create([
                'user_id' => $user->id,
                'type' => $type,
                'title' => $title,
                'message' => $message,
                'data' => $data,
                'is_read' => false,
            ]);
        }
    }

    private function sendAlertMail(string $subject, string $htmlBody): void
    {
        $recipients = config('client_portal.alert_emails', []);
        if ($recipients === []) {
            Log::info('Client portal admin mail skipped: CLIENT_PORTAL_ALERT_EMAIL is empty.', ['subject' => $subject]);

            return;
        }

        try {
            $mailer = new EmailService;
            foreach ($recipients as $to) {
                if (! filter_var($to, FILTER_VALIDATE_EMAIL)) {
                    continue;
                }
                $ok = $mailer->sendEmail($to, $subject, $htmlBody, 'Solvesta — بوابة العميل');
                if (! $ok) {
                    Log::warning('Client portal admin mail failed.', ['to' => $to, 'subject' => $subject]);
                }
            }
        } catch (\Throwable $e) {
            Log::error('Client portal admin mail exception: '.$e->getMessage(), ['subject' => $subject]);
        }
    }

    private function buildBody(string $headline, array $rows, string $adminUrl): string
    {
        $lines = '';
        foreach ($rows as $label => $value) {
            $lines .= '<tr><td style="padding:8px 12px;border:1px solid #e5e7eb;background:#f9fafb;font-weight:bold;width:140px;">'
                .$label
                .'</td><td style="padding:8px 12px;border:1px solid #e5e7eb;">'
                .$value
                .'</td></tr>';
        }

        return '<div dir="rtl" style="font-family:Tahoma,Arial,sans-serif;font-size:14px;color:#111827;">'
            .'<h2 style="margin:0 0 12px;">'.$headline.'</h2>'
            .'<table style="border-collapse:collapse;width:100%;max-width:560px;">'.$lines.'</table>'
            .'<p style="margin-top:16px;"><a href="'.e($adminUrl).'" style="color:#2563eb;">فتح في لوحة الإدارة</a></p>'
            .'</div>';
    }
}
