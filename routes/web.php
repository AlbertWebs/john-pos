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
use App\Http\Controllers\NextOrderController;

// Public E-commerce Routes (for johllyautospares.co.ke)
// These routes are accessible without authentication
Route::prefix('shop')->name('ecommerce.')->group(function () {
    Route::get('/', [\App\Http\Controllers\EcommerceController::class, 'index'])->name('index');
    Route::get('/products', [\App\Http\Controllers\EcommerceController::class, 'products'])->name('products');
    Route::get('/product/{id}', [\App\Http\Controllers\EcommerceController::class, 'product'])->name('product');
    Route::post('/cart/add', [\App\Http\Controllers\EcommerceController::class, 'addToCart'])->name('cart.add');
    Route::post('/cart/update', [\App\Http\Controllers\EcommerceController::class, 'updateCart'])->name('cart.update');
    Route::post('/cart/remove', [\App\Http\Controllers\EcommerceController::class, 'removeFromCart'])->name('cart.remove');
    Route::get('/cart', [\App\Http\Controllers\EcommerceController::class, 'cart'])->name('cart');
    Route::get('/checkout', [\App\Http\Controllers\EcommerceController::class, 'checkout'])->name('checkout');
    Route::post('/order', [\App\Http\Controllers\EcommerceController::class, 'placeOrder'])->name('order');
    Route::get('/order-confirmation/{id}', [\App\Http\Controllers\EcommerceController::class, 'orderConfirmation'])->name('order-confirmation');
    Route::get('/api/vehicle-models/{makeId}', [\App\Http\Controllers\EcommerceController::class, 'getVehicleModels'])->name('api.vehicle-models');
    Route::get('/payment-status/{id}', [\App\Http\Controllers\EcommerceController::class, 'checkPaymentStatus'])->name('payment-status');
});

// Sitemap
Route::get('/sitemap.xml', [\App\Http\Controllers\SitemapController::class, 'index'])->name('sitemap');

// Redirect old order-confirmation URLs (without /shop prefix) to new format
Route::get('/order-confirmation/{id}', function($id) {
    return redirect()->route('ecommerce.order-confirmation', $id);
});

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
    Route::get('/inventory/import', [InventoryController::class, 'showImportForm'])
        ->name('inventory.import.form');
    Route::post('/inventory/import', [InventoryController::class, 'import'])
        ->name('inventory.import');
    Route::get('/inventory/template', [InventoryController::class, 'downloadTemplate'])
        ->name('inventory.template');
    Route::get('/inventory/check-unique', [InventoryController::class, 'checkUnique'])
        ->name('inventory.checkUnique');
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

    // Next Orders (Backorder requests)
    Route::get('/next-orders', [NextOrderController::class, 'index'])->name('next-orders.index');
    Route::post('/next-orders', [NextOrderController::class, 'store'])->name('next-orders.store');
    Route::post('/next-orders/mark-purchased', [NextOrderController::class, 'markPurchased'])->name('next-orders.mark-purchased');
    Route::patch('/next-orders/{nextOrder}/status', [NextOrderController::class, 'updateStatus'])->name('next-orders.update-status');
    
    // Reports
    Route::get('/reports', [\App\Http\Controllers\ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/sales', [\App\Http\Controllers\ReportController::class, 'sales'])->name('reports.sales');
    Route::get('/reports/inventory', [\App\Http\Controllers\ReportController::class, 'inventory'])->name('reports.inventory');
    Route::get('/reports/top-selling', [\App\Http\Controllers\ReportController::class, 'topSelling'])->name('reports.top-selling');
    Route::get('/most-selling-items', [\App\Http\Controllers\ReportController::class, 'mostSelling'])->name('most-selling.index');
    
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
        Route::post('/stock-status/send-email', [\App\Http\Controllers\Admin\StockStatusController::class, 'sendEmail'])->name('stock-status.send-email');
        Route::resource('seo-settings', \App\Http\Controllers\Admin\SeoSettingsController::class)->only(['index', 'show', 'store', 'update', 'destroy']);
        Route::get('/users', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
        Route::post('/users', [\App\Http\Controllers\Admin\UserController::class, 'store'])->name('users.store');
        Route::get('/users/{id}', [\App\Http\Controllers\Admin\UserController::class, 'show'])->name('users.show');
        Route::put('/users/{id}', [\App\Http\Controllers\Admin\UserController::class, 'update'])->name('users.update');
        Route::post('/users/{id}/reset-pin', [\App\Http\Controllers\Admin\UserController::class, 'resetPin'])->name('users.reset-pin');
        Route::post('/users/{id}/unlock', [\App\Http\Controllers\Admin\UserController::class, 'unlockAccount'])->name('users.unlock');
        Route::post('/users/{id}/reset-attempts', [\App\Http\Controllers\Admin\UserController::class, 'resetAttempts'])->name('users.reset-attempts');
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
    
    // Website Management (Images and Descriptions only)
    Route::prefix('website')->name('website.')->group(function () {
        Route::get('/products', [\App\Http\Controllers\Website\WebsiteProductController::class, 'index'])->name('products.index');
        Route::get('/products/{product}/edit', [\App\Http\Controllers\Website\WebsiteProductController::class, 'edit'])->name('products.edit');
        Route::put('/products/{product}', [\App\Http\Controllers\Website\WebsiteProductController::class, 'update'])->name('products.update');
        
        Route::get('/categories', [\App\Http\Controllers\Website\WebsiteCategoryController::class, 'index'])->name('categories.index');
        Route::get('/categories/{category}/edit', [\App\Http\Controllers\Website\WebsiteCategoryController::class, 'edit'])->name('categories.edit');
        Route::put('/categories/{category}', [\App\Http\Controllers\Website\WebsiteCategoryController::class, 'update'])->name('categories.update');
        
        Route::get('/brands', [\App\Http\Controllers\Website\WebsiteBrandController::class, 'index'])->name('brands.index');
        Route::get('/brands/{brand}/edit', [\App\Http\Controllers\Website\WebsiteBrandController::class, 'edit'])->name('brands.edit');
        Route::put('/brands/{brand}', [\App\Http\Controllers\Website\WebsiteBrandController::class, 'update'])->name('brands.update');
        
        Route::get('/vehicle-models', [\App\Http\Controllers\Website\WebsiteVehicleModelController::class, 'index'])->name('vehicle-models.index');
        Route::get('/vehicle-models/{vehicleModel}/edit', [\App\Http\Controllers\Website\WebsiteVehicleModelController::class, 'edit'])->name('vehicle-models.edit');
        Route::put('/vehicle-models/{vehicleModel}', [\App\Http\Controllers\Website\WebsiteVehicleModelController::class, 'update'])->name('vehicle-models.update');
    });
    
    // Redirect root to POS for authenticated users
    Route::get('/', function () {
        return redirect()->route('pos.index');
    });
});

// Root route - redirect based on subdomain or to e-commerce
// In production: pos.johllyautospares.co.ke -> POS, johllyautospares.co.ke -> E-commerce
// In localhost: /shop -> E-commerce, / -> POS (if authenticated) or login
Route::get('/', function () {
    // Check if we're on the main domain (not pos subdomain)
    // For localhost, we'll redirect to shop
    if (request()->getHost() === 'localhost' || request()->getHost() === '127.0.0.1' || !str_contains(request()->getHost(), 'pos.')) {
        return redirect()->route('ecommerce.index');
    }
    // If on pos subdomain or authenticated, go to POS
    if (auth()->check()) {
        return redirect()->route('pos.index');
    }
    return redirect()->route('login');
})->middleware('guest');
