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
        <h1 class="text-3xl font-bold text-gray-900">Social Media</h1>
        <p class="text-gray-600 mt-2">Update your social media links</p>
    </div>

    <div class="bg-white border border-gray-200 rounded-lg p-6">
        <form action="{{ route('admin.settings.update') }}" method="POST">
            @csrf
            <div class="space-y-6">
                <div>
                    <label for="social_facebook" class="block text-sm font-medium text-gray-700 mb-2">Facebook URL</label>
                    <input type="url" id="social_facebook" name="settings[social_facebook]" 
                           value="{{ Setting::get('social_facebook') }}" 
                           placeholder="https://facebook.com/yourpage"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label for="social_twitter" class="block text-sm font-medium text-gray-700 mb-2">Twitter URL</label>
                    <input type="url" id="social_twitter" name="settings[social_twitter]" 
                           value="{{ Setting::get('social_twitter') }}" 
                           placeholder="https://twitter.com/yourhandle"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label for="social_instagram" class="block text-sm font-medium text-gray-700 mb-2">Instagram URL</label>
                    <input type="url" id="social_instagram" name="settings[social_instagram]" 
                           value="{{ Setting::get('social_instagram') }}" 
                           placeholder="https://instagram.com/yourhandle"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label for="social_linkedin" class="block text-sm font-medium text-gray-700 mb-2">LinkedIn URL</label>
                    <input type="url" id="social_linkedin" name="settings[social_linkedin]" 
                           value="{{ Setting::get('social_linkedin') }}" 
                           placeholder="https://linkedin.com/company/yourcompany"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
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