@extends('frontend.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-white rounded-lg shadow-md p-6 mb-8 text-center">
        <div class="text-green-500 text-6xl mb-4">
            <i class="fas fa-check-circle"></i>
        </div>
        <h1 class="text-3xl font-bold mb-4">Booking Successful!</h1>
        <p class="text-gray-600 mb-6">Thank you for your booking. Your payment has been processed successfully.</p>
        
        <div class="bg-gray-50 rounded-lg p-6 mb-6 text-left">
            <h2 class="text-xl font-bold mb-4">Booking Details</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p><strong>Booking Code:</strong> {{ $booking->booking_code }}</p>
                    <p><strong>Route:</strong> {{ $booking->schedule->route->origin }} - {{ $booking->schedule->route->destination }}</p>
                    <p><strong>Date:</strong> {{ $booking->schedule->departure_time->format('F j, Y') }}</p>
                    <p><strong>Departure:</strong> {{ $booking->schedule->departure_time->format('H:i') }}</p>
                </div>
                <div>
                    <p><strong>Name:</strong> {{ $booking->passenger_name }}</p>
                    <p><strong>Email:</strong> {{ $booking->passenger_email }}</p>
                    <p><strong>Phone:</strong> {{ $booking->passenger_phone }}</p>
                    <p><strong>Number of Seats:</strong> {{ $booking->number_of_seats }}</p>
                    <p><strong>Selected Seats:</strong> {{ $booking->seat_numbers }}</p>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-gray-200">
                <div class="flex justify-between">
                    <span class="text-lg font-bold">Total Paid:</span>
                    <span class="text-lg font-bold">Rp. {{ number_format($booking->total_price, 0, ',', '.') }}</span>
                </div>
                <div class="mt-2">
                    <span class="text-sm text-gray-600">Payment Status: </span>
                    <span class="text-sm font-bold text-green-600">Completed</span>
                </div>
            </div>
        </div>
        
        <div class="flex flex-col sm:flex-row justify-center gap-4">
            <a href="{{ route('frontend.booking.index') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Book Another Ticket
            </a>
            <a href="{{ route('frontend.home') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Back to Home
            </a>
        </div>
    </div>
</div>
@endsection