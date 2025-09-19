@extends('frontend.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 page-spacing">
    <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl shadow-lg p-4 sm:p-8 mb-6 sm:mb-10 text-center mobile-booking-card">
        <div class="text-green-500 text-5xl sm:text-7xl mb-4 sm:mb-6 animate-bounce">
            <i class="fas fa-check-circle"></i>
        </div>
        <h1 class="text-2xl sm:text-4xl font-bold text-gray-800 mb-3 sm:mb-4">Booking Successful!</h1>
        <p class="text-base sm:text-lg text-gray-600 mb-6 sm:mb-8 max-w-2xl mx-auto">Thank you for your booking. Your payment has been processed successfully. Your ticket is ready to use!</p>
        
        <div class="bg-white rounded-xl shadow-md p-4 sm:p-8 mb-6 sm:mb-8 text-left mobile-info-card">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-4 sm:mb-6 gap-3 sm:gap-0">
                <h2 class="text-xl sm:text-2xl font-bold text-gray-800">Booking Details</h2>
                <div class="bg-green-100 text-green-800 px-3 py-1 sm:px-4 sm:py-2 rounded-full font-bold text-sm sm:text-base inline-flex items-center">
                    <i class="fas fa-check mr-1 sm:mr-2"></i>Confirmed
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-8">
                <div class="bg-gray-50 p-4 sm:p-5 rounded-lg mobile-info-card">
                    <h3 class="text-base sm:text-lg font-medium mb-3 sm:mb-4 text-gray-800 border-b border-gray-200 pb-2 mobile-info-card-title">Trip Information</h3>
                    <div class="space-y-3">
                        <div class="flex items-center">
                            <div class="bg-green-100 p-2 rounded-full mr-3">
                                <i class="fas fa-hashtag text-green-600"></i>
                            </div>
                            <div>
                                <p class="text-xs sm:text-sm text-gray-500">Booking Code</p>
                                <p class="font-medium text-sm sm:text-base">{{ $booking->booking_code }}</p>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <div class="bg-green-100 p-2 rounded-full mr-3">
                                <i class="fas fa-route text-green-600"></i>
                            </div>
                            <div>
                                <p class="text-xs sm:text-sm text-gray-500">Route</p>
                                <p class="font-medium text-sm sm:text-base">{{ $booking->schedule->route->origin }} <i class="fas fa-arrow-right mx-1 sm:mx-2 text-green-500"></i> {{ $booking->schedule->route->destination }}</p>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <div class="bg-green-100 p-2 rounded-full mr-3">
                                <i class="fas fa-calendar-alt text-green-600"></i>
                            </div>
                            <div>
                                <p class="text-xs sm:text-sm text-gray-500">Date</p>
                                <p class="font-medium text-sm sm:text-base">
                                    @if($booking->schedule->is_weekly && $booking->schedule->day_of_week !== null)
                                        @php
                                            $nextDate = $booking->schedule->getNextAvailableDate();
                                            $displayDate = $nextDate ? $nextDate : $booking->schedule->departure_time;
                                            echo $displayDate->format('l, F j, Y');
                                        @endphp
                                    @else
                                        {{ $booking->schedule->departure_time->format('l, F j, Y') }}
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <div class="bg-green-100 p-2 rounded-full mr-3">
                                <i class="fas fa-sign-out-alt text-green-600"></i>
                            </div>
                            <div>
                                <p class="text-xs sm:text-sm text-gray-500">Departure</p>
                                <p class="font-medium text-sm sm:text-base">
                                    @if($booking->schedule->is_weekly && $booking->schedule->day_of_week !== null)
                                        @php
                                            $nextDate = $booking->schedule->getNextAvailableDate();
                                            $displayTime = $nextDate ? $nextDate->copy()->setTimeFromTimeString($booking->schedule->departure_time->format('H:i:s')) : $booking->schedule->departure_time;
                                            echo $displayTime->format('H:i');
                                        @endphp
                                    @else
                                        {{ $booking->schedule->departure_time->format('H:i') }}
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gray-50 p-4 sm:p-5 rounded-lg mobile-info-card">
                    <h3 class="text-base sm:text-lg font-medium mb-3 sm:mb-4 text-gray-800 border-b border-gray-200 pb-2 mobile-info-card-title">Passenger Information</h3>
                    <div class="space-y-3">
                        <div class="flex items-center">
                            <div class="bg-green-100 p-2 rounded-full mr-3">
                                <i class="fas fa-user text-green-600"></i>
                            </div>
                            <div>
                                <p class="text-xs sm:text-sm text-gray-500">Name</p>
                                <p class="font-medium text-sm sm:text-base">{{ $booking->passenger_name }}</p>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <div class="bg-green-100 p-2 rounded-full mr-3">
                                <i class="fas fa-envelope text-green-600"></i>
                            </div>
                            <div>
                                <p class="text-xs sm:text-sm text-gray-500">Email</p>
                                <p class="font-medium text-sm sm:text-base">{{ $booking->passenger_email }}</p>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <div class="bg-green-100 p-2 rounded-full mr-3">
                                <i class="fas fa-phone text-green-600"></i>
                            </div>
                            <div>
                                <p class="text-xs sm:text-sm text-gray-500">Phone</p>
                                <p class="font-medium text-sm sm:text-base">{{ $booking->passenger_phone }}</p>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="flex items-center">
                                <div class="bg-green-100 p-2 rounded-full mr-3">
                                    <i class="fas fa-chair text-green-600"></i>
                                </div>
                                <div>
                                    <p class="text-xs sm:text-sm text-gray-500">Seats</p>
                                    <p class="font-medium text-sm sm:text-base">{{ $booking->number_of_seats }}</p>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <div class="bg-green-100 p-2 rounded-full mr-3">
                                    <i class="fas fa-hashtag text-green-600"></i>
                                </div>
                                <div>
                                    <p class="text-xs sm:text-sm text-gray-500">Seat Numbers</p>
                                    <p class="font-medium text-sm sm:text-base">{{ $booking->seat_numbers }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mt-6 sm:mt-8 pt-4 sm:pt-6 border-t border-gray-200 flex flex-col sm:flex-row justify-between items-center gap-4">
                <div class="text-center sm:text-left">
                    <p class="text-gray-600 text-sm sm:text-base">Payment Status: <span class="font-bold text-green-600">Completed</span></p>
                    <p class="text-xs sm:text-sm text-gray-500 mt-1">+ Service Fee: Rp. 5,000</p>
                </div>
                <div class="text-center sm:text-right">
                    <p class="text-gray-600 text-sm sm:text-base">Total Paid:</p>
                    <p class="text-2xl sm:text-3xl font-bold text-green-600">Rp. {{ number_format($booking->total_price + 5000, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
        
        <!-- Ticket Preview -->
        <div class="bg-white rounded-xl shadow-md p-4 sm:p-6 mb-6 sm:mb-8 mobile-info-card">
            <h2 class="text-xl sm:text-2xl font-bold text-gray-800 mb-4 sm:mb-6">Your Ticket</h2>
            @include('frontend.booking.partials.ticket-preview', [
                'origin' => $booking->schedule->route->origin,
                'destination' => $booking->schedule->route->destination,
                'departureDate' => $booking->schedule->departure_time->format('d M Y'),
                'departureTime' => $booking->schedule->departure_time->format('H:i'),
                'arrivalTime' => $booking->schedule->arrival_time->format('H:i'),
                'passengerName' => $booking->passenger_name,
                'passengerEmail' => $booking->passenger_email,
                'passengerPhone' => $booking->passenger_phone,
                'bookingCode' => $booking->booking_code,
                'bookingId' => $booking->id,
                'busType' => $booking->schedule->bus->bus_type ?? 'Standard',
                'seatNumber' => $booking->seat_numbers,
                'price' => 'Rp. ' . number_format($booking->total_price, 0, ',', '.'),
            ])
        </div>
        
        <div class="flex flex-col sm:flex-row justify-center gap-3 sm:gap-4 mt-6 sm:mt-8 mobile-button-group">
            <a href="{{ route('frontend.booking.view-ticket', $booking->id) }}" 
               class="bg-gradient-to-r from-green-600 to-emerald-700 hover:from-green-700 hover:to-emerald-800 text-white font-bold py-2 px-4 sm:py-3 sm:px-6 rounded-lg transition duration-300 shadow-lg transform hover:scale-105 flex items-center justify-center mobile-action-button text-sm sm:text-base"
               target="_blank">
                <i class="fas fa-eye mr-1 sm:mr-2"></i> View Ticket
            </a>
            <a href="{{ route('frontend.booking.download-ticket', $booking->id) }}" 
               class="bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800 text-white font-bold py-2 px-4 sm:py-3 sm:px-6 rounded-lg transition duration-300 shadow-lg transform hover:scale-105 flex items-center justify-center mobile-action-button text-sm sm:text-base">
                <i class="fas fa-download mr-1 sm:mr-2"></i> Download Ticket (PDF)
            </a>
            <a href="{{ route('frontend.booking.index') }}" 
               class="bg-gradient-to-r from-gray-600 to-gray-700 hover:from-gray-700 hover:to-gray-800 text-white font-bold py-2 px-4 sm:py-3 sm:px-6 rounded-lg transition duration-300 shadow-lg transform hover:scale-105 flex items-center justify-center mobile-action-button text-sm sm:text-base">
                <i class="fas fa-bus mr-1 sm:mr-2"></i> Book Another Ticket
            </a>
            <a href="{{ route('frontend.home') }}" 
               class="bg-gradient-to-r from-purple-600 to-violet-700 hover:from-purple-700 hover:to-violet-800 text-white font-bold py-2 px-4 sm:py-3 sm:px-6 rounded-lg transition duration-300 shadow-lg transform hover:scale-105 flex items-center justify-center mobile-action-button text-sm sm:text-base">
                <i class="fas fa-home mr-1 sm:mr-2"></i> Back to Home
            </a>
        </div>
    </div>
    
    <!-- Additional Information -->
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl shadow-lg p-4 sm:p-8 mb-6 sm:mb-10">
        <h2 class="text-xl sm:text-2xl font-bold text-gray-800 mb-4 sm:mb-6 text-center">Next Steps</h2>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6">
            <div class="bg-white p-4 sm:p-6 rounded-lg shadow-sm text-center mobile-info-card">
                <div class="text-blue-500 text-3xl sm:text-4xl mb-3 sm:mb-4">
                    <i class="fas fa-print"></i>
                </div>
                <h3 class="text-base sm:text-lg font-bold mb-2">Print Your Ticket</h3>
                <p class="text-gray-600 text-xs sm:text-sm">Download and print your ticket or save it on your mobile device for easy access.</p>
            </div>
            <div class="bg-white p-4 sm:p-6 rounded-lg shadow-sm text-center mobile-info-card">
                <div class="text-blue-500 text-3xl sm:text-4xl mb-3 sm:mb-4">
                    <i class="fas fa-clock"></i>
                </div>
                <h3 class="text-base sm:text-lg font-bold mb-2">Arrive Early</h3>
                <p class="text-gray-600 text-xs sm:text-sm">Please arrive at the departure point at least 30 minutes before departure time.</p>
            </div>
            <div class="bg-white p-4 sm:p-6 rounded-lg shadow-sm text-center mobile-info-card">
                <div class="text-blue-500 text-3xl sm:text-4xl mb-3 sm:mb-4">
                    <i class="fas fa-headset"></i>
                </div>
                <h3 class="text-base sm:text-lg font-bold mb-2">Contact Us</h3>
                <p class="text-gray-600 text-xs sm:text-sm">If you have any questions, contact our customer service at +1 (555) 123-4567.</p>
            </div>
        </div>
    </div>
</div>
@endsection