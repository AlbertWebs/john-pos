<?php

namespace App\Jobs;

use App\Mail\NextOrderReminderMail;
use App\Models\NextOrder;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendNextOrderReminder implements ShouldQueue
{
    use Queueable;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $adminEmail = DB::table('settings')->where('key', 'admin_email')->value('value');

        if (!$adminEmail) {
            Log::warning('Next Order Reminder: Admin email not configured');
            return;
        }

        $frequency = DB::table('settings')->where('key', 'next_order_reminder_frequency')->value('value') ?? 'daily';

        if ($frequency === 'weekly' && now()->dayOfWeek !== now()->startOfWeek()->dayOfWeek) {
            Log::info('Next Order Reminder: Skipping because weekly frequency and today is not the scheduled day.');
            return;
        }

        $pendingOrders = NextOrder::with('requester')
            ->whereIn('status', [NextOrder::STATUS_PENDING, NextOrder::STATUS_ORDERED])
            ->orderBy('status')
            ->orderBy('created_at')
            ->get();

        if ($pendingOrders->isEmpty()) {
            Log::info('Next Order Reminder: No pending next orders to notify.');
            return;
        }

        $summary = $this->buildSummary($pendingOrders);

        try {
            Mail::to($adminEmail)->send(new NextOrderReminderMail($pendingOrders, $summary));
            Log::info('Next Order Reminder sent successfully to ' . $adminEmail);
        } catch (\Exception $exception) {
            Log::error('Failed to send Next Order Reminder: ' . $exception->getMessage());
        }
    }

    private function buildSummary(Collection $orders): array
    {
        $byStatus = $orders->groupBy('status')->map->count();
        $oldestOrder = $orders->min('created_at');

        return [
            'total' => $orders->count(),
            'by_status' => $byStatus,
            'oldest_date' => $oldestOrder ? $oldestOrder->format('F j, Y') : null,
        ];
    }
}

