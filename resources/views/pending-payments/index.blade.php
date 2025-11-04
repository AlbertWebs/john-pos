@extends('layouts.app')

@section('title', 'Pending Payments')

@section('content')
<div class="space-y-6" x-data="pendingPayments()">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Pending Payments</h1>
            <p class="text-gray-600 mt-1">Allocate C2B M-Pesa payments to sales</p>
        </div>
        <button 
            @click="refreshPayments()"
            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-semibold transition flex items-center gap-2"
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
            </svg>
            Refresh
        </button>
    </div>

    <!-- Search Bar -->
    <div class="bg-white rounded-lg shadow-md p-4">
        <input 
            type="text" 
            x-model="searchQuery"
            @input.debounce.300ms="searchPayments()"
            placeholder="Search by transaction reference, phone number, account reference..."
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
        >
    </div>

    <!-- Pending Payments Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Transaction Ref</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Account Ref</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <template x-for="payment in pendingPayments" :key="payment.id">
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900" x-text="payment.transaction_reference"></div>
                                <div class="text-xs text-gray-500" x-text="payment.transaction_type"></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900" x-text="formatDate(payment.transaction_date)"></div>
                                <div class="text-xs text-gray-500" x-text="formatTime(payment.transaction_date)"></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900" x-text="payment.full_name || 'Unknown'"></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900" x-text="payment.phone_number || '-'"></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <div class="text-sm font-semibold text-green-600" x-text="'KES ' + formatPrice(payment.amount)"></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900" x-text="payment.account_reference || '-'"></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <button 
                                    @click="openAllocateModal(payment)"
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm font-medium transition"
                                >
                                    Allocate
                                </button>
                                <button 
                                    @click="cancelPayment(payment.id)"
                                    class="ml-2 bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-sm font-medium transition"
                                >
                                    Cancel
                                </button>
                            </td>
                        </tr>
                    </template>
                    <tr x-show="pendingPayments.length === 0">
                        <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                            <div class="flex flex-col items-center gap-2">
                                <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <p>No pending payments found</p>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Allocate Payment Modal -->
    <div 
        x-show="showAllocateModal"
        x-cloak
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4"
        @click.self="closeAllocateModal()"
        @keydown.escape.window="closeAllocateModal()"
    >
        <div 
            class="bg-white rounded-lg shadow-xl max-w-2xl w-full p-6 max-h-[90vh] overflow-y-auto"
            @click.stop
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform scale-95"
            x-transition:enter-end="opacity-100 transform scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 transform scale-100"
            x-transition:leave-end="opacity-0 transform scale-95"
        >
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold text-gray-900">Allocate Payment</h2>
                <button 
                    @click="closeAllocateModal()"
                    class="text-gray-400 hover:text-gray-600"
                >
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div class="space-y-4" x-show="selectedPayment">
                <!-- Payment Details -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="font-semibold text-gray-900 mb-2">Payment Details</h3>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-gray-600">Transaction Ref:</span>
                            <span class="font-medium text-gray-900" x-text="selectedPayment?.transaction_reference"></span>
                        </div>
                        <div>
                            <span class="text-gray-600">Amount:</span>
                            <span class="font-medium text-green-600" x-text="'KES ' + formatPrice(selectedPayment?.amount)"></span>
                        </div>
                        <div>
                            <span class="text-gray-600">Payer:</span>
                            <span class="font-medium text-gray-900" x-text="selectedPayment?.full_name || 'Unknown'"></span>
                        </div>
                        <div>
                            <span class="text-gray-600">Phone:</span>
                            <span class="font-medium text-gray-900" x-text="selectedPayment?.phone_number || '-'"></span>
                        </div>
                    </div>
                </div>

                <!-- Search Sales -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Search Sale</label>
                    <input 
                        type="text" 
                        x-model="saleSearch"
                        @input.debounce.300ms="searchSales()"
                        placeholder="Search by invoice number, customer name, phone..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                </div>

                <!-- Sales Results -->
                <div class="max-h-64 overflow-y-auto border border-gray-200 rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50 sticky top-0">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Invoice</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                                <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Amount</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <template x-for="sale in sales" :key="sale.id">
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-2 text-sm font-medium text-gray-900" x-text="sale.invoice_number"></td>
                                    <td class="px-4 py-2 text-sm text-gray-900" x-text="sale.customer_name"></td>
                                    <td class="px-4 py-2 text-sm text-right text-gray-900" x-text="'KES ' + formatPrice(sale.total_amount)"></td>
                                    <td class="px-4 py-2 text-sm">
                                        <span 
                                            class="px-2 py-1 rounded text-xs font-medium"
                                            :class="{
                                                'bg-yellow-100 text-yellow-800': sale.payment_status === 'pending',
                                                'bg-green-100 text-green-800': sale.payment_status === 'paid',
                                                'bg-blue-100 text-blue-800': sale.payment_status === 'partial'
                                            }"
                                            x-text="sale.payment_status"
                                        ></span>
                                    </td>
                                    <td class="px-4 py-2 text-right">
                                        <button 
                                            @click="allocatePayment(sale.id)"
                                            :disabled="allocating"
                                            class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm font-medium transition disabled:bg-gray-400"
                                        >
                                            Select
                                        </button>
                                    </td>
                                </tr>
                            </template>
                            <tr x-show="sales.length === 0 && saleSearch.length >= 2">
                                <td colspan="5" class="px-4 py-4 text-center text-gray-500 text-sm">
                                    No sales found matching your search
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Error Message -->
                <div x-show="error" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    <p x-text="error"></p>
                </div>

                <!-- Success Message -->
                <div x-show="success" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    <p x-text="success"></p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function pendingPayments() {
    return {
        pendingPayments: [],
        searchQuery: '',
        showAllocateModal: false,
        selectedPayment: null,
        saleSearch: '',
        sales: [],
        allocating: false,
        error: '',
        success: '',

        init() {
            this.loadPayments();
            // Auto-refresh every 30 seconds
            setInterval(() => {
                this.loadPayments();
            }, 30000);
        },

        async loadPayments() {
            try {
                const response = await fetch('{{ route("pending-payments.getPending") }}', {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    }
                });
                const data = await response.json();
                this.pendingPayments = data;
            } catch (error) {
                console.error('Error loading payments:', error);
            }
        },

        async refreshPayments() {
            await this.loadPayments();
            this.success = 'Payments refreshed';
            setTimeout(() => this.success = '', 3000);
        },

        async searchPayments() {
            if (this.searchQuery.length < 2) {
                this.loadPayments();
                return;
            }
            // Implement search if needed
            this.loadPayments();
        },

        openAllocateModal(payment) {
            this.selectedPayment = payment;
            this.showAllocateModal = true;
            this.saleSearch = '';
            this.sales = [];
            this.error = '';
            this.success = '';
        },

        closeAllocateModal() {
            this.showAllocateModal = false;
            this.selectedPayment = null;
            this.saleSearch = '';
            this.sales = [];
            this.error = '';
            this.success = '';
        },

        async searchSales() {
            if (this.saleSearch.length < 2) {
                this.sales = [];
                return;
            }
            try {
                const response = await fetch('{{ route("pending-payments.searchSales") }}?search=' + encodeURIComponent(this.saleSearch), {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    }
                });
                const data = await response.json();
                this.sales = data;
            } catch (error) {
                console.error('Error searching sales:', error);
                this.error = 'Error searching sales';
            }
        },

        async allocatePayment(saleId) {
            if (!this.selectedPayment) return;
            
            this.allocating = true;
            this.error = '';
            this.success = '';

            try {
                const response = await fetch(`{{ url("/pending-payments") }}/${this.selectedPayment.id}/allocate`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: JSON.stringify({
                        sale_id: saleId
                    })
                });

                const data = await response.json();

                if (data.success) {
                    this.success = data.message || 'Payment allocated successfully';
                    this.loadPayments();
                    setTimeout(() => {
                        this.closeAllocateModal();
                    }, 2000);
                } else {
                    this.error = data.message || 'Failed to allocate payment';
                }
            } catch (error) {
                console.error('Error allocating payment:', error);
                this.error = 'An error occurred while allocating payment';
            } finally {
                this.allocating = false;
            }
        },

        async cancelPayment(paymentId) {
            if (!confirm('Are you sure you want to cancel this pending payment?')) {
                return;
            }

            try {
                const response = await fetch(`{{ url("/pending-payments") }}/${paymentId}/cancel`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest',
                    }
                });

                const data = await response.json();

                if (data.success) {
                    this.success = data.message || 'Payment cancelled';
                    this.loadPayments();
                    setTimeout(() => this.success = '', 3000);
                } else {
                    this.error = data.message || 'Failed to cancel payment';
                }
            } catch (error) {
                console.error('Error cancelling payment:', error);
                this.error = 'An error occurred while cancelling payment';
            }
        },

        formatPrice(amount) {
            if (!amount) return '0.00';
            return parseFloat(amount).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        },

        formatDate(dateString) {
            if (!dateString) return '';
            const date = new Date(dateString);
            return date.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
        },

        formatTime(dateString) {
            if (!dateString) return '';
            const date = new Date(dateString);
            return date.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
        }
    }
}
</script>
@endsection

