<?php

namespace App\Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Illuminate\Support\Facades\Log;

class EmailService
{
    private $mail;

    public function __construct()
    {
        $this->mail = new PHPMailer(true);
        $this->configureSMTP();
    }

    /**
     * Configure SMTP from .env (Hostinger / any SMTP provider).
     */
    private function configureSMTP(): void
    {
        try {
            $host = (string) config('mail.mailers.smtp.host', env('MAIL_HOST', 'smtp.hostinger.com'));
            $port = (int) config('mail.mailers.smtp.port', env('MAIL_PORT', 465));
            $username = (string) config('mail.mailers.smtp.username', env('MAIL_USERNAME'));
            $password = (string) config('mail.mailers.smtp.password', env('MAIL_PASSWORD'));
            $encryption = strtolower((string) env('MAIL_ENCRYPTION', 'ssl'));

            $this->mail->isSMTP();
            $this->mail->Host = $host;
            $this->mail->SMTPAuth = true;
            $this->mail->Username = $username;
            $this->mail->Password = $password;
            $this->mail->Port = $port;
            $this->mail->CharSet = 'UTF-8';
            $this->mail->Encoding = '8bit';

            if ($encryption === 'ssl' || $port === 465) {
                $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            } elseif ($encryption === 'tls' || $port === 587) {
                $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            } else {
                $this->mail->SMTPSecure = false;
            }

            $this->mail->SMTPDebug = 0;
            $this->mail->Debugoutput = function ($str, $level) {
                Log::info("PHPMailer Debug [$level]: $str");
            };
            $this->mail->Timeout = 60;
            $this->mail->SMTPOptions = [
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true,
                ],
            ];
        } catch (Exception $e) {
            Log::error('Failed to configure SMTP: '.$e->getMessage());
        }
    }

    protected function fromAddress(): string
    {
        return (string) (config('mail.from.address') ?: env('MAIL_FROM_ADDRESS', 'info@s0lvesta.com'));
    }

    protected function fromName(string $fallback = 'Solvesta'): string
    {
        $name = config('mail.from.name') ?: env('MAIL_FROM_NAME', $fallback);

        return (string) ($name ?: $fallback);
    }

    /**
     * Send verification code email
     */
    public function sendVerificationCode(string $email, string $code, string $userName = 'المستخدم'): bool
    {
        $finalUserName = 'المستخدم';
        if (! empty($userName) && trim($userName) !== '' && ! is_null($userName)) {
            $finalUserName = trim($userName);
        }

        try {
            $this->mail = new PHPMailer(true);
            $this->configureSMTP();

            Log::info('Sending verification code', [
                'email' => $email,
                'userName' => $finalUserName,
            ]);

            $this->mail->clearAddresses();
            $this->mail->clearAttachments();
            $this->mail->clearCustomHeaders();
            $this->mail->clearReplyTos();

            $this->mail->setFrom($this->fromAddress(), $this->fromName());
            $this->mail->addAddress($email);

            $this->mail->isHTML(true);
            $this->mail->Subject = 'كود التحقق - Solvesta';

            $cleanCode = is_string($code) ? preg_replace('/\s+/', '', $code) : (string) $code;

            $mailBody = view('emails.verification-code', [
                'code' => $cleanCode,
                'userName' => $finalUserName,
            ])->render();

            $this->mail->Body = $mailBody;
            $this->mail->ContentType = 'text/html; charset=UTF-8';
            $this->mail->AltBody = sprintf(
                "مرحباً %s،\n\nكود التحقق الخاص بك هو: %s\n\nهذا الكود صالح لمدة 10 دقائق فقط.",
                $finalUserName,
                $cleanCode
            );

            $result = $this->mail->send();

            if ($result) {
                Log::info('Verification code sent successfully', [
                    'email' => $email,
                    'message_id' => $this->mail->getLastMessageID(),
                ]);

                return true;
            }

            Log::error('PHPMailer send() returned false', [
                'email' => $email,
                'error' => $this->mail->ErrorInfo,
            ]);

            return false;

        } catch (Exception $e) {
            Log::error('Mailer Error: '.$this->mail->ErrorInfo);
            Log::error('Exception: '.$e->getMessage());

            try {
                $this->mail = new PHPMailer(true);
                $this->configureSMTP();
            } catch (Exception $reinitException) {
                Log::error('Failed to reinitialize PHPMailer: '.$reinitException->getMessage());
            }

            return false;
        }
    }

    /**
     * Send custom email
     *
     * @param  array<int, array{path:string,name?:string}>  $attachments
     * @param  array<int, string>  $cc
     * @param  array<int, string>  $bcc
     */
    public function sendEmail(
        string $to,
        string $subject,
        string $body,
        string $fromName = 'Solvesta',
        array $attachments = [],
        array $cc = [],
        array $bcc = []
    ): bool {
        try {
            $this->mail = new PHPMailer(true);
            $this->configureSMTP();

            $this->mail->clearAddresses();
            $this->mail->clearAttachments();
            $this->mail->clearCCs();
            $this->mail->clearBCCs();

            $this->mail->setFrom($this->fromAddress(), $fromName ?: $this->fromName());
            $this->mail->addAddress($to);

            foreach ($cc as $ccEmail) {
                if (filter_var($ccEmail, FILTER_VALIDATE_EMAIL)) {
                    $this->mail->addCC($ccEmail);
                }
            }
            foreach ($bcc as $bccEmail) {
                if (filter_var($bccEmail, FILTER_VALIDATE_EMAIL)) {
                    $this->mail->addBCC($bccEmail);
                }
            }

            foreach ($attachments as $file) {
                $path = $file['path'] ?? null;
                if (! $path || ! is_file($path)) {
                    continue;
                }
                $this->mail->addAttachment($path, $file['name'] ?? basename($path));
            }

            $this->mail->isHTML(true);
            $this->mail->Subject = $subject;
            $this->mail->Body = $body;
            $this->mail->AltBody = trim(strip_tags(str_replace(['<br>', '<br/>', '<br />'], "\n", $body)));

            $this->mail->send();

            return true;

        } catch (Exception $e) {
            Log::error('Mailer Error: '.$this->mail->ErrorInfo);

            return false;
        }
    }

    /**
     * Send meeting reminder / confirmation email.
     */
    public function sendMeetingReminder(
        \App\Models\Meeting $meeting,
        string $toEmail,
        string $whenLabel,
        ?string $recipientName = null
    ): bool {
        $meeting->loadMissing(['organizer', 'participants.user']);

        $body = view('emails.meeting-reminder', [
            'meeting' => $meeting,
            'whenLabel' => $whenLabel,
            'recipientName' => $recipientName,
        ])->render();

        $subject = $whenLabel.' — '.$meeting->title;

        return $this->sendEmail($toEmail, $subject, $body);
    }

    /**
     * Send reminder emails to organizer + participants. Returns count sent.
     */
    public function sendMeetingRemindersToRecipients(\App\Models\Meeting $meeting, string $whenLabel): int
    {
        $sent = 0;
        $meeting->loadMissing(['organizer', 'participants.user']);

        $targets = [];
        if ($meeting->organizer?->email) {
            $targets[strtolower($meeting->organizer->email)] = $meeting->organizer->name;
        }
        foreach ($meeting->participants as $participant) {
            $email = $participant->user?->email;
            if ($email) {
                $targets[strtolower($email)] = $participant->user->name ?? null;
            }
        }

        foreach ($targets as $email => $name) {
            if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
                continue;
            }
            if ($this->sendMeetingReminder($meeting, $email, $whenLabel, $name)) {
                $sent++;
            }
        }

        return $sent;
    }
}
