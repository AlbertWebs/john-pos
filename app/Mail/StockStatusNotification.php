<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class StockStatusNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $inventory;
    public $settings;
    public $lowStockOnly;

    /**
     * Create a new message instance.
     */
    public function __construct($inventory, $settings, $lowStockOnly = false)
    {
        $this->inventory = $inventory;
        $this->settings = $settings;
        $this->lowStockOnly = $lowStockOnly;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        $companyName = $this->settings['company_name'] ?? 'Auto Spares POS';
        $subject = $this->lowStockOnly 
            ? "Low Stock Alert - {$companyName}" 
            : "Stock Status Report - {$companyName}";

        return $this->subject($subject)
                    ->view('emails.stock-status')
                    ->with([
                        'inventory' => $this->inventory,
                        'settings' => $this->settings,
                        'lowStockOnly' => $this->lowStockOnly,
                    ]);
    }
}
