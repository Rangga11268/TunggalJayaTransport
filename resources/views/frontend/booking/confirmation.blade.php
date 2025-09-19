@extends('frontend.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 page-spacing">
    <!-- Header -->
    <div class="text-center mb-8">
        <h1 class="text-4xl font-bold text-gray-800 mb-3">Booking Confirmation</h1>
        <p class="text-lg text-gray-600">Please select your seats to complete the booking</p>
    </div>
    
    <!-- Departure Reminder -->
    @php
        $departureTime = $booking->schedule->departure_time;
        $hoursUntilDeparture = now()->diffInHours($departureTime, false);
    @endphp
    
    @if($hoursUntilDeparture >= 0 && $hoursUntilDeparture <= 24)
        <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded mb-8">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-triangle text-yellow-500"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-yellow-700">
                        <strong>Reminder:</strong> Your bus departs in {{ $hoursUntilDeparture }} hours! 
                        Please arrive at the terminal at least 30 minutes before departure.
                    </p>
                </div>
            </div>
        </div>
    @endif
    
    @if($departureTime->isPast())
        <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded mb-8">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-triangle text-red-500"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-red-700">
                        <strong>Warning:</strong> This schedule has already departed. 
                        Please contact customer service for assistance.
                    </p>
                </div>
            </div>
        </div>
    @endif
    
    <!-- Booking Summary -->
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl shadow-lg p-6 mb-8 mobile-booking-summary">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Booking Details</h2>
            <div class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">
                <i class="fas fa-receipt mr-1"></i>Booking #{{ $booking->booking_code }}
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="bg-white p-6 rounded-lg shadow-sm mobile-info-card">
                <h3 class="text-lg font-medium mb-4 text-gray-800 border-b border-gray-200 pb-2 mobile-info-card-title">Route Information</h3>
                <div class="space-y-4">
                    <div class="flex items-center">
                        <div class="bg-blue-100 p-3 rounded-full mr-4">
                            <i class="fas fa-route text-blue-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Route</p>
                            <p class="font-medium text-lg">{{ $booking->schedule->route->origin }} <i class="fas fa-arrow-right mx-2 text-blue-500"></i> {{ $booking->schedule->route->destination }}</p>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <div class="bg-blue-100 p-3 rounded-full mr-4">
                            <i class="fas fa-calendar-alt text-blue-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Date</p>
                            <p class="font-medium">{{ $booking->schedule->departure_time->format('l, F j, Y') }}</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="flex items-center">
                            <div class="bg-blue-100 p-2 rounded-full mr-3">
                                <i class="fas fa-sign-out-alt text-blue-600"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Departure</p>
                                <p class="font-medium">{{ $booking->schedule->departure_time->format('H:i') }}</p>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <div class="bg-blue-100 p-2 rounded-full mr-3">
                                <i class="fas fa-sign-in-alt text-blue-600"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Arrival</p>
                                <p class="font-medium">{{ $booking->schedule->arrival_time->format('H:i') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <div class="bg-blue-100 p-2 rounded-full mr-3">
                            <i class="fas fa-bus text-blue-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Bus Type</p>
                            <p class="font-medium">{{ $booking->schedule->bus->bus_type ?? 'Standard' }}</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-white p-6 rounded-lg shadow-sm mobile-info-card">
                <h3 class="text-lg font-medium mb-4 text-gray-800 border-b border-gray-200 pb-2 mobile-info-card-title">Passenger Information</h3>
                <div class="space-y-4">
                    <div class="flex items-center">
                        <div class="bg-blue-100 p-3 rounded-full mr-4">
                            <i class="fas fa-user text-blue-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Name</p>
                            <p class="font-medium text-lg">{{ $booking->passenger_name }}</p>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <div class="bg-blue-100 p-3 rounded-full mr-4">
                            <i class="fas fa-envelope text-blue-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Email</p>
                            <p class="font-medium">{{ $booking->passenger_email }}</p>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <div class="bg-blue-100 p-3 rounded-full mr-4">
                            <i class="fas fa-phone text-blue-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Phone</p>
                            <p class="font-medium">{{ $booking->passenger_phone }}</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="flex items-center">
                            <div class="bg-blue-100 p-2 rounded-full mr-3">
                                <i class="fas fa-chair text-blue-600"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Seats</p>
                                <p class="font-medium">{{ $booking->number_of_seats }}</p>
                            </div>
                        </div>
                        @if($booking->seat_numbers)
                        <div class="flex items-center">
                            <div class="bg-blue-100 p-2 rounded-full mr-3">
                                <i class="fas fa-hashtag text-blue-600"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Seat Numbers</p>
                                <p class="font-medium">{{ $booking->seat_numbers }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <div class="mt-8 pt-6 border-t border-gray-200 bg-white p-6 rounded-lg shadow-sm mobile-info-card">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-gray-600">Total Price ({{ $booking->number_of_seats }} seats)</p>
                    <p class="text-sm text-gray-500">+ Service Fee: Rp. 5,000</p>
                </div>
                <div class="text-right">
                    <p class="text-3xl font-bold text-blue-600">Rp. {{ number_format($booking->total_price + 5000, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Options -->
    <div class="bg-white rounded-xl shadow-lg p-4 sm:p-6 mb-8 mobile-booking-card">
        <h2 class="text-2xl font-bold text-gray-800 mb-6 mobile-info-card-title">Payment Method</h2>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mobile-payment-options">
            <div class="border-2 border-gray-200 rounded-lg p-4 sm:p-5 cursor-pointer hover:border-blue-500 payment-method transition duration-200 hover:shadow-md" data-method="credit_card">
                <div class="flex items-center">
                    <input type="radio" id="credit-card" name="payment" class="h-5 w-5 text-blue-600">
                    <label for="credit-card" class="ml-3 block text-base sm:text-lg font-medium text-gray-700">Credit/Debit Card</label>
                </div>
                <div class="mt-4 flex space-x-2">
                    <div class="bg-gray-200 border-2 border-dashed rounded-xl w-8 h-5 sm:w-10 sm:h-6"></div>
                    <div class="bg-gray-200 border-2 border-dashed rounded-xl w-8 h-5 sm:w-10 sm:h-6"></div>
                    <div class="bg-gray-200 border-2 border-dashed rounded-xl w-8 h-5 sm:w-10 sm:h-6"></div>
                </div>
            </div>
            <div class="border-2 border-gray-200 rounded-lg p-4 sm:p-5 cursor-pointer hover:border-blue-500 payment-method transition duration-200 hover:shadow-md" data-method="bank_transfer">
                <div class="flex items-center">
                    <input type="radio" id="bank-transfer" name="payment" class="h-5 w-5 text-blue-600">
                    <label for="bank-transfer" class="ml-3 block text-base sm:text-lg font-medium text-gray-700">Bank Transfer</label>
                </div>
                <div class="mt-4 flex space-x-2">
                    <div class="bg-gray-200 border-2 border-dashed rounded-xl w-10 h-6"></div>
                    <div class="bg-gray-200 border-2 border-dashed rounded-xl w-10 h-6"></div>
                    <div class="bg-gray-200 border-2 border-dashed rounded-xl w-10 h-6"></div>
                </div>
            </div>
            <div class="border-2 border-gray-200 rounded-lg p-4 sm:p-5 cursor-pointer hover:border-blue-500 payment-method transition duration-200 hover:shadow-md" data-method="e_wallet">
                <div class="flex items-center">
                    <input type="radio" id="e-wallet" name="payment" class="h-5 w-5 text-blue-600">
                    <label for="e-wallet" class="ml-3 block text-base sm:text-lg font-medium text-gray-700">E-Wallet</label>
                </div>
                <div class="mt-4 flex space-x-2">
                    <div class="bg-gray-200 border-2 border-dashed rounded-xl w-8 h-5 sm:w-10 sm:h-6"></div>
                    <div class="bg-gray-200 border-2 border-dashed rounded-xl w-8 h-5 sm:w-10 sm:h-6"></div>
                    <div class="bg-gray-200 border-2 border-dashed rounded-xl w-8 h-5 sm:w-10 sm:h-6"></div>
                </div>
            </div>
        </div>
        
        <div class="mt-6 sm:mt-8">
            <button id="pay-button" class="w-full bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800 text-white font-bold py-3 px-4 rounded-lg transition duration-300 transform hover:scale-105 shadow-lg text-base mobile-btn-full flex items-center justify-center">
                <i class="fas fa-lock mr-2"></i> Proceed to Secure Payment
            </button>
        </div>
    </div>

    <!-- Seat Selection -->
    <div class=\"bg-white rounded-xl shadow-lg p-4 sm:p-6 mb-8 mobile-booking-card\">
        <h2 class=\"text-xl sm:text-2xl font-bold text-gray-800 mb-4 sm:mb-6 flex items-center mobile-info-card-title\">
            <i class=\"fas fa-bus mr-2 sm:mr-3 text-blue-500\"></i> Select Your Seat (2-3 Layout)
        </h2>
        
        <!-- Bus Visualization -->
        <div class=\"bus-layout mb-6 sm:mb-8\">
            <div class=\"bus-driver-area text-center mb-4 sm:mb-6\">
                <div class=\"driver-seat bg-gradient-to-r from-gray-800 to-gray-900 text-white px-4 sm:px-8 py-2 sm:py-3 rounded-t-xl inline-block font-bold shadow-lg text-sm sm:text-base\">
                    <i class=\"fas fa-steering-wheel mr-1 sm:mr-2\"></i>Driver
                </div>
            </div>
            
            <div class="bus-seats-area">
                <!-- Aisle Separator (main) -->
                <div class="aisle-separator my-4 sm:my-6 flex justify-center">
                    <div class="w-4/5 h-1 bg-gradient-to-r from-transparent via-gray-400 to-transparent rounded-full"></div>
                </div>
                
                <!-- Seat Rows (based on bus capacity) -->
                <div class="seat-layout flex flex-col items-center">
                    @php
                        $totalSeats = $booking->schedule->bus->capacity;
                        // Calculate rows needed (2+3 seats per row = 5 seats per row)
                        $rows = ceil($totalSeats / 5);
                    @endphp
                    
                    @for ($row = 0; $row < $rows; $row++)
                        <div class="seat-row flex items-center justify-center mb-4 sm:mb-6 w-full max-w-lg bg-gray-50 p-3 sm:p-4 rounded-xl shadow-sm">
                            <!-- Left side (2 seats) -->
                            <div class="left-seats flex gap-2 sm:gap-4">
                                @for ($leftSeat = 0; $leftSeat < 2; $leftSeat++)
                                    @php
                                        $seatNumber = ($row * 5) + $leftSeat + 1;
                                        if ($seatNumber > $totalSeats) {
                                            break;
                                        }
                                        $isOccupied = in_array($seatNumber, $occupiedSeats ?? []);
                                        $isSelected = $booking->seat_numbers && in_array($seatNumber, explode(',', $booking->seat_numbers));
                                    @endphp
                                    <div 
                                        class="seat-item w-10 h-10 sm:w-14 sm:h-14 flex items-center justify-center rounded-xl cursor-pointer transition-all duration-300 transform hover:scale-110 shadow-lg relative mobile-seat-item
                                            {{ $isOccupied ? 'bg-red-100 cursor-not-allowed opacity-70' : ($isSelected ? 'bg-blue-100 ring-4 ring-blue-500' : 'bg-green-100 hover:bg-green-200') }}"
                                        data-seat="{{ $seatNumber }}"
                                        {{ $isOccupied ? 'title=This seat is already booked' : 'title=Select seat '.$seatNumber }}
                                    >
                                        <!-- Seat Image -->
                                        <div class="seat-image w-6 h-6 sm:w-10 sm:h-10 flex items-center justify-center {{ $isOccupied ? 'opacity-50' : ($isSelected ? 'filter brightness-75' : '') }} mobile-seat-image">
                                            <img src="{{ asset('img/car-seat.png') }}" alt="Seat" class="w-full h-full object-contain">
                                        </div>
                                        
                                        <!-- Seat Number -->
                                        <div class="seat-number absolute -bottom-1 -right-0 sm:-bottom-2 sm:-right-1 text-xs font-bold {{ $isOccupied ? 'text-red-700' : ($isSelected ? 'text-blue-700' : 'text-green-700') }}">
                                            {{ $seatNumber }}
                                        </div>
                                        
                                        @if($isOccupied)
                                            <div class="occupied-overlay absolute inset-0 bg-red-500 bg-opacity-70 rounded-xl flex items-center justify-center">
                                                <i class="fas fa-times text-white text-sm sm:text-lg"></i>
                                            </div>
                                        @endif
                                    </div>
                                @endfor
                            </div>
                            
                            <!-- Aisle separator -->
                            <div class="aisle w-4 sm:w-10 flex items-center justify-center mx-2 sm:mx-4">
                                <div class="w-full h-1 bg-gradient-to-r from-transparent via-gray-400 to-transparent rounded-full"></div>
                            </div>
                            
                            <!-- Right side (3 seats) -->
                            <div class="right-seats flex gap-2 sm:gap-4">
                                @for ($rightSeat = 0; $rightSeat < 3; $rightSeat++)
                                    @php
                                        $seatNumber = ($row * 5) + $rightSeat + 3;
                                        if ($seatNumber > $totalSeats) {
                                            break;
                                        }
                                        $isOccupied = in_array($seatNumber, $occupiedSeats ?? []);
                                        $isSelected = $booking->seat_numbers && in_array($seatNumber, explode(',', $booking->seat_numbers));
                                    @endphp
                                    <div 
                                        class="seat-item w-10 h-10 sm:w-14 sm:h-14 flex items-center justify-center rounded-xl cursor-pointer transition-all duration-300 transform hover:scale-110 shadow-lg relative mobile-seat-item
                                            {{ $isOccupied ? 'bg-red-100 cursor-not-allowed opacity-70' : ($isSelected ? 'bg-blue-100 ring-4 ring-blue-500' : 'bg-green-100 hover:bg-green-200') }}"
                                        data-seat="{{ $seatNumber }}"
                                        {{ $isOccupied ? 'title=This seat is already booked' : 'title=Select seat '.$seatNumber }}
                                    >
                                        <!-- Seat Image -->
                                        <div class="seat-image w-6 h-6 sm:w-10 sm:h-10 flex items-center justify-center {{ $isOccupied ? 'opacity-50' : ($isSelected ? 'filter brightness-75' : '') }} mobile-seat-image">
                                            <img src="{{ asset('img/car-seat.png') }}" alt="Seat" class="w-full h-full object-contain">
                                        </div>
                                        
                                        <!-- Seat Number -->
                                        <div class="seat-number absolute -bottom-1 -right-0 sm:-bottom-2 sm:-right-1 text-xs font-bold {{ $isOccupied ? 'text-red-700' : ($isSelected ? 'text-blue-700' : 'text-green-700') }}">
                                            {{ $seatNumber }}
                                        </div>
                                        
                                        @if($isOccupied)
                                            <div class="occupied-overlay absolute inset-0 bg-red-500 bg-opacity-70 rounded-xl flex items-center justify-center">
                                                <i class="fas fa-times text-white text-sm sm:text-lg"></i>
                                            </div>
                                        @endif
                                    </div>
                                @endfor
                            </div>
                        </div>
                        
                        @if (($row * 5) + 5 >= $totalSeats)
                            @php
                                break;
                            @endphp
                        @endif
                    @endfor
                </div>
            </div>
        </div>
        
        <!-- Seat Legend -->
        <div class="seat-legend flex flex-wrap justify-center gap-2 sm:gap-4 mb-6 sm:mb-8 mobile-legend">
            <div class="flex items-center bg-gray-50 px-2 py-1 sm:px-3 sm:py-2 rounded-lg shadow-sm">
                <div class="w-4 h-4 sm:w-5 sm:h-5 bg-green-100 rounded mr-1 sm:mr-2 flex items-center justify-center">
                    <img src="{{ asset('img/car-seat.png') }}" alt="Available Seat" class="w-3 h-3 sm:w-4 sm:h-4 object-contain">
                </div>
                <span class="text-xs sm:text-sm font-medium">Available</span>
            </div>
            <div class="flex items-center bg-gray-50 px-2 py-1 sm:px-3 sm:py-2 rounded-lg shadow-sm">
                <div class="w-4 h-4 sm:w-5 sm:h-5 bg-red-100 rounded mr-1 sm:mr-2 flex items-center justify-center">
                    <i class="fas fa-times text-red-500 text-xs sm:text-sm"></i>
                </div>
                <span class="text-xs sm:text-sm font-medium">Occupied</span>
            </div>
            <div class="flex items-center bg-gray-50 px-2 py-1 sm:px-3 sm:py-2 rounded-lg shadow-sm">
                <div class="w-4 h-4 sm:w-5 sm:h-5 bg-blue-100 rounded mr-1 sm:mr-2 flex items-center justify-center">
                    <img src="{{ asset('img/car-seat.png') }}" alt="Selected Seat" class="w-3 h-3 sm:w-4 sm:h-4 object-contain filter brightness-75">
                </div>
                <span class="text-xs sm:text-sm font-medium">Selected</span>
            </div>
            <div class="flex items-center bg-gray-50 px-2 py-1 sm:px-3 sm:py-2 rounded-lg shadow-sm">
                <div class="w-6 sm:w-8 h-1 flex items-center justify-center">
                    <div class="w-4 h-0.5 sm:h-1 bg-gradient-to-r from-transparent via-gray-400 to-transparent rounded-full"></div>
                </div>
                <span class="text-xs sm:text-sm font-medium ml-1 sm:ml-2">Aisle</span>
            </div>
        </div>
        
        <!-- Selected Seats Info -->
        <div class="selected-seats-info text-center mb-6 sm:mb-8 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-3 sm:p-4">
            <p class="text-gray-700 text-sm sm:text-base">
                Selected Seats: 
                <span id="selected-seats-display" class="font-bold text-blue-600 bg-white px-2 py-1 rounded-lg text-base sm:text-lg shadow-sm">
                    @if($booking->seat_numbers)
                        {{ $booking->seat_numbers }}
                    @else
                        None
                    @endif
                </span>
            </p>
            <p class="text-gray-600 mt-2 text-sm sm:text-base">
                <span id="selected-count" class="font-bold text-lg">{{ $booking->seat_numbers ? count(explode(',', $booking->seat_numbers)) : 0 }}</span> of {{ $booking->number_of_seats }} seats selected
            </p>
        </div>
        
        <!-- Action Buttons -->
        <div class="flex justify-center w-full mt-4">
            <button id="save-seats" class="save-seats-btn text-sm sm:text-base" title="Save your seat selection">
                <i class="fas fa-save mr-2"></i> Save Seats
            </button>
        </div>
    </div>
    
    <!-- Ticket Preview and Download -->
    <div class="bg-white rounded-xl shadow-lg p-4 sm:p-6 mobile-booking-card">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-4 sm:mb-6 gap-4">
            <div>
                <h2 class="text-xl sm:text-2xl font-bold text-gray-800">Ticket Preview</h2>
                <p class="text-gray-600 text-sm sm:text-base">Your ticket will be available after payment</p>
            </div>
            @if($booking->seat_numbers)
            <a href="{{ route('frontend.booking.download-ticket', $booking->id) }}" target="_blank" class="bg-gradient-to-r from-green-600 to-emerald-700 hover:from-green-700 hover:to-emerald-800 text-white font-bold py-2 px-4 sm:py-3 sm:px-6 rounded-lg inline-flex items-center transition duration-300 shadow-lg hover:shadow-xl transform hover:scale-105 mobile-action-button text-sm sm:text-base">
                <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                </svg>
                Download Ticket
            </a>
            @endif
        </div>
        
        @if($booking->seat_numbers)
            @include('frontend.booking.partials.ticket-preview', [
                'passengerName' => $booking->passenger_name,
                'passengerEmail' => $booking->passenger_email,
                'passengerPhone' => $booking->passenger_phone,
                'bookingCode' => $booking->booking_code,
                'bookingId' => $booking->id,
                'origin' => $booking->schedule->route->origin,
                'destination' => $booking->schedule->route->destination,
                'departureDate' => $booking->schedule->departure_time->format('d M Y'),
                'departureTime' => $booking->schedule->departure_time->format('H:i'),
                'arrivalTime' => $booking->schedule->arrival_time->format('H:i'),
                'seatNumber' => $booking->seat_numbers,
                'busType' => $booking->schedule->bus->bus_type ?? 'Standard',
                'price' => 'Rp. ' . number_format($booking->total_price, 0, ',', '.'),
            ])
        @else
            <div class="text-center py-12 bg-gray-50 rounded-xl">
                <div class="text-gray-400 text-5xl mb-4">
                    <i class="fas fa-ticket-alt"></i>
                </div>
                <p class="text-gray-600 text-lg">Please select and save your seat to view and download your ticket.</p>
                <p class="text-gray-500 mt-2">Your ticket will be available after payment is completed.</p>
            </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Seat selection functionality
    const seats = document.querySelectorAll('.seat-item:not(.bg-red-100)');
    const selectedSeatsDisplay = document.getElementById('selected-seats-display');
    const selectedCount = document.getElementById('selected-count');
    const saveSeatsButton = document.getElementById('save-seats');
    let selectedSeats = [];
    
    // Initialize with already selected seats
    @if($booking->seat_numbers)
        selectedSeats = "{{ $booking->seat_numbers }}".split(',').map(Number);
        selectedCount.textContent = selectedSeats.length;
        // Enable save button if correct number of seats are selected
        saveSeatsButton.disabled = selectedSeats.length !== {{ $booking->number_of_seats }};
        
        // Update the styling of already selected seats
        selectedSeats.forEach(seatNumber => {
            const seatElement = document.querySelector(`.seat-item[data-seat="${seatNumber}"]`);
            if (seatElement && !seatElement.classList.contains('bg-red-100')) {
                seatElement.classList.remove('bg-green-100');
                seatElement.classList.add('bg-blue-100', 'ring-4', 'ring-blue-500');
                // Add filter to seat image
                const seatImage = seatElement.querySelector('.seat-image');
                if (seatImage) {
                    seatImage.classList.add('filter', 'brightness-75');
                }
                // Update seat number color
                const seatNumberEl = seatElement.querySelector('.seat-number');
                if (seatNumberEl) {
                    seatNumberEl.classList.remove('text-green-700');
                    seatNumberEl.classList.add('text-blue-700');
                }
            }
        });
    @else
        saveSeatsButton.disabled = true;
    @endif
    
    seats.forEach(seat => {
        seat.addEventListener('click', function() {
            const seatNumber = parseInt(this.getAttribute('data-seat'));
            
            // Check if seat is already occupied
            if (this.classList.contains('bg-red-100')) {
                return; // Do nothing for occupied seats
            }
            
            // Toggle seat selection
            if (this.classList.contains('bg-blue-100')) {
                // Deselect seat
                this.classList.remove('bg-blue-100', 'ring-4', 'ring-blue-500');
                this.classList.add('bg-green-100');
                // Remove filter from seat image
                const seatImage = this.querySelector('.seat-image');
                if (seatImage) {
                    seatImage.classList.remove('filter', 'brightness-75');
                }
                // Update seat number color
                const seatNumberEl = this.querySelector('.seat-number');
                if (seatNumberEl) {
                    seatNumberEl.classList.remove('text-blue-700');
                    seatNumberEl.classList.add('text-green-700');
                }
                selectedSeats = selectedSeats.filter(num => num !== seatNumber);
            } else {
                // Select seat (limit to number of seats requested)
                if (selectedSeats.length < {{ $booking->number_of_seats }}) {
                    this.classList.remove('bg-green-100');
                    this.classList.add('bg-blue-100', 'ring-4', 'ring-blue-500');
                    // Add filter to seat image
                    const seatImage = this.querySelector('.seat-image');
                    if (seatImage) {
                        seatImage.classList.add('filter', 'brightness-75');
                    }
                    // Update seat number color
                    const seatNumberEl = this.querySelector('.seat-number');
                    if (seatNumberEl) {
                        seatNumberEl.classList.remove('text-green-700');
                        seatNumberEl.classList.add('text-blue-700');
                    }
                    selectedSeats.push(seatNumber);
                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Seat Limit Reached',
                        text: 'You can only select up to {{ $booking->number_of_seats }} seats.',
                        confirmButtonColor: '#3b82f6'
                    });
                    return;
                }
            }
            
            // Update display
            selectedSeatsDisplay.textContent = selectedSeats.length > 0 ? selectedSeats.join(', ') : 'None';
            selectedCount.textContent = selectedSeats.length;
            
            // Enable/disable save button
            saveSeatsButton.disabled = selectedSeats.length !== {{ $booking->number_of_seats }};
            
            // Add visual feedback for seat selection
            if (selectedSeats.length > 0) {
                selectedSeatsDisplay.classList.add('bg-blue-100', 'text-blue-800');
                selectedSeatsDisplay.classList.remove('text-gray-500');
            } else {
                selectedSeatsDisplay.classList.remove('bg-blue-100', 'text-blue-800');
                selectedSeatsDisplay.classList.add('text-gray-500');
            }
        });
    });
    
    // Save seat selection
    saveSeatsButton.addEventListener('click', function() {
        if (selectedSeats.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'No Seats Selected',
                text: 'Please select at least one seat.',
                confirmButtonColor: '#3b82f6'
            });
            return;
        }
        
        // Validate that the number of selected seats matches the requested number
        if (selectedSeats.length != {{ $booking->number_of_seats }}) {
            Swal.fire({
                icon: 'warning',
                title: 'Incorrect Number of Seats',
                text: 'Please select exactly {{ $booking->number_of_seats }} seats.',
                confirmButtonColor: '#3b82f6'
            });
            return;
        }
        
        // Show processing message
        const originalText = saveSeatsButton.innerHTML;
        saveSeatsButton.disabled = true;
        saveSeatsButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Saving...';
        
        // Send selected seats to backend
        fetch('{{ route("frontend.booking.select-seats") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                booking_id: {{ $booking->id }},
                seat_numbers: selectedSeats
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'Seat selection saved successfully!',
                    confirmButtonColor: '#3b82f6'
                }).then(() => {
                    // Reload the page to show updated seat selection
                    location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error: ' + data.message,
                    confirmButtonColor: '#3b82f6'
                });
                // Reset button
                saveSeatsButton.disabled = false;
                saveSeatsButton.innerHTML = originalText;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error saving seat selection. Please try again.',
                confirmButtonColor: '#3b82f6'
            });
            // Reset button
            saveSeatsButton.disabled = false;
            saveSeatsButton.innerHTML = originalText;
        });
    });
    
    // Payment method selection
    const paymentMethods = document.querySelectorAll('.payment-method');
    let selectedPaymentMethod = 'credit_card';
    
    paymentMethods.forEach(method => {
        method.addEventListener('click', function() {
            // Remove active class from all methods
            paymentMethods.forEach(m => m.classList.remove('border-blue-500'));
            
            // Add active class to clicked method
            this.classList.add('border-blue-500');
            
            // Update selected payment method
            selectedPaymentMethod = this.getAttribute('data-method');
        });
    });
    
    // Payment button functionality
    const payButton = document.getElementById('pay-button');
    payButton.addEventListener('click', function() {
        // Check if seats have been selected and saved
        if (selectedSeats.length !== {{ $booking->number_of_seats }}) {
            Swal.fire({
                icon: 'warning',
                title: 'Seat Selection Required',
                text: 'Please select and save exactly {{ $booking->number_of_seats }} seats before proceeding to payment.',
                confirmButtonColor: '#3b82f6'
            });
            return;
        }
        
        // Show processing message
        const originalText = payButton.innerHTML;
        payButton.disabled = true;
        payButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Processing...';
        
        // Process payment
        const data = {
            booking_id: {{ $booking->id }},
            payment_method: selectedPaymentMethod,
            _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        };
        
        fetch('{{ route("frontend.booking.process-payment") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok: ' + response.status);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Redirect to success page
                window.location.href = '{{ route("frontend.booking.success", ["id" => $booking->id]) }}';
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Payment Error',
                    text: 'Error: ' + data.message,
                    confirmButtonColor: '#3b82f6'
                });
                // Reset button
                payButton.disabled = false;
                payButton.innerHTML = originalText;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Payment Error',
                text: 'Error processing payment: ' + error.message + '. Please try again.',
                confirmButtonColor: '#3b82f6'
            });
            // Reset button
            payButton.disabled = false;
            payButton.innerHTML = originalText;
        });
    });
});
</script>
@endsection