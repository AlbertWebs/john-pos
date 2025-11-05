<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Jobs\SendDailySalesReport;
use App\Jobs\SendHourlyStockStatus;
use App\Jobs\SendLowStockAlert;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule daily sales report at 9:00 AM
Schedule::job(new SendDailySalesReport)->dailyAt('09:00');

// Schedule hourly stock status report
Schedule::job(new SendHourlyStockStatus)->hourly();

// Schedule low stock alert check every hour (runs every 30 minutes)
Schedule::job(new SendLowStockAlert)->everyThirtyMinutes();
