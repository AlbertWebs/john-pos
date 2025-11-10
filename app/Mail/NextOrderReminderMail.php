<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class NextOrderReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public Collection $orders;
    public array $summary;

    /**
     * Create a new message instance.
     */
    public function __construct(Collection $orders, array $summary)
    {
        $this->orders = $orders;
        $this->summary = $summary;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $count = $this->summary['total'] ?? $this->orders->count();

        return new Envelope(
            subject: 'Next Orders Reminder – ' . $count . ' item(s) pending restock',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.next-order-reminder',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}

