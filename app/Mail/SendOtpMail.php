<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $otp;
    public string $userName;
    public string $purpose; // 'forgot_password' | 'change_password'

    public function __construct(string $otp, string $userName, string $purpose = 'forgot_password')
    {
        $this->otp      = $otp;
        $this->userName = $userName;
        $this->purpose  = $purpose;
    }

    public function envelope(): Envelope
    {
        $subject = match ($this->purpose) {
            'change_password' => '[ERP Kopdes] Kode OTP Ubah Password',
            default           => '[ERP Kopdes] Kode OTP Reset Password',
        };

        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        return new Content(view: 'emails.otp', with: [
            'otp'      => $this->otp,
            'userName' => $this->userName,
            'purpose'  => $this->purpose,
        ]);
    }
}
