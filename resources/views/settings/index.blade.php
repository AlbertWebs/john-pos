@extends('layouts.app')

@section('title', 'System Settings')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">System Settings</h1>
        <p class="text-gray-600 mt-1">Configure your system preferences</p>
    </div>

    <div class="bg-white rounded-lg shadow-md p-8">
        <form method="POST" action="{{ route('settings.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Company Information -->
            <div class="mb-8">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Company Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="company_name" class="block text-sm font-medium text-gray-700 mb-2">Company Name</label>
                        <input 
                            type="text" 
                            name="company_name" 
                            id="company_name"
                            value="{{ old('company_name', $settings['company_name'] ?? '') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('company_name') border-red-500 @enderror"
                            placeholder="Your Company Name"
                        >
                        @error('company_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input 
                            type="email" 
                            name="email" 
                            id="email"
                            value="{{ old('email', $settings['email'] ?? '') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('email') border-red-500 @enderror"
                            placeholder="info@company.com"
                        >
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                        <input 
                            type="text" 
                            name="phone" 
                            id="phone"
                            value="{{ old('phone', $settings['phone'] ?? '') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('phone') border-red-500 @enderror"
                            placeholder="+254 700 000000"
                        >
                        @error('phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Physical Address</label>
                        <textarea 
                            name="address" 
                            id="address"
                            rows="2"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('address') border-red-500 @enderror"
                            placeholder="Street address, City, Country"
                        >{{ old('address', $settings['address'] ?? '') }}</textarea>
                        @error('address')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="logo" class="block text-sm font-medium text-gray-700 mb-2">Company Logo</label>
                        <div class="flex items-center gap-4">
                            @if(isset($settings['logo']) && $settings['logo'])
                            <div class="w-32 h-32 border-2 border-gray-300 rounded-lg overflow-hidden bg-gray-100">
                                <img src="{{ Storage::url($settings['logo']) }}" alt="Logo" class="w-full h-full object-contain">
                            </div>
                            @endif
                            <div class="flex-1">
                                <input 
                                    type="file" 
                                    name="logo" 
                                    id="logo"
                                    accept="image/*"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('logo') border-red-500 @enderror"
                                >
                                <p class="mt-1 text-xs text-gray-500">Upload a logo (max 2MB, JPG/PNG/GIF)</p>
                                @error('logo')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- M-Pesa Configuration -->
            <div class="mb-8 pt-6 border-t">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">M-Pesa Configuration</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="paybill_number" class="block text-sm font-medium text-gray-700 mb-2">Paybill Number</label>
                        <input 
                            type="text" 
                            name="paybill_number" 
                            id="paybill_number"
                            value="{{ old('paybill_number', $settings['paybill_number'] ?? '') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('paybill_number') border-red-500 @enderror"
                            placeholder="123456"
                        >
                        @error('paybill_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="till_number" class="block text-sm font-medium text-gray-700 mb-2">Till Number</label>
                        <input 
                            type="text" 
                            name="till_number" 
                            id="till_number"
                            value="{{ old('till_number', $settings['till_number'] ?? '') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('till_number') border-red-500 @enderror"
                            placeholder="123456"
                        >
                        @error('till_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Email Notifications -->
            <div class="mb-8 pt-6 border-t">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Email Notifications</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="admin_email" class="block text-sm font-medium text-gray-700 mb-2">
                            Admin Email <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="email" 
                            name="admin_email" 
                            id="admin_email"
                            value="{{ old('admin_email', $settings['admin_email'] ?? '') }}"
                            required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('admin_email') border-red-500 @enderror"
                            placeholder="admin@example.com"
                        >
                        <p class="mt-1 text-xs text-gray-500">Receives daily sales reports, hourly stock status, and low stock alerts</p>
                        @error('admin_email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- General Settings -->
            <div class="mb-8 pt-6 border-t">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">General Settings</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="tax_rate" class="block text-sm font-medium text-gray-700 mb-2">Tax Rate (%)</label>
                        <input 
                            type="number" 
                            step="0.01"
                            name="tax_rate" 
                            id="tax_rate"
                            value="{{ old('tax_rate', $settings['tax_rate'] ?? '') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('tax_rate') border-red-500 @enderror"
                            placeholder="16"
                        >
                        @error('tax_rate')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="currency" class="block text-sm font-medium text-gray-700 mb-2">Currency</label>
                        <input 
                            type="text" 
                            name="currency" 
                            id="currency"
                            value="{{ old('currency', $settings['currency'] ?? 'KES') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('currency') border-red-500 @enderror"
                            placeholder="KES"
                        >
                        @error('currency')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end gap-4 pt-6 border-t">
                <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold transition">
                    Save Settings
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

