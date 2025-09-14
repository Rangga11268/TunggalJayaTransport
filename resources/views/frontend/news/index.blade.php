@extends('frontend.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <h1 class="text-3xl font-bold mb-6">News & Updates</h1>
    
    <!-- Category Filter -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h2 class="text-xl font-bold mb-4">Filter by Category</h2>
        <div class="flex flex-wrap gap-2">
            <a href="#" class="px-4 py-2 bg-blue-500 text-white rounded-full">All</a>
            <a href="#" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-full hover:bg-gray-300">Announcements</a>
            <a href="#" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-full hover:bg-gray-300">Promotions</a>
            <a href="#" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-full hover:bg-gray-300">Events</a>
        </div>
    </div>

    <!-- News List -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="bg-gray-200 border-2 border-dashed rounded-xl w-full h-48"></div>
            <div class="p-4">
                <div class="text-sm text-gray-500 mb-2">September 10, 2025</div>
                <h3 class="text-xl font-bold mb-2">New Route Added</h3>
                <p class="text-gray-600">We're excited to announce a new route connecting Jakarta and Bali.</p>
                <a href="#" class="text-blue-500 hover:underline mt-2 inline-block">Read More</a>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="bg-gray-200 border-2 border-dashed rounded-xl w-full h-48"></div>
            <div class="p-4">
                <div class="text-sm text-gray-500 mb-2">September 5, 2025</div>
                <h3 class="text-xl font-bold mb-2">Fleet Expansion</h3>
                <p class="text-gray-600">Our fleet has expanded with 10 new buses for better service.</p>
                <a href="#" class="text-blue-500 hover:underline mt-2 inline-block">Read More</a>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="bg-gray-200 border-2 border-dashed rounded-xl w-full h-48"></div>
            <div class="p-4">
                <div class="text-sm text-gray-500 mb-2">September 1, 2025</div>
                <h3 class="text-xl font-bold mb-2">Safety First</h3>
                <p class="text-gray-600">All our buses now have enhanced safety features for your peace of mind.</p>
                <a href="#" class="text-blue-500 hover:underline mt-2 inline-block">Read More</a>
            </div>
        </div>
    </div>

    <!-- Newsletter Subscription -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold mb-4">Subscribe to Our Newsletter</h2>
        <form class="flex flex-col sm:flex-row gap-4">
            <input type="email" placeholder="Your email address" class="flex-grow px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            <button type="submit" class="px-6 py-2 bg-blue-500 text-white font-medium rounded-md hover:bg-blue-600">
                Subscribe
            </button>
        </form>
    </div>
</div>
@endsection