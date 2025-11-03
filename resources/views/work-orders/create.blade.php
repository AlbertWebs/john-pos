@extends('layouts.app')

@section('title', 'Create Work Order')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('work-orders.index') }}" class="text-blue-600 hover:text-blue-800 flex items-center gap-2 mb-4">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Work Orders
        </a>
        <h1 class="text-3xl font-bold text-gray-900">Create Work Order</h1>
    </div>

    <div class="bg-white rounded-lg shadow-md p-8">
        <form method="POST" action="{{ route('work-orders.store') }}" x-data="workOrderForm()">
            @csrf

            <!-- Customer & Vehicle Information -->
            <div class="mb-8">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Customer & Vehicle Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Customer -->
                    <div>
                        <label for="customer_id" class="block text-sm font-medium text-gray-700 mb-2">Customer (Optional)</label>
                        <select 
                            name="customer_id" 
                            id="customer_id"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('customer_id') border-red-500 @enderror"
                        >
                            <option value="">Select Customer</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                    {{ $customer->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('customer_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Vehicle Make -->
                    <div>
                        <label for="vehicle_make_id" class="block text-sm font-medium text-gray-700 mb-2">Vehicle Make</label>
                        <select 
                            name="vehicle_make_id" 
                            id="vehicle_make_id"
                            x-model="vehicleMakeId"
                            @change="loadVehicleModels()"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('vehicle_make_id') border-red-500 @enderror"
                        >
                            <option value="">Select Make</option>
                            @foreach($vehicleMakes as $make)
                                <option value="{{ $make->id }}" {{ old('vehicle_make_id') == $make->id ? 'selected' : '' }}>
                                    {{ $make->make_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('vehicle_make_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Vehicle Model -->
                    <div>
                        <label for="vehicle_model_id" class="block text-sm font-medium text-gray-700 mb-2">Vehicle Model</label>
                        <select 
                            name="vehicle_model_id" 
                            id="vehicle_model_id"
                            :disabled="!vehicleMakeId"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent disabled:bg-gray-100 disabled:cursor-not-allowed @error('vehicle_model_id') border-red-500 @enderror"
                        >
                            <option value="">Select Model</option>
                            <template x-for="model in vehicleModels" :key="model.id">
                                <option :value="model.id" x-text="model.model_name"></option>
                            </template>
                        </select>
                        @error('vehicle_model_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Vehicle Registration -->
                    <div>
                        <label for="vehicle_registration" class="block text-sm font-medium text-gray-700 mb-2">Registration Number</label>
                        <input 
                            type="text" 
                            name="vehicle_registration" 
                            id="vehicle_registration"
                            value="{{ old('vehicle_registration') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('vehicle_registration') border-red-500 @enderror"
                            placeholder="e.g., KCA 123A"
                        >
                        @error('vehicle_registration')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Vehicle Year -->
                    <div>
                        <label for="vehicle_year" class="block text-sm font-medium text-gray-700 mb-2">Year</label>
                        <input 
                            type="text" 
                            name="vehicle_year" 
                            id="vehicle_year"
                            value="{{ old('vehicle_year') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('vehicle_year') border-red-500 @enderror"
                            placeholder="e.g., 2015"
                        >
                        @error('vehicle_year')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Work Order Details -->
            <div class="mb-8">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Work Order Details</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Description -->
                    <div class="md:col-span-3">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            Description <span class="text-red-500">*</span>
                        </label>
                        <textarea 
                            name="description" 
                            id="description"
                            required
                            rows="4"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('description') border-red-500 @enderror"
                            placeholder="Describe the work needed..."
                        >{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <select 
                            name="status" 
                            id="status"
                            required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('status') border-red-500 @enderror"
                        >
                            <option value="pending" {{ old('status', 'pending') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="in_progress" {{ old('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Estimated Cost -->
                    <div>
                        <label for="estimated_cost" class="block text-sm font-medium text-gray-700 mb-2">Estimated Cost (KES)</label>
                        <input 
                            type="number" 
                            step="0.01"
                            name="estimated_cost" 
                            id="estimated_cost"
                            value="{{ old('estimated_cost') }}"
                            min="0"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('estimated_cost') border-red-500 @enderror"
                        >
                        @error('estimated_cost')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Assigned To -->
                    <div>
                        <label for="assigned_to" class="block text-sm font-medium text-gray-700 mb-2">Assigned To</label>
                        <select 
                            name="assigned_to" 
                            id="assigned_to"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('assigned_to') border-red-500 @enderror"
                        >
                            <option value="">Unassigned</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('assigned_to') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('assigned_to')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Notes -->
                    <div class="md:col-span-3">
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                        <textarea 
                            name="notes" 
                            id="notes"
                            rows="3"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('notes') border-red-500 @enderror"
                            placeholder="Additional notes..."
                        >{{ old('notes') }}</textarea>
                        @error('notes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Work Order Items -->
            <div class="mb-8">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold text-gray-900">Items</h2>
                    <button type="button" @click="addItem()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Add Item
                    </button>
                </div>
                <div class="space-y-4" x-show="items.length > 0">
                    <template x-for="(item, index) in items" :key="index">
                        <div class="border rounded-lg p-4 bg-gray-50">
                            <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
                                <input type="hidden" :name="`items[${index}][part_id]`" :value="item.part_id || ''">
                                
                                <!-- Part Selection -->
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Part/Item</label>
                                    <select 
                                        :name="`items[${index}][part_id]`"
                                        x-model="item.part_id"
                                        @change="updateItem(index)"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    >
                                        <option value="">Select Part</option>
                                        @foreach($inventory as $part)
                                            <option value="{{ $part->id }}">{{ $part->name }} ({{ $part->part_number }})</option>
                                        @endforeach
                                        <option value="">Other (Custom)</option>
                                    </select>
                                </div>

                                <!-- Description -->
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Description <span class="text-red-500">*</span>
                                    </label>
                                    <input 
                                        type="text"
                                        :name="`items[${index}][item_description]`"
                                        x-model="item.item_description"
                                        required
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        placeholder="Item description"
                                    >
                                </div>

                                <!-- Type -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Type</label>
                                    <select 
                                        :name="`items[${index}][type]`"
                                        x-model="item.type"
                                        required
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    >
                                        <option value="part">Part</option>
                                        <option value="labor">Labor</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>

                                <!-- Quantity -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Qty</label>
                                    <input 
                                        type="number"
                                        :name="`items[${index}][quantity]`"
                                        x-model.number="item.quantity"
                                        @input="calculateSubtotal(index)"
                                        required
                                        min="1"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    >
                                </div>

                                <!-- Unit Price -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Unit Price</label>
                                    <input 
                                        type="number"
                                        step="0.01"
                                        :name="`items[${index}][unit_price]`"
                                        x-model.number="item.unit_price"
                                        @input="calculateSubtotal(index)"
                                        required
                                        min="0"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    >
                                </div>

                                <!-- Subtotal -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Subtotal</label>
                                    <input 
                                        type="text"
                                        :value="formatPrice(item.subtotal)"
                                        readonly
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 font-semibold"
                                    >
                                    <input type="hidden" :name="`items[${index}][subtotal]`" :value="item.subtotal">
                                </div>

                                <!-- Remove Button -->
                                <div class="flex items-end">
                                    <button type="button" @click="removeItem(index)" class="text-red-600 hover:text-red-800">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
                <div x-show="items.length === 0" class="text-center py-8 text-gray-500">
                    <p>No items added. Click "Add Item" to add items.</p>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end gap-4 pt-6 border-t">
                <a href="{{ route('work-orders.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition font-medium">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold transition">
                    Create Work Order
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function workOrderForm() {
    return {
        items: [],
        vehicleMakeId: null,
        vehicleModels: @json($vehicleModels),

        init() {
            if (this.vehicleModels.length > 0) {
                // Filter models based on initial selection
                this.filterModels();
            }
        },

        addItem() {
            this.items.push({
                part_id: '',
                item_description: '',
                quantity: 1,
                unit_price: 0,
                subtotal: 0,
                type: 'part'
            });
        },

        removeItem(index) {
            this.items.splice(index, 1);
        },

        updateItem(index) {
            const item = this.items[index];
            if (item.part_id) {
                // Auto-fill description from part
                fetch(`{{ route('inventory.index') }}/${item.part_id}`)
                    .then(response => response.json())
                    .then(data => {
                        item.item_description = data.name;
                        item.unit_price = parseFloat(data.selling_price);
                        this.calculateSubtotal(index);
                    })
                    .catch(() => {
                        // Part not found, keep manual entry
                    });
            }
        },

        calculateSubtotal(index) {
            const item = this.items[index];
            item.subtotal = (item.quantity || 0) * (item.unit_price || 0);
        },

        loadVehicleModels() {
            if (!this.vehicleMakeId) {
                this.filterModels();
                return;
            }
            
            fetch(`{{ route('inventory.getVehicleModels') }}?make_id=${this.vehicleMakeId}`)
                .then(response => response.json())
                .then(data => {
                    this.vehicleModels = data;
                })
                .catch(error => {
                    console.error('Error loading vehicle models:', error);
                });
        },

        filterModels() {
            if (this.vehicleMakeId) {
                this.vehicleModels = @json($vehicleModels).filter(m => m.vehicle_make_id == this.vehicleMakeId);
            }
        },

        formatPrice(price) {
            return parseFloat(price).toFixed(2);
        }
    }
}
</script>
@endsection

