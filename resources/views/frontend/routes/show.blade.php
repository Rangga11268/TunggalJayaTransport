@extends('frontend.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="text-center mb-8">
        <h1 class="text-4xl font-bold text-gray-800 mb-3">{{ $route->name ?? $route->origin . ' - ' . $route->destination }}</h1>
        <p class="text-lg text-gray-600">Detailed information about this route and available schedules</p>
    </div>
    
    <!-- Route Information -->
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl shadow-lg p-6 mb-10">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Route Details</h2>
            <div class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">
                <i class="fas fa-info-circle mr-1"></i>Route Info
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="bg-white p-6 rounded-lg shadow-sm">
                <h3 class="text-lg font-medium mb-4 text-gray-800 border-b border-gray-200 pb-2">Route Overview</h3>
                <div class="space-y-4">
                    <div class="flex items-center">
                        <div class="bg-blue-100 p-3 rounded-full mr-4">
                            <i class="fas fa-map-marker-alt text-blue-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Origin</p>
                            <p class="font-medium text-lg">{{ $route->origin }}</p>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <div class="bg-blue-100 p-3 rounded-full mr-4">
                            <i class="fas fa-map-pin text-blue-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Destination</p>
                            <p class="font-medium text-lg">{{ $route->destination }}</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="flex items-center">
                            <div class="bg-blue-100 p-2 rounded-full mr-3">
                                <i class="fas fa-road text-blue-600"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Distance</p>
                                <p class="font-medium">{{ $route->distance }} km</p>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <div class="bg-blue-100 p-2 rounded-full mr-3">
                                <i class="fas fa-clock text-blue-600"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Duration</p>
                                <p class="font-medium">{{ $route->duration }} hours</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-white p-6 rounded-lg shadow-sm">
                <h3 class="text-lg font-medium mb-4 text-gray-800 border-b border-gray-200 pb-2">Description</h3>
                <div class="prose max-w-none">
                    <p class="text-gray-700">{{ $route->description ?? 'No detailed description available for this route. Our buses operate regularly on this route with comfortable seating and professional drivers to ensure a pleasant journey.' }}</p>
                </div>
                
                <div class="mt-6 pt-4 border-t border-gray-200">
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-sync-alt mr-2 text-blue-500"></i>
                        <span>Regular service with multiple departure times daily</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Schedules -->
    <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl shadow-lg p-6 mb-10">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Available Schedules</h2>
            <div class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">
                <i class="fas fa-calendar-alt mr-1"></i>{{ $route->schedules->count() }} Schedules
            </div>
        </div>
        
        @if($route->schedules->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gradient-to-r from-green-500 to-emerald-600 text-white">
                    <tr>
                        <th scope="col" class="px-6 py-4 text-left text-sm font-medium uppercase tracking-wider">Departure</th>
                        <th scope="col" class="px-6 py-4 text-left text-sm font-medium uppercase tracking-wider">Arrival</th>
                        <th scope="col" class="px-6 py-4 text-left text-sm font-medium uppercase tracking-wider">Bus Type</th>
                        <th scope="col" class="px-6 py-4 text-left text-sm font-medium uppercase tracking-wider">Price</th>
                        <th scope="col" class="px-6 py-4 text-left text-sm font-medium uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($route->schedules as $schedule)
                    <tr class="hover:bg-green-50 transition duration-150">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="bg-green-100 p-2 rounded-full mr-3">
                                    <i class="fas fa-sign-out-alt text-green-600"></i>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $schedule->departure_time->format('H:i') }}</div>
                                    <div class="text-sm text-gray-500">Terminal {{ $schedule->departure_terminal ?? '1' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="bg-green-100 p-2 rounded-full mr-3">
                                    <i class="fas fa-sign-in-alt text-green-600"></i>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $schedule->arrival_time->format('H:i') }}</div>
                                    <div class="text-sm text-gray-500">Terminal {{ $schedule->arrival_terminal ?? '1' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="bg-green-100 p-2 rounded-full mr-3">
                                    <i class="fas fa-bus text-green-600"></i>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $schedule->bus->bus_type ?? 'Standard' }}</div>
                                    <div class="text-sm text-gray-500">{{ $schedule->bus->plate_number }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-lg font-bold text-gray-900">Rp. {{ number_format($schedule->price, 0, ',', '.') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('frontend.booking.show', $schedule->id) }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-700 border border-transparent rounded-md font-semibold text-white hover:from-green-700 hover:to-emerald-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 shadow-sm transition duration-300 transform hover:scale-105">
                                <i class="fas fa-ticket-alt mr-1"></i>Book Now
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-12 bg-white rounded-xl shadow-sm">
            <div class="text-gray-400 text-5xl mb-4">
                <i class="fas fa-calendar-times"></i>
            </div>
            <p class="text-gray-600 text-lg">No schedules available for this route at the moment.</p>
            <p class="text-gray-500 mt-2">Please check back later for updates or contact our customer service.</p>
        </div>
        @endif
    </div>
    
    <!-- Back to Routes -->
    <div class="mb-10">
        <a href="{{ route('frontend.routes.index') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-gray-600 to-gray-700 hover:from-gray-700 hover:to-gray-800 text-white font-bold rounded-lg transition duration-300 shadow-lg transform hover:scale-105">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Routes
        </a>
    </div>
</div>
@endsection