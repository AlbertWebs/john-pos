@php
use App\Models\Setting;
use App\Helpers\SocialMediaHelper;
@endphp

<!-- Footer Top Bar -->
<div class="bg-gray-800 py-8">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <div class="flex items-center space-x-3">
                <i class="fas fa-shipping-fast text-white text-xl"></i>
                <div>
                    <h4 class="text-white font-semibold">FREE DELIVERY</h4>
                    <p class="text-gray-300 text-sm">On orders over KES 5,000</p>
                </div>
            </div>

            <div class="flex items-center space-x-3">
                <i class="fas fa-credit-card text-white text-xl"></i>
                <div>
                    <h4 class="text-white font-semibold">SECURE CHECKOUT</h4>
                    <p class="text-gray-300 text-sm">Shop safely and confidently</p>
                </div>
            </div>

            <div class="flex items-center space-x-3">
                <i class="fas fa-sync-alt text-white text-xl"></i>
                <div>
                    <h4 class="text-white font-semibold">EASY RETURNS</h4>
                    <p class="text-gray-300 text-sm">15-day return window</p>
                </div>
            </div>

            <div class="flex items-center space-x-3">
                <i class="fas fa-headset text-white text-xl"></i>
                <div>
                    <h4 class="text-white font-semibold">CUSTOMER CARE</h4>
                    <p class="text-gray-300 text-sm">We're here 24/7</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Footer -->
<footer class="bg-gray-900 text-white">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <!-- Company Info -->
            <div>
                <h3 class="text-2xl font-bold text-pink-500 mb-4">Speed and Style Hub</h3>
                <p class="text-gray-400 mb-4">{{ Setting::get('contact_address', 'Westlands, Nairobi') }}, {{ Setting::get('contact_city', 'Kenya') }}</p>
                <p class="text-gray-400 mb-2">{{ Setting::get('contact_phone', '+254 700 123 456') }}</p>
                <p class="text-gray-400 mb-6">{{ Setting::get('contact_email', 'hello@speedandstylehub.com') }}</p>

                <!-- Social Media -->
                <div class="flex space-x-4">
                    @php
                        $socialUrls = SocialMediaHelper::getSocialMediaUrls();
                    @endphp

                    @if(isset($socialUrls['facebook']))
                        <a href="{{ $socialUrls['facebook'] }}" target="_blank" class="text-gray-400 hover:text-white"><i class="fab fa-facebook-f"></i></a>
                    @endif

                    @if(isset($socialUrls['twitter']))
                        <a href="{{ $socialUrls['twitter'] }}" target="_blank" class="text-gray-400 hover:text-white"><i class="fab fa-twitter"></i></a>
                    @endif

                    @if(isset($socialUrls['instagram']))
                        <a href="{{ $socialUrls['instagram'] }}" target="_blank" class="text-gray-400 hover:text-white"><i class="fab fa-instagram"></i></a>
                    @endif

                    @if(isset($socialUrls['linkedin']))
                        <a href="{{ $socialUrls['linkedin'] }}" target="_blank" class="text-gray-400 hover:text-white"><i class="fab fa-linkedin-in"></i></a>
                    @endif
                </div>
            </div>

            <!-- Support Links -->
            <div>
                <h4 class="text-lg font-semibold mb-4">SUPPORT</h4>
                <ul class="space-y-2">
                    <li><a href="{{ route('pages.contact') }}" class="text-gray-400 hover:text-white">Contact Us</a></li>
                    <li><a href="{{ route('pages.about') }}" class="text-gray-400 hover:text-white">About Us</a></li>
                    <li><a href="{{ route('pages.technical-support') }}" class="text-gray-400 hover:text-white">Customer Support</a></li>
                    <li><a href="{{ route('pages.shipping-returns') }}" class="text-gray-400 hover:text-white">Shipping & Returns</a></li>
                    <li><a href="{{ route('pages.faq') }}" class="text-gray-400 hover:text-white">FAQs</a></li>
                    <li><a href="{{ route('pages.privacy') }}" class="text-gray-400 hover:text-white">Privacy Policy</a></li>
                </ul>
            </div>

            <!-- Fashion/Beauty Links -->
            <div>
                <h4 class="text-lg font-semibold mb-4">SHOP</h4>
                <ul class="space-y-2">
                    <li><a href="{{ route('products.index', ['category' => 'women-fashion']) }}" class="text-gray-400 hover:text-white">Women's Fashion</a></li>
                    <li><a href="{{ route('products.index', ['category' => 'men-fashion']) }}" class="text-gray-400 hover:text-white">Men's Fashion</a></li>
                    <li><a href="{{ route('products.index', ['category' => 'skincare']) }}" class="text-gray-400 hover:text-white">Skincare</a></li>
                    <li><a href="{{ route('products.index', ['category' => 'makeup']) }}" class="text-gray-400 hover:text-white">Makeup</a></li>
                    <li><a href="{{ route('products.index', ['category' => 'accessories']) }}" class="text-gray-400 hover:text-white">Accessories</a></li>
                </ul>
            </div>

            <!-- Subscribe -->
            <div>
                <h4 class="text-lg font-semibold mb-4">SUBSCRIBE</h4>
                <p class="text-gray-400 mb-4">Get the latest trends, beauty tips, and exclusive offers delivered to your inbox.</p>

                <!-- Email Subscription -->
                <div class="flex mb-6">
                    <input type="email" placeholder="Your Email" class="flex-1 px-3 py-2 bg-gray-800 border border-gray-700 rounded-l text-white placeholder-gray-400 focus:outline-none focus:border-gray-600">
                    <button class="bg-pink-500 px-4 py-2 rounded-r hover:bg-pink-600">
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Copyright -->
    <div class="bg-gray-800 py-4">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <p class="text-center text-gray-400 text-sm">
                © 2025 Speed and Style Hub. Designed & Developed by
                <a href="https://designekta.com" target="_blank" class="text-white hover:text-pink-400 transition-colors">Designekta Studios</a>.
            </p>
        </div>
    </div>
</footer>
