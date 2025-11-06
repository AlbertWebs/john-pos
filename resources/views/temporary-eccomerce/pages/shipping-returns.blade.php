@extends('layouts.app')

@php
use App\Models\Setting;
@endphp

@section('content')
<div class="bg-gray-50 py-12">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Shipping & Returns</h1>
            <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                We want you to be completely satisfied with your purchase. Learn about our shipping options,
                delivery times, and hassle-free return policy.
            </p>
        </div>

        <!-- Shipping Information -->
        <div class="mb-16">
            <h2 class="text-3xl font-bold text-gray-900 text-center mb-12">Shipping Information</h2>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                <!-- Shipping Options -->
                <div class="space-y-6">
                    <div class="border border-gray-200 rounded-lg bg-gray-50 p-6">
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">Shipping Options</h3>
                        <div class="space-y-4">
                            <div class="border-l-4 border-green-500 pl-4">
                                <h4 class="font-medium text-gray-900">Free Standard Delivery</h4>
                                <p class="text-gray-600">Orders over KES 50,000</p>
                                <p class="text-sm text-gray-500">3-5 business days</p>
                            </div>
                            <div class="border-l-4 border-blue-500 pl-4">
                                <h4 class="font-medium text-gray-900">Express Delivery</h4>
                                <p class="text-gray-600">KES 1,500</p>
                                <p class="text-sm text-gray-500">1-2 business days</p>
                            </div>
                            <div class="border-l-4 border-purple-500 pl-4">
                                <h4 class="font-medium text-gray-900">Same Day Delivery</h4>
                                <p class="text-gray-600">KES 3,000</p>
                                <p class="text-sm text-gray-500">Nairobi only, order before 2 PM</p>
                            </div>
                        </div>
                    </div>

                    <div class="border border-gray-200 rounded-lg bg-gray-50 p-6">
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">Delivery Areas</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-700">Nairobi</span>
                                <span class="text-green-600 font-medium">Free over KES 50,000</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-700">Mombasa, Kisumu, Nakuru</span>
                                <span class="text-gray-600">KES 800</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-700">Other Major Towns</span>
                                <span class="text-gray-600">KES 1,200</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-700">Remote Areas</span>
                                <span class="text-gray-600">KES 1,500</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Delivery Process -->
                <div class="space-y-6">
                    <div class="border border-gray-200 rounded-lg bg-gray-50 p-6">
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">How Delivery Works</h3>
                        <div class="space-y-4">
                            <div class="flex items-start space-x-4">
                                <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-white text-sm font-bold">1</div>
                                <div>
                                    <h4 class="font-medium text-gray-900">Order Confirmation</h4>
                                    <p class="text-gray-600 text-sm">You'll receive an email confirmation with tracking details</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-4">
                                <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-white text-sm font-bold">2</div>
                                <div>
                                    <h4 class="font-medium text-gray-900">Processing</h4>
                                    <p class="text-gray-600 text-sm">We'll prepare your order and arrange delivery</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-4">
                                <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-white text-sm font-bold">3</div>
                                <div>
                                    <h4 class="font-medium text-gray-900">Shipping</h4>
                                    <p class="text-gray-600 text-sm">Your package will be shipped via our trusted courier partners</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-4">
                                <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-white text-sm font-bold">4</div>
                                <div>
                                    <h4 class="font-medium text-gray-900">Delivery</h4>
                                    <p class="text-gray-600 text-sm">You'll receive a call before delivery to confirm location</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="border border-gray-200 rounded-lg bg-gray-50 p-6">
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">Important Notes</h3>
                        <ul class="space-y-2 text-sm text-gray-600">
                            <li class="flex items-start space-x-2">
                                <i class="fas fa-check text-green-500 mt-1"></i>
                                <span>Please ensure someone is available to receive the delivery</span>
                            </li>
                            <li class="flex items-start space-x-2">
                                <i class="fas fa-check text-green-500 mt-1"></i>
                                <span>Valid ID required for delivery confirmation</span>
                            </li>
                            <li class="flex items-start space-x-2">
                                <i class="fas fa-check text-green-500 mt-1"></i>
                                <span>Cash on delivery available for orders under KES 100,000</span>
                            </li>
                            <li class="flex items-start space-x-2">
                                <i class="fas fa-check text-green-500 mt-1"></i>
                                <span>Free installation available for TVs and large appliances</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Returns Information -->
        <div class="mb-16">
            <h2 class="text-3xl font-bold text-gray-900 text-center mb-12">Returns & Refunds</h2>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                <!-- Return Policy -->
                <div class="space-y-6">
                    <div class="border border-gray-200 rounded-lg bg-gray-50 p-6">
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">Return Policy</h3>
                        <div class="space-y-4">
                            <div class="border-l-4 border-green-500 pl-4">
                                <h4 class="font-medium text-gray-900">30-Day Return Window</h4>
                                <p class="text-gray-600">Return any item within 30 days of purchase</p>
                                <p class="text-sm text-gray-500">Must be in original condition with all packaging</p>
                            </div>
                            <div class="border-l-4 border-blue-500 pl-4">
                                <h4 class="font-medium text-gray-900">Free Returns</h4>
                                <p class="text-gray-600">We cover return shipping costs</p>
                                <p class="text-sm text-gray-500">For defective items or wrong products</p>
                            </div>
                            <div class="border-l-4 border-purple-500 pl-4">
                                <h4 class="font-medium text-gray-900">Refund Processing</h4>
                                <p class="text-gray-600">Refunds processed within 5-7 business days</p>
                                <p class="text-sm text-gray-500">Original payment method</p>
                            </div>
                        </div>
                    </div>

                    <div class="border border-gray-200 rounded-lg bg-gray-50 p-6">
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">What Can Be Returned</h3>
                        <div class="space-y-3">
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-check text-green-500"></i>
                                <span class="text-gray-700">Defective or damaged items</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-check text-green-500"></i>
                                <span class="text-gray-700">Wrong items received</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-check text-green-500"></i>
                                <span class="text-gray-700">Items not as described</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-check text-green-500"></i>
                                <span class="text-gray-700">Change of mind (within 30 days)</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Return Process -->
                <div class="space-y-6">
                    <div class="border border-gray-200 rounded-lg bg-gray-50 p-6">
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">How to Return</h3>
                        <div class="space-y-4">
                            <div class="flex items-start space-x-4">
                                <div class="w-8 h-8 bg-red-600 rounded-full flex items-center justify-center text-white text-sm font-bold">1</div>
                                <div>
                                    <h4 class="font-medium text-gray-900">Contact Us</h4>
                                    <p class="text-gray-600 text-sm">Call or email us to initiate the return process</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-4">
                                <div class="w-8 h-8 bg-red-600 rounded-full flex items-center justify-center text-white text-sm font-bold">2</div>
                                <div>
                                    <h4 class="font-medium text-gray-900">Return Authorization</h4>
                                    <p class="text-gray-600 text-sm">We'll provide a return authorization number</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-4">
                                <div class="w-8 h-8 bg-red-600 rounded-full flex items-center justify-center text-white text-sm font-bold">3</div>
                                <div>
                                    <h4 class="font-medium text-gray-900">Package Item</h4>
                                    <p class="text-gray-600 text-sm">Pack the item securely with all original packaging</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-4">
                                <div class="w-8 h-8 bg-red-600 rounded-full flex items-center justify-center text-white text-sm font-bold">4</div>
                                <div>
                                    <h4 class="font-medium text-gray-900">Ship Back</h4>
                                    <p class="text-gray-600 text-sm">We'll arrange pickup or provide shipping label</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="border border-gray-200 rounded-lg bg-gray-50 p-6">
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">Non-Returnable Items</h3>
                        <div class="space-y-3">
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-times text-red-500"></i>
                                <span class="text-gray-700">Software and digital downloads</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-times text-red-500"></i>
                                <span class="text-gray-700">Personalized or custom items</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-times text-red-500"></i>
                                <span class="text-gray-700">Items used beyond normal wear</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-times text-red-500"></i>
                                <span class="text-gray-700">Gift cards and vouchers</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Information -->
        <div class="bg-blue-900 rounded-lg p-8 text-white">
            <h2 class="text-3xl font-bold text-center mb-8">Need Help?</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
                <div>
                    <div class="w-16 h-16 bg-blue-700 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-phone text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Call Us</h3>
                    <p class="text-blue-200">{{ Setting::get('contact_phone', '+254 700 123 456') }}</p>
                    <p class="text-sm text-blue-300">{{ Setting::get('business_hours_weekdays', 'Mon-Fri: 8AM-6PM') }}</p>
                </div>
                <div>
                    <div class="w-16 h-16 bg-blue-700 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-envelope text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Email Us</h3>
                    <p class="text-blue-200">support@speedandstylehub.com</p>
                    <p class="text-sm text-blue-300">24-hour response</p>
                </div>
                <div>
                    <div class="w-16 h-16 bg-blue-700 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-comments text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Live Chat</h3>
                    <p class="text-blue-200">Available on website</p>
                    <p class="text-sm text-blue-300">Instant support</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
