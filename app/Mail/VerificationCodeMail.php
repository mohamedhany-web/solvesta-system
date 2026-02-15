<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerificationCodeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $code;
    public $userName;

    /**
     * Create a new message instance.
     */
    public function __construct(string $code, string $userName = 'المستخدم')
    {
        $this->code = $code;
        $this->userName = $userName;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('كود التحقق - Solvesta')
            ->view('emails.verification-code')
            ->with([
                'code' => $this->code,
                'userName' => $this->userName,
            ]);
    }
}

