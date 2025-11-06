<?php

namespace App\Jobs;

use App\Models\Sale;
use App\Models\SalesReportLog;
use App\Mail\DailySalesReportMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class SendDailySalesReport implements ShouldQueue
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
            \Log::warning('Daily Sales Report: Admin email not configured');
            return;
        }

        // Get yesterday's sales (previous day's sales)
        // If run at 9 AM, this will get yesterday's complete sales
        $reportDate = Carbon::yesterday()->startOfDay();
        $endDate = Carbon::yesterday()->endOfDay();
        
        $sales = Sale::with(['customer', 'user', 'saleItems.part', 'payments'])
            ->whereBetween('date', [$reportDate, $endDate])
            ->orderBy('date', 'desc')
            ->get();

        // Calculate totals
        $totals = [
            'total_transactions' => $sales->count(),
            'total_subtotal' => $sales->sum('subtotal'),
            'total_tax' => $sales->sum('tax'),
            'total_discount' => $sales->sum('discount'),
            'total_amount' => $sales->sum('total_amount'),
            'avg_sale' => $sales->count() > 0 ? $sales->sum('total_amount') / $sales->count() : 0,
        ];

        // Generate summary
        $summary = sprintf(
            "Date: %s\nTotal Transactions: %d\nTotal Revenue: KES %s\nAverage Sale: KES %s",
            $reportDate->format('F d, Y'),
            $totals['total_transactions'],
            number_format($totals['total_amount'], 2),
            number_format($totals['avg_sale'], 2)
        );

        // Create report log entry
        $log = SalesReportLog::create([
            'report_date' => $reportDate,
            'report_type' => 'daily',
            'summary' => $summary,
            'recipient_email' => $adminEmail,
            'sent' => false,
        ]);

        try {
            // Send email
            Mail::to($adminEmail)->send(new DailySalesReportMail($reportDate, $sales, $totals, $summary));

            // Update log as sent
            $log->update([
                'sent' => true,
                'sent_at' => now(),
            ]);

            \Log::info('Daily Sales Report sent successfully to ' . $adminEmail);
        } catch (\Exception $e) {
            \Log::error('Failed to send Daily Sales Report: ' . $e->getMessage());
        }
    }
}
