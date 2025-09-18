@extends('frontend.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 page-spacing">
    <!-- Header -->
    <div class="text-center mb-10">
        <h1 class="text-4xl font-bold text-gray-800 mb-3">Our Routes</h1>
        <p class="text-lg text-gray-600 max-w-2xl mx-auto">Explore our extensive network of routes connecting cities and towns across the region</p>
    </div>
    
    <!-- Interactive Map -->
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl shadow-lg p-6 mb-10">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Route Map</h2>
            <div class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">
                <i class="fas fa-map-marked-alt mr-1"></i>{{ $routes->count() }} Routes
            </div>
        </div>
        <div class="bg-gradient-to-r from-gray-200 to-gray-300 rounded-xl w-full h-96 flex items-center justify-center">
            <div class="text-center">
                <i class="fas fa-map-marked-alt text-gray-500 text-6xl mb-4"></i>
                <p class="text-2xl text-gray-500 font-bold">Interactive Map</p>
                <p class="text-gray-600 mt-2">Visualize all our routes on an interactive map</p>
            </div>
        </div>
    </div>

    <!-- Route List -->
    <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl shadow-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">All Routes</h2>
            <div class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">
                <i class="fas fa-route mr-1"></i>{{ $routes->count() }} Routes
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($routes as $route)
            <div class="bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 overflow-hidden">
                <div class="bg-gradient-to-r from-green-500 to-emerald-600 p-4">
                    <div class="flex justify-between items-center">
                        <h3 class="text-xl font-bold text-white">{{ $route->origin }}</h3>
                        <i class="fas fa-exchange-alt text-white text-xl"></i>
                        <h3 class="text-xl font-bold text-white">{{ $route->destination }}</h3>
                    </div>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div class="flex items-center">
                            <div class="bg-green-100 p-2 rounded-full mr-3">
                                <i class="fas fa-road text-green-600"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Distance</p>
                                <p class="font-medium">{{ $route->distance }} km</p>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <div class="bg-green-100 p-2 rounded-full mr-3">
                                <i class="fas fa-clock text-green-600"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Duration</p>
                                <p class="font-medium">{{ $route->duration }} hours</p>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('frontend.routes.show', $route->id) }}" class="w-full bg-gradient-to-r from-green-600 to-emerald-700 hover:from-green-700 hover:to-emerald-800 text-white font-bold py-3 px-4 rounded-lg transition duration-300 transform hover:scale-105 shadow-md flex items-center justify-center">
                        <i class="fas fa-calendar-alt mr-2"></i>View Schedule
                    </a>
                </div>
            </div>
            @empty
            <div class="col-span-3">
                <div class="text-center py-12 bg-white rounded-xl shadow-sm">
                    <div class="text-gray-400 text-5xl mb-4">
                        <i class="fas fa-route"></i>
                    </div>
                    <p class="text-gray-600 text-lg">No routes available at the moment.</p>
                    <p class="text-gray-500 mt-2">Please check back later for updates.</p>
                </div>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection