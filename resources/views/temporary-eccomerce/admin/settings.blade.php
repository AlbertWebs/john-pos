@extends('layouts.admin')

@section('title', 'Settings')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Settings</h1>
            <p class="text-gray-600">Manage application settings and configuration</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- General Settings -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">General Settings</h2>
            <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-4">
                @csrf
                
                <div>
                    <label for="site_name" class="block text-sm font-medium text-gray-700 mb-1">Site Name</label>
                    <input type="text" id="site_name" name="site_name" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           value="{{ old('site_name', config('app.name')) }}">
                </div>

                <div>
                    <label for="site_description" class="block text-sm font-medium text-gray-700 mb-1">Site Description</label>
                    <textarea id="site_description" name="site_description" rows="3" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                              placeholder="Enter site description...">{{ old('site_description') }}</textarea>
                </div>

                <div>
                    <label for="admin_email" class="block text-sm font-medium text-gray-700 mb-1">Admin Email</label>
                    <input type="email" id="admin_email" name="admin_email" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           value="{{ old('admin_email', config('mail.admin_email', 'admin@example.com')) }}">
                </div>

                <div>
                    <label for="contact_email" class="block text-sm font-medium text-gray-700 mb-1">Contact Email</label>
                    <input type="email" id="contact_email" name="contact_email" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           value="{{ old('contact_email') }}">
                </div>

                <div>
                    <label for="contact_phone" class="block text-sm font-medium text-gray-700 mb-1">Contact Phone</label>
                    <input type="text" id="contact_phone" name="contact_phone" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           value="{{ old('contact_phone') }}">
                </div>

                <div class="flex justify-end">
                    <button type="submit" 
                            class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                        Save General Settings
                    </button>
                </div>
            </form>
        </div>

        <!-- Email Settings -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Email Settings</h2>
            <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-4">
                @csrf
                
                <div>
                    <label for="mail_host" class="block text-sm font-medium text-gray-700 mb-1">SMTP Host</label>
                    <input type="text" id="mail_host" name="mail_host" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           value="{{ old('mail_host', config('mail.mailers.smtp.host')) }}">
                </div>

                <div>
                    <label for="mail_port" class="block text-sm font-medium text-gray-700 mb-1">SMTP Port</label>
                    <input type="number" id="mail_port" name="mail_port" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           value="{{ old('mail_port', config('mail.mailers.smtp.port')) }}">
                </div>

                <div>
                    <label for="mail_username" class="block text-sm font-medium text-gray-700 mb-1">SMTP Username</label>
                    <input type="text" id="mail_username" name="mail_username" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           value="{{ old('mail_username', config('mail.mailers.smtp.username')) }}">
                </div>

                <div>
                    <label for="mail_password" class="block text-sm font-medium text-gray-700 mb-1">SMTP Password</label>
                    <input type="password" id="mail_password" name="mail_password" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           value="{{ old('mail_password') }}">
                </div>

                <div>
                    <label for="mail_encryption" class="block text-sm font-medium text-gray-700 mb-1">Encryption</label>
                    <select id="mail_encryption" name="mail_encryption" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="tls" {{ old('mail_encryption', config('mail.mailers.smtp.encryption')) == 'tls' ? 'selected' : '' }}>TLS</option>
                        <option value="ssl" {{ old('mail_encryption', config('mail.mailers.smtp.encryption')) == 'ssl' ? 'selected' : '' }}>SSL</option>
                        <option value="" {{ old('mail_encryption', config('mail.mailers.smtp.encryption')) == '' ? 'selected' : '' }}>None</option>
                    </select>
                </div>

                <div class="flex justify-end">
                    <button type="submit" 
                            class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition-colors">
                        Save Email Settings
                    </button>
                </div>
            </form>
        </div>

        <!-- Order Settings -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Order Settings</h2>
            <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-4">
                @csrf
                
                <div>
                    <label for="order_prefix" class="block text-sm font-medium text-gray-700 mb-1">Order Number Prefix</label>
                    <input type="text" id="order_prefix" name="order_prefix" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           value="{{ old('order_prefix', 'ORD') }}">
                </div>

                <div>
                    <label for="currency" class="block text-sm font-medium text-gray-700 mb-1">Currency</label>
                    <select id="currency" name="currency" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="KES" {{ old('currency') == 'KES' ? 'selected' : '' }}>Kenyan Shilling (KES)</option>
                        <option value="USD" {{ old('currency') == 'USD' ? 'selected' : '' }}>US Dollar (USD)</option>
                        <option value="EUR" {{ old('currency') == 'EUR' ? 'selected' : '' }}>Euro (EUR)</option>
                    </select>
                </div>

                <div>
                    <label for="tax_rate" class="block text-sm font-medium text-gray-700 mb-1">Tax Rate (%)</label>
                    <input type="number" id="tax_rate" name="tax_rate" step="0.01" min="0" max="100" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           value="{{ old('tax_rate', 0) }}">
                </div>

                <div>
                    <label for="shipping_cost" class="block text-sm font-medium text-gray-700 mb-1">Default Shipping Cost</label>
                    <input type="number" id="shipping_cost" name="shipping_cost" step="0.01" min="0" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           value="{{ old('shipping_cost', 0) }}">
                </div>

                <div class="flex justify-end">
                    <button type="submit" 
                            class="bg-purple-600 text-white px-6 py-2 rounded-lg hover:bg-purple-700 transition-colors">
                        Save Order Settings
                    </button>
                </div>
            </form>
        </div>

        <!-- System Information -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">System Information</h2>
            <div class="space-y-3">
                <div>
                    <p class="text-sm text-gray-600">Laravel Version</p>
                    <p class="font-medium">{{ app()->version() }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">PHP Version</p>
                    <p class="font-medium">{{ phpversion() }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Database</p>
                    <p class="font-medium">{{ config('database.default') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Environment</p>
                    <p class="font-medium">{{ config('app.env') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Debug Mode</p>
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ config('app.debug') ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                        {{ config('app.debug') ? 'Enabled' : 'Disabled' }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 