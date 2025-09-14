@extends('frontend.layouts.app')

@php
use Illuminate\Support\Str;
@endphp

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Hero Slider -->
    <div class="swiper mySwiper mb-8">
        <div class="swiper-wrapper">
            <div class="swiper-slide">
                <div class="bg-gray-200 border-2 border-dashed rounded-xl w-full h-96 flex items-center justify-center">
                    <span class="text-2xl text-gray-500">Hero Image 1</span>
                </div>
            </div>
            <div class="swiper-slide">
                <div class="bg-gray-200 border-2 border-dashed rounded-xl w-full h-96 flex items-center justify-center">
                    <span class="text-2xl text-gray-500">Hero Image 2</span>
                </div>
            </div>
            <div class="swiper-slide">
                <div class="bg-gray-200 border-2 border-dashed rounded-xl w-full h-96 flex items-center justify-center">
                    <span class="text-2xl text-gray-500">Hero Image 3</span>
                </div>
            </div>
        </div>
        <div class="swiper-pagination"></div>
    </div>

    <!-- Quick Booking Form -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8" x-data="{ origin: '', destination: '', date: '' }">
        <h2 class="text-2xl font-bold mb-4">Quick Booking</h2>
        <form method="GET" action="{{ route('frontend.booking.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="origin" class="block text-sm font-medium text-gray-700">Origin</label>
                <input type="text" id="origin" name="origin" x-model="origin" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            </div>
            <div>
                <label for="destination" class="block text-sm font-medium text-gray-700">Destination</label>
                <input type="text" id="destination" name="destination" x-model="destination" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            </div>
            <div>
                <label for="date" class="block text-sm font-medium text-gray-700">Date</label>
                <input type="date" id="date" name="date" x-model="date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Search
                </button>
            </div>
        </form>
    </div>

    <!-- Featured Routes -->
    <div class="mb-8">
        <h2 class="text-2xl font-bold mb-4">Featured Routes</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($featuredRoutes as $route)
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="bg-gray-200 border-2 border-dashed rounded-xl w-full h-48"></div>
                <div class="p-4">
                    <h3 class="text-xl font-bold">{{ $route->origin }} - {{ $route->destination }}</h3>
                    @if($route->distance)
                        <p class="text-gray-600">{{ $route->distance }} km</p>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Company Highlights -->
    <div class="mb-8">
        <h2 class="text-2xl font-bold mb-4">Why Choose Us</h2>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="text-center">
                <div class="bg-gray-200 border-2 border-dashed rounded-xl w-16 h-16 mx-auto mb-2"></div>
                <h3 class="text-lg font-bold">Comfortable Seats</h3>
                <p class="text-gray-600">Ergonomic seating for maximum comfort</p>
            </div>
            <div class="text-center">
                <div class="bg-gray-200 border-2 border-dashed rounded-xl w-16 h-16 mx-auto mb-2"></div>
                <h3 class="text-lg font-bold">On-time Guarantee</h3>
                <p class="text-gray-600">Punctual departures and arrivals</p>
            </div>
            <div class="text-center">
                <div class="bg-gray-200 border-2 border-dashed rounded-xl w-16 h-16 mx-auto mb-2"></div>
                <h3 class="text-lg font-bold">Friendly Staff</h3>
                <p class="text-gray-600">Professional and courteous service</p>
            </div>
            <div class="text-center">
                <div class="bg-gray-200 border-2 border-dashed rounded-xl w-16 h-16 mx-auto mb-2"></div>
                <h3 class="text-lg font-bold">Safe Travel</h3>
                <p class="text-gray-600">Regular maintenance and safety checks</p>
            </div>
        </div>
    </div>

    <!-- Testimonials Carousel -->
    <div class="mb-8">
        <h2 class="text-2xl font-bold mb-4">What Our Customers Say</h2>
        <div class="swiper myTestimonialsSwiper">
            <div class="swiper-wrapper">
                <div class="swiper-slide bg-white rounded-lg shadow-md p-6">
                    <p class="text-gray-600 italic">"The service was excellent and the bus was very comfortable. I'll definitely use Tunggal Jaya Transport again!"</p>
                    <p class="font-bold mt-4">- John Doe</p>
                </div>
                <div class="swiper-slide bg-white rounded-lg shadow-md p-6">
                    <p class="text-gray-600 italic">"Punctual, clean, and affordable. Highly recommended for long-distance travel."</p>
                    <p class="font-bold mt-4">- Jane Smith</p>
                </div>
                <div class="swiper-slide bg-white rounded-lg shadow-md p-6">
                    <p class="text-gray-600 italic">"Great customer service and comfortable journey. Will book again for my next trip."</p>
                    <p class="font-bold mt-4">- Robert Johnson</p>
                </div>
            </div>
            <div class="swiper-pagination"></div>
        </div>
    </div>

    <!-- News Section -->
    <div class="mb-8">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold">Latest News</h2>
            <a href="{{ route('frontend.news.index') }}" class="text-blue-500 hover:underline">View All</a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($latestNews as $article)
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                @if($article->getFirstMediaUrl('featured_images'))
                    <img src="{{ $article->getFirstMediaUrl('featured_images') }}" alt="{{ $article->title }}" class="w-full h-48 object-cover">
                @else
                    <div class="bg-gray-200 border-2 border-dashed rounded-xl w-full h-48"></div>
                @endif
                <div class="p-4">
                    <h3 class="text-xl font-bold">{{ $article->title }}</h3>
                    <p class="text-gray-600 text-sm">{{ $article->created_at->format('F j, Y') }}</p>
                    <p class="mt-2">{{ Str::limit($article->excerpt ?? strip_tags($article->content), 100) }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Statistics Counter -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8" x-data="{
        fleetCount: 0,
        routeCount: 0,
        customerCount: 0,
        init() {
            // Animate counters when element is in viewport
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        this.animateValue('fleetCount', 0, {{ $fleetCount }}, 2000);
                        this.animateValue('routeCount', 0, {{ $routeCount }}, 2000);
                        this.animateValue('customerCount', 0, {{ $customerCount }}, 2000);
                    }
                });
            });
            observer.observe(this.$el);
        },
        animateValue(obj, start, end, duration) {
            let startTimestamp = null;
            const step = (timestamp) => {
                if (!startTimestamp) startTimestamp = timestamp;
                const progress = Math.min((timestamp - startTimestamp) / duration, 1);
                this[obj] = Math.floor(progress * (end - start) + start);
                if (progress < 1) {
                    window.requestAnimationFrame(step);
                }
            };
            window.requestAnimationFrame(step);
        }
    }">
        <h2 class="text-2xl font-bold mb-4 text-center">Our Achievements</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-center">
            <div>
                <div class="text-4xl font-bold text-blue-500" x-text="fleetCount">0</div>
                <div class="text-lg">Modern Fleet</div>
            </div>
            <div>
                <div class="text-4xl font-bold text-blue-500" x-text="routeCount">0</div>
                <div class="text-lg">Routes</div>
            </div>
            <div>
                <div class="text-4xl font-bold text-blue-500" x-text="customerCount">0</div>
                <div class="text-lg">Happy Customers</div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var swiper = new Swiper(".mySwiper", {
            pagination: {
                el: ".swiper-pagination",
            },
            autoplay: {
                delay: 5000,
            },
            loop: true,
        });

        var testimonialsSwiper = new Swiper(".myTestimonialsSwiper", {
            pagination: {
                el: ".swiper-pagination",
            },
            autoplay: {
                delay: 3000,
            },
            loop: true,
        });
    });
</script>
@endsection