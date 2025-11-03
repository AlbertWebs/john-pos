@extends('layouts.app')

@section('title', 'Process Return')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('returns.index') }}" class="text-blue-600 hover:text-blue-800 flex items-center gap-2 mb-4">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Returns
        </a>
        <h1 class="text-3xl font-bold text-gray-900">Process Return</h1>
    </div>

    <div class="bg-white rounded-lg shadow-md p-8">
        <form method="POST" action="{{ route('returns.store') }}" x-data="returnForm({{ $sale ? $sale->id : 'null' }})">
            @csrf

            <!-- Sale Selection -->
            <div class="mb-8">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Select Sale</h2>
                <div>
                    <label for="sale_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Invoice Number <span class="text-red-500">*</span>
                    </label>
                    <select 
                        name="sale_id" 
                        id="sale_id"
                        x-model="selectedSaleId"
                        @change="loadSaleItems()"
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('sale_id') border-red-500 @enderror"
                    >
                        <option value="">Select a Sale</option>
                        @if($sale)
                            <option value="{{ $sale->id }}" selected>{{ $sale->invoice_number }} - {{ $sale->customer ? $sale->customer->name : 'Walk-in' }} - KES {{ number_format($sale->total_amount, 2) }}</option>
                        @endif
                        @foreach($recentSales as $recentSale)
                            @if(!$sale || $recentSale->id != $sale->id)
                            <option value="{{ $recentSale->id }}">
                                {{ $recentSale->invoice_number }} - {{ $recentSale->customer ? $recentSale->customer->name : 'Walk-in' }} - KES {{ number_format($recentSale->total_amount, 2) }}
                            </option>
                            @endif
                        @endforeach
                    </select>
                    @error('sale_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Item Selection -->
            <div x-show="selectedSaleId && saleItems.length > 0" class="mb-8">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Select Item to Return</h2>
                <div class="space-y-4">
                    <template x-for="item in saleItems" :key="item.id">
                        <div class="border rounded-lg p-4 hover:bg-gray-50 cursor-pointer" @click="selectItem(item)" :class="selectedItem && selectedItem.id === item.id ? 'border-blue-500 bg-blue-50' : ''">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <h3 class="font-medium text-gray-900" x-text="item.part_name"></h3>
                                    <p class="text-xs text-gray-500" x-text="item.part_number"></p>
                                    <div class="mt-2 text-sm text-gray-600">
                                        <span x-text="'Sold: ' + item.quantity_sold"></span>
                                        <span class="mx-2">•</span>
                                        <span x-text="'Returned: ' + item.quantity_returned"></span>
                                        <span class="mx-2">•</span>
                                        <span x-text="'Available: ' + item.quantity_available"></span>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-semibold text-gray-900" x-text="'KES ' + formatPrice(item.price)"></p>
                                    <p class="text-xs text-gray-500" x-text="'Total: KES ' + formatPrice(item.subtotal)"></p>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Return Details -->
            <div x-show="selectedItem" class="mb-8">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Return Details</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <input type="hidden" name="sale_item_id" x-model="selectedItem.id">
                    <input type="hidden" name="part_id" x-model="selectedItem.part_id">

                    <!-- Quantity -->
                    <div>
                        <label for="quantity" class="block text-sm font-medium text-gray-700 mb-2">
                            Quantity to Return <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="number" 
                            name="quantity" 
                            id="quantity"
                            x-model.number="returnQuantity"
                            :max="selectedItem ? selectedItem.quantity_available : 0"
                            min="1"
                            required
                            @input="calculateRefund()"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('quantity') border-red-500 @enderror"
                        >
                        <p class="mt-1 text-xs text-gray-500" x-show="selectedItem">
                            Maximum: <span x-text="selectedItem.quantity_available"></span>
                        </p>
                        @error('quantity')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Refund Amount -->
                    <div>
                        <label for="refund_amount" class="block text-sm font-medium text-gray-700 mb-2">
                            Refund Amount (KES) <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="number" 
                            step="0.01"
                            name="refund_amount" 
                            id="refund_amount"
                            x-model.number="refundAmount"
                            min="0"
                            required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('refund_amount') border-red-500 @enderror"
                        >
                        @error('refund_amount')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Reason -->
                    <div class="md:col-span-2">
                        <label for="reason" class="block text-sm font-medium text-gray-700 mb-2">Reason for Return</label>
                        <textarea 
                            name="reason" 
                            id="reason"
                            rows="3"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('reason') border-red-500 @enderror"
                            placeholder="Enter reason for return..."
                        >{{ old('reason') }}</textarea>
                        @error('reason')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end gap-4 pt-6 border-t" x-show="selectedItem && selectedItem.quantity_available > 0">
                <a href="{{ route('returns.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition font-medium">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold transition">
                    Process Return
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function returnForm(initialSaleId = null) {
    return {
        selectedSaleId: initialSaleId,
        saleItems: [],
        selectedItem: null,
        returnQuantity: 1,
        refundAmount: 0,

        init() {
            if (this.selectedSaleId) {
                this.loadSaleItems();
            }
        },

        async loadSaleItems() {
            if (!this.selectedSaleId) {
                this.saleItems = [];
                return;
            }

            try {
                const response = await fetch(`{{ route('returns.saleItems', ['saleId' => ':id']) }}`.replace(':id', this.selectedSaleId));
                if (!response.ok) throw new Error('Failed to load sale items');
                const data = await response.json();
                this.saleItems = data;
            } catch (error) {
                console.error('Error loading sale items:', error);
                alert('Error loading sale items');
                this.saleItems = [];
            }
        },

        selectItem(item) {
            if (item.quantity_available <= 0) {
                alert('No items available to return');
                return;
            }
            this.selectedItem = item;
            this.returnQuantity = Math.min(1, item.quantity_available);
            this.calculateRefund();
            
            // Set hidden inputs
            const saleItemInput = document.querySelector('input[name="sale_item_id"]');
            const partInput = document.querySelector('input[name="part_id"]');
            if (saleItemInput) saleItemInput.value = item.id;
            if (partInput) partInput.value = item.part_id;
        },

        calculateRefund() {
            if (this.selectedItem && this.returnQuantity > 0) {
                const amount = parseFloat(this.selectedItem.price) * this.returnQuantity;
                this.refundAmount = amount.toFixed(2);
                const refundInput = document.getElementById('refund_amount');
                if (refundInput) refundInput.value = this.refundAmount;
            }
        },

        formatPrice(price) {
            return parseFloat(price).toFixed(2);
        }
    }
}
</script>
@endsection

