<div 
    x-data="nextOrderModal('{{ route('next-orders.store') }}', '{{ csrf_token() }}')"
    x-init="init()"
    x-show="open"
    x-cloak
    class="fixed inset-0 z-[60] flex items-center justify-center bg-black bg-opacity-40 p-4"
>
    <div 
        class="bg-white rounded-lg shadow-2xl max-w-2xl w-full"
        @click.away="close()"
    >
        <div class="px-6 py-4 border-b flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-900">Add to Next Orders</h2>
                <p class="text-sm text-gray-500 mt-1">Capture unavailable items so the admin can reorder them later.</p>
            </div>
            <button 
                type="button"
                @click="close()"
                class="text-gray-400 hover:text-gray-600"
            >
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <form @submit.prevent="submit()" class="px-6 py-5 space-y-5">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Item Name <span class="text-red-500">*</span></label>
                    <input 
                        type="text"
                        x-model="form.item_name"
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Requested item name"
                    >
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Part Number</label>
                    <input 
                        type="text"
                        x-model="form.part_number"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="If known"
                    >
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Requested Quantity</label>
                    <input 
                        type="number"
                        min="1"
                        x-model.number="form.requested_quantity"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Customer Name</label>
                    <input 
                        type="text"
                        x-model="form.customer_name"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Optional"
                    >
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Customer Contact</label>
                    <input 
                        type="text"
                        x-model="form.customer_contact"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Phone or email"
                    >
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                <textarea 
                    rows="3"
                    x-model="form.notes"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="Any additional details, vehicle model, urgency, etc."
                ></textarea>
            </div>

            <template x-if="error">
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    <p x-text="error"></p>
                </div>
            </template>

            <template x-if="success">
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    <p x-text="success"></p>
                </div>
            </template>

            <div class="flex justify-end gap-3 pt-4 border-t">
                <button 
                    type="button"
                    @click="close()"
                    class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition font-medium"
                >
                    Cancel
                </button>
                <button 
                    type="submit"
                    :disabled="submitting"
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold transition disabled:opacity-60 disabled:cursor-not-allowed flex items-center gap-2"
                >
                    <svg x-show="submitting" class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span x-text="submitting ? 'Saving...' : 'Save Next Order'"></span>
                </button>
            </div>
        </form>
    </div>
</div>

