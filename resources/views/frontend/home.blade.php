@extends('frontend.layouts.app')

@php
use Illuminate\Support\Str;
@endphp

@section('content')
<!-- Hero Section -->
<div class="relative bg-gradient-to-r from-blue-800 to-indigo-900 text-white overflow-hidden">
    <div class="absolute inset-0 bg-black opacity-50"></div>
    <!-- Hero image as background for small screens -->
    <div class="absolute inset-0 bg-cover bg-center md:hidden" style="background-image: url('{{ asset('img/heroImg.jpg') }}'); background-position: center center;"></div>
    
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16 md:py-24 lg:py-32">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
            <div class="text-center md:text-left bg-black bg-opacity-40 md:bg-transparent p-4 sm:p-6 rounded-xl sm:rounded-none">
                <h1 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold mb-3 sm:mb-4">Comfortable & Reliable Transportation</h1>
                <p class="text-base sm:text-lg md:text-xl mb-6 sm:mb-8 text-blue-100">Experience safe and comfortable journeys with our modern fleet and professional drivers.</p>
                <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 justify-center md:justify-start">
                    <a href="{{ route('frontend.booking.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 sm:py-3 sm:px-6 rounded-lg transition duration-300 text-center text-sm sm:text-base">
                        Book Now
                    </a>
                    <a href="{{ route('frontend.fleet.index') }}" class="bg-transparent border-2 border-white hover:bg-white hover:text-blue-900 text-white font-bold py-2 px-4 sm:py-3 sm:px-6 rounded-lg transition duration-300 text-center text-sm sm:text-base">
                        View Fleet
                    </a>
                </div>
            </div>
            <!-- Show image separately on medium and larger screens -->
            <div class="hidden md:block">
                <img src="{{ asset('img/heroImg.jpg') }}" alt="Tunggal Jaya Transport" class="w-full h-64 md:h-80 lg:h-96 object-cover rounded-xl shadow-lg">
            </div>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

    <!-- Quick Booking Form -->
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg shadow-md p-4 sm:p-6 mb-8 sm:mb-12 -mt-8 sm:-mt-12 relative z-10 mx-4 sm:mx-6 md:mx-0" x-data="{ origin: '', destination: '', date: '' }">
        <h2 class="text-xl sm:text-2xl font-bold mb-4 text-center text-gray-800">Quick Booking</h2>
        <form method="GET" action="{{ route('frontend.booking.index') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3 sm:gap-4">
            <div>
                <label for="origin" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Origin</label>
                <input type="text" id="origin" name="origin" x-model="origin" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 py-2 px-3 sm:py-3 sm:px-4 text-sm">
            </div>
            <div>
                <label for="destination" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Destination</label>
                <input type="text" id="destination" name="destination" x-model="destination" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 py-2 px-3 sm:py-3 sm:px-4 text-sm">
            </div>
            <div>
                <label for="date" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Date</label>
                <input type="date" id="date" name="date" x-model="date" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 py-2 px-3 sm:py-3 sm:px-4 text-sm">
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800 text-white font-bold py-2 px-3 sm:py-3 sm:px-4 rounded-lg transition duration-300 transform hover:scale-105 text-sm sm:text-base">
                    <i class="fas fa-search mr-1 sm:mr-2"></i>Search
                </button>
            </div>
        </form>
    </div>

    <!-- Featured Routes -->
    <div class="mb-8 sm:mb-12">
        <h2 class="text-2xl sm:text-3xl font-bold mb-4 sm:mb-6 text-center text-gray-800">Popular Routes</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 sm:gap-6">
            @foreach($featuredRoutes as $route)
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition duration-300 transform hover:-translate-y-1">
                <div class="bg-gradient-to-r from-blue-400 to-indigo-500 h-1 sm:h-2"></div>
                <div class="p-4 sm:p-6">
                    <div class="flex justify-between items-center mb-3 sm:mb-4">
                        <h3 class="text-base sm:text-xl font-bold text-gray-800">{{ $route->origin }}</h3>
                        <i class="fas fa-arrow-right text-blue-500 text-sm sm:text-base"></i>
                        <h3 class="text-base sm:text-xl font-bold text-gray-800">{{ $route->destination }}</h3>
                    </div>
                    @if($route->distance)
                        <p class="text-xs sm:text-sm text-gray-600 mb-3 sm:mb-4"><i class="fas fa-road mr-1 sm:mr-2"></i>{{ $route->distance }} km</p>
                    @endif
                    <a href="{{ route('frontend.routes.show', $route) }}" class="text-blue-600 hover:text-blue-800 font-medium flex items-center text-sm sm:text-base">
                        View Details <i class="fas fa-chevron-right ml-1 sm:ml-2 text-xs sm:text-sm"></i>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Company Highlights -->
    <div class="mb-8 sm:mb-12 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-4 sm:p-6 md:p-8">
        <h2 class="text-2xl sm:text-3xl font-bold mb-4 sm:mb-8 text-center text-gray-800">Why Choose Us</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 sm:gap-6">
            <div class="text-center bg-white p-4 sm:p-6 rounded-lg shadow-md hover:shadow-lg transition duration-300">
                <div class="bg-blue-100 w-12 h-12 sm:w-16 sm:h-16 rounded-full flex items-center justify-center mx-auto mb-3 sm:mb-4">
                    <i class="fas fa-couch text-blue-600 text-xl sm:text-2xl"></i>
                </div>
                <h3 class="text-base sm:text-lg font-bold mb-2">Comfortable Seats</h3>
                <p class="text-xs sm:text-sm text-gray-600">Ergonomic seating for maximum comfort</p>
            </div>
            <div class="text-center bg-white p-4 sm:p-6 rounded-lg shadow-md hover:shadow-lg transition duration-300">
                <div class="bg-blue-100 w-12 h-12 sm:w-16 sm:h-16 rounded-full flex items-center justify-center mx-auto mb-3 sm:mb-4">
                    <i class="fas fa-clock text-blue-600 text-xl sm:text-2xl"></i>
                </div>
                <h3 class="text-base sm:text-lg font-bold mb-2">On-time Guarantee</h3>
                <p class="text-xs sm:text-sm text-gray-600">Punctual departures and arrivals</p>
            </div>
            <div class="text-center bg-white p-4 sm:p-6 rounded-lg shadow-md hover:shadow-lg transition duration-300">
                <div class="bg-blue-100 w-12 h-12 sm:w-16 sm:h-16 rounded-full flex items-center justify-center mx-auto mb-3 sm:mb-4">
                    <i class="fas fa-user-friends text-blue-600 text-xl sm:text-2xl"></i>
                </div>
                <h3 class="text-base sm:text-lg font-bold mb-2">Friendly Staff</h3>
                <p class="text-xs sm:text-sm text-gray-600">Professional and courteous service</p>
            </div>
            <div class="text-center bg-white p-4 sm:p-6 rounded-lg shadow-md hover:shadow-lg transition duration-300">
                <div class="bg-blue-100 w-12 h-12 sm:w-16 sm:h-16 rounded-full flex items-center justify-center mx-auto mb-3 sm:mb-4">
                    <i class="fas fa-shield-alt text-blue-600 text-xl sm:text-2xl"></i>
                </div>
                <h3 class="text-base sm:text-lg font-bold mb-2">Safe Travel</h3>
                <p class="text-xs sm:text-sm text-gray-600">Regular maintenance and safety checks</p>
            </div>
        </div>
    </div>

    <!-- Testimonials Carousel -->
    <div class="mb-8 sm:mb-12">
        <h2 class="text-2xl sm:text-3xl font-bold mb-4 sm:mb-8 text-center text-gray-800">What Our Customers Say</h2>
        <div class="swiper myTestimonialsSwiper">
            <div class="swiper-wrapper">
                <div class="swiper-slide bg-white rounded-lg shadow-md p-4 sm:p-6 mx-2 sm:mx-4">
                    <div class="flex items-center mb-3 sm:mb-4">
                        <div class="bg-gray-200 border-2 border-dashed rounded-xl w-12 h-12 sm:w-16 sm:h-16"></div>
                        <div class="ml-3 sm:ml-4">
                            <p class="font-bold text-base sm:text-lg">John Doe</p>
                            <div class="flex text-yellow-400 text-sm sm:text-base">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                    </div>
                    <p class="text-gray-600 italic text-sm sm:text-base">"The service was excellent and the bus was very comfortable. I'll definitely use Tunggal Jaya Transport again!"</p>
                </div>
                <div class="swiper-slide bg-white rounded-lg shadow-md p-4 sm:p-6 mx-2 sm:mx-4">
                    <div class="flex items-center mb-3 sm:mb-4">
                        <div class="bg-gray-200 border-2 border-dashed rounded-xl w-12 h-12 sm:w-16 sm:h-16"></div>
                        <div class="ml-3 sm:ml-4">
                            <p class="font-bold text-base sm:text-lg">Jane Smith</p>
                            <div class="flex text-yellow-400 text-sm sm:text-base">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                            </div>
                        </div>
                    </div>
                    <p class="text-gray-600 italic text-sm sm:text-base">"Punctual, clean, and affordable. Highly recommended for long-distance travel."</p>
                </div>
                <div class="swiper-slide bg-white rounded-lg shadow-md p-4 sm:p-6 mx-2 sm:mx-4">
                    <div class="flex items-center mb-3 sm:mb-4">
                        <div class="bg-gray-200 border-2 border-dashed rounded-xl w-12 h-12 sm:w-16 sm:h-16"></div>
                        <div class="ml-3 sm:ml-4">
                            <p class="font-bold text-base sm:text-lg">Robert Johnson</p>
                            <div class="flex text-yellow-400 text-sm sm:text-base">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="far fa-star"></i>
                            </div>
                        </div>
                    </div>
                    <p class="text-gray-600 italic text-sm sm:text-base">"Great customer service and comfortable journey. Will book again for my next trip."</p>
                </div>
            </div>
            <div class="swiper-pagination"></div>
        </div>
    </div>

    <!-- News Section -->
    <div class="mb-8 sm:mb-12">
        <div class="flex justify-between items-center mb-4 sm:mb-8">
            <h2 class="text-2xl sm:text-3xl font-bold text-gray-800">Latest News</h2>
            <a href="{{ route('frontend.news.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-1 px-3 sm:py-2 sm:px-4 rounded-lg transition duration-300 text-sm sm:text-base">
                <i class="fas fa-newspaper mr-1 sm:mr-2"></i>View All News
            </a>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 sm:gap-6">
            @foreach($latestNews as $article)
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition duration-300 transform hover:-translate-y-1">
                @if($article->getFirstMediaUrl('featured_images'))
                    <img src="{{ $article->getFirstMediaUrl('featured_images') }}" alt="{{ $article->title }}" class="w-full h-32 sm:h-48 object-cover">
                @else
                    <div class="bg-gradient-to-r from-blue-400 to-indigo-500 w-full h-32 sm:h-48 flex items-center justify-center">
                        <i class="fas fa-image text-white text-2xl sm:text-4xl"></i>
                    </div>
                @endif
                <div class="p-4 sm:p-6">
                    <h3 class="text-base sm:text-xl font-bold mb-2 text-gray-800">{{ $article->title }}</h3>
                    <p class="text-xs sm:text-sm text-gray-600 mb-2 sm:mb-3"><i class="far fa-calendar mr-1 sm:mr-2"></i>{{ $article->created_at->format('F j, Y') }}</p>
                    <p class="text-xs sm:text-sm text-gray-700 mb-3 sm:mb-4">{{ Str::limit($article->excerpt ?? strip_tags($article->content), 80) }}</p>
                    <a href="{{ route('frontend.news.show', $article) }}" class="text-blue-600 hover:text-blue-800 font-medium flex items-center text-sm sm:text-base">
                        Read More <i class="fas fa-chevron-right ml-1 sm:ml-2 text-xs sm:text-sm"></i>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Statistics Counter -->
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg shadow-md p-4 sm:p-6 mb-6 sm:mb-8" x-data="{
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
        <h2 class="text-2xl sm:text-3xl font-bold mb-4 sm:mb-6 text-center text-gray-800">Our Achievements</h2>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6 text-center">
            <div class="bg-white p-4 sm:p-6 rounded-lg shadow-md hover:shadow-lg transition duration-300">
                <div class="text-3xl sm:text-5xl font-bold text-blue-600 mb-2" x-text="fleetCount">0</div>
                <div class="text-base sm:text-xl text-gray-700">Modern Fleet</div>
            </div>
            <div class="bg-white p-4 sm:p-6 rounded-lg shadow-md hover:shadow-lg transition duration-300">
                <div class="text-3xl sm:text-5xl font-bold text-blue-600 mb-2" x-text="routeCount">0</div>
                <div class="text-base sm:text-xl text-gray-700">Routes</div>
            </div>
            <div class="bg-white p-4 sm:p-6 rounded-lg shadow-md hover:shadow-lg transition duration-300">
                <div class="text-3xl sm:text-5xl font-bold text-blue-600 mb-2" x-text="customerCount">0</div>
                <div class="text-base sm:text-xl text-gray-700">Happy Customers</div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var testimonialsSwiper = new Swiper(".myTestimonialsSwiper", {
            pagination: {
                el: ".swiper-pagination",
            },
            autoplay: {
                delay: 5000,
            },
            loop: true,
            slidesPerView: 1,
            spaceBetween: 20,
            breakpoints: {
                768: {
                    slidesPerView: 2,
                    spaceBetween: 30,
                },
                1024: {
                    slidesPerView: 3,
                    spaceBetween: 40,
                },
            }
        });
    });
</script>
@endsection