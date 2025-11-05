<?php

namespace App\Jobs;

use App\Models\Inventory;
use App\Models\SalesReportLog;
use App\Mail\HourlyStockStatusMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class SendHourlyStockStatus implements ShouldQueue
{
    use Queueable;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Get admin email from settings
        $adminEmail = DB::table('settings')->where('key', 'admin_email')->value('value');
        
        if (!$adminEmail) {
            \Log::warning('Hourly Stock Status: Admin email not configured');
            return;
        }

        // Get all inventory items
        $inventory = Inventory::with(['category', 'brand'])
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        // Get low stock items
        $lowStockItems = $inventory->filter(function($item) {
            return $item->stock_quantity <= $item->reorder_level;
        });

        // Generate summary
        $summary = sprintf(
            "Total Items: %d\nLow Stock Items: %d\nStatus: %s",
            $inventory->count(),
            $lowStockItems->count(),
            $lowStockItems->count() > 0 ? 'ALERT - Low stock detected' : 'All items are in stock'
        );

        // Create report log entry
        $log = SalesReportLog::create([
            'report_date' => now()->toDateString(),
            'report_type' => 'hourly',
            'summary' => $summary,
            'recipient_email' => $adminEmail,
            'sent' => false,
        ]);

        try {
            // Send email
            Mail::to($adminEmail)->send(new HourlyStockStatusMail($inventory, $lowStockItems, $summary));

            // Update log as sent
            $log->update([
                'sent' => true,
                'sent_at' => now(),
            ]);

            \Log::info('Hourly Stock Status sent successfully to ' . $adminEmail);
        } catch (\Exception $e) {
            \Log::error('Failed to send Hourly Stock Status: ' . $e->getMessage());
        }
    }
}
