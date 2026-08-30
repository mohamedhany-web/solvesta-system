<?php

namespace App\Services;

use App\Models\MailMessage;
use App\Models\Notification;
use App\Models\SystemSetting;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class OfficialMailInboxService
{
    protected function mailbox(): string
    {
        $host = env('MAIL_IMAP_HOST', 'imap.hostinger.com');
        $port = (int) env('MAIL_IMAP_PORT', 993);
        $encryption = strtolower((string) env('MAIL_IMAP_ENCRYPTION', 'ssl'));
        $flags = '/imap';
        if (in_array($encryption, ['ssl', 'tls'], true)) {
            $flags .= '/'.$encryption;
        }
        $flags .= '/novalidate-cert';

        return '{'.$host.':'.$port.$flags.'}INBOX';
    }

    protected function credentials(): array
    {
        return [
            'user' => (string) env('MAIL_USERNAME', env('MAIL_FROM_ADDRESS')),
            'pass' => (string) env('MAIL_PASSWORD'),
        ];
    }

    public function connect()
    {
        if (! function_exists('imap_open')) {
            throw new \RuntimeException('امتداد PHP IMAP غير مفعّل على السيرفر.');
        }

        $creds = $this->credentials();
        if ($creds['user'] === '' || $creds['pass'] === '') {
            throw new \RuntimeException('بيانات البريد (MAIL_USERNAME / MAIL_PASSWORD) غير مكتملة في .env');
        }

        $inbox = @imap_open($this->mailbox(), $creds['user'], $creds['pass']);
        if (! $inbox) {
            throw new \RuntimeException('فشل الاتصال بصندوق البريد: '.imap_last_error());
        }

        return $inbox;
    }

    /**
     * @return array{synced:int, forwarded:int, notified:int}
     */
    public function sync(int $limit = 40): array
    {
        $inbox = $this->connect();
        $synced = 0;
        $forwarded = 0;
        $notified = 0;

        try {
            $emails = imap_search($inbox, 'ALL') ?: [];
            rsort($emails);
            $emails = array_slice($emails, 0, $limit);

            foreach ($emails as $msgNo) {
                $uid = (string) imap_uid($inbox, $msgNo);
                if (MailMessage::where('folder', 'INBOX')->where('imap_uid', $uid)->exists()) {
                    continue;
                }

                $overview = imap_fetch_overview($inbox, (string) $msgNo, 0)[0] ?? null;
                $structure = imap_fetchstructure($inbox, $msgNo);
                $body = $this->extractBody($inbox, $msgNo, $structure);

                $from = $this->parseAddress($overview->from ?? '');
                $to = $this->parseAddress($overview->to ?? '');
                $subject = $this->decodeMime((string) ($overview->subject ?? ''));
                $messageId = isset($overview->message_id) ? trim((string) $overview->message_id) : null;
                $date = isset($overview->date) ? date('Y-m-d H:i:s', strtotime($overview->date)) : now();

                try {
                    $mail = MailMessage::create([
                        'imap_uid' => $uid,
                        'message_id' => $messageId,
                        'folder' => 'INBOX',
                        'direction' => 'inbound',
                        'from_email' => $from['email'],
                        'from_name' => $this->sanitizeUtf8($from['name']),
                        'to_email' => $to['email'] ?: env('MAIL_FROM_ADDRESS'),
                        'subject' => $this->sanitizeUtf8($subject),
                        'body_html' => $this->sanitizeUtf8($body['html']),
                        'body_text' => $this->sanitizeUtf8($body['text']),
                        'is_read' => false,
                        'received_at' => $date,
                    ]);
                } catch (\Throwable $e) {
                    Log::warning('Skip mail message due to save error', [
                        'uid' => $uid,
                        'subject' => $subject,
                        'error' => $e->getMessage(),
                    ]);

                    continue;
                }

                $synced++;
                $notified += $this->notifyAdmins($mail) ? 1 : 0;

                if ($this->shouldForward() && $this->forwardIncoming($mail)) {
                    $mail->update(['forwarded' => true, 'forwarded_at' => now()]);
                    $forwarded++;
                }
            }
        } finally {
            imap_close($inbox);
        }

        return compact('synced', 'forwarded', 'notified');
    }

    protected function sanitizeUtf8(?string $value): ?string
    {
        if ($value === null || $value === '') {
            return $value;
        }

        if (! mb_check_encoding($value, 'UTF-8')) {
            $converted = @mb_convert_encoding($value, 'UTF-8', 'Windows-1252');
            $value = $converted !== false ? $converted : $value;
        }

        $clean = @iconv('UTF-8', 'UTF-8//IGNORE', $value);
        if ($clean === false) {
            $clean = preg_replace('/[^\x09\x0A\x0D\x20-\x7E\x{0080}-\x{FFFF}]/u', '', $value) ?? $value;
        }

        $clean = str_replace(["\xC2\xA0", "\xA0"], ' ', $clean);
        $clean = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F]/', '', $clean) ?? $clean;

        return $clean;
    }

    public function reply(MailMessage $original, string $body, ?User $actor = null): MailMessage
    {
        $to = $original->from_email;
        if (! $to || ! filter_var($to, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('لا يوجد بريد صالح للرد عليه.');
        }

        $subject = $original->subject ?? '';
        if (! str_starts_with(mb_strtolower($subject), 're:')) {
            $subject = 'Re: '.$subject;
        }

        $html = nl2br(e($body));
        $quoted = $original->body_html
            ?: nl2br(e((string) $original->body_text));
        $fullHtml = '<div style="font-family:Tahoma,Arial,sans-serif;font-size:14px;line-height:1.7">'.$html
            .'<hr style="margin:24px 0;border:none;border-top:1px solid #ddd">'
            .'<p style="color:#666;font-size:12px">—— الرسالة الأصلية ——</p>'
            .'<blockquote style="border-right:3px solid #ccc;padding-right:12px;margin:0;color:#444">'.$quoted.'</blockquote></div>';

        $mailer = new EmailService;
        $ok = $mailer->sendEmail($to, $subject, $fullHtml, 'Solvesta');
        if (! $ok) {
            throw new \RuntimeException('فشل إرسال الرد عبر SMTP.');
        }

        return MailMessage::create([
            'folder' => 'SENT',
            'direction' => 'outbound',
            'from_email' => env('MAIL_FROM_ADDRESS'),
            'from_name' => env('MAIL_FROM_NAME', 'Solvesta'),
            'to_email' => $to,
            'subject' => $subject,
            'body_html' => $fullHtml,
            'body_text' => $body,
            'is_read' => true,
            'in_reply_to_id' => $original->id,
            'created_by' => $actor?->id,
            'received_at' => now(),
        ]);
    }

    /**
     * @param  array<int, array{path:string,name?:string}>  $attachments
     * @param  array<int, string>  $cc
     * @param  array<int, string>  $bcc
     */
    public function compose(
        string $to,
        string $subject,
        string $body,
        ?User $actor = null,
        array $attachments = [],
        array $cc = [],
        array $bcc = []
    ): MailMessage {
        if (! filter_var($to, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('البريد المستلم غير صالح.');
        }

        $html = '<div style="font-family:Tahoma,Arial,sans-serif;font-size:14px;line-height:1.7;color:#202124">'.nl2br(e($body)).'</div>';
        if ($attachments !== []) {
            $names = collect($attachments)->pluck('name')->filter()->implode('، ');
            if ($names !== '') {
                $html .= '<p style="margin-top:16px;font-size:12px;color:#5f6368">مرفقات: '.e($names).'</p>';
            }
        }

        $mailer = new EmailService;
        $ok = $mailer->sendEmail($to, $subject, $html, 'Solvesta', $attachments, $cc, $bcc);
        if (! $ok) {
            throw new \RuntimeException('فشل إرسال الرسالة عبر SMTP.');
        }

        $ccLine = $cc !== [] ? implode(', ', $cc) : null;

        return MailMessage::create([
            'folder' => 'SENT',
            'direction' => 'outbound',
            'from_email' => env('MAIL_FROM_ADDRESS'),
            'from_name' => env('MAIL_FROM_NAME', 'Solvesta'),
            'to_email' => $to,
            'cc' => $ccLine,
            'subject' => $subject,
            'body_html' => $html,
            'body_text' => $body,
            'is_read' => true,
            'created_by' => $actor?->id,
            'received_at' => now(),
        ]);
    }

    public function shouldForward(): bool
    {
        return SystemSetting::get('mail_forward_enabled', '0') === '1'
            && filter_var(SystemSetting::get('mail_forward_to', ''), FILTER_VALIDATE_EMAIL);
    }

    public function forwardIncoming(MailMessage $mail): bool
    {
        $to = SystemSetting::get('mail_forward_to');
        if (! filter_var($to, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        $subject = '[تحويل] '.($mail->subject ?: '(بدون موضوع)');
        $body = '<div style="font-family:Tahoma,Arial,sans-serif;font-size:14px">'
            .'<p>تم استلام رسالة على البريد الرسمي وتم تحويلها تلقائياً.</p>'
            .'<p><strong>من:</strong> '.e(($mail->from_name ?: '').' <'.$mail->from_email.'>').'<br>'
            .'<strong>إلى:</strong> '.e((string) $mail->to_email).'<br>'
            .'<strong>الموضوع:</strong> '.e((string) $mail->subject).'</p>'
            .'<hr>'.($mail->body_html ?: nl2br(e((string) $mail->body_text)))
            .'</div>';

        try {
            return (new EmailService)->sendEmail($to, $subject, $body, 'Solvesta — تحويل');
        } catch (\Throwable $e) {
            Log::error('Mail forward failed: '.$e->getMessage());

            return false;
        }
    }

    protected function notifyAdmins(MailMessage $mail): bool
    {
        $admins = User::role(['super_admin', 'admin'])->get();
        if ($admins->isEmpty()) {
            return false;
        }

        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'type' => 'official_mail',
                'title' => 'بريد رسمي جديد',
                'message' => ($mail->from_name ?: $mail->from_email).' — '.($mail->subject ?: 'بدون موضوع'),
                'data' => [
                    'mail_message_id' => $mail->id,
                    'url' => url(route('official-mail.show', $mail, false)),
                ],
                'is_read' => false,
            ]);
        }

        return true;
    }

    protected function parseAddress(string $raw): array
    {
        $raw = trim($raw);
        if ($raw === '') {
            return ['email' => null, 'name' => null];
        }

        if (preg_match('/(.*)<([^>]+)>/', $raw, $m)) {
            return [
                'name' => trim($this->decodeMime(trim($m[1], " \t\"'")), " \t\"'") ?: null,
                'email' => trim($m[2]),
            ];
        }

        if (filter_var($raw, FILTER_VALIDATE_EMAIL)) {
            return ['email' => $raw, 'name' => null];
        }

        return ['email' => null, 'name' => $this->decodeMime($raw)];
    }

    protected function decodeMime(string $value): string
    {
        $decoded = @imap_mime_header_decode($value);
        if (! is_array($decoded)) {
            return $value;
        }

        $out = '';
        foreach ($decoded as $part) {
            $charset = ($part->charset === 'default') ? 'UTF-8' : $part->charset;
            $text = $part->text;
            if (strtoupper($charset) !== 'UTF-8') {
                $converted = @iconv($charset, 'UTF-8//IGNORE', $text);
                $text = $converted !== false ? $converted : $text;
            }
            $out .= $text;
        }

        return $out;
    }

    protected function extractBody($inbox, int $msgNo, $structure): array
    {
        $html = null;
        $text = null;

        if (! isset($structure->parts)) {
            $raw = imap_body($inbox, $msgNo);
            $raw = $this->decodePart($raw, $structure->encoding ?? 0);
            if (($structure->subtype ?? '') === 'HTML') {
                $html = $raw;
            } else {
                $text = $raw;
            }

            return ['html' => $html, 'text' => $text];
        }

        $this->walkParts($inbox, $msgNo, $structure->parts, '', $html, $text);

        return ['html' => $html, 'text' => $text];
    }

    protected function walkParts($inbox, int $msgNo, array $parts, string $prefix, ?string &$html, ?string &$text): void
    {
        foreach ($parts as $i => $part) {
            $partNo = $prefix === '' ? (string) ($i + 1) : $prefix.'.'.($i + 1);
            $type = (int) ($part->type ?? 0);
            $subtype = strtoupper((string) ($part->subtype ?? ''));

            if ($type === 1 && ! empty($part->parts)) {
                $this->walkParts($inbox, $msgNo, $part->parts, $partNo, $html, $text);
                continue;
            }

            if ($type !== 0) {
                continue;
            }

            $raw = imap_fetchbody($inbox, $msgNo, $partNo);
            $decoded = $this->decodePart($raw, $part->encoding ?? 0);
            $charset = 'UTF-8';
            if (! empty($part->parameters)) {
                foreach ($part->parameters as $param) {
                    if (strtolower($param->attribute) === 'charset') {
                        $charset = $param->value;
                    }
                }
            }
            if (strtoupper($charset) !== 'UTF-8') {
                $converted = @iconv($charset, 'UTF-8//IGNORE', $decoded);
                $decoded = $converted !== false ? $converted : $decoded;
            }

            if ($subtype === 'HTML' && $html === null) {
                $html = $decoded;
            } elseif ($subtype === 'PLAIN' && $text === null) {
                $text = $decoded;
            }
        }
    }

    protected function decodePart(string $data, int $encoding): string
    {
        return match ($encoding) {
            3 => base64_decode($data) ?: $data,
            4 => quoted_printable_decode($data),
            default => $data,
        };
    }
}
