@extends('frontend.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 page-spacing">
    <!-- Header -->
    <div class="text-center mb-8">
        <h1 class="text-4xl font-bold text-gray-800 mb-3">{{ $route->name ?? $route->origin . ' - ' . $route->destination }}</h1>
        <p class="text-lg text-gray-600">Detailed information about this route and available schedules</p>
    </div>
    
    <!-- Route Information -->
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl shadow-lg p-6 mb-10 mobile-info-card">
        <div class="flex justify-between items-center mb-6 flex-col md:flex-row gap-4">
            <h2 class="text-2xl font-bold text-gray-800">Route Details</h2>
            <div class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">
                <i class="fas fa-info-circle mr-1"></i>Route Info
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="bg-white p-6 rounded-lg shadow-sm mobile-info-card">
                <h3 class="text-lg font-medium mb-4 text-gray-800 border-b border-gray-200 pb-2 mobile-info-card-title">Route Overview</h3>
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
                                <p class="font-medium">{{ $route->formatted_duration }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-white p-6 rounded-lg shadow-sm mobile-info-card">
                <h3 class="text-lg font-medium mb-4 text-gray-800 border-b border-gray-200 pb-2 mobile-info-card-title">Description</h3>
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
    
    <!-- Route Map -->
    <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl shadow-lg p-6 mb-10 mobile-info-card">
        <div class="flex justify-between items-center mb-6 flex-col md:flex-row gap-4">
            <h2 class="text-2xl font-bold text-gray-800">Route Map</h2>
            <div class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">
                <i class="fas fa-map-marked-alt mr-1"></i>Visualize Route
            </div>
        </div>
        
        @if($route->origin_lat && $route->origin_lng && $route->destination_lat && $route->destination_lng)
        <div id="route-map" style="height: 400px; width: 100%; border-radius: 0.5rem; z-index: 10;"></div>
        <div class="mt-4 text-sm text-gray-600">
            <p><i class="fas fa-mouse-pointer mr-2"></i>Click and drag to pan the map</p>
        </div>
        @else
        <div class="bg-white rounded-xl shadow-sm p-8 text-center">
            <div class="text-gray-400 text-5xl mb-4">
                <i class="fas fa-map"></i>
            </div>
            <p class="text-gray-600 text-lg">Map information is not available for this route.</p>
            <p class="text-gray-500 mt-2">Please check back later for updates.</p>
        </div>
        @endif
    </div>
    
    <!-- Schedule Legend -->
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg shadow-md p-4 mb-6 mobile-legend">
        <div class="flex flex-wrap items-center justify-between">
            <div class="flex items-center mb-2 md:mb-0">
                <div class="w-3 h-3 bg-blue-500 rounded-full mr-2"></div>
                <span class="text-sm">Daily Schedule</span>
            </div>
            <div class="flex items-center mb-2 md:mb-0">
                <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
                <span class="text-sm">Weekly Schedule</span>
            </div>
            <div class="flex items-center">
                <div class="w-3 h-3 bg-red-500 rounded-full mr-2"></div>
                <span class="text-sm">Departed</span>
            </div>
        </div>
    </div>
    
    <!-- Schedules -->
    <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl shadow-lg p-6 mb-10 mobile-info-card">
        <div class="flex justify-between items-center mb-6 flex-col md:flex-row gap-4">
            <h2 class="text-2xl font-bold text-gray-800">Available Schedules</h2>
            <div class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">
                <i class="fas fa-calendar-alt mr-1"></i>{{ $route->schedules->count() }} Schedules
            </div>
        </div>
        
        @if($route->schedules->count() > 0)
        <!-- Mobile responsive table -->
        <div class="mobile-table-responsive">
            <table class="min-w-full divide-y divide-gray-200 mobile-table mobile-booking-table">
                <thead class="bg-gradient-to-r from-green-500 to-emerald-600 text-white">
                    <tr>
                        <th scope="col" class="px-6 py-4 text-left text-sm font-medium uppercase tracking-wider">Schedule Type</th>
                        <th scope="col" class="px-6 py-4 text-left text-sm font-medium uppercase tracking-wider">Departure</th>
                        <th scope="col" class="px-6 py-4 text-left text-sm font-medium uppercase tracking-wider">Arrival</th>
                        <th scope="col" class="px-6 py-4 text-left text-sm font-medium uppercase tracking-wider">Bus Type</th>
                        <th scope="col" class="px-6 py-4 text-left text-sm font-medium uppercase tracking-wider">Availability</th>
                        <th scope="col" class="px-6 py-4 text-left text-sm font-medium uppercase tracking-wider">Price</th>
                        <th scope="col" class="px-6 py-4 text-left text-sm font-medium uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($route->schedules as $schedule)
                    <tr class="hover:bg-green-50 transition duration-150">
                        <td class="px-6 py-4 whitespace-nowrap" data-label="Schedule Type">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 rounded-full flex items-center justify-center {{ $schedule->is_weekly ? 'bg-green-100' : 'bg-blue-100' }}">
                                    <i class="fas fa-{{ $schedule->is_weekly ? 'calendar-week' : 'clock' }} text-{{ $schedule->is_weekly ? 'green' : 'blue' }}-600"></i>
                                </div>
                                <div class="ml-3">
                                    <div class="text-sm font-medium text-gray-900">{{ $schedule->is_weekly ? 'Weekly' : 'Daily' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap" data-label="Departure">
                            <div class="flex items-center">
                                <div class="bg-green-100 p-2 rounded-full mr-3">
                                    <i class="fas fa-sign-out-alt text-green-600"></i>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">
                                        @if($schedule->is_weekly && $schedule->day_of_week !== null)
                                            @php
                                                $nextDate = $schedule->getNextAvailableDate();
                                                $displayTime = $nextDate ? $nextDate->copy()->setTimeFromTimeString($schedule->departure_time->format('H:i:s')) : $schedule->departure_time;
                                                echo $displayTime->format('H:i');
                                            @endphp
                                        @else
                                            {{ $schedule->departure_time->format('H:i') }}
                                        @endif
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        @if($schedule->is_weekly && $schedule->day_of_week !== null)
                                            @php
                                                $nextDate = $schedule->getNextAvailableDate();
                                                echo $nextDate ? $nextDate->format('l, F j') : $schedule->departure_time->format('l, F j');
                                            @endphp
                                        @else
                                            {{ $schedule->departure_time->format('l, F j') }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap" data-label="Arrival">
                            <div class="flex items-center">
                                <div class="bg-green-100 p-2 rounded-full mr-3">
                                    <i class="fas fa-sign-in-alt text-green-600"></i>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $schedule->arrival_time->format('H:i') }}</div>
                                    <div class="text-sm text-gray-500">{{ $schedule->arrival_time->format('l, F j') }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap" data-label="Bus Type">
                            <div class="flex items-center">
                                <div class="bg-green-100 p-2 rounded-full mr-3">
                                    <i class="fas fa-bus text-green-600"></i>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $schedule->bus->name ?? $schedule->bus->bus_type ?? 'Standard' }}</div>
                                    <div class="text-sm text-gray-500">{{ $schedule->bus->plate_number }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap" data-label="Availability">
                            @if($schedule->hasDeparted())
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    <i class="fas fa-times-circle mr-1"></i>Departed
                                </span>
                            @else
                                <div class="text-sm text-gray-900">{{ $schedule->getAvailableSeatsCount() }} / {{ $schedule->bus->capacity }} seats</div>
                                <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                                    <div class="bg-green-600 h-2 rounded-full" style="width: {{ ($schedule->getAvailableSeatsCount() / max(1, $schedule->bus->capacity)) * 100 }}%"></div>
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap" data-label="Price">
                            <div class="text-lg font-bold text-gray-900">Rp. {{ number_format($schedule->price, 0, ',', '.') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium" data-label="Action">
                            @if($schedule->hasDeparted())
                                <span class="inline-flex items-center px-3 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-gray-700 cursor-not-allowed">
                                    Departed
                                </span>
                            @elseif($schedule->getAvailableSeatsCount() > 0 && $schedule->isAvailableForBooking())
                                <a href="{{ route('frontend.booking.show', $schedule->id) }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-700 border border-transparent rounded-md font-semibold text-white hover:from-green-700 hover:to-emerald-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 shadow-sm transition duration-300 transform hover:scale-105">
                                    <i class="fas fa-ticket-alt mr-1"></i>Book Now
                                </a>
                            @elseif($schedule->getAvailableSeatsCount() == 0)
                                <span class="inline-flex items-center px-3 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-gray-700 cursor-not-allowed">
                                    Full
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-gray-700 cursor-not-allowed">
                                    Not Available
                                </span>
                            @endif
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
    
    <!-- Info about schedule reset -->
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl shadow-md p-4 mb-8">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <i class="fas fa-info-circle text-blue-500 text-xl"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm text-blue-700">
                    <strong>Schedule Information:</strong> Daily schedules reset automatically each day. Weekly schedules repeat on their designated days. 
                    Once a bus has departed, tickets can no longer be purchased for that schedule.
                </p>
            </div>
        </div>
    </div>
    
    <!-- Back to Routes -->
    <div class="mb-10">
        <a href="{{ route('frontend.routes.index') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-gray-600 to-gray-700 hover:from-gray-700 hover:to-gray-800 text-white font-bold rounded-lg transition duration-300 shadow-lg transform hover:scale-105 mobile-action-button">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Routes
        </a>
    </div>
</div>

@endsection

@section('scripts')
@if($route->origin_lat && $route->origin_lng && $route->destination_lat && $route->destination_lng)
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize the map with zoom disabled
        var map = L.map('route-map', {
            center: [{{ $route->origin_lat }}, {{ $route->origin_lng }}],
            zoom: 6,
            zoomControl: false,
            scrollWheelZoom: false,
            dragging: true,
            touchZoom: false,
            doubleClickZoom: false,
            boxZoom: false,
            keyboard: false
        });

        // Add OpenStreetMap tiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
            maxZoom: 6,
            minZoom: 6
        }).addTo(map);

        // Parse waypoints if they exist
        var waypoints = [];
        if ({{ $route->waypoints ? 'true' : 'false' }}) {
            try {
                waypoints = @json($route->waypoints);
            } catch (e) {
                console.error('Error parsing waypoints:', e);
            }
        }

        // Add marker for origin
        var originLatLng = L.latLng({{ $route->origin_lat }}, {{ $route->origin_lng }});
        var originMarker = L.marker(originLatLng, {
            title: "{{ $route->origin }} (Origin)"
        }).addTo(map);
        originMarker.bindPopup("<b>{{ $route->origin }}</b><br>Origin").openPopup();

        // Add marker for destination
        var destinationLatLng = L.latLng({{ $route->destination_lat }}, {{ $route->destination_lng }});
        var destinationMarker = L.marker(destinationLatLng, {
            title: "{{ $route->destination }} (Destination)"
        }).addTo(map);
        destinationMarker.bindPopup("<b>{{ $route->destination }}</b><br>Destination");

        // Create polyline from waypoints
        var routeCoordinates = [];
        
        // Add origin
        routeCoordinates.push(originLatLng);
        
        // Add waypoints if available
        if (waypoints && Array.isArray(waypoints)) {
            waypoints.forEach(function(waypoint) {
                if (waypoint.lat && waypoint.lng) {
                    routeCoordinates.push(L.latLng(waypoint.lat, waypoint.lng));
                }
            });
        }
        
        // Add destination
        routeCoordinates.push(destinationLatLng);

        var routeLine = L.polyline(routeCoordinates, {
            color: 'blue',
            weight: 4,
            opacity: 0.7,
            smoothFactor: 1
        }).addTo(map);

        // Fit the map to the route bounds with padding
        var bounds = L.latLngBounds(routeCoordinates);
        map.fitBounds(bounds, {padding: [50, 50]});

        // Add a legend
        var legend = L.control({position: 'bottomright'});

        legend.onAdd = function(map) {
            var div = L.DomUtil.create('div', 'info legend');
            div.style.backgroundColor = 'rgba(255, 255, 255, 0.9)';
            div.style.padding = '6px';
            div.style.borderRadius = '3px';
            div.style.boxShadow = '0 0 8px rgba(0,0,0,0.2)';
            div.style.marginBottom = '10px';
            div.style.fontSize = '9px';
            div.style.lineHeight = '1.2';
            div.innerHTML = `
                <h4 style="margin: 0 0 4px 0; font-weight: bold; font-size: 11px;">Route</h4>
                <div style="display: flex; align-items: center; margin-bottom: 3px;">
                    <div style="width: 12px; height: 2px; background: blue; margin-right: 4px;"></div>
                    <span>Path</span>
                </div>
                <div style="display: flex; align-items: center; margin-bottom: 3px;">
                    <div style="width: 8px; height: 8px; background: red; border-radius: 50%; margin-right: 4px;"></div>
                    <span>Start</span>
                </div>
                <div style="display: flex; align-items: center;">
                    <div style="width: 8px; height: 8px; background: green; border-radius: 50%; margin-right: 4px;"></div>
                    <span>End</span>
                </div>
            `;
            return div;
        };

        legend.addTo(map);
    });
</script>

<style>
    #route-map {
        cursor: grab;
    }
    
    #route-map:active {
        cursor: grabbing;
    }
    
    .info.legend {
        background: rgba(255, 255, 255, 0.9);
        padding: 6px;
        border-radius: 3px;
        box-shadow: 0 0 8px rgba(0,0,0,0.2);
        line-height: 1.2;
        font-size: 9px;
        margin-bottom: 10px;
    }
    
    .info.legend h4 {
        margin: 0 0 4px 0;
        font-weight: bold;
        font-size: 11px;
    }
    
    /* Ensure map is properly sized */
    .leaflet-container {
        height: 100%;
        width: 100%;
    }
    
    /* Mobile responsive fixes */
    @media (max-width: 768px) {
        #route-map {
            height: 300px !important;
        }
        
        .info.legend {
            font-size: 8px;
            padding: 5px;
            border-radius: 2px;
            box-shadow: 0 0 6px rgba(0,0,0,0.2);
            margin-bottom: 8px;
        }
        
        .info.legend h4 {
            font-size: 10px;
            margin-bottom: 3px;
        }
    }
    
    @media (max-width: 480px) {
        #route-map {
            height: 250px !important;
        }
        
        .info.legend {
            font-size: 8px;
            padding: 5px;
            border-radius: 3px;
            boxShadow: 0 0 6px rgba(0,0,0,0.2);
            margin-bottom: 10px;
        }
        
        .info.legend h4 {
            font-size: 10px;
            margin-bottom: 3px;
        }
    }
</style>
@endif
@endsection