@extends('layouts.admin')

@php
use App\Models\Setting;
@endphp

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Site Settings</h1>
        <p class="text-gray-600 mt-2">Manage your website settings and contact information</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Contact Settings -->
        <div class="bg-white border border-gray-200 rounded-lg p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-semibold text-gray-900">Contact Information</h2>
                <a href="{{ route('admin.settings.contact') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                    Edit →
                </a>
            </div>
            <div class="space-y-4">
                <div>
                    <label class="text-sm font-medium text-gray-600">Phone</label>
                    <p class="text-gray-900">{{ Setting::get('contact_phone', 'Not set') }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600">Email</label>
                    <p class="text-gray-900">{{ Setting::get('contact_email', 'Not set') }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600">Address</label>
                    <p class="text-gray-900">{{ Setting::get('contact_address', 'Not set') }}, {{ Setting::get('contact_city', 'Not set') }}</p>
                </div>
            </div>
        </div>

        <!-- Social Media Settings -->
        <div class="bg-white border border-gray-200 rounded-lg p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-semibold text-gray-900">Social Media</h2>
                <a href="{{ route('admin.settings.social') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                    Edit →
                </a>
            </div>
            <div class="space-y-4">
                <div>
                    <label class="text-sm font-medium text-gray-600">Facebook</label>
                    <p class="text-gray-900">{{ Setting::get('social_facebook', 'Not set') }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600">Twitter</label>
                    <p class="text-gray-900">{{ Setting::get('social_twitter', 'Not set') }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600">Instagram</label>
                    <p class="text-gray-900">{{ Setting::get('social_instagram', 'Not set') }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600">LinkedIn</label>
                    <p class="text-gray-900">{{ Setting::get('social_linkedin', 'Not set') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 