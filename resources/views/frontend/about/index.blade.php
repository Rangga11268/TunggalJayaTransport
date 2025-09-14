@extends('frontend.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <h1 class="text-3xl font-bold mb-6">About Us</h1>
    
    <!-- Company Profile -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h2 class="text-xl font-bold mb-4">Our Story</h2>
        <div class="flex flex-col md:flex-row gap-6">
            <div class="md:w-1/3">
                <div class="bg-gray-200 border-2 border-dashed rounded-xl w-full h-64"></div>
            </div>
            <div class="md:w-2/3">
                <p class="text-gray-600 mb-4">
                    Tunggal Jaya Transport has been providing reliable and comfortable transportation services since 2005. 
                    With over 18 years of experience, we have grown to become one of the most trusted bus companies in the region.
                </p>
                <p class="text-gray-600 mb-4">
                    Our commitment to safety, punctuality, and customer satisfaction has made us the preferred choice for 
                    thousands of travelers every day.
                </p>
                <p class="text-gray-600">
                    We continuously invest in our fleet and staff training to ensure we provide the best possible service 
                    to our valued customers.
                </p>
            </div>
        </div>
    </div>

    <!-- Vision and Mission -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold mb-4">Our Vision</h2>
            <p class="text-gray-600">
                To be the leading transportation provider in the region, known for our commitment to safety, 
                comfort, and environmental responsibility.
            </p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold mb-4">Our Mission</h2>
            <ul class="list-disc list-inside text-gray-600 space-y-2">
                <li>Provide safe and reliable transportation services</li>
                <li>Ensure passenger comfort and satisfaction</li>
                <li>Continuously improve our fleet and facilities</li>
                <li>Contribute to sustainable transportation solutions</li>
                <li>Maintain the highest standards of professionalism</li>
            </ul>
        </div>
    </div>

    <!-- FAQ Section -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h2 class="text-xl font-bold mb-4">Frequently Asked Questions</h2>
        <div x-data="{ open: null }" class="space-y-4">
            <div class="border-b border-gray-200 pb-4">
                <button @click="open = open === 1 ? null : 1" class="flex justify-between items-center w-full text-left">
                    <span class="text-lg font-medium">How can I book a ticket?</span>
                    <svg class="h-5 w-5 text-gray-500" :class="{ 'transform rotate-180': open === 1 }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="open === 1" class="mt-2 text-gray-600">
                    <p>You can book a ticket through our website, mobile app, or by visiting one of our ticket offices.</p>
                </div>
            </div>
            <div class="border-b border-gray-200 pb-4">
                <button @click="open = open === 2 ? null : 2" class="flex justify-between items-center w-full text-left">
                    <span class="text-lg font-medium">What payment methods do you accept?</span>
                    <svg class="h-5 w-5 text-gray-500" :class="{ 'transform rotate-180': open === 2 }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="open === 2" class="mt-2 text-gray-600">
                    <p>We accept cash, credit cards, debit cards, and digital payments through our mobile app.</p>
                </div>
            </div>
            <div class="border-b border-gray-200 pb-4">
                <button @click="open = open === 3 ? null : 3" class="flex justify-between items-center w-full text-left">
                    <span class="text-lg font-medium">Can I cancel or change my booking?</span>
                    <svg class="h-5 w-5 text-gray-500" :class="{ 'transform rotate-180': open === 3 }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="open === 3" class="mt-2 text-gray-600">
                    <p>Yes, you can cancel or change your booking up to 2 hours before departure, subject to our cancellation policy.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection