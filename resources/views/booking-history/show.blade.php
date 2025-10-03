@extends('frontend.layouts.app')

@section('title', 'Booking Details')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-2">Booking Details</h1>
    <p class="text-gray-600 mb-6">View details of your booking: {{ $booking->booking_code }}</p>
    
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h2 class="text-xl font-semibold mb-4">Booking Information</h2>
                <div class="space-y-3">
                    <div class="flex justify-between border-b pb-2">
                        <span class="text-gray-600">Booking Code:</span>
                        <span class="font-medium">{{ $booking->booking_code }}</span>
                    </div>
                    <div class="flex justify-between border-b pb-2">
                        <span class="text-gray-600">Passenger Name:</span>
                        <span class="font-medium">{{ $booking->passenger_name }}</span>
                    </div>
                    <div class="flex justify-between border-b pb-2">
                        <span class="text-gray-600">Passenger Phone:</span>
                        <span class="font-medium">{{ $booking->passenger_phone }}</span>
                    </div>
                    <div class="flex justify-between border-b pb-2">
                        <span class="text-gray-600">Passenger Email:</span>
                        <span class="font-medium">{{ $booking->passenger_email }}</span>
                    </div>
                    <div class="flex justify-between border-b pb-2">
                        <span class="text-gray-600">Number of Seats:</span>
                        <span class="font-medium">{{ $booking->number_of_seats }}</span>
                    </div>
                    <div class="flex justify-between border-b pb-2">
                        <span class="text-gray-600">Seat Numbers:</span>
                        <span class="font-medium">{{ $booking->seat_numbers ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between border-b pb-2">
                        <span class="text-gray-600">Booking Date:</span>
                        <span class="font-medium">{{ $booking->created_at->format('d M Y, H:i') }}</span>
                    </div>
                    <div class="flex justify-between border-b pb-2">
                        <span class="text-gray-600">Status:</span>
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            @if($booking->booking_status === 'confirmed') bg-green-100 text-green-800
                            @elseif($booking->booking_status === 'pending') bg-yellow-100 text-yellow-800
                            @elseif($booking->booking_status === 'cancelled') bg-red-100 text-red-800
                            @elseif($booking->booking_status === 'completed') bg-blue-100 text-blue-800
                            @endif">
                            {{ ucfirst($booking->booking_status) }}
                        </span>
                    </div>
                    <div class="flex justify-between border-b pb-2">
                        <span class="text-gray-600">Payment Status:</span>
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            @if($booking->payment_status === 'paid') bg-green-100 text-green-800
                            @elseif($booking->payment_status === 'pending') bg-yellow-100 text-yellow-800
                            @elseif($booking->payment_status === 'failed') bg-red-100 text-red-800
                            @elseif($booking->payment_status === 'refunded') bg-gray-100 text-gray-800
                            @endif">
                            {{ ucfirst($booking->payment_status) }}
                        </span>
                    </div>
                </div>
            </div>
            
            <div>
                <h2 class="text-xl font-semibold mb-4">Trip Information</h2>
                <div class="space-y-3">
                    <div class="flex justify-between border-b pb-2">
                        <span class="text-gray-600">Route:</span>
                        <span class="font-medium">{{ $booking->schedule->route->name ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between border-b pb-2">
                        <span class="text-gray-600">From:</span>
                        <span class="font-medium">{{ $booking->schedule->route->origin ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between border-b pb-2">
                        <span class="text-gray-600">To:</span>
                        <span class="font-medium">{{ $booking->schedule->route->destination ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between border-b pb-2">
                        <span class="text-gray-600">Departure Time:</span>
                        <span class="font-medium">{{ $booking->schedule->getDepartureTimeWIB() ? $booking->schedule->getDepartureTimeWIB()->format('d M Y, H:i') : 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between border-b pb-2">
                        <span class="text-gray-600">Arrival Time:</span>
                        <span class="font-medium">{{ $booking->schedule->getArrivalTimeWIB() ? $booking->schedule->getArrivalTimeWIB()->format('d M Y, H:i') : 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between border-b pb-2">
                        <span class="text-gray-600">Bus:</span>
                        <span class="font-medium">{{ $booking->schedule->bus->name ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between border-b pb-2">
                        <span class="text-gray-600">Bus Type:</span>
                        <span class="font-medium">{{ $booking->schedule->bus->bus_type ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-lg font-semibold text-gray-800">Total Price:</span>
                        <span class="text-lg font-semibold text-green-600">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="flex justify-between">
        <a href="{{ route('booking-history.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
            ← Back to History
        </a>
        
        <!-- Recommendation section will be added here after booking -->
        @if($booking->booking_status === 'completed')
            <a href="{{ route('recommendations.show', ['origin' => $booking->schedule->route->destination]) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700">
                View Recommendations →
            </a>
        @endif
    </div>
</div>
@endsection