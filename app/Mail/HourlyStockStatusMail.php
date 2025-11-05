<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class HourlyStockStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public $inventory;
    public $lowStockItems;
    public $summary;

    /**
     * Create a new message instance.
     */
    public function __construct($inventory, $lowStockItems, $summary)
    {
        $this->inventory = $inventory;
        $this->lowStockItems = $lowStockItems;
        $this->summary = $summary;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Hourly Stock Status Report - ' . now()->format('M d, Y H:i'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.hourly-stock-status',
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
