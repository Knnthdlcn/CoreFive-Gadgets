<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EmailVerificationOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly string $code,
        public readonly int $expiresMinutes,
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Email Verification Code',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.email-verification-otp',
            with: [
                'code' => $this->code,
                'expiresMinutes' => $this->expiresMinutes,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
