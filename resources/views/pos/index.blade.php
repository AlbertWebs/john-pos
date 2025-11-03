@extends('layouts.app')

@section('title', 'Point of Sale')

@section('content')
<div class="h-screen flex flex-col" x-data="posInterface()">
    <!-- Header -->
    <div class="bg-white border-b px-6 py-4">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-900">Point of Sale</h1>
            <div class="text-sm text-gray-600">
                <span>{{ Auth::user()->name }}</span>
                <span class="mx-2">•</span>
                <span>{{ now()->format('M d, Y h:i A') }}</span>
            </div>
        </div>
    </div>

    <div class="flex-1 flex overflow-hidden">
        <!-- Left Panel - Product Search & Selection -->
        <div class="w-2/3 bg-gray-50 border-r flex flex-col">
            <!-- Search Bar -->
            <div class="bg-white border-b p-4">
                <div class="relative">
                    <input 
                        type="text" 
                        x-model="searchQuery"
                        @input.debounce.300ms="searchProducts()"
                        placeholder="Search by name, part number, SKU..."
                        class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                    <svg class="absolute left-4 top-4 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
            </div>

            <!-- Search Results -->
            <div class="flex-1 overflow-y-auto p-4">
                <div class="grid grid-cols-2 gap-4" x-show="!loading && products.length > 0">
                    <template x-for="product in products" :key="product.id">
                        <div 
                            @click="addToCart(product)"
                            class="bg-white rounded-lg shadow-md p-4 cursor-pointer hover:shadow-lg transition border-2 hover:border-blue-500"
                            :class="{ 'border-blue-500': product.stock_quantity <= 0 }"
                        >
                            <div class="flex justify-between items-start mb-2">
                                <div class="flex-1">
                                    <h3 class="font-semibold text-gray-900" x-text="product.name"></h3>
                                    <p class="text-xs text-gray-500 mt-1" x-text="product.part_number"></p>
                                </div>
                                <span class="text-lg font-bold text-green-600" x-text="'KES ' + formatPrice(product.selling_price)"></span>
                            </div>
                            
                            <div class="mt-2 flex items-center justify-between text-xs">
                                <div class="flex gap-2">
                                    <span x-show="product.category" class="px-2 py-1 bg-blue-100 text-blue-800 rounded" x-text="product.category"></span>
                                    <span x-show="product.brand" class="px-2 py-1 bg-gray-100 text-gray-800 rounded" x-text="product.brand"></span>
                                </div>
                                <span 
                                    class="px-2 py-1 rounded font-medium"
                                    :class="product.stock_quantity > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                                    x-text="'Stock: ' + product.stock_quantity"
                                ></span>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Loading State -->
                <div x-show="loading" class="flex items-center justify-center h-64">
                    <svg class="animate-spin h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>

                <!-- Empty State -->
                <div x-show="!loading && products.length === 0 && searchQuery" class="flex flex-col items-center justify-center h-64">
                    <svg class="w-16 h-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <p class="text-gray-500">No products found</p>
                </div>

                <!-- Initial State -->
                <div x-show="!loading && products.length === 0 && !searchQuery" class="flex flex-col items-center justify-center h-64">
                    <svg class="w-16 h-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <p class="text-gray-500">Start typing to search for products</p>
                </div>
            </div>
        </div>

        <!-- Right Panel - Cart & Checkout -->
        <div class="w-1/3 bg-white flex flex-col">
            <!-- Customer Selection -->
            <div class="border-b p-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Customer (Optional)</label>
                <div class="flex gap-2">
                    <input 
                        type="text" 
                        x-model="customerSearch"
                        @input.debounce.300ms="searchCustomers()"
                        placeholder="Search customer..."
                        class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                    >
                    <button 
                        @click="showCustomerModal = true"
                        class="px-3 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg text-sm font-medium"
                    >
                        New
                    </button>
                </div>
                <div x-show="selectedCustomer" class="mt-2 p-2 bg-blue-50 rounded-lg">
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium text-gray-900" x-text="selectedCustomer.name"></span>
                        <button @click="selectedCustomer = null" class="text-red-600 hover:text-red-800">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <p class="text-xs text-gray-600 mt-1" x-text="'Points: ' + (selectedCustomer.loyalty_points || 0)"></p>
                </div>
            </div>

            <!-- Cart -->
            <div class="flex-1 overflow-y-auto p-4">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Cart</h2>
                
                <div x-show="cart.length === 0" class="text-center py-12">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                    <p class="text-gray-500">Cart is empty</p>
                </div>

                <div class="space-y-3">
                    <template x-for="(item, index) in cart" :key="index">
                        <div class="bg-gray-50 rounded-lg p-3 border">
                            <div class="flex justify-between items-start mb-2">
                                <div class="flex-1">
                                    <h3 class="font-medium text-gray-900 text-sm" x-text="item.name"></h3>
                                    <p class="text-xs text-gray-500" x-text="item.part_number"></p>
                                </div>
                                <button @click="removeFromCart(index)" class="text-red-600 hover:text-red-800">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                            
                            <div class="flex items-center justify-between mt-2">
                                <div class="flex items-center gap-2">
                                    <button 
                                        @click="updateQuantity(index, -1)"
                                        class="w-7 h-7 rounded border border-gray-300 hover:bg-gray-200 flex items-center justify-center text-sm font-medium"
                                        :disabled="item.quantity <= 1"
                                    >-</button>
                                    <input 
                                        type="number" 
                                        x-model="item.quantity"
                                        @change="updateCartItem(index)"
                                        min="1"
                                        :max="item.stock_quantity"
                                        class="w-16 text-center border border-gray-300 rounded text-sm"
                                    >
                                    <button 
                                        @click="updateQuantity(index, 1)"
                                        class="w-7 h-7 rounded border border-gray-300 hover:bg-gray-200 flex items-center justify-center text-sm font-medium"
                                        :disabled="item.quantity >= item.stock_quantity"
                                    >+</button>
                                </div>
                                <span class="font-semibold text-gray-900" x-text="'KES ' + formatPrice(item.quantity * item.price)"></span>
                            </div>
                            
                            <div class="mt-2 flex items-center justify-between text-xs">
                                <span class="text-gray-500" x-text="'KES ' + formatPrice(item.price) + ' each'"></span>
                                <span 
                                    class="px-2 py-1 rounded"
                                    :class="item.quantity > item.stock_quantity ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800'"
                                    x-text="'Stock: ' + item.stock_quantity"
                                ></span>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Totals & Checkout -->
            <div class="border-t bg-gray-50 p-4">
                <div class="space-y-2 mb-4">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Subtotal</span>
                        <span class="font-medium" x-text="'KES ' + formatPrice(cartTotal.subtotal)"></span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Tax</span>
                        <input 
                            type="number" 
                            x-model.number="tax"
                            @input="calculateTotal()"
                            min="0"
                            step="0.01"
                            class="w-24 text-right border border-gray-300 rounded px-2 py-1 text-sm"
                            placeholder="0.00"
                        >
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Discount</span>
                        <input 
                            type="number" 
                            x-model.number="discount"
                            @input="calculateTotal()"
                            min="0"
                            step="0.01"
                            class="w-24 text-right border border-gray-300 rounded px-2 py-1 text-sm"
                            placeholder="0.00"
                        >
                    </div>
                    <div class="flex justify-between text-lg font-bold border-t pt-2">
                        <span>Total</span>
                        <span class="text-green-600" x-text="'KES ' + formatPrice(cartTotal.total)"></span>
                    </div>
                </div>

                <div class="space-y-2">
                    <div class="flex gap-2">
                        <button 
                            @click="paymentMethod = 'Cash'"
                            class="flex-1 py-2 px-4 rounded-lg font-medium transition"
                            :class="paymentMethod === 'Cash' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'"
                        >
                            Cash
                        </button>
                        <button 
                            @click="paymentMethod = 'M-Pesa'"
                            class="flex-1 py-2 px-4 rounded-lg font-medium transition"
                            :class="paymentMethod === 'M-Pesa' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'"
                        >
                            M-Pesa
                        </button>
                    </div>
                    
                    <div x-show="paymentMethod === 'M-Pesa'" class="mt-2 space-y-2">
                        <input 
                            type="tel" 
                            x-model="mpesaPhoneNumber"
                            placeholder="Customer phone number (2547XXXXXXXX)"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                        >
                        <button 
                            type="button"
                            @click="initiateSTKPush()"
                            :disabled="!mpesaPhoneNumber || cartTotal.total <= 0 || processingSTK"
                            class="w-full py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg font-medium transition disabled:bg-gray-300 disabled:cursor-not-allowed text-sm flex items-center justify-center gap-2"
                        >
                            <span x-show="!processingSTK">Initiate STK Push</span>
                            <span x-show="processingSTK" class="flex items-center gap-2">
                                <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Processing...
                            </span>
                        </button>
                        <input 
                            type="text" 
                            x-model="transactionReference"
                            placeholder="Transaction reference (auto-filled after STK)"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                            readonly
                        >
                        <p class="text-xs text-gray-500">Enter phone number and click "Initiate STK Push" to prompt customer</p>
                    </div>

                    <button 
                        @click="checkout()"
                        :disabled="cart.length === 0 || processing"
                        class="w-full py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg font-semibold transition disabled:bg-gray-300 disabled:cursor-not-allowed"
                    >
                        <span x-show="!processing">Complete Sale</span>
                        <span x-show="processing" class="flex items-center justify-center gap-2">
                            <svg class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Processing...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function posInterface() {
    return {
        searchQuery: '',
        products: [],
        loading: false,
        cart: [],
        customerSearch: '',
        selectedCustomer: null,
        paymentMethod: 'Cash',
        mpesaPhoneNumber: '',
        transactionReference: '',
        processingSTK: false,
        tax: 0,
        discount: 0,
        processing: false,
        showCustomerModal: false,

        cartTotal: {
            subtotal: 0,
            tax: 0,
            discount: 0,
            total: 0,
        },

        init() {
            this.calculateTotal();
        },

        async searchProducts() {
            if (!this.searchQuery || this.searchQuery.length < 2) {
                this.products = [];
                return;
            }

            this.loading = true;
            try {
                const response = await fetch(`{{ route('pos.search') }}?search=${encodeURIComponent(this.searchQuery)}`);
                const data = await response.json();
                this.products = data;
            } catch (error) {
                console.error('Search error:', error);
            } finally {
                this.loading = false;
            }
        },

        addToCart(product) {
            if (product.stock_quantity <= 0) {
                alert('This item is out of stock');
                return;
            }

            const existingIndex = this.cart.findIndex(item => item.id === product.id);
            
            if (existingIndex >= 0) {
                if (this.cart[existingIndex].quantity < product.stock_quantity) {
                    this.cart[existingIndex].quantity++;
                } else {
                    alert('Cannot add more. Stock limit reached.');
                }
            } else {
                this.cart.push({
                    id: product.id,
                    name: product.name,
                    part_number: product.part_number,
                    price: product.selling_price,
                    quantity: 1,
                    stock_quantity: product.stock_quantity,
                    min_price: product.min_price,
                });
            }
            
            this.calculateTotal();
        },

        removeFromCart(index) {
            this.cart.splice(index, 1);
            this.calculateTotal();
        },

        updateQuantity(index, change) {
            const item = this.cart[index];
            const newQuantity = item.quantity + change;
            
            if (newQuantity < 1) return;
            if (newQuantity > item.stock_quantity) {
                alert('Cannot exceed available stock');
                return;
            }
            
            item.quantity = newQuantity;
            this.calculateTotal();
        },

        updateCartItem(index) {
            const item = this.cart[index];
            if (item.quantity < 1) {
                item.quantity = 1;
            }
            if (item.quantity > item.stock_quantity) {
                item.quantity = item.stock_quantity;
                alert('Quantity adjusted to available stock');
            }
            this.calculateTotal();
        },

        calculateTotal() {
            this.cartTotal.subtotal = this.cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            this.cartTotal.tax = this.tax || 0;
            this.cartTotal.discount = this.discount || 0;
            this.cartTotal.total = this.cartTotal.subtotal + this.cartTotal.tax - this.cartTotal.discount;
        },

        async checkout() {
            if (this.cart.length === 0) {
                alert('Cart is empty');
                return;
            }

            // Validate cart items
            for (let item of this.cart) {
                if (item.price < item.min_price) {
                    alert(`${item.name}: Price below minimum (KES ${this.formatPrice(item.min_price)})`);
                    return;
                }
                if (item.quantity > item.stock_quantity) {
                    alert(`${item.name}: Insufficient stock`);
                    return;
                }
            }

            if (!this.paymentMethod) {
                alert('Please select a payment method');
                return;
            }

            if (this.paymentMethod === 'M-Pesa' && !this.transactionReference) {
                alert('Please initiate STK Push and wait for payment confirmation');
                return;
            }

            this.processing = true;

            try {
                const response = await fetch('{{ route("sales.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({
                        customer_id: this.selectedCustomer ? this.selectedCustomer.id : null,
                        items: this.cart.map(item => ({
                            part_id: item.id,
                            quantity: item.quantity,
                            price: item.price,
                        })),
                        payment_method: this.paymentMethod,
                        transaction_reference: this.transactionReference || null,
                        subtotal: this.cartTotal.subtotal,
                        tax: this.cartTotal.tax,
                        discount: this.cartTotal.discount,
                        total_amount: this.cartTotal.total,
                    }),
                });

                const data = await response.json();

                if (data.success) {
                    // Redirect to receipt
                    window.location.href = data.redirect_url;
                } else {
                    alert(data.message || 'Checkout failed');
                }
            } catch (error) {
                console.error('Checkout error:', error);
                alert('An error occurred during checkout');
            } finally {
                this.processing = false;
            }
        },

        async searchCustomers() {
            if (!this.customerSearch || this.customerSearch.length < 2) {
                return;
            }
            
            try {
                const response = await fetch(`/customers?search=${encodeURIComponent(this.customerSearch)}`);
                // Handle customer search if needed
            } catch (error) {
                console.error('Customer search error:', error);
            }
        },

        async initiateSTKPush() {
            if (!this.mpesaPhoneNumber || this.mpesaPhoneNumber.length < 10) {
                alert('Please enter a valid phone number');
                return;
            }

            if (this.cart.length === 0) {
                alert('Cart is empty');
                return;
            }

            this.processingSTK = true;

            try {
                const response = await fetch('{{ route("mpesa.stkPush") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({
                        phone_number: this.mpesaPhoneNumber,
                        amount: this.cartTotal.total,
                        account_reference: 'POS-' + Date.now(),
                        transaction_desc: 'Payment for ' + this.cart.length + ' item(s)',
                    }),
                });

                const data = await response.json();

                if (data.success) {
                    this.transactionReference = data.checkout_request_id;
                    alert(data.customer_message || 'STK Push initiated. Please check your phone.');
                    
                    // Poll for payment status
                    this.checkPaymentStatus(data.checkout_request_id);
                } else {
                    alert(data.message || 'Failed to initiate STK Push');
                }
            } catch (error) {
                console.error('STK Push error:', error);
                alert('Failed to initiate STK Push. Please try again.');
            } finally {
                this.processingSTK = false;
            }
        },

        async checkPaymentStatus(checkoutRequestId) {
            // Poll for payment status every 3 seconds, max 20 times (1 minute)
            let attempts = 0;
            const maxAttempts = 20;

            const pollInterval = setInterval(async () => {
                attempts++;

                try {
                    const response = await fetch('{{ route("mpesa.checkStatus") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        body: JSON.stringify({
                            checkout_request_id: checkoutRequestId,
                        }),
                    });

                    const data = await response.json();

                    if (data.ResultCode == 0) {
                        // Payment successful
                        clearInterval(pollInterval);
                        this.transactionReference = data.MpesaReceiptNumber || checkoutRequestId;
                        alert('Payment confirmed! Transaction: ' + (data.MpesaReceiptNumber || checkoutRequestId));
                    } else if (data.ResultCode && data.ResultCode != 1032) {
                        // Payment failed (1032 is still processing)
                        clearInterval(pollInterval);
                        alert('Payment failed: ' + (data.ResultDesc || 'Unknown error'));
                    }

                    if (attempts >= maxAttempts) {
                        clearInterval(pollInterval);
                        alert('Payment confirmation timeout. Please verify payment manually.');
                    }
                } catch (error) {
                    console.error('Status check error:', error);
                }
            }, 3000);
        },

        formatPrice(price) {
            return parseFloat(price).toFixed(2);
        }
    }
}
</script>
@endsection
