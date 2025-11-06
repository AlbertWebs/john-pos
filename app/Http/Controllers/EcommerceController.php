<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\Category;
use App\Models\Brand;
use App\Models\VehicleMake;
use App\Models\VehicleModel;
use App\Models\Customer;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Payment;
use App\Models\InventoryMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Services\MpesaService;

class EcommerceController extends Controller
{
    protected $mpesaService;

    public function __construct(MpesaService $mpesaService)
    {
        $this->mpesaService = $mpesaService;
    }
    protected function getSettings()
    {
        return DB::table('settings')
            ->pluck('value', 'key')
            ->toArray();
    }

    public function index()
    {
        // Get all categories with products
        $allCategories = Category::with(['inventory' => function($query) {
                $query->where('status', 'active')
                      ->where('stock_quantity', '>', 0)
                      ->with(['brand', 'category'])
                      ->orderBy('stock_quantity', 'desc')
                      ->orderBy('created_at', 'desc');
            }])
            ->orderBy('name')
            ->get();

        // Filter and prepare categories with at least 1 product, prioritizing those with 4+
        $categoriesWithProducts = $allCategories
            ->filter(function($category) {
                return $category->inventory->count() > 0;
            })
            ->map(function($category) {
                // Get up to 4 products for each category
                $products = $category->inventory->take(4);
                $category->products = $products;
                return $category;
            })
            ->sortByDesc(function($category) {
                // Sort by product count (categories with more products first)
                return $category->products->count();
            })
            ->take(7)
            ->values();

        // Featured products (active, in stock)
        $featuredProducts = Inventory::with(['brand', 'category'])
            ->where('status', 'active')
            ->where('stock_quantity', '>', 0)
            ->orderBy('created_at', 'desc')
            ->limit(4)
            ->get();

        // Recent products (active, in stock)
        $recentProducts = Inventory::with(['brand', 'category'])
            ->where('status', 'active')
            ->where('stock_quantity', '>', 0)
            ->orderBy('created_at', 'desc')
            ->limit(4)
            ->get();

        // Categories with images
        $categories = Category::whereNotNull('image')
            ->orderBy('name')
            ->get();

        // Brands with images
        $brands = Brand::whereNotNull('image')
            ->orderBy('brand_name')
            ->get();

        // Get company settings
        $settings = $this->getSettings();

        // SEO Data
        $pageType = 'homepage';
        $pageTitle = 'Home';
        $seoData = [
            'company_name' => $settings['company_name'] ?? 'Johlly Auto Spares',
        ];

        return view('ecommerce.index', compact('categoriesWithProducts', 'featuredProducts', 'recentProducts', 'categories', 'brands', 'settings', 'pageType', 'pageTitle', 'seoData'));
    }

    public function products(Request $request)
    {
        $query = Inventory::with(['brand', 'category', 'vehicleMake', 'vehicleModel'])
            ->where('status', 'active');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('part_number', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Category filter
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Brand filter
        if ($request->filled('brand')) {
            $query->where('brand_id', $request->brand);
        }

        // Vehicle Make filter
        if ($request->filled('make')) {
            $query->where('vehicle_make_id', $request->make);
        }

        // Vehicle Model filter
        if ($request->filled('model')) {
            $query->where('vehicle_model_id', $request->model);
        }

        // In stock filter
        if ($request->filled('in_stock')) {
            $query->where('stock_quantity', '>', 0);
        }

        $products = $query->orderBy('name')->paginate(20);
        $categories = Category::orderBy('name')->get();
        $brands = Brand::orderBy('brand_name')->get();
        $vehicleMakes = VehicleMake::orderBy('make_name')->get();
        $settings = $this->getSettings();

        // SEO Data - Check which filters are applied
        $selectedCategory = null;
        $selectedBrand = null;
        $selectedMake = null;
        $selectedModel = null;
        
        if ($request->filled('category')) {
            $selectedCategory = Category::find($request->category);
        }
        
        if ($request->filled('brand')) {
            $selectedBrand = Brand::find($request->brand);
        }
        
        if ($request->filled('make')) {
            $selectedMake = VehicleMake::find($request->make);
        }
        
        if ($request->filled('model')) {
            $selectedModel = VehicleModel::with('vehicleMake')->find($request->model);
        }

        // Determine page type and title based on filters (priority: category > brand > make/model)
        if ($selectedCategory) {
            $pageType = 'category_detail';
            $pageTitle = $selectedCategory->name . ' Products';
            $seoData = [
                'category_name' => $selectedCategory->name,
                'company_name' => $settings['company_name'] ?? 'Johlly Auto Spares',
            ];
        } elseif ($selectedBrand) {
            $pageType = 'brand_detail';
            $pageTitle = $selectedBrand->brand_name . ' Products';
            $seoData = [
                'brand_name' => $selectedBrand->brand_name,
                'company_name' => $settings['company_name'] ?? 'Johlly Auto Spares',
            ];
        } elseif ($selectedModel && $selectedModel->vehicleMake) {
            $pageType = 'vehicle_model_detail';
            $pageTitle = $selectedModel->vehicleMake->make_name . ' ' . $selectedModel->model_name . ' Products';
            $seoData = [
                'make_name' => $selectedModel->vehicleMake->make_name,
                'model_name' => $selectedModel->model_name,
                'company_name' => $settings['company_name'] ?? 'Johlly Auto Spares',
            ];
        } elseif ($selectedMake) {
            $pageType = 'vehicle_models';
            $pageTitle = $selectedMake->make_name . ' Products';
            $seoData = [
                'make_name' => $selectedMake->make_name,
                'company_name' => $settings['company_name'] ?? 'Johlly Auto Spares',
            ];
        } else {
            $pageType = 'products';
            $pageTitle = 'Products';
            $seoData = [
                'company_name' => $settings['company_name'] ?? 'Johlly Auto Spares',
            ];
        }

        return view('ecommerce.products', compact('products', 'categories', 'brands', 'vehicleMakes', 'settings', 'pageType', 'pageTitle', 'seoData', 'selectedCategory', 'selectedBrand', 'selectedMake', 'selectedModel'));
    }

    public function product($id)
    {
        $product = Inventory::with(['brand', 'category', 'vehicleMake', 'vehicleModel', 'vehicleModels'])
            ->where('status', 'active')
            ->findOrFail($id);

        // Related products (same category)
        $relatedProducts = Inventory::with(['brand', 'category'])
            ->where('status', 'active')
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('stock_quantity', '>', 0)
            ->limit(4)
            ->get();
        
        $settings = $this->getSettings();

        // SEO Data
        $pageType = 'product_detail';
        $seoData = [
            'product_name' => $product->name,
            'part_number' => $product->part_number,
            'category_name' => $product->category->name ?? '',
            'brand_name' => $product->brand->brand_name ?? '',
            'price' => $product->selling_price,
            'company_name' => $settings['company_name'] ?? 'Johlly Auto Spares',
        ];

        // Generate product structured data
        $structuredData = \App\Services\SeoService::generateProductStructuredData($product, $settings);

        return view('ecommerce.product', compact('product', 'relatedProducts', 'settings', 'pageType', 'seoData', 'structuredData'));
    }

    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:inventory,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Inventory::findOrFail($request->product_id);

        // Check if product is active
        if ($product->status !== 'active') {
            return response()->json([
                'success' => false,
                'message' => 'Product is not available',
            ], 422);
        }

        // Check stock availability
        if ($product->stock_quantity < $request->quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Insufficient stock. Only ' . $product->stock_quantity . ' available.',
            ], 422);
        }

        $cart = session()->get('cart', []);

        // Check if product already in cart
        if (isset($cart[$request->product_id])) {
            $newQuantity = $cart[$request->product_id]['quantity'] + $request->quantity;
            
            // Check stock again with new quantity
            if ($product->stock_quantity < $newQuantity) {
                return response()->json([
                    'success' => false,
                    'message' => 'Insufficient stock. Only ' . $product->stock_quantity . ' available.',
                ], 422);
            }
            
            $cart[$request->product_id]['quantity'] = $newQuantity;
        } else {
            $cart[$request->product_id] = [
                'id' => $product->id,
                'name' => $product->name,
                'part_number' => $product->part_number,
                'price' => $product->selling_price,
                'quantity' => $request->quantity,
                'image' => $product->image,
            ];
        }

        session()->put('cart', $cart);

        return response()->json([
            'success' => true,
            'message' => 'Product added to cart',
            'cart_count' => $this->getCartCount(),
            'cart_total' => $this->getCartTotal(),
        ]);
    }

    public function updateCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:inventory,id',
            'quantity' => 'required|integer|min:0',
        ]);

        $cart = session()->get('cart', []);

        if ($request->quantity == 0) {
            unset($cart[$request->product_id]);
        } else {
            $product = Inventory::findOrFail($request->product_id);
            
            // Check stock availability
            if ($product->stock_quantity < $request->quantity) {
                return response()->json([
                    'success' => false,
                    'message' => 'Insufficient stock. Only ' . $product->stock_quantity . ' available.',
                ], 422);
            }

            if (isset($cart[$request->product_id])) {
                $cart[$request->product_id]['quantity'] = $request->quantity;
            }
        }

        session()->put('cart', $cart);

        // Get updated item data
        $item = null;
        if (isset($cart[$request->product_id])) {
            $product = Inventory::findOrFail($request->product_id);
            $item = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->selling_price,
                'quantity' => $cart[$request->product_id]['quantity'],
                'subtotal' => $product->selling_price * $cart[$request->product_id]['quantity'],
            ];
        }

        return response()->json([
            'success' => true,
            'message' => 'Cart updated',
            'cart_count' => $this->getCartCount(),
            'cart_total' => $this->getCartTotal(),
            'item' => $item,
        ]);
    }

    public function removeFromCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:inventory,id',
        ]);

        $cart = session()->get('cart', []);
        unset($cart[$request->product_id]);
        session()->put('cart', $cart);

        return response()->json([
            'success' => true,
            'message' => 'Product removed from cart',
            'cart_count' => $this->getCartCount(),
            'cart_total' => $this->getCartTotal(),
        ]);
    }

    public function cart(Request $request)
    {
        $cart = session()->get('cart', []);
        
        // Return JSON for AJAX requests (cart count and items)
        if ($request->ajax() || $request->wantsJson()) {
            $cartItems = [];
            foreach ($cart as $item) {
                $product = Inventory::find($item['id']);
                if ($product && $product->status === 'active') {
                    $cartItems[] = [
                        'id' => $product->id,
                        'name' => $product->name,
                        'price' => $product->selling_price,
                        'quantity' => $item['quantity'],
                        'image' => $product->image,
                    ];
                }
            }
            
            return response()->json([
                'count' => $this->getCartCount(),
                'total' => $this->getCartTotal(),
                'items' => $cartItems,
            ]);
        }
        
        $cartItems = [];
        $total = 0;

        foreach ($cart as $item) {
            $product = Inventory::find($item['id']);
            if ($product && $product->status === 'active') {
                // Update price from database (in case it changed)
                $item['price'] = $product->selling_price;
                $item['subtotal'] = $item['price'] * $item['quantity'];
                $item['stock_available'] = $product->stock_quantity;
                $item['product'] = $product;
                $cartItems[] = $item;
                $total += $item['subtotal'];
            }
        }
        
        $settings = $this->getSettings();

        // SEO Data
        $pageType = 'cart';
        $seoData = [
            'company_name' => $settings['company_name'] ?? 'Johlly Auto Spares',
        ];

        return view('ecommerce.cart', compact('cartItems', 'total', 'settings', 'pageType', 'seoData'));
    }

    public function checkout()
    {
        $cart = session()->get('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('ecommerce.cart')->with('error', 'Your cart is empty');
        }

        // Validate cart items are still available and calculate totals
        $cartItems = [];
        $total = 0;
        
        foreach ($cart as $item) {
            $product = Inventory::find($item['id']);
            if (!$product || $product->status !== 'active' || $product->stock_quantity < $item['quantity']) {
                return redirect()->route('ecommerce.cart')->with('error', 'Some items in your cart are no longer available');
            }
            
            // Update price from database (in case it changed)
            $item['price'] = $product->selling_price;
            $item['subtotal'] = $item['price'] * $item['quantity'];
            $item['name'] = $product->name;
            $item['part_number'] = $product->part_number;
            $item['image'] = $product->image;
            
            $cartItems[] = $item;
            $total += $item['subtotal'];
        }

        $vehicleMakes = VehicleMake::orderBy('make_name')->get();
        $settings = $this->getSettings();

        // SEO Data
        $pageType = 'checkout';
        $seoData = [
            'company_name' => $settings['company_name'] ?? 'Johlly Auto Spares',
        ];
        
        return view('ecommerce.checkout', compact('cartItems', 'total', 'vehicleMakes', 'settings', 'pageType', 'seoData'));
    }

    public function placeOrder(Request $request)
    {
        $cart = session()->get('cart', []);
        
        if (empty($cart)) {
            return response()->json([
                'success' => false,
                'message' => 'Your cart is empty',
            ], 422);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'required|string',
            'payment_method' => 'required|in:Cash,M-Pesa',
            'vehicle_make_id' => 'nullable|exists:vehicle_makes,id',
            'vehicle_model_id' => 'nullable|exists:vehicle_models,id',
        ]);

        DB::beginTransaction();
        try {
            // Find or create customer
            $customer = Customer::where('phone', $validated['phone'])->first();
            
            if (!$customer) {
                $customer = Customer::create([
                    'name' => $validated['name'],
                    'phone' => $validated['phone'],
                    'email' => $validated['email'] ?? null,
                    'address' => $validated['address'],
                ]);
            } else {
                // Update customer info
                $customer->update([
                    'name' => $validated['name'],
                    'email' => $validated['email'] ?? $customer->email,
                    'address' => $validated['address'],
                ]);
            }

            // Calculate totals
            $subtotal = 0;
            $items = [];

            foreach ($cart as $item) {
                $product = Inventory::findOrFail($item['id']);
                
                // Validate stock
                if ($product->stock_quantity < $item['quantity']) {
                    throw new \Exception("Insufficient stock for {$product->name}");
                }

                if ($product->status !== 'active') {
                    throw new \Exception("Product {$product->name} is no longer available");
                }

                $price = $product->selling_price;
                $itemSubtotal = $price * $item['quantity'];
                $subtotal += $itemSubtotal;

                $items[] = [
                    'part_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $price,
                ];
            }

            $tax = 0; // Can be configured later
            $discount = 0;
            $totalAmount = $subtotal + $tax - $discount;

            // Generate invoice number
            $invoiceNumber = $this->generateInvoiceNumber();

            // Create sale
            $sale = Sale::create([
                'invoice_number' => $invoiceNumber,
                'customer_id' => $customer->id,
                'user_id' => 1, // System user for online orders
                'date' => now(),
                'subtotal' => $subtotal,
                'tax' => $tax,
                'discount' => $discount,
                'total_amount' => $totalAmount,
                'payment_status' => $validated['payment_method'] === 'M-Pesa' ? 'pending' : 'completed',
            ]);

            // Create sale items and update inventory
            foreach ($items as $item) {
                $product = Inventory::findOrFail($item['part_id']);

                SaleItem::create([
                    'sale_id' => $sale->id,
                    'part_id' => $item['part_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['quantity'] * $item['price'],
                ]);

                // Update inventory
                $product->decrement('stock_quantity', $item['quantity']);

                // Create inventory movement
                InventoryMovement::create([
                    'part_id' => $item['part_id'],
                    'change_quantity' => -$item['quantity'],
                    'movement_type' => 'sale',
                    'reference_id' => $sale->id,
                    'user_id' => 1, // System user
                    'timestamp' => now(),
                ]);
            }

            // Create payment record
            if ($validated['payment_method'] === 'Cash') {
                Payment::create([
                    'sale_id' => $sale->id,
                    'payment_method' => 'Cash',
                    'amount' => $totalAmount,
                    'payment_date' => now(),
                ]);
            } elseif ($validated['payment_method'] === 'M-Pesa') {
                // Initiate M-Pesa STK Push
                try {
                    $phoneNumber = $validated['phone'];
                    $accountReference = $invoiceNumber;
                    $transactionDesc = 'Payment for order ' . $invoiceNumber;
                    
                    $stkResult = $this->mpesaService->initiateStkPush(
                        $phoneNumber,
                        $totalAmount,
                        $accountReference,
                        $transactionDesc
                    );

                    // Create payment record with checkout request ID
                    Payment::create([
                        'sale_id' => $sale->id,
                        'payment_method' => 'M-Pesa',
                        'amount' => $totalAmount,
                        'transaction_reference' => $stkResult['checkout_request_id'],
                        'payment_date' => now(),
                    ]);

                    return response()->json([
                        'success' => true,
                        'message' => 'M-Pesa STK Push initiated. Please check your phone to complete payment.',
                        'order_id' => $sale->id,
                        'invoice_number' => $sale->invoice_number,
                        'payment_method' => 'M-Pesa',
                        'checkout_request_id' => $stkResult['checkout_request_id'],
                        'customer_message' => $stkResult['customer_message'],
                        'redirect_url' => route('ecommerce.order-confirmation', $sale->id),
                    ]);
                } catch (\Exception $e) {
                    Log::error('M-Pesa STK Push Error in placeOrder', [
                        'error' => $e->getMessage(),
                        'sale_id' => $sale->id,
                    ]);
                    
                    // Order was created but payment failed
                    return response()->json([
                        'success' => false,
                        'message' => 'Order created but M-Pesa payment failed: ' . $e->getMessage(),
                        'order_id' => $sale->id,
                        'invoice_number' => $sale->invoice_number,
                        'redirect_url' => route('ecommerce.order-confirmation', $sale->id),
                    ], 422);
                }
            }

            // Update customer loyalty points
            $points = floor($totalAmount / 100);
            if ($points > 0) {
                $customer->increment('loyalty_points', $points);
            }

            // Clear cart
            session()->forget('cart');

            DB::commit();

            // For Cash payments, return success
            if ($validated['payment_method'] === 'Cash') {
                return response()->json([
                    'success' => true,
                    'message' => 'Order placed successfully',
                    'order_id' => $sale->id,
                    'invoice_number' => $sale->invoice_number,
                    'redirect_url' => route('ecommerce.order-confirmation', $sale->id),
                ]);
            }

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    public function orderConfirmation($id)
    {
        try {
            $sale = Sale::with(['customer', 'saleItems.part', 'payments'])
                ->findOrFail($id);

            $settings = $this->getSettings();

            // SEO Data
            $pageType = 'order_confirmation';
            $seoData = [
                'company_name' => $settings['company_name'] ?? 'Johlly Auto Spares',
            ];

            return view('ecommerce.order-confirmation', compact('sale', 'settings', 'pageType', 'seoData'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            abort(404, 'Order not found. The order may have been deleted or the order ID is incorrect.');
        }
    }

    public function checkPaymentStatus($id)
    {
        $sale = Sale::with('payments')->findOrFail($id);
        
        $payment = $sale->payments()->where('payment_method', 'M-Pesa')->first();
        
        if (!$payment) {
            return response()->json([
                'success' => false,
                'message' => 'No M-Pesa payment found for this order',
            ], 404);
        }

        // Check if payment is already completed
        if ($sale->payment_status === 'completed') {
            return response()->json([
                'success' => true,
                'payment_status' => 'completed',
                'message' => 'Payment completed',
            ]);
        }

        // Query STK Push status
        try {
            $statusResult = $this->mpesaService->queryStkPushStatus($payment->transaction_reference);
            
            // If payment was successful, update the sale and payment
            if (isset($statusResult['result_code']) && $statusResult['result_code'] == 0) {
                $sale->update(['payment_status' => 'completed']);
                
                // Update payment with receipt number if available
                if (isset($statusResult['data']['CallbackMetadata']['Item'])) {
                    foreach ($statusResult['data']['CallbackMetadata']['Item'] as $item) {
                        if ($item['Name'] === 'MpesaReceiptNumber') {
                            $payment->update([
                                'transaction_reference' => $item['Value'],
                                'payment_date' => now(),
                            ]);
                            break;
                        }
                    }
                }
                
                return response()->json([
                    'success' => true,
                    'payment_status' => 'completed',
                    'message' => 'Payment completed successfully',
                ]);
            } else {
                return response()->json([
                    'success' => true,
                    'payment_status' => 'pending',
                    'message' => $statusResult['result_desc'] ?? 'Payment is still pending',
                    'result_code' => $statusResult['result_code'] ?? null,
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error checking payment status', [
                'error' => $e->getMessage(),
                'sale_id' => $id,
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error checking payment status: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function getVehicleModels($makeId)
    {
        $models = VehicleModel::where('vehicle_make_id', $makeId)
            ->orderBy('model_name')
            ->get();

        return response()->json($models);
    }

    private function getCartCount()
    {
        $cart = session()->get('cart', []);
        return array_sum(array_column($cart, 'quantity'));
    }

    private function getCartTotal()
    {
        $cart = session()->get('cart', []);
        $total = 0;
        
        foreach ($cart as $item) {
            $product = Inventory::find($item['id']);
            if ($product) {
                $total += $product->selling_price * $item['quantity'];
            }
        }
        
        return $total;
    }

    private function generateInvoiceNumber()
    {
        $year = date('Y');
        $month = date('m');
        $lastSale = Sale::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->orderBy('id', 'desc')
            ->first();

        if ($lastSale) {
            $lastNumber = (int) substr($lastSale->invoice_number, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return sprintf('INV-%s%s-%04d', $year, $month, $newNumber);
    }
}

