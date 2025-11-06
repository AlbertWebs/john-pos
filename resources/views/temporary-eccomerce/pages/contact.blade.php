@extends('layouts.app')

@php
use App\Models\Setting;
@endphp

@section('content')
<div class="bg-gray-50 py-12">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Contact Us</h1>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                Have questions or need assistance? We're here to help! Reach out to us through any of the channels below.
            </p>
        </div>

        @if(session('success'))
            <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <!-- Contact Information -->
            <div class="space-y-8">
                <div>
                    <h2 class="text-2xl font-semibold text-gray-900 mb-6">Get in Touch</h2>
                    <div class="space-y-6">
                        <!-- Phone -->
                        <div class="flex items-start space-x-4 p-4 border border-gray-200 rounded-lg bg-gray-50">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-phone text-blue-600 text-sm"></i>
                                </div>
                            </div>
                            <div>
                                <h3 class="text-base font-medium text-gray-900">Phone</h3>
                                <p class="text-gray-600 text-sm">{{ Setting::get('contact_phone', '+254 700 123 456') }}</p>
                                <p class="text-xs text-gray-500">{{ Setting::get('business_hours_weekdays', 'Monday - Friday, 8:00 AM - 6:00 PM') }}</p>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="flex items-start space-x-4 p-4 border border-gray-200 rounded-lg bg-gray-50">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-envelope text-green-600 text-sm"></i>
                                </div>
                            </div>
                            <div>
                                <h3 class="text-base font-medium text-gray-900">Email</h3>
                                <p class="text-gray-600 text-sm">{{ Setting::get('contact_email', 'hello@speedandstylehub.com') }}</p>
                                <p class="text-xs text-gray-500">We'll respond within 24 hours</p>
                            </div>
                        </div>

                        <!-- Address -->
                        <div class="flex items-start space-x-4 p-4 border border-gray-200 rounded-lg bg-gray-50">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-map-marker-alt text-purple-600 text-sm"></i>
                                </div>
                            </div>
                            <div>
                                <h3 class="text-base font-medium text-gray-900">Address</h3>
                                <p class="text-gray-600 text-sm">{{ Setting::get('contact_address', 'Westlands, Nairobi') }}</p>
                                <p class="text-xs text-gray-500">{{ Setting::get('contact_city', 'Kenya') }}</p>
                            </div>
                        </div>

                        <!-- Business Hours -->
                        <div class="flex items-start space-x-4 p-4 border border-gray-200 rounded-lg bg-gray-50">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-clock text-orange-600 text-sm"></i>
                                </div>
                            </div>
                            <div>
                                <h3 class="text-base font-medium text-gray-900">Business Hours</h3>
                                <p class="text-gray-600 text-sm">{{ Setting::get('business_hours_weekdays', 'Monday - Friday: 8:00 AM - 6:00 PM') }}</p>
                                <p class="text-gray-600 text-sm">{{ Setting::get('business_hours_saturday', 'Saturday: 9:00 AM - 4:00 PM') }}</p>
                                <p class="text-xs text-gray-500">{{ Setting::get('business_hours_sunday', 'Sunday: Closed') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Social Media -->
                <div class="p-4 border border-gray-200 rounded-lg bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Follow Us</h3>
                    <div class="flex space-x-3">
                        <a href="{{ Setting::get('social_facebook', '#') }}" class="w-9 h-9 bg-blue-600 rounded-lg flex items-center justify-center text-white hover:bg-blue-700 transition-colors">
                            <i class="fab fa-facebook-f text-sm"></i>
                        </a>
                        <a href="{{ Setting::get('social_twitter', '#') }}" class="w-9 h-9 bg-blue-400 rounded-lg flex items-center justify-center text-white hover:bg-blue-500 transition-colors">
                            <i class="fab fa-twitter text-sm"></i>
                        </a>
                        <a href="{{ Setting::get('social_instagram', '#') }}" class="w-9 h-9 bg-pink-600 rounded-lg flex items-center justify-center text-white hover:bg-pink-700 transition-colors">
                            <i class="fab fa-instagram text-sm"></i>
                        </a>
                        <a href="{{ Setting::get('social_linkedin', '#') }}" class="w-9 h-9 bg-blue-800 rounded-lg flex items-center justify-center text-white hover:bg-blue-900 transition-colors">
                            <i class="fab fa-linkedin-in text-sm"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="border border-gray-200 rounded-lg p-6 bg-gray-50">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">Send us a Message</h2>
                <form action="{{ route('contact-messages.store') }}" method="POST" class="space-y-5">
                    @csrf
                    <input type="hidden" name="source" value="contact">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">First Name</label>
                            <input type="text" id="first_name" name="first_name" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                        </div>
                        <div>
                            <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">Last Name</label>
                            <input type="text" id="last_name" name="last_name" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                        </div>
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                        <input type="email" id="email" name="email" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                        <input type="tel" id="phone" name="phone" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                    </div>

                    <div>
                        <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">Subject</label>
                        <select id="subject" name="subject" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                            <option value="">Select a subject</option>
                            <option value="general">General Inquiry</option>
                            <option value="support">Technical Support</option>
                            <option value="order">Order Status</option>
                            <option value="returns">Returns & Refunds</option>
                            <option value="feedback">Feedback</option>
                        </select>
                    </div>

                    <div>
                        <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Message</label>
                        <textarea id="message" name="message" rows="4" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm" placeholder="Tell us how we can help you..."></textarea>
                    </div>

                    <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg font-medium hover:bg-blue-700 transition-colors text-sm">
                        Send Message
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
