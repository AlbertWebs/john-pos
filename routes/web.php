<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\VehicleMakeController;
use App\Http\Controllers\VehicleModelController;
use App\Http\Controllers\CustomerController;

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

// M-Pesa Callback (must be public - no auth required)
Route::post('/mpesa/callback', [\App\Http\Controllers\MpesaController::class, 'callback'])->name('mpesa.callback');

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Inventory Management
    Route::resource('inventory', InventoryController::class);
    Route::get('/inventory/ajax/vehicle-models', [InventoryController::class, 'getVehicleModels'])
        ->name('inventory.getVehicleModels');
    Route::post('/inventory/bulk-delete', [InventoryController::class, 'bulkDelete'])
        ->name('inventory.bulkDelete');
    
    // Supporting Tables (AJAX endpoints for dropdowns)
    Route::get('/api/categories', [CategoryController::class, 'index'])->name('api.categories');
    Route::get('/api/brands', [BrandController::class, 'index'])->name('api.brands');
    Route::get('/api/vehicle-makes', [VehicleMakeController::class, 'index'])->name('api.vehicleMakes');
    Route::get('/api/vehicle-models/{makeId}', [VehicleModelController::class, 'index'])->name('api.vehicleModels');
    
    // Categories CRUD (for management)
    Route::resource('categories', CategoryController::class);
    
    // Brands CRUD (for management)
    Route::resource('brands', BrandController::class);
    
    // Vehicle Makes CRUD (for management)
    Route::resource('vehicle-makes', VehicleMakeController::class);
    
    // Vehicle Models CRUD (for management)
    Route::resource('vehicle-models', VehicleModelController::class);
    
    // Customers CRUD
    Route::resource('customers', CustomerController::class);
    
    // POS (Point of Sale)
    Route::get('/pos', [\App\Http\Controllers\PosController::class, 'index'])->name('pos.index');
    Route::get('/pos/search', [\App\Http\Controllers\PosController::class, 'search'])->name('pos.search');
    Route::get('/pos/item/{id}', [\App\Http\Controllers\PosController::class, 'getItem'])->name('pos.getItem');
    
    // Sales
    Route::get('/sales', [\App\Http\Controllers\SaleController::class, 'index'])->name('sales.index');
    Route::post('/sales', [\App\Http\Controllers\SaleController::class, 'store'])->name('sales.store');
    Route::get('/sales/{sale}', [\App\Http\Controllers\SaleController::class, 'show'])->name('sales.show');
    Route::get('/sales/{sale}/print', [\App\Http\Controllers\SaleController::class, 'print'])->name('sales.print');
    
    // Reports
    Route::get('/reports', [\App\Http\Controllers\ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/sales', [\App\Http\Controllers\ReportController::class, 'sales'])->name('reports.sales');
    Route::get('/reports/inventory', [\App\Http\Controllers\ReportController::class, 'inventory'])->name('reports.inventory');
    Route::get('/reports/top-selling', [\App\Http\Controllers\ReportController::class, 'topSelling'])->name('reports.top-selling');
    
    // Returns
    Route::resource('returns', \App\Http\Controllers\ReturnController::class);
    Route::get('/returns/sale-items/{saleId}', [\App\Http\Controllers\ReturnController::class, 'getSaleItems'])->name('returns.saleItems');
    
    // Loyalty Points
    Route::get('/loyalty-points', [\App\Http\Controllers\LoyaltyPointsController::class, 'index'])->name('loyalty-points.index');
    Route::get('/loyalty-points/{customer}', [\App\Http\Controllers\LoyaltyPointsController::class, 'show'])->name('loyalty-points.show');
    Route::post('/loyalty-points/{customer}/redeem', [\App\Http\Controllers\LoyaltyPointsController::class, 'redeem'])->name('loyalty-points.redeem');
    Route::post('/loyalty-points/{customer}/adjust', [\App\Http\Controllers\LoyaltyPointsController::class, 'adjust'])->name('loyalty-points.adjust');
    
    // Work Orders
    Route::resource('work-orders', \App\Http\Controllers\WorkOrderController::class);
    
    // Settings
    Route::get('/settings', [\App\Http\Controllers\SettingController::class, 'index'])->name('settings.index');
    Route::put('/settings', [\App\Http\Controllers\SettingController::class, 'update'])->name('settings.update');
    
    // Admin Pages (only for super_admin)
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/sales-reports', [\App\Http\Controllers\Admin\SalesReportLogController::class, 'index'])->name('sales-reports.index');
        Route::get('/stock-status', [\App\Http\Controllers\Admin\StockStatusController::class, 'index'])->name('stock-status.index');
    });
    
    // M-Pesa (STK Push and status check require authentication)
    Route::post('/mpesa/stk-push', [\App\Http\Controllers\MpesaController::class, 'stkPush'])->name('mpesa.stkPush');
    Route::post('/mpesa/check-status', [\App\Http\Controllers\MpesaController::class, 'checkStatus'])->name('mpesa.checkStatus');
    
    // M-Pesa Testing (Simulate C2B transactions)
    Route::post('/mpesa/simulate-c2b', [\App\Http\Controllers\MpesaController::class, 'simulateC2B'])->name('mpesa.simulateC2B');
    
    // Pending Payments (C2B)
    Route::get('/pending-payments', [\App\Http\Controllers\PendingPaymentController::class, 'index'])->name('pending-payments.index');
    Route::get('/pending-payments/get-pending', [\App\Http\Controllers\PendingPaymentController::class, 'getPending'])->name('pending-payments.getPending');
    Route::get('/pending-payments/search-sales', [\App\Http\Controllers\PendingPaymentController::class, 'searchSales'])->name('pending-payments.searchSales');
    Route::post('/pending-payments/{pendingPayment}/allocate', [\App\Http\Controllers\PendingPaymentController::class, 'allocate'])->name('pending-payments.allocate');
    Route::post('/pending-payments/{pendingPayment}/cancel', [\App\Http\Controllers\PendingPaymentController::class, 'cancel'])->name('pending-payments.cancel');
    Route::get('/pending-payments/{pendingPayment}', [\App\Http\Controllers\PendingPaymentController::class, 'show'])->name('pending-payments.show');
    
    // Redirect root to POS for authenticated users
    Route::get('/', function () {
        return redirect()->route('pos.index');
    });
});

// Redirect unauthenticated root to login
Route::get('/', function () {
    return redirect()->route('login');
})->middleware('guest');
