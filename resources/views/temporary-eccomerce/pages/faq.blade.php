@extends('layouts.app')

@section('content')
<div class="bg-gray-50 py-12">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Frequently Asked Questions</h1>
            <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                Find answers to the most common questions about our products, services, and policies. 
                Can't find what you're looking for? Contact our support team.
            </p>
        </div>

        <!-- FAQ Categories -->
        <div class="mb-12">
            <div class="flex flex-wrap justify-center gap-3">
                <button class="faq-category px-5 py-2.5 bg-blue-600 text-white rounded-md font-medium hover:bg-blue-700 transition-colors duration-200 text-sm" data-category="general">
                    General
                </button>
                <button class="faq-category px-5 py-2.5 bg-gray-200 text-gray-700 rounded-md font-medium hover:bg-gray-300 transition-colors duration-200 text-sm" data-category="ordering">
                    Ordering
                </button>
                <button class="faq-category px-5 py-2.5 bg-gray-200 text-gray-700 rounded-md font-medium hover:bg-gray-300 transition-colors duration-200 text-sm" data-category="shipping">
                    Shipping
                </button>
                <button class="faq-category px-5 py-2.5 bg-gray-200 text-gray-700 rounded-md font-medium hover:bg-gray-300 transition-colors duration-200 text-sm" data-category="returns">
                    Returns
                </button>
                <button class="faq-category px-5 py-2.5 bg-gray-200 text-gray-700 rounded-md font-medium hover:bg-gray-300 transition-colors duration-200 text-sm" data-category="technical">
                    Technical
                </button>
            </div>
        </div>

        <!-- FAQ Content -->
        <div class="max-w-4xl mx-auto">
            @php
                $categoryNames = [
                    'general' => 'General Questions',
                    'ordering' => 'Ordering Questions',
                    'shipping' => 'Shipping Questions',
                    'returns' => 'Returns Questions',
                    'technical' => 'Technical Questions'
                ];
            @endphp

            @foreach($faqs as $category => $categoryFaqs)
                <div class="faq-section {{ $loop->first ? '' : 'hidden' }}" id="{{ $category }}">
                    <h2 class="text-2xl font-bold text-gray-900 mb-8">{{ $categoryNames[$category] ?? ucfirst($category) }}</h2>
                    <div class="space-y-3">
                        @forelse($categoryFaqs as $faq)
                            <div class="border border-gray-200 rounded-lg overflow-hidden">
                                <button class="faq-toggle w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-50 transition-colors duration-200">
                                    <span class="font-medium text-gray-900 text-left">{{ $faq->question }}</span>
                                    <i class="fas fa-chevron-down text-gray-400 transition-transform duration-200 flex-shrink-0 ml-4"></i>
                                </button>
                                <div class="faq-content px-6 pb-4 hidden border-t border-gray-100 bg-gray-50">
                                    <div class="text-gray-700 leading-relaxed pt-4">
                                        {!! nl2br(e($faq->answer)) !!}
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="border border-gray-200 rounded-lg p-6 text-center text-gray-500 bg-gray-50">
                                No FAQs available in this category yet.
                            </div>
                        @endforelse
                    </div>
                </div>
            @endforeach

            @if($faqs->isEmpty())
                <div class="border border-gray-200 rounded-lg p-8 text-center bg-gray-50">
                    <i class="fas fa-question-circle text-4xl text-gray-400 mb-4"></i>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">No FAQs Available</h3>
                    <p class="text-gray-600">Check back soon for frequently asked questions and answers.</p>
                </div>
            @endif
        </div>

        <!-- Contact Section -->
        <div class="mt-16 bg-blue-900 rounded-lg p-8 text-white text-center">
            <h2 class="text-3xl font-bold mb-4">Still Have Questions?</h2>
            <p class="text-blue-200 mb-6">
                Can't find the answer you're looking for? Our support team is here to help!
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('pages.contact') }}" class="bg-white text-blue-900 px-6 py-3 rounded-lg font-medium hover:bg-gray-100 transition-colors">
                    Contact Us
                </a>
                <a href="{{ route('pages.technical-support') }}" class="border border-white text-white px-6 py-3 rounded-lg font-medium hover:bg-white hover:text-blue-900 transition-colors">
                    Technical Support
                </a>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // FAQ Category Tabs
    const categoryButtons = document.querySelectorAll('.faq-category');
    const faqSections = document.querySelectorAll('.faq-section');

    categoryButtons.forEach(button => {
        button.addEventListener('click', function() {
            const category = this.getAttribute('data-category');
            
            // Update button styles
            categoryButtons.forEach(btn => {
                btn.classList.remove('bg-blue-600', 'text-white');
                btn.classList.add('bg-gray-200', 'text-gray-700');
            });
            this.classList.remove('bg-gray-200', 'text-gray-700');
            this.classList.add('bg-blue-600', 'text-white');
            
            // Show/hide sections
            faqSections.forEach(section => {
                section.classList.add('hidden');
            });
            document.getElementById(category).classList.remove('hidden');
        });
    });

    // FAQ Toggle with smooth animation
    const faqToggles = document.querySelectorAll('.faq-toggle');
    
    faqToggles.forEach(toggle => {
        toggle.addEventListener('click', function() {
            const content = this.nextElementSibling;
            const icon = this.querySelector('i');
            
            // Close all other open FAQs
            faqToggles.forEach(otherToggle => {
                if (otherToggle !== this) {
                    const otherContent = otherToggle.nextElementSibling;
                    const otherIcon = otherToggle.querySelector('i');
                    otherContent.classList.add('hidden');
                    otherIcon.style.transform = 'rotate(0deg)';
                }
            });
            
            if (content.classList.contains('hidden')) {
                content.classList.remove('hidden');
                icon.style.transform = 'rotate(180deg)';
            } else {
                content.classList.add('hidden');
                icon.style.transform = 'rotate(0deg)';
            }
        });
    });
});
</script>
@endsection 