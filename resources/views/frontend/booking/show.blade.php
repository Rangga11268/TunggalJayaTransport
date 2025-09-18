@extends('frontend.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="text-center mb-8">
        <h1 class="text-4xl font-bold text-gray-800 mb-3">Booking Details</h1>
        <p class="text-lg text-gray-600">Complete your booking information to secure your seat</p>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Schedule Information -->
        <div class="lg:col-span-2">
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl shadow-lg p-6 mb-8">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">Schedule Information</h2>
                    <div class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">
                        <i class="fas fa-clock mr-1"></i>{{ $schedule->route->duration }} hours
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-white p-5 rounded-lg shadow-sm">
                        <h3 class="text-lg font-medium mb-4 text-gray-800 border-b border-gray-200 pb-2">Route Details</h3>
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
                    
                    <div class="bg-white p-5 rounded-lg shadow-sm">
                        <h3 class="text-lg font-medium mb-4 text-gray-800 border-b border-gray-200 pb-2">Schedule Details</h3>
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <div class="bg-blue-100 p-2 rounded-full mr-3">
                                    <i class="fas fa-calendar-alt text-blue-600"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Date</p>
                                    <p class="font-medium">{{ $schedule->departure_time->format('d M Y') }}</p>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <div class="bg-blue-100 p-2 rounded-full mr-3">
                                    <i class="fas fa-sign-out-alt text-blue-600"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Departure</p>
                                    <p class="font-medium">{{ $schedule->departure_time->format('H:i') }}</p>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <div class="bg-blue-100 p-2 rounded-full mr-3">
                                    <i class="fas fa-sign-in-alt text-blue-600"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Arrival</p>
                                    <p class="font-medium">{{ $schedule->arrival_time->format('H:i') }}</p>
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
                
                <div class="mt-6 bg-white p-5 rounded-lg shadow-sm">
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
            <div class="bg-white rounded-xl shadow-lg p-6 sticky top-6">
                <h2 class="text-2xl font-bold mb-6 text-gray-800">Passenger Information</h2>
                <form method="POST" action="{{ route('frontend.booking.store') }}" class="space-y-5">
                    @csrf
                    <input type="hidden" name="schedule_id" value="{{ $schedule->id }}">
                    
                    <div>
                        <label for="passenger_name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                        <input type="text" id="passenger_name" name="passenger_name" required class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 py-3 px-4">
                    </div>
                    
                    <div>
                        <label for="passenger_email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                        <input type="email" id="passenger_email" name="passenger_email" required class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 py-3 px-4">
                    </div>
                    
                    <div>
                        <label for="passenger_phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                        <input type="tel" id="passenger_phone" name="passenger_phone" required class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 py-3 px-4">
                    </div>
                    
                    <div>
                        <label for="number_of_seats" class="block text-sm font-medium text-gray-700 mb-1">Number of Seats</label>
                        <select id="number_of_seats" name="number_of_seats" required class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 py-3 px-4">
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
                        <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800 text-white font-bold py-3 px-4 rounded-lg transition duration-300 transform hover:scale-105 shadow-lg">
                            <i class="fas fa-credit-card mr-2"></i>Proceed to Payment
                        </button>
                    </div>
                </form>
                
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <div class="flex justify-between mb-2">
                        <span class="text-gray-600">Subtotal:</span>
                        <span class="font-medium">Rp. {{ number_format($schedule->price, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between mb-2">
                        <span class="text-gray-600">Service Fee:</span>
                        <span class="font-medium">Rp. 5,000</span>
                    </div>
                    <div class="flex justify-between text-lg font-bold mt-3 pt-3 border-t border-gray-200">
                        <span>Total:</span>
                        <span class="text-blue-600">Rp. {{ number_format($schedule->price + 5000, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection