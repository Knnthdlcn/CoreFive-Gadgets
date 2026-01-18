<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminCustomerEmailMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly string $customerName,
        public readonly string $emailSubject,
        public readonly string $messageBody,
        public readonly string $originalMessage = '',
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->emailSubject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.admin-customer-email',
            with: [
                'customerName' => $this->customerName,
                'messageBody' => $this->messageBody,
                'originalMessage' => $this->originalMessage,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
