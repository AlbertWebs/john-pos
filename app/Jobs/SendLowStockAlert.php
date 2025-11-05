<?php

namespace App\Jobs;

use App\Models\Inventory;
use App\Models\SalesReportLog;
use App\Mail\LowStockAlertMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class SendLowStockAlert implements ShouldQueue
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
            \Log::warning('Low Stock Alert: Admin email not configured');
            return;
        }

        // Get low stock items
        $lowStockItems = Inventory::with(['category', 'brand'])
            ->where('status', 'active')
            ->whereColumn('stock_quantity', '<=', 'reorder_level')
            ->orderBy('stock_quantity', 'asc')
            ->get();

        // Only send if there are low stock items
        if ($lowStockItems->isEmpty()) {
            return;
        }

        // Create report log entry
        $log = SalesReportLog::create([
            'report_date' => now()->toDateString(),
            'report_type' => 'alert',
            'summary' => sprintf('URGENT: %d item(s) are below reorder level', $lowStockItems->count()),
            'recipient_email' => $adminEmail,
            'sent' => false,
        ]);

        try {
            // Send email
            Mail::to($adminEmail)->send(new LowStockAlertMail($lowStockItems));

            // Update log as sent
            $log->update([
                'sent' => true,
                'sent_at' => now(),
            ]);

            \Log::info('Low Stock Alert sent successfully to ' . $adminEmail);
        } catch (\Exception $e) {
            \Log::error('Failed to send Low Stock Alert: ' . $e->getMessage());
        }
    }
}
