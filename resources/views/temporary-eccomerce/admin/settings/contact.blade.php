@extends('layouts.admin')

@php
use App\Models\Setting;
@endphp

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="mb-6">
        <a href="{{ route('admin.settings') }}" class="text-blue-600 hover:text-blue-800 mb-4 inline-flex items-center">
            <i class="fas fa-arrow-left mr-2"></i>
            Back to Settings
        </a>
    </div>

    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Contact Information</h1>
        <p class="text-gray-600 mt-2">Update your contact details and business hours</p>
    </div>

    <div class="bg-white border border-gray-200 rounded-lg p-6">
        <form action="{{ route('admin.settings.update') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Contact Details -->
                <div class="space-y-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Contact Details</h3>
                    
                    <div>
                        <label for="contact_phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                        <input type="text" id="contact_phone" name="settings[contact_phone]" 
                               value="{{ Setting::get('contact_phone') }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label for="contact_email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                        <input type="email" id="contact_email" name="settings[contact_email]" 
                               value="{{ Setting::get('contact_email') }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label for="contact_address" class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                        <input type="text" id="contact_address" name="settings[contact_address]" 
                               value="{{ Setting::get('contact_address') }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label for="contact_city" class="block text-sm font-medium text-gray-700 mb-2">City/Country</label>
                        <input type="text" id="contact_city" name="settings[contact_city]" 
                               value="{{ Setting::get('contact_city') }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>

                <!-- Business Hours -->
                <div class="space-y-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Business Hours</h3>
                    
                    <div>
                        <label for="business_hours_weekdays" class="block text-sm font-medium text-gray-700 mb-2">Weekdays</label>
                        <input type="text" id="business_hours_weekdays" name="settings[business_hours_weekdays]" 
                               value="{{ Setting::get('business_hours_weekdays') }}" 
                               placeholder="Monday - Friday: 8:00 AM - 6:00 PM"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label for="business_hours_saturday" class="block text-sm font-medium text-gray-700 mb-2">Saturday</label>
                        <input type="text" id="business_hours_saturday" name="settings[business_hours_saturday]" 
                               value="{{ Setting::get('business_hours_saturday') }}" 
                               placeholder="Saturday: 9:00 AM - 4:00 PM"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label for="business_hours_sunday" class="block text-sm font-medium text-gray-700 mb-2">Sunday</label>
                        <input type="text" id="business_hours_sunday" name="settings[business_hours_sunday]" 
                               value="{{ Setting::get('business_hours_sunday') }}" 
                               placeholder="Sunday: Closed"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>
            </div>

            <div class="mt-8 flex justify-end">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection 