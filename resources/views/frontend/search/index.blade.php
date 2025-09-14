@extends('frontend.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <h1 class="text-3xl font-bold mb-6">Search Results</h1>
    
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <form class="flex">
            <input type="text" placeholder="Search..." class="flex-grow px-4 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-blue-500" value="Jakarta">
            <button type="submit" class="px-6 py-2 bg-blue-500 text-white font-medium rounded-r-md hover:bg-blue-600">
                Search
            </button>
        </form>
    </div>
    
    <div class="mb-6">
        <p class="text-gray-600">Found 12 results for "Jakarta"</p>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="p-6">
                <h3 class="text-xl font-bold mb-2">
                    <a href="#" class="text-blue-500 hover:underline">New Route: Jakarta to Bali Now Available</a>
                </h3>
                <div class="text-sm text-gray-500 mb-3">News • September 10, 2025</div>
                <p class="text-gray-600">We're excited to announce a new route connecting Jakarta and Bali...</p>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="p-6">
                <h3 class="text-xl font-bold mb-2">
                    <a href="#" class="text-blue-500 hover:underline">Jakarta Terminal Renovation Completed</a>
                </h3>
                <div class="text-sm text-gray-500 mb-3">News • August 22, 2025</div>
                <p class="text-gray-600">Our main terminal in Jakarta has been renovated to provide better...</p>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="p-6">
                <h3 class="text-xl font-bold mb-2">
                    <a href="#" class="text-blue-500 hover:underline">Special Jakarta Independence Day Promotions</a>
                </h3>
                <div class="text-sm text-gray-500 mb-3">Promotions • August 15, 2025</div>
                <p class="text-gray-600">Celebrate Independence Day with special discounts on all routes...</p>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="p-6">
                <h3 class="text-xl font-bold mb-2">
                    <a href="#" class="text-blue-500 hover:underline">Jakarta Traffic Advisory</a>
                </h3>
                <div class="text-sm text-gray-500 mb-3">Announcements • July 30, 2025</div>
                <p class="text-gray-600">Please note that there may be delays due to road construction...</p>
            </div>
        </div>
    </div>
    
    <!-- Pagination -->
    <div class="mt-8 flex justify-center">
        <nav class="flex items-center space-x-2">
            <a href="#" class="px-3 py-1 rounded-md bg-gray-200 text-gray-700 hover:bg-gray-300">Previous</a>
            <a href="#" class="px-3 py-1 rounded-md bg-blue-500 text-white">1</a>
            <a href="#" class="px-3 py-1 rounded-md bg-gray-200 text-gray-700 hover:bg-gray-300">2</a>
            <a href="#" class="px-3 py-1 rounded-md bg-gray-200 text-gray-700 hover:bg-gray-300">3</a>
            <span class="px-3 py-1 text-gray-500">...</span>
            <a href="#" class="px-3 py-1 rounded-md bg-gray-200 text-gray-700 hover:bg-gray-300">10</a>
            <a href="#" class="px-3 py-1 rounded-md bg-gray-200 text-gray-700 hover:bg-gray-300">Next</a>
        </nav>
    </div>
</div>
@endsection