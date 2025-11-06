@extends('layouts.app')

@php
use App\Models\Setting;
@endphp

@section('content')
<div class="bg-gray-50 py-12">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Privacy Policy</h1>
            <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                Your privacy is important to us. This policy explains how we collect, use, and protect your personal information.
            </p>
        </div>

        <!-- Privacy Policy Content -->
        <div class="max-w-4xl mx-auto space-y-8">
            <!-- Introduction -->
            <div class="p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Introduction</h2>
                <p class="text-gray-600 mb-4">
                    At Speed and Style Hub, we are committed to protecting your privacy and ensuring the security of your personal information.
                    This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you visit our website,
                    use our services, or interact with us in any way.
                </p>
                <p class="text-gray-600">
                    By using our services, you agree to the collection and use of information in accordance with this policy.
                    If you do not agree with our policies and practices, please do not use our services.
                </p>
            </div>

            <!-- Information We Collect -->
            <div class="p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Information We Collect</h2>
                <div class="space-y-6">
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-3">Personal Information</h3>
                        <p class="text-gray-600 mb-3">We may collect the following personal information:</p>
                        <ul class="list-disc list-inside space-y-2 text-gray-600 ml-4">
                            <li>Name and contact information (email, phone number, address)</li>
                            <li>Payment information and billing details</li>
                            <li>Account credentials and profile information</li>
                            <li>Communication preferences and marketing preferences</li>
                            <li>Order history and purchase behavior</li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-3">Technical Information</h3>
                        <p class="text-gray-600 mb-3">We automatically collect certain technical information:</p>
                        <ul class="list-disc list-inside space-y-2 text-gray-600 ml-4">
                            <li>IP address and device information</li>
                            <li>Browser type and version</li>
                            <li>Operating system and platform</li>
                            <li>Website usage data and analytics</li>
                            <li>Cookies and similar tracking technologies</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- How We Use Information -->
            <div class="p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">How We Use Your Information</h2>
                <div class="space-y-4">
                    <div class="border-l-4 border-blue-600 pl-4">
                        <h3 class="font-semibold text-gray-900">Processing Orders</h3>
                        <p class="text-gray-600">To process and fulfill your orders, handle payments, and provide customer support.</p>
                    </div>

                    <div class="border-l-4 border-blue-600 pl-4">
                        <h3 class="font-semibold text-gray-900">Communication</h3>
                        <p class="text-gray-600">To send order confirmations, shipping updates, and respond to your inquiries.</p>
                    </div>

                    <div class="border-l-4 border-blue-600 pl-4">
                        <h3 class="font-semibold text-gray-900">Improving Services</h3>
                        <p class="text-gray-600">To analyze usage patterns and improve our website, products, and services.</p>
                    </div>

                    <div class="border-l-4 border-blue-600 pl-4">
                        <h3 class="font-semibold text-gray-900">Marketing</h3>
                        <p class="text-gray-600">To send promotional offers and newsletters (with your consent).</p>
                    </div>

                    <div class="border-l-4 border-blue-600 pl-4">
                        <h3 class="font-semibold text-gray-900">Legal Compliance</h3>
                        <p class="text-gray-600">To comply with legal obligations and protect our rights and safety.</p>
                    </div>
                </div>
            </div>

            <!-- Information Sharing -->
            <div class="p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Information Sharing and Disclosure</h2>
                <p class="text-gray-600 mb-4">
                    We do not sell, trade, or otherwise transfer your personal information to third parties without your consent,
                    except in the following circumstances:
                </p>
                <div class="space-y-4">
                    <div class="border-l-4 border-green-500 pl-4">
                        <h3 class="font-semibold text-gray-900">Service Providers</h3>
                        <p class="text-gray-600">We may share information with trusted third-party service providers who assist us in operating our website, processing payments, and delivering products.</p>
                    </div>

                    <div class="border-l-4 border-blue-500 pl-4">
                        <h3 class="font-semibold text-gray-900">Legal Requirements</h3>
                        <p class="text-gray-600">We may disclose information when required by law or to protect our rights, property, or safety.</p>
                    </div>

                    <div class="border-l-4 border-purple-500 pl-4">
                        <h3 class="font-semibold text-gray-900">Business Transfers</h3>
                        <p class="text-gray-600">In the event of a merger, acquisition, or sale of assets, your information may be transferred as part of the transaction.</p>
                    </div>
                </div>
            </div>

            <!-- Data Security -->
            <div class="p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Data Security</h2>
                <p class="text-gray-600 mb-4">
                    We implement appropriate technical and organizational security measures to protect your personal information against
                    unauthorized access, alteration, disclosure, or destruction.
                </p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-3">
                        <h3 class="font-semibold text-gray-900">Security Measures</h3>
                        <ul class="space-y-2 text-gray-600">
                            <li>SSL encryption for data transmission</li>
                            <li>Secure payment processing</li>
                            <li>Regular security audits</li>
                            <li>Access controls and authentication</li>
                            <li>Data encryption at rest</li>
                        </ul>
                    </div>
                    <div class="space-y-3">
                        <h3 class="font-semibold text-gray-900">Your Responsibilities</h3>
                        <ul class="space-y-2 text-gray-600">
                            <li>Keep your account credentials secure</li>
                            <li>Log out after each session</li>
                            <li>Report suspicious activity</li>
                            <li>Use strong, unique passwords</li>
                            <li>Keep your devices secure</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Cookies and Tracking -->
            <div class="p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Cookies and Tracking Technologies</h2>
                <p class="text-gray-600 mb-4">
                    We use cookies and similar tracking technologies to enhance your browsing experience and analyze website usage.
                </p>
                <div class="space-y-4">
                    <div>
                        <h3 class="font-semibold text-gray-900 mb-2">Types of Cookies We Use</h3>
                        <ul class="list-disc list-inside space-y-2 text-gray-600 ml-4">
                            <li><strong>Essential Cookies:</strong> Required for basic website functionality</li>
                            <li><strong>Analytics Cookies:</strong> Help us understand how visitors use our website</li>
                            <li><strong>Marketing Cookies:</strong> Used to deliver relevant advertisements</li>
                            <li><strong>Preference Cookies:</strong> Remember your settings and preferences</li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 mb-2">Managing Cookies</h3>
                        <p class="text-gray-600">
                            You can control and manage cookies through your browser settings. However, disabling certain cookies
                            may affect the functionality of our website.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Your Rights -->
            <div class="p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Your Rights and Choices</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div>
                            <h3 class="font-semibold text-gray-900">Access</h3>
                            <p class="text-gray-600 text-sm">Request access to your personal information</p>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">Correction</h3>
                            <p class="text-gray-600 text-sm">Request correction of inaccurate information</p>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">Deletion</h3>
                            <p class="text-gray-600 text-sm">Request deletion of your personal information</p>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <h3 class="font-semibold text-gray-900">Restriction</h3>
                            <p class="text-gray-600 text-sm">Request restriction of processing</p>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">Portability</h3>
                            <p class="text-gray-600 text-sm">Request data portability</p>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">Objection</h3>
                            <p class="text-gray-600 text-sm">Object to processing of your data</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Data Retention -->
            <div class="p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Data Retention</h2>
                <p class="text-gray-600 mb-4">
                    We retain your personal information for as long as necessary to fulfill the purposes outlined in this policy,
                    unless a longer retention period is required or permitted by law.
                </p>
                <div class="space-y-3">
                    <div class="flex justify-between items-center py-2 border-b border-gray-200">
                        <span class="font-medium text-gray-900">Account Information</span>
                        <span class="text-gray-600">Until account deletion</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-200">
                        <span class="font-medium text-gray-900">Order History</span>
                        <span class="text-gray-600">7 years (legal requirement)</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-200">
                        <span class="font-medium text-gray-900">Marketing Data</span>
                        <span class="text-gray-600">Until consent withdrawal</span>
                    </div>
                    <div class="flex justify-between items-center py-2">
                        <span class="font-medium text-gray-900">Analytics Data</span>
                        <span class="text-gray-600">2 years</span>
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="bg-blue-900 p-8 text-white">
                <h2 class="text-2xl font-bold text-center mb-6">Contact Us</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="text-center">
                        <h3 class="text-lg font-semibold mb-2">Privacy Questions</h3>
                        <p class="text-blue-200">privacy@Speedandstylehub.com</p>
                        <p class="text-sm text-blue-300">For privacy-related inquiries</p>
                    </div>
                    <div class="text-center">
                        <h3 class="text-lg font-semibold mb-2">General Support</h3>
                        <p class="text-blue-200">{{ Setting::get('contact_phone', '+254 700 123 456') }}</p>
                        <p class="text-sm text-blue-300">For general questions</p>
                    </div>
                </div>
                <div class="mt-6 text-center">
                    <p class="text-blue-200">
                        <strong>Address:</strong> {{ Setting::get('contact_address', 'Westlands, Nairobi') }}, {{ Setting::get('contact_city', 'Kenya') }}
                    </p>
                </div>
            </div>

            <!-- Policy Updates -->
            <div class="p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Updates to This Policy</h2>
                <p class="text-gray-600">
                    We may update this Privacy Policy from time to time to reflect changes in our practices or for other operational,
                    legal, or regulatory reasons. We will notify you of any material changes by posting the new policy on this page
                    and updating the "Last Updated" date.
                </p>
                <div class="mt-4 p-4 bg-gray-50">
                    <p class="text-sm text-gray-600">
                        <strong>Last Updated:</strong> October 2025
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
