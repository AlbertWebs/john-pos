@extends('layouts.app')

@section('content')
<div class="bg-gray-50 py-12">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">About Speed and Style Hub</h1>
            <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                We are Kenya’s ultimate destination for fashion-forward styles, beauty essentials, and curated elegance—empowering confidence through fashion and self-care.
            </p>
        </div>

        <!-- Company Story -->
        <div class="mb-16">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div>
                    <h2 class="text-3xl font-bold text-gray-900 mb-6">Our Story</h2>
                    <p class="text-gray-600 mb-4">
                        Founded in 2020, Speed and Style Hub began with a simple mission: to redefine fashion and beauty in Kenya by blending style, quality, and affordability. What started as a small boutique has flourished into a beloved lifestyle brand.
                    </p>
                    <p class="text-gray-600 mb-4">
                        We believe fashion is an expression of identity. That's why we handpick each piece—from bold statement outfits to subtle beauty staples—ensuring it meets our standards for comfort, confidence, and charm.
                    </p>
                    <p class="text-gray-600">
                        Today, we proudly serve fashion enthusiasts and beauty lovers across the country, offering not just products, but a stylish experience backed by excellent customer service.
                    </p>
                </div>
                <div class="border border-gray-200 rounded-lg p-8 bg-gray-50">
                    <div class="text-center">
                        <div class="w-20 h-20 bg-pink-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-heart text-pink-600 text-2xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Our Mission</h3>
                        <p class="text-gray-600 leading-relaxed">
                            To empower every individual through accessible fashion and beauty, promoting confidence and self-love one style at a time.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Values -->
        <div class="mb-16">
            <h2 class="text-3xl font-bold text-gray-900 text-center mb-12">Our Values</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="border border-gray-200 rounded-lg p-6 text-center bg-gray-50">
                    <div class="w-14 h-14 bg-pink-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-gem text-pink-600 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Style & Quality</h3>
                    <p class="text-gray-600 leading-relaxed text-sm">
                        We only offer items that make you feel your best—every garment and product is curated for its quality and aesthetic appeal.
                    </p>
                </div>

                <div class="border border-gray-200 rounded-lg p-6 text-center bg-gray-50">
                    <div class="w-14 h-14 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-user-friends text-blue-600 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Customer First</h3>
                    <p class="text-gray-600 leading-relaxed text-sm">
                        Our customers inspire everything we do. We go the extra mile to ensure every experience with us is personal and delightful.
                    </p>
                </div>

                <div class="border border-gray-200 rounded-lg p-6 text-center bg-gray-50">
                    <div class="w-14 h-14 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-star text-purple-600 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Empowerment</h3>
                    <p class="text-gray-600 leading-relaxed text-sm">
                        Fashion is power. We strive to inspire confidence and self-expression through every product we offer.
                    </p>
                </div>
            </div>
        </div>

        <!-- Stats -->
        <div class="mb-16">
            <div class="bg-gradient-to-r from-pink-700 to-pink-600 rounded-lg p-8 text-white">
                <h2 class="text-3xl font-bold text-center mb-8">Our Numbers</h2>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 text-center">
                    <div>
                        <div class="text-3xl font-bold mb-2">15,000+</div>
                        <div class="text-pink-200 text-sm">Happy Customers</div>
                    </div>
                    <div>
                        <div class="text-3xl font-bold mb-2">800+</div>
                        <div class="text-pink-200 text-sm">Fashion & Beauty Products</div>
                    </div>
                    <div>
                        <div class="text-3xl font-bold mb-2">5+</div>
                        <div class="text-pink-200 text-sm">Years of Style</div>
                    </div>
                    <div>
                        <div class="text-3xl font-bold mb-2">24/7</div>
                        <div class="text-pink-200 text-sm">Support</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Why Choose Us -->
        <div>
            <h2 class="text-3xl font-bold text-gray-900 text-center mb-12">Why Choose Speed and Style Hub?</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="text-center">
                    <div class="w-14 h-14 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-shipping-fast text-yellow-600 text-lg"></i>
                    </div>
                    <h3 class="text-base font-semibold text-gray-900 mb-2">Fast Delivery</h3>
                    <p class="text-gray-600 text-sm">Free shipping on orders over KES 5,000</p>
                </div>

                <div class="text-center">
                    <div class="w-14 h-14 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-sync text-green-600 text-lg"></i>
                    </div>
                    <h3 class="text-base font-semibold text-gray-900 mb-2">Easy Returns</h3>
                    <p class="text-gray-600 text-sm">Hassle-free 15-day returns</p>
                </div>

                <div class="text-center">
                    <div class="w-14 h-14 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-headset text-blue-600 text-lg"></i>
                    </div>
                    <h3 class="text-base font-semibold text-gray-900 mb-2">24/7 Support</h3>
                    <p class="text-gray-600 text-sm">We're always here to help</p>
                </div>

                <div class="text-center">
                    <div class="w-14 h-14 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-hand-holding-heart text-purple-600 text-lg"></i>
                    </div>
                    <h3 class="text-base font-semibold text-gray-900 mb-2">Trusted Brand</h3>
                    <p class="text-gray-600 text-sm">Thousands of loyal customers across Kenya</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
