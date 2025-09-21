@extends('frontend.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 page-spacing">
    <!-- Header -->
    <div class="text-center mb-8">
        <h1 class="text-4xl font-bold text-gray-800 mb-3">Booking Details</h1>
        <p class="text-lg text-gray-600">Complete your booking information to secure your seat</p>
    </div>
    
    <!-- Check if schedule has departed -->
    @if($schedule->hasDeparted())
    <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded mb-8">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-triangle text-red-500"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm text-red-700">
                    <strong>This schedule has already departed.</strong> 
                    Bookings cannot be made for schedules that have already departed. 
                    Please select another schedule.
                </p>
            </div>
        </div>
    </div>
    
    <div class="text-center">
        <a href="{{ route('frontend.booking.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition duration-300 shadow-lg transform hover:scale-105">
            <i class="fas fa-arrow-left mr-2"></i>Back to Schedule Selection
        </a>
    </div>
    
    @else
    
    @if($schedule->is_weekly && $schedule->day_of_week !== null)
        @php
            $nextDate = $schedule->is_weekly && $schedule->day_of_week !== null ? $schedule->getNextAvailableDate() : null;
        @endphp
        @if($nextDate)
            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded mb-8">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-info-circle text-blue-500"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-blue-700">
                            <strong>Next Available:</strong> This weekly schedule is next available on {{ $nextDate->format('l, F j, Y') }}.
                        </p>
                    </div>
                </div>
            </div>
        @endif
    @endif
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Schedule Information -->
        <div class="lg:col-span-2">
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl shadow-lg p-6 mb-8 mobile-info-card">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">Schedule Information</h2>
                    <div class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">
                        <i class="fas fa-clock mr-1"></i>{{ $schedule->route->formatted_duration }}
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-white p-5 rounded-lg shadow-sm mobile-info-card">
                        <h3 class="text-lg font-medium mb-4 text-gray-800 border-b border-gray-200 pb-2 mobile-info-card-title">Route Details</h3>
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <div class="bg-blue-100 p-2 rounded-full mr-3">
                                    <i class="fas fa-map-marker-alt text-blue-600"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Origin</p>
                                    <p class="font-medium">{{ $schedule->route->origin }}</p>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <div class="bg-blue-100 p-2 rounded-full mr-3">
                                    <i class="fas fa-map-pin text-blue-600"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Destination</p>
                                    <p class="font-medium">{{ $schedule->route->destination }}</p>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <div class="bg-blue-100 p-2 rounded-full mr-3">
                                    <i class="fas fa-road text-blue-600"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Distance</p>
                                    <p class="font-medium">{{ $schedule->route->distance }} km</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white p-5 rounded-lg shadow-sm mobile-info-card">
                        <h3 class="text-lg font-medium mb-4 text-gray-800 border-b border-gray-200 pb-2 mobile-info-card-title">Schedule Details</h3>
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <div class="bg-blue-100 p-2 rounded-full mr-3">
                                    <i class="fas fa-calendar-alt text-blue-600"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Date</p>
                                    <p class="font-medium">
                                        {{ $schedule->getDepartureTimeWIB()->format('d M Y') }}
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <div class="bg-blue-100 p-2 rounded-full mr-3">
                                    <i class="fas fa-sign-out-alt text-blue-600"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Departure</p>
                                    <p class="font-medium">
                                        {{ $schedule->getDepartureTimeWIB()->format('H:i') }}
                                        <span class="text-xs text-gray-500 ml-1">(WIB)</span>
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <div class="bg-blue-100 p-2 rounded-full mr-3">
                                    <i class="fas fa-sign-in-alt text-blue-600"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Arrival</p>
                                    <p class="font-medium">
                                        {{ $schedule->getArrivalTimeWIB()->format('H:i') }}
                                        <span class="text-xs text-gray-500 ml-1">(WIB)</span>
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <div class="bg-blue-100 p-2 rounded-full mr-3">
                                    <i class="fas fa-bus text-blue-600"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Bus Type</p>
                                    <p class="font-medium">{{ $schedule->bus->bus_type ?? 'Standard' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-6 bg-white p-5 rounded-lg shadow-sm mobile-info-card">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm text-gray-500">Available Seats</p>
                            <p class="text-xl font-bold text-gray-800">{{ $schedule->getAvailableSeatsCount() }} / {{ $schedule->bus->capacity }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Price per seat</p>
                            <p class="text-2xl font-bold text-blue-600">Rp. {{ number_format($schedule->price, 0, ',', '.') }}</p>
                        </div>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3 mt-3">
                        <div class="bg-blue-600 h-3 rounded-full" style="width: {{ ($schedule->getAvailableSeatsCount() / $schedule->bus->capacity) * 100 }}%"></div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Passenger Information -->
        <div>
            <div class="bg-white rounded-xl shadow-lg p-6 sticky top-6 mobile-booking-card">
                <h2 class="text-2xl font-bold mb-6 text-gray-800 mobile-info-card-title">Passenger Information</h2>
                <form method="POST" action="{{ route('frontend.booking.store') }}" class="space-y-5">
                    @csrf
                    <input type="hidden" name="schedule_id" value="{{ $schedule->id }}">
                    
                    <div class="mobile-form-group">
                        <label for="passenger_name" class="block text-sm font-medium text-gray-700 mb-1 mobile-form-label">Full Name</label>
                        <input type="text" id="passenger_name" name="passenger_name" required class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 py-3 px-4 mobile-form-control">
                    </div>
                    
                    <div class="mobile-form-group">
                        <label for="passenger_email" class="block text-sm font-medium text-gray-700 mb-1 mobile-form-label">Email Address</label>
                        <input type="email" id="passenger_email" name="passenger_email" required class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 py-3 px-4 mobile-form-control">
                    </div>
                    
                    <div class="mobile-form-group">
                        <label for="passenger_phone" class="block text-sm font-medium text-gray-700 mb-1 mobile-form-label">Phone Number</label>
                        <input type="tel" id="passenger_phone" name="passenger_phone" required class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 py-3 px-4 mobile-form-control">
                    </div>
                    
                    <div class="mobile-form-group">
                        <label for="number_of_seats" class="block text-sm font-medium text-gray-700 mb-1 mobile-form-label">Number of Seats</label>
                        <select id="number_of_seats" name="number_of_seats" required class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 py-3 px-4 mobile-select">
                            <option value="1">1 Seat (Rp. {{ number_format($schedule->price, 0, ',', '.') }})</option>
                            <option value="2">2 Seats (Rp. {{ number_format($schedule->price * 2, 0, ',', '.') }})</option>
                            <option value="3">3 Seats (Rp. {{ number_format($schedule->price * 3, 0, ',', '.') }})</option>
                            <option value="4">4 Seats (Rp. {{ number_format($schedule->price * 4, 0, ',', '.') }})</option>
                            <option value="5">5 Seats (Rp. {{ number_format($schedule->price * 5, 0, ',', '.') }})</option>
                        </select>
                    </div>
                    
                    <div class="flex items-start">
                        <input type="checkbox" id="terms" name="terms" required class="mt-1 rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <label for="terms" class="ml-2 block text-sm text-gray-700">
                            I agree to the <a href="#" class="text-blue-600 hover:underline">Terms and Conditions</a> and <a href="#" class="text-blue-600 hover:underline">Privacy Policy</a>
                        </label>
                    </div>
                    
                    <div>
                        <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800 text-white font-bold py-4 px-4 rounded-lg transition duration-300 transform hover:scale-105 shadow-lg text-lg mobile-btn-full">
                            <i class="fas fa-credit-card mr-2"></i>Proceed to Payment
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    @endif
    
</div>
@endsection