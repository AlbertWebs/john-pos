@extends('layouts.app')

@php
use App\Models\Setting;
@endphp

@section('content')
<div class="bg-gray-50 py-12">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Technical Support</h1>
            <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                Need help with your purchase? Our technical support team is here to assist you with any questions
                or issues you may have with our products.
            </p>
        </div>

        <!-- Support Categories -->
        <div class="mb-16">
            <h2 class="text-3xl font-bold text-gray-900 text-center mb-12">How Can We Help?</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Product Setup -->
                <div class="border border-gray-200 rounded-lg p-6 bg-gray-50 hover:bg-gray-100 transition-colors">
                    <div class="w-14 h-14 bg-blue-100 rounded-lg flex items-center justify-center mb-4">
                        <i class="fas fa-cog text-blue-600 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Product Setup</h3>
                    <p class="text-gray-600 mb-4 text-sm leading-relaxed">
                        Need help setting up your new device? Our team can guide you through the installation
                        and configuration process.
                    </p>
                    <a href="#contact" class="text-blue-600 hover:text-blue-700 font-medium text-sm">
                        Get Help →
                    </a>
                </div>

                <!-- Troubleshooting -->
                <div class="border border-gray-200 rounded-lg p-6 bg-gray-50 hover:bg-gray-100 transition-colors">
                    <div class="w-14 h-14 bg-green-100 rounded-lg flex items-center justify-center mb-4">
                        <i class="fas fa-tools text-green-600 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Troubleshooting</h3>
                    <p class="text-gray-600 mb-4 text-sm leading-relaxed">
                        Experiencing issues with your product? We can help diagnose and resolve technical problems.
                    </p>
                    <a href="#contact" class="text-blue-600 hover:text-blue-700 font-medium text-sm">
                        Get Help →
                    </a>
                </div>

                <!-- Warranty Claims -->
                <div class="border border-gray-200 rounded-lg p-6 bg-gray-50 hover:bg-gray-100 transition-colors">
                    <div class="w-14 h-14 bg-purple-100 rounded-lg flex items-center justify-center mb-4">
                        <i class="fas fa-shield-alt text-purple-600 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Warranty Claims</h3>
                    <p class="text-gray-600 mb-4 text-sm leading-relaxed">
                        Need to file a warranty claim? We'll help you through the process and ensure quick resolution.
                    </p>
                    <a href="#contact" class="text-blue-600 hover:text-blue-700 font-medium text-sm">
                        Get Help →
                    </a>
                </div>

                <!-- Software Support -->
                <div class="border border-gray-200 rounded-lg p-6 bg-gray-50 hover:bg-gray-100 transition-colors">
                    <div class="w-14 h-14 bg-orange-100 rounded-lg flex items-center justify-center mb-4">
                        <i class="fas fa-laptop-code text-orange-600 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Software Support</h3>
                    <p class="text-gray-600 mb-4 text-sm leading-relaxed">
                        Having issues with software or drivers? Our experts can help with installation and updates.
                    </p>
                    <a href="#contact" class="text-blue-600 hover:text-blue-700 font-medium text-sm">
                        Get Help →
                    </a>
                </div>

                <!-- Compatibility -->
                <div class="border border-gray-200 rounded-lg p-6 bg-gray-50 hover:bg-gray-100 transition-colors">
                    <div class="w-14 h-14 bg-red-100 rounded-lg flex items-center justify-center mb-4">
                        <i class="fas fa-link text-red-600 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Compatibility</h3>
                    <p class="text-gray-600 mb-4 text-sm leading-relaxed">
                        Unsure if a product is compatible with your system? We can help you find the right match.
                    </p>
                    <a href="#contact" class="text-blue-600 hover:text-blue-700 font-medium text-sm">
                        Get Help →
                    </a>
                </div>

                <!-- Training -->
                <div class="border border-gray-200 rounded-lg p-6 bg-gray-50 hover:bg-gray-100 transition-colors">
                    <div class="w-14 h-14 bg-indigo-100 rounded-lg flex items-center justify-center mb-4">
                        <i class="fas fa-graduation-cap text-indigo-600 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Training</h3>
                    <p class="text-gray-600 mb-4 text-sm leading-relaxed">
                        Need training on how to use your new device? We offer personalized training sessions.
                    </p>
                    <a href="#contact" class="text-blue-600 hover:text-blue-700 font-medium text-sm">
                        Get Help →
                    </a>
                </div>
            </div>
        </div>

        <!-- Support Channels -->
        <div class="mb-16">
            <h2 class="text-3xl font-bold text-gray-900 text-center mb-12">Support Channels</h2>
            @if(session('success'))
            <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                <!-- Contact Information -->
                <div class="space-y-6">
                    <div class="border border-gray-200 rounded-lg p-6 bg-gray-50">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Get in Touch</h3>
                        <div class="space-y-4">
                            <div class="flex items-center space-x-4">
                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-phone text-blue-600 text-sm"></i>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-900 text-sm">Phone Support</h4>
                                    <p class="text-gray-600 text-sm">{{ Setting::get('contact_phone', '+254 700 123 456') }}</p>
                                    <p class="text-xs text-gray-500">{{ Setting::get('business_hours_weekdays', 'Mon-Fri: 8AM-6PM, Sat: 9AM-4PM') }}</p>
                                </div>
                            </div>

                            <div class="flex items-center space-x-4">
                                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-envelope text-green-600 text-sm"></i>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-900 text-sm">Email Support</h4>
                                    <p class="text-gray-600 text-sm">support@speedandstylehub.com</p>
                                    <p class="text-xs text-gray-500">Response within 24 hours</p>
                                </div>
                            </div>

                            <div class="flex items-center space-x-4">
                                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-comments text-purple-600 text-sm"></i>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-900 text-sm">Live Chat</h4>
                                    <p class="text-gray-600 text-sm">Available on website</p>
                                    <p class="text-xs text-gray-500">Instant response</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Support Form -->
                <div class="border border-gray-200 rounded-lg p-6 bg-gray-50">
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Submit a Support Ticket</h3>
                    <form action="{{ route('contact-messages.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <input type="hidden" name="source" value="technical_support">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                            <input type="text" id="name" name="name" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                            <input type="email" id="email" name="email" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div>
                            <label for="product" class="block text-sm font-medium text-gray-700 mb-1">Product (if applicable)</label>
                            <input type="text" id="product" name="product" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div>
                            <label for="issue_type" class="block text-sm font-medium text-gray-700 mb-1">Issue Type</label>
                            <select id="issue_type" name="issue_type" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Select issue type</option>
                                <option value="setup">Product Setup</option>
                                <option value="troubleshooting">Troubleshooting</option>
                                <option value="warranty">Warranty Claim</option>
                                <option value="software">Software Support</option>
                                <option value="compatibility">Compatibility</option>
                                <option value="training">Training</option>
                                <option value="other">Other</option>
                            </select>
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <textarea id="description" name="description" rows="4" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Please describe your issue in detail..."></textarea>
                        </div>

                        <button type="submit" class="w-full bg-blue-600 text-white py-3 px-6 rounded-lg font-medium hover:bg-blue-700 transition-colors">
                            Submit Ticket
                        </button>
                    </form>
                </div>
            </div>
        </div>


    </div>
</div>


@endsection
