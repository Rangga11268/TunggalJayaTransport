@extends('frontend.layouts.app')

@section('title', 'Destination Recommendations')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-2">Recommended Destinations</h1>
    @if($origin)
        <p class="text-gray-600 mb-6">Based on your recent trip from {{ $origin }}, here are destinations you might like</p>
    @else
        <p class="text-gray-600 mb-6">Here are destinations you might like based on your previous bookings</p>
    @endif
    
    @if($recommendedRoutes->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($recommendedRoutes as $item)
                @php
                    $route = $item['route'];
                    $schedule = $item['schedule'];
                @endphp
                <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-200 hover:shadow-lg transition-shadow duration-300">
                    <div class="p-6">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">{{ $route->name }}</h3>
                                <p class="text-gray-600 mt-1">{{ $route->origin }} → {{ $route->destination }}</p>
                            </div>
                            <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded">
                                Recommended
                            </span>
                        </div>
                        
                        <div class="mt-4 space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Departure:</span>
                                <span class="font-medium">{{ $schedule->getDepartureTimeWIB() ? $schedule->getDepartureTimeWIB()->format('H:i') : 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Arrival:</span>
                                <span class="font-medium">{{ $schedule->getArrivalTimeWIB() ? $schedule->getArrivalTimeWIB()->format('H:i') : 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Duration:</span>
                                <span class="font-medium">{{ $route->formatted_duration }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Bus Type:</span>
                                <span class="font-medium">{{ $schedule->bus->bus_type ?? 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Price:</span>
                                <span class="font-medium text-green-600">Rp {{ number_format($schedule->price, 0, ',', '.') }}</span>
                            </div>
                        </div>
                        
                        <div class="mt-6">
                            <a href="{{ route('frontend.booking.show', $schedule->id) }}" class="w-full flex justify-center items-center px-4 py-2 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700">
                                Book Now
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-white rounded-lg shadow p-8 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <h3 class="mt-2 text-lg font-medium text-gray-900">No recommendations available</h3>
            <p class="mt-1 text-gray-500">We couldn't find any destinations matching your criteria.</p>
            <div class="mt-6">
                <a href="{{ route('frontend.booking.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none">
                    Browse All Routes
                </a>
            </div>
        </div>
    @endif
</div>
@endsection