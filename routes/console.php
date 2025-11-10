<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Jobs\SendDailySalesReport;
use App\Jobs\SendHourlyStockStatus;
use App\Jobs\SendLowStockAlert;
use App\Jobs\SendNextOrderReminder;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule daily sales report at 9:00 AM
Schedule::job(new SendDailySalesReport)->dailyAt('09:00');

// Schedule hourly stock status report
Schedule::job(new SendHourlyStockStatus)->hourly();

// Schedule low stock alert check every hour (runs every 30 minutes)
Schedule::job(new SendLowStockAlert)->everyThirtyMinutes();

// Schedule next order reminders
Schedule::job(new SendNextOrderReminder)->dailyAt('08:00');

// Command to manually fire all three jobs
Artisan::command('jobs:run-all', function () {
    $this->info('Dispatching all scheduled jobs...');
    
    // Dispatch Daily Sales Report
    $this->info('1. Dispatching SendDailySalesReport...');
    dispatch(new SendDailySalesReport);
    
    // Dispatch Hourly Stock Status
    $this->info('2. Dispatching SendHourlyStockStatus...');
    dispatch(new SendHourlyStockStatus);
    
    // Dispatch Low Stock Alert
    $this->info('3. Dispatching SendLowStockAlert...');
    dispatch(new SendLowStockAlert);
    
    $this->info('All jobs have been dispatched!');
    $this->warn('Note: Jobs are queued. Make sure queue worker is running: php artisan queue:work');
})->purpose('Manually fire all three scheduled jobs (Daily Sales Report, Hourly Stock Status, Low Stock Alert)');

// Command to delete all sales and restore inventory
Artisan::command('sales:delete-all', function () {
    if (!$this->confirm('⚠️  WARNING: This will delete ALL sales, payments, sale items, returns, and inventory movements. Inventory stock will be restored. Are you sure?')) {
        $this->info('Operation cancelled.');
        return;
    }
    
    $this->info('Deleting all sales data...');
    
    DB::beginTransaction();
    try {
        // Get all sales with their items to restore inventory
        $sales = \App\Models\Sale::with('saleItems')->get();
        
        $this->info('Restoring inventory stock...');
        foreach ($sales as $sale) {
            foreach ($sale->saleItems as $item) {
                // Restore stock quantity
                \App\Models\Inventory::where('id', $item->part_id)
                    ->increment('stock_quantity', $item->quantity);
            }
        }
        
        $this->info('Deleting inventory movements...');
        DB::table('inventory_movements')
            ->where('movement_type', 'sale')
            ->orWhere('reference_type', 'Sale')
            ->delete();
        
        $this->info('Deleting returns (must be deleted before sale_items)...');
        \App\Models\ReturnModel::query()->delete();
        
        $this->info('Deleting payments...');
        \App\Models\Payment::query()->delete();
        
        $this->info('Deleting sale items...');
        \App\Models\SaleItem::query()->delete();
        
        $this->info('Deleting sales...');
        \App\Models\Sale::query()->delete();
        
        DB::commit();
        
        $this->info('✅ All sales data has been deleted successfully!');
        $this->info('Inventory stock has been restored.');
    } catch (\Exception $e) {
        DB::rollBack();
        $this->error('❌ Error: ' . $e->getMessage());
        return 1;
    }
})->purpose('Delete all sales and related data, restore inventory stock');

// Command to delete all sales, inventory, and users (keep categories, brands, vehicle makes/models)
Artisan::command('data:reset-all', function () {
    if (!$this->confirm('⚠️  CRITICAL WARNING: This will delete ALL sales, inventory, users, customers, work orders, and related data. Only categories, brands, vehicle makes, and vehicle models will be kept. Are you absolutely sure?')) {
        $this->info('Operation cancelled.');
        return;
    }
    
    if (!$this->confirm('⚠️  This action cannot be undone. Type "yes" to confirm:')) {
        $this->info('Operation cancelled.');
        return;
    }
    
    $this->info('Starting complete data reset...');
    
    DB::beginTransaction();
    try {
        // 1. Delete all sales and related data
        $this->info('1. Deleting sales and related data...');
        
        // Delete returns first (references sale_items)
        $this->info('   - Deleting returns...');
        \App\Models\ReturnModel::query()->delete();
        
        // Delete payments
        $this->info('   - Deleting payments...');
        \App\Models\Payment::query()->delete();
        
        // Delete sale items
        $this->info('   - Deleting sale items...');
        \App\Models\SaleItem::query()->delete();
        
        // Delete sales
        $this->info('   - Deleting sales...');
        \App\Models\Sale::query()->delete();
        
        // 2. Delete all inventory and related data
        $this->info('2. Deleting inventory and related data...');
        
        // Delete inventory movements (all types)
        $this->info('   - Deleting inventory movements...');
        \App\Models\InventoryMovement::query()->delete();
        
        // Delete price histories (if table exists)
        if (Schema::hasTable('price_histories')) {
            $this->info('   - Deleting price histories...');
            DB::table('price_histories')->delete();
        }
        
        // Delete inventory-vehicle_model pivot table entries (if table exists)
        if (Schema::hasTable('inventory_vehicle_model')) {
            $this->info('   - Deleting inventory-vehicle model relationships...');
            DB::table('inventory_vehicle_model')->delete();
        }
        
        // Delete all inventory items
        $this->info('   - Deleting inventory items...');
        \App\Models\Inventory::query()->delete();
        
        // 3. Delete customers
        $this->info('3. Deleting customers...');
        \App\Models\Customer::query()->delete();
        
        // 4. Delete pending payments (if table exists)
        if (Schema::hasTable('pending_payments')) {
            $this->info('4. Deleting pending payments...');
            DB::table('pending_payments')->delete();
        }
        
        // 5. Delete work orders and related (if tables exist)
        if (Schema::hasTable('work_order_items')) {
            $this->info('5. Deleting work order items...');
            DB::table('work_order_items')->delete();
        }
        if (Schema::hasTable('work_orders')) {
            $this->info('   - Deleting work orders...');
            DB::table('work_orders')->delete();
        }
        
        // 6. Delete sales report logs
        $this->info('6. Deleting sales report logs...');
        \App\Models\SalesReportLog::query()->delete();
        
        // 7. Delete all users (except if you want to keep at least one admin)
        $this->info('7. Deleting users...');
        \App\Models\User::query()->delete();
        
        DB::commit();
        
        $this->info('');
        $this->info('✅ Complete data reset successful!');
        $this->info('');
        $this->info('Deleted:');
        $this->info('  - All sales, payments, sale items, returns');
        $this->info('  - All inventory items and movements');
        $this->info('  - All customers');
        $this->info('  - All users');
        $this->info('  - All work orders');
        $this->info('  - All pending payments');
        $this->info('  - All sales report logs');
        $this->info('');
        $this->info('Kept:');
        $this->info('  - Categories');
        $this->info('  - Brands');
        $this->info('  - Vehicle Makes');
        $this->info('  - Vehicle Models');
        $this->warn('');
        $this->warn('⚠️  You will need to create a new admin user to log in!');
        
    } catch (\Exception $e) {
        DB::rollBack();
        $this->error('❌ Error: ' . $e->getMessage());
        $this->error('Stack trace: ' . $e->getTraceAsString());
        return 1;
    }
})->purpose('Delete all sales, inventory, users, customers, and related data (keeps categories, brands, vehicle makes/models)');
