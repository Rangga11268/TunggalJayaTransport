@extends('frontend.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 page-spacing">
    <!-- Header -->
    <div class="text-center mb-12">
        <h1 class="text-4xl font-bold text-gray-800 mb-4">About Us</h1>
        <p class="text-xl text-gray-600 max-w-3xl mx-auto">Learn more about Tunggal Jaya Transport, our story, values, and commitment to providing exceptional transportation services</p>
    </div>
    
    <!-- Company Profile -->
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl shadow-lg p-8 mb-12">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-3xl font-bold text-gray-800">Our Story</h2>
            <div class="bg-blue-100 text-blue-800 px-4 py-2 rounded-full text-sm font-bold">
                <i class="fas fa-history mr-2"></i>Since 2005
            </div>
        </div>
        <div class="flex flex-col lg:flex-row gap-10 items-center">
            <div class="lg:w-2/5">
                <div class="relative">
                    <div class="bg-gradient-to-r from-gray-200 to-gray-300 rounded-xl w-full h-80 flex items-center justify-center">
                        <i class="fas fa-bus text-gray-500 text-7xl"></i>
                    </div>
                    <div class="absolute -bottom-6 -right-6 bg-gradient-to-r from-blue-600 to-indigo-700 text-white p-6 rounded-xl shadow-lg">
                        <div class="text-3xl font-bold">18+</div>
                        <div class="text-sm">Years Experience</div>
                    </div>
                </div>
            </div>
            <div class="lg:w-3/5">
                <p class="text-gray-700 text-lg mb-6">
                    Tunggal Jaya Transport has been providing reliable and comfortable transportation services since 2005. 
                    With over 18 years of experience, we have grown to become one of the most trusted bus companies in the region.
                </p>
                <p class="text-gray-700 text-lg mb-6">
                    Our commitment to safety, punctuality, and customer satisfaction has made us the preferred choice for 
                    thousands of travelers every day. We believe in delivering more than just transportation - we deliver experiences.
                </p>
                <p class="text-gray-700 text-lg mb-8">
                    We continuously invest in our fleet and staff training to ensure we provide the best possible service 
                    to our valued customers. Our modern buses are equipped with the latest amenities for your comfort and safety.
                </p>
                <div class="flex flex-wrap gap-4">
                    <div class="flex items-center bg-white p-3 rounded-lg shadow-sm">
                        <div class="bg-blue-100 p-2 rounded-full mr-3">
                            <i class="fas fa-bus text-blue-600"></i>
                        </div>
                        <div>
                            <div class="font-bold text-gray-800">Modern Fleet</div>
                            <div class="text-sm text-gray-600">Well-maintained buses</div>
                        </div>
                    </div>
                    <div class="flex items-center bg-white p-3 rounded-lg shadow-sm">
                        <div class="bg-blue-100 p-2 rounded-full mr-3">
                            <i class="fas fa-user-tie text-blue-600"></i>
                        </div>
                        <div>
                            <div class="font-bold text-gray-800">Professional Staff</div>
                            <div class="text-sm text-gray-600">Trained drivers & crew</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Vision and Mission -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
        <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl shadow-lg p-8">
            <div class="flex items-center mb-6">
                <div class="bg-green-100 p-3 rounded-full mr-4">
                    <i class="fas fa-eye text-green-600 text-xl"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-800">Our Vision</h2>
            </div>
            <p class="text-gray-700 text-lg">
                To be the leading transportation provider in the region, known for our commitment to safety, 
                comfort, and environmental responsibility. We strive to connect communities while preserving our planet 
                for future generations.
            </p>
            <div class="mt-6 flex items-center text-green-600">
                <i class="fas fa-quote-left text-3xl mr-3"></i>
                <p class="italic">Connecting people, connecting futures</p>
            </div>
        </div>
        <div class="bg-gradient-to-r from-purple-50 to-violet-50 rounded-xl shadow-lg p-8">
            <div class="flex items-center mb-6">
                <div class="bg-purple-100 p-3 rounded-full mr-4">
                    <i class="fas fa-bullseye text-purple-600 text-xl"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-800">Our Mission</h2>
            </div>
            <ul class="space-y-4">
                <li class="flex items-start">
                    <div class="bg-purple-100 p-2 rounded-full mr-3 mt-1">
                        <i class="fas fa-check text-purple-600"></i>
                    </div>
                    <span class="text-gray-700 text-lg">Provide safe and reliable transportation services</span>
                </li>
                <li class="flex items-start">
                    <div class="bg-purple-100 p-2 rounded-full mr-3 mt-1">
                        <i class="fas fa-check text-purple-600"></i>
                    </div>
                    <span class="text-gray-700 text-lg">Ensure passenger comfort and satisfaction</span>
                </li>
                <li class="flex items-start">
                    <div class="bg-purple-100 p-2 rounded-full mr-3 mt-1">
                        <i class="fas fa-check text-purple-600"></i>
                    </div>
                    <span class="text-gray-700 text-lg">Continuously improve our fleet and facilities</span>
                </li>
                <li class="flex items-start">
                    <div class="bg-purple-100 p-2 rounded-full mr-3 mt-1">
                        <i class="fas fa-check text-purple-600"></i>
                    </div>
                    <span class="text-gray-700 text-lg">Contribute to sustainable transportation solutions</span>
                </li>
                <li class="flex items-start">
                    <div class="bg-purple-100 p-2 rounded-full mr-3 mt-1">
                        <i class="fas fa-check text-purple-600"></i>
                    </div>
                    <span class="text-gray-700 text-lg">Maintain the highest standards of professionalism</span>
                </li>
            </ul>
        </div>
    </div>

    <!-- Stats Section -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-xl shadow-lg p-8 mb-12 text-white">
        <h2 class="text-3xl font-bold text-center mb-10">By The Numbers</h2>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 text-center">
            <div class="bg-white bg-opacity-10 p-6 rounded-xl">
                <div class="text-4xl font-bold mb-2">50+</div>
                <div class="text-lg">Destinations</div>
            </div>
            <div class="bg-white bg-opacity-10 p-6 rounded-xl">
                <div class="text-4xl font-bold mb-2">100+</div>
                <div class="text-lg">Modern Buses</div>
            </div>
            <div class="bg-white bg-opacity-10 p-6 rounded-xl">
                <div class="text-4xl font-bold mb-2">200+</div>
                <div class="text-lg">Professional Staff</div>
            </div>
            <div class="bg-white bg-opacity-10 p-6 rounded-xl">
                <div class="text-4xl font-bold mb-2">1M+</div>
                <div class="text-lg">Happy Passengers</div>
            </div>
        </div>
    </div>

    <!-- FAQ Section -->
    <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl shadow-lg p-8">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-3xl font-bold text-gray-800">Frequently Asked Questions</h2>
            <div class="bg-gray-200 text-gray-800 px-4 py-2 rounded-full text-sm font-bold">
                <i class="fas fa-question-circle mr-2"></i>FAQ
            </div>
        </div>
        <div x-data="{ open: null }" class="space-y-6">
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <button @click="open = open === 1 ? null : 1" class="flex justify-between items-center w-full text-left p-6 font-medium text-gray-800 hover:bg-gray-50 transition duration-200">
                    <span class="text-lg">How can I book a ticket?</span>
                    <svg class="h-6 w-6 text-gray-500 transition-transform duration-300" :class="{ 'transform rotate-180': open === 1 }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="open === 1" class="px-6 pb-6 text-gray-600" x-collapse>
                    <p>You can book a ticket through our website, mobile app, or by visiting one of our ticket offices. Our online booking system is available 24/7 for your convenience.</p>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <button @click="open = open === 2 ? null : 2" class="flex justify-between items-center w-full text-left p-6 font-medium text-gray-800 hover:bg-gray-50 transition duration-200">
                    <span class="text-lg">What payment methods do you accept?</span>
                    <svg class="h-6 w-6 text-gray-500 transition-transform duration-300" :class="{ 'transform rotate-180': open === 2 }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="open === 2" class="px-6 pb-6 text-gray-600" x-collapse>
                    <p>We accept cash, credit cards, debit cards, and digital payments through our mobile app. All major credit cards including Visa, Mastercard, and American Express are accepted.</p>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <button @click="open = open === 3 ? null : 3" class="flex justify-between items-center w-full text-left p-6 font-medium text-gray-800 hover:bg-gray-50 transition duration-200">
                    <span class="text-lg">Can I cancel or change my booking?</span>
                    <svg class="h-6 w-6 text-gray-500 transition-transform duration-300" :class="{ 'transform rotate-180': open === 3 }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="open === 3" class="px-6 pb-6 text-gray-600" x-collapse>
                    <p>Yes, you can cancel or change your booking up to 2 hours before departure, subject to our cancellation policy. Cancellations made less than 24 hours before departure may incur a fee.</p>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <button @click="open = open === 4 ? null : 4" class="flex justify-between items-center w-full text-left p-6 font-medium text-gray-800 hover:bg-gray-50 transition duration-200">
                    <span class="text-lg">What amenities are available on your buses?</span>
                    <svg class="h-6 w-6 text-gray-500 transition-transform duration-300" :class="{ 'transform rotate-180': open === 4 }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="open === 4" class="px-6 pb-6 text-gray-600" x-collapse>
                    <p>Our buses are equipped with comfortable reclining seats, air conditioning, onboard restrooms, complimentary WiFi, charging ports, and entertainment systems. Premium services may include meals and beverages.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection