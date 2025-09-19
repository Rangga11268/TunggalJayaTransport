@extends('frontend.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 page-spacing">
    <!-- Header -->
    <div class="text-center mb-10">
        <h1 class="text-4xl font-bold text-gray-800 mb-3">Our Routes</h1>
        <p class="text-lg text-gray-600 max-w-2xl mx-auto">Explore our extensive network of routes connecting cities and towns across the region</p>
    </div>
    
    <!-- Info about schedule reset -->
    <div class="mobile-info-section">
        <div class="mobile-info-section-content">
            <div class="mobile-info-section-icon">
                <i class="fas fa-info-circle"></i>
            </div>
            <div class="mobile-info-section-text">
                <div class="mobile-info-section-title">Route Information</div>
                <div class="mobile-info-section-description">
                    Our routes operate with daily and weekly schedules. 
                    Daily schedules reset automatically each day, while weekly schedules repeat on their designated days.
                </div>
            </div>
        </div>
    </div>
    
    <!-- Interactive Map -->
    <div class="mobile-route-map-container">
        <div class="mobile-route-map-header">
            <div class="flex justify-between items-center mb-6 flex-col md:flex-row gap-4">
                <h2 class="text-2xl font-bold text-gray-800">Route Network Map</h2>
                <div class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">
                    <i class="fas fa-map-marked-alt mr-1"></i>{{ $routes->count() }} Routes
                </div>
            </div>
        </div>
        <div class="mobile-route-map-content">
            <div id="routes-map" style="height: 500px; width: 100%; border-radius: 0.5rem; z-index: 10;"></div>
            <div class="mt-4 text-sm text-gray-600">
                <p><i class="fas fa-mouse-pointer mr-2"></i>Click and drag to pan the map</p>
            </div>
        </div>
    </div>

    <!-- Route List -->
    <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl shadow-lg p-6">
        <div class="flex justify-between items-center mb-6 flex-col md:flex-row gap-4">
            <h2 class="text-2xl font-bold text-gray-800">All Routes</h2>
            <div class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">
                <i class="fas fa-route mr-1"></i>{{ $routes->count() }} Routes
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($routes as $route)
            <div class="bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 overflow-hidden mobile-info-card">
                <div class="bg-gradient-to-r from-green-500 to-emerald-600 p-4">
                    <div class="flex justify-between items-center flex-col gap-2">
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
                                <p class="font-medium">{{ $route->formatted_duration }}</p>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('frontend.routes.show', $route->id) }}" class="w-full bg-gradient-to-r from-green-600 to-emerald-700 hover:from-green-700 hover:to-emerald-800 text-white font-bold py-3 px-4 rounded-lg transition duration-300 transform hover:scale-105 shadow-md flex items-center justify-center mobile-btn-full">
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

@section('scripts')
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Check if we have routes with coordinates
        var routesWithCoordinates = @json($routes->filter(function($route) {
            return $route->origin_lat && $route->origin_lng && $route->destination_lat && $route->destination_lng;
        }));
        
        if (routesWithCoordinates.length > 0) {
            // Initialize the map with zoom disabled
            var map = L.map('routes-map', {
                center: [-4.0, 106.0], // Center of all routes
                zoom: 5,
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
                maxZoom: 5,
                minZoom: 5
            }).addTo(map);

            // Bounds to fit all routes
            var bounds = L.latLngBounds();

            // Add routes to the map
            routesWithCoordinates.forEach(function(route) {
                // Parse waypoints if they exist
                var waypoints = [];
                if (route.waypoints) {
                    try {
                        waypoints = typeof route.waypoints === 'string' ? JSON.parse(route.waypoints) : route.waypoints;
                    } catch (e) {
                        console.error('Error parsing waypoints for route:', route.id, e);
                    }
                }

                // Add markers for origin and destination
                var originLatLng = L.latLng(route.origin_lat, route.origin_lng);
                var destinationLatLng = L.latLng(route.destination_lat, route.destination_lng);
                
                bounds.extend(originLatLng);
                bounds.extend(destinationLatLng);

                var originMarker = L.marker(originLatLng, {
                    title: route.origin + " (Origin)"
                }).addTo(map);
                originMarker.bindPopup("<b>" + route.origin + "</b><br>Origin<br><a href='/routes/" + route.id + "'>View Details</a>", {
                    autoClose: false,
                    closeOnClick: false
                });

                var destinationMarker = L.marker(destinationLatLng, {
                    title: route.destination + " (Destination)"
                }).addTo(map);
                destinationMarker.bindPopup("<b>" + route.destination + "</b><br>Destination<br><a href='/routes/" + route.id + "'>View Details</a>", {
                    autoClose: false,
                    closeOnClick: false
                });

                // Create polyline from waypoints if available
                if (waypoints && waypoints.length > 0) {
                    var routeCoordinates = [];
                    
                    // Add origin
                    routeCoordinates.push(originLatLng);
                    
                    // Add waypoints
                    waypoints.forEach(function(waypoint) {
                        if (waypoint.lat && waypoint.lng) {
                            var waypointLatLng = L.latLng(waypoint.lat, waypoint.lng);
                            routeCoordinates.push(waypointLatLng);
                            bounds.extend(waypointLatLng);
                        }
                    });
                    
                    // Add destination
                    routeCoordinates.push(destinationLatLng);

                    var routeLine = L.polyline(routeCoordinates, {
                        color: getRandomColor(),
                        weight: 4,
                        opacity: 0.7,
                        smoothFactor: 1
                    }).addTo(map);
                } else {
                    // Simple line from origin to destination if no waypoints
                    var routeLine = L.polyline([
                        originLatLng,
                        destinationLatLng
                    ], {
                        color: getRandomColor(),
                        weight: 4,
                        opacity: 0.7
                    }).addTo(map);
                }
            });

            // Function to generate random colors for different routes
            function getRandomColor() {
                var colors = ['#3b82f6', '#10b981', '#8b5cf6', '#f59e0b', '#ef4444', '#06b6d4', '#ec4899', '#6366f1'];
                return colors[Math.floor(Math.random() * colors.length)];
            }

            // Fit map to bounds with padding
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
                    <h4 style="margin: 0 0 4px 0; font-weight: bold; font-size: 11px;">Routes</h4>
                    <div style="display: flex; align-items: center; margin-bottom: 3px;">
                        <div style="width: 12px; height: 2px; background: #3b82f6; margin-right: 4px;"></div>
                        <span>Routes</span>
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
            
        } else {
            // Fallback if no routes have coordinates
            document.getElementById('routes-map').innerHTML = `
                <div class="bg-gradient-to-r from-gray-200 to-gray-300 rounded-xl w-full h-full flex items-center justify-center">
                    <div class="text-center">
                        <i class="fas fa-map-marked-alt text-gray-500 text-6xl mb-4"></i>
                        <p class="text-2xl text-gray-500 font-bold">Interactive Map</p>
                        <p class="text-gray-600 mt-2">Route visualization data is being prepared</p>
                    </div>
                </div>
            `;
        }
    });
</script>

<style>
    #routes-map {
        cursor: grab;
    }
    
    #routes-map:active {
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
        #routes-map {
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
        #routes-map {
            height: 250px !important;
        }
        
        .info.legend {
            font-size: 7px;
            padding: 4px;
            border-radius: 2px;
            boxShadow: 0 0 6px rgba(0,0,0,0.2);
            margin-bottom: 6px;
        }
        
        .info.legend h4 {
            font-size: 9px;
            margin-bottom: 2px;
        }
    }
</style>
@endsection