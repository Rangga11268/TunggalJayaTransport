@extends('frontend.layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <article class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="bg-gray-200 border-2 border-dashed rounded-xl w-full h-96"></div>
        
        <div class="p-6">
            <div class="flex items-center text-sm text-gray-500 mb-4">
                <span>September 10, 2025</span>
                <span class="mx-2">•</span>
                <span>By Admin</span>
                <span class="mx-2">•</span>
                <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">Announcements</span>
            </div>
            
            <h1 class="text-3xl font-bold mb-4">New Route Added: Jakarta to Bali</h1>
            
            <div class="prose max-w-none">
                <p class="text-gray-700 mb-4">
                    We're excited to announce a new route connecting Jakarta and Bali, two of Indonesia's most popular destinations. 
                    This new service will provide travelers with a comfortable and convenient way to journey between these amazing locations.
                </p>
                
                <p class="text-gray-700 mb-4">
                    The new route will operate twice daily, with morning and evening departures from Jakarta's main terminal. 
                    Our executive class buses will feature extra legroom, complimentary refreshments, and onboard entertainment 
                    to ensure a pleasant journey.
                </p>
                
                <h2 class="text-2xl font-bold mt-6 mb-3">Key Features of the New Route</h2>
                
                <ul class="list-disc list-inside text-gray-700 mb-4">
                    <li>Direct route with no stops in between</li>
                    <li>Approximate travel time of 18 hours</li>
                    <li>Executive class buses with 36 seats</li>
                    <li>Complimentary meals and refreshments</li>
                    <li>Onboard entertainment system</li>
                    <li>Free Wi-Fi throughout the journey</li>
                </ul>
                
                <p class="text-gray-700 mb-4">
                    Tickets for this new route will be available for booking starting next Monday. Early bird discounts 
                    of 15% will be offered for the first week of sales. We recommend booking early to secure your preferred seats.
                </p>
                
                <blockquote class="border-l-4 border-blue-500 pl-4 italic text-gray-600 my-6">
                    "We're committed to expanding our network to serve more destinations across Indonesia. 
                    This new Jakarta-Bali route is just the beginning of our expansion plans for 2025."
                    <footer class="mt-2 text-gray-500">— Director of Operations</footer>
                </blockquote>
                
                <p class="text-gray-700">
                    For more information about schedules, pricing, and booking, please visit our Routes section or 
                    contact our customer service team. We look forward to welcoming you aboard our new service!
                </p>
            </div>
        </div>
    </article>
    
    <!-- Social Sharing -->
    <div class="bg-white rounded-lg shadow-md p-6 mt-6">
        <h3 class="text-lg font-bold mb-3">Share this article</h3>
        <div class="flex space-x-4">
            <a href="#" class="flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                <svg class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"/>
                </svg>
                Facebook
            </a>
            <a href="#" class="flex items-center px-4 py-2 bg-sky-500 text-white rounded-md hover:bg-sky-600">
                <svg class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84"/>
                </svg>
                Twitter
            </a>
            <a href="#" class="flex items-center px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                <svg class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2C6.477 2 2 6.477 2 12c0 5.523 4.477 10 10 10 5.523 0 10-4.477 10-10 0-5.523-4.477-10-10-10zm0 18c-4.418 0-8-3.582-8-8s3.582-8 8-8 8 3.582 8 8-3.582 8-8 8zm-2-12c0-.552.448-1 1-1s1 .448 1 1-.448 1-1 1-1-.448-1-1zm4 0c0-.552.448-1 1-1s1 .448 1 1-.448 1-1 1-1-.448-1-1zm-6 6v-2h8v2H8z"/>
                </svg>
                Reddit
            </a>
        </div>
    </div>
    
    <!-- Related Articles -->
    <div class="bg-white rounded-lg shadow-md p-6 mt-6">
        <h3 class="text-lg font-bold mb-4">Related Articles</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="border rounded-lg overflow-hidden">
                <div class="bg-gray-200 border-2 border-dashed rounded-xl w-full h-32"></div>
                <div class="p-3">
                    <h4 class="font-bold text-sm">Fleet Expansion Announcement</h4>
                    <p class="text-xs text-gray-500 mt-1">September 5, 2025</p>
                </div>
            </div>
            <div class="border rounded-lg overflow-hidden">
                <div class="bg-gray-200 border-2 border-dashed rounded-xl w-full h-32"></div>
                <div class="p-3">
                    <h4 class="font-bold text-sm">Safety First Initiative</h4>
                    <p class="text-xs text-gray-500 mt-1">September 1, 2025</p>
                </div>
            </div>
            <div class="border rounded-lg overflow-hidden">
                <div class="bg-gray-200 border-2 border-dashed rounded-xl w-full h-32"></div>
                <div class="p-3">
                    <h4 class="font-bold text-sm">New Booking System Launched</h4>
                    <p class="text-xs text-gray-500 mt-1">August 28, 2025</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection