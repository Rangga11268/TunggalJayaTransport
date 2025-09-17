@extends('frontend.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <h1 class="text-3xl font-bold mb-6">Booking Confirmation</h1>
    
    <!-- Booking Summary -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h2 class="text-xl font-bold mb-4">Booking Details</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h3 class="text-lg font-medium mb-2">Route Information</h3>
                <p><strong>Route:</strong> {{ $booking->schedule->route->origin }} - {{ $booking->schedule->route->destination }}</p>
                <p><strong>Date:</strong> {{ $booking->schedule->departure_time->format('F j, Y') }}</p>
                <p><strong>Departure:</strong> {{ $booking->schedule->departure_time->format('H:i') }}</p>
                <p><strong>Arrival:</strong> {{ $booking->schedule->arrival_time->format('H:i') }}</p>
                <p><strong>Bus Type:</strong> {{ $booking->schedule->bus->bus_type ?? 'Standard' }}</p>
            </div>
            <div>
                <h3 class="text-lg font-medium mb-2">Passenger Information</h3>
                <p><strong>Name:</strong> {{ $booking->passenger_name }}</p>
                <p><strong>Email:</strong> {{ $booking->passenger_email }}</p>
                <p><strong>Phone:</strong> {{ $booking->passenger_phone }}</p>
                <p><strong>Booking Code:</strong> {{ $booking->booking_code }}</p>
                <p><strong>Number of Seats:</strong> {{ $booking->number_of_seats }}</p>
                @if($booking->seat_numbers)
                <p><strong>Selected Seats:</strong> {{ $booking->seat_numbers }}</p>
                @endif
            </div>
        </div>
        <div class="mt-6 pt-6 border-t border-gray-200">
            <div class="flex justify-between">
                <span class="text-lg font-bold">Total Price:</span>
                <span class="text-lg font-bold">Rp. {{ number_format($booking->total_price, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    <!-- Payment Options -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h2 class="text-xl font-bold mb-4">Payment Method</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="border rounded-lg p-4 cursor-pointer hover:border-blue-500 payment-method" data-method="credit_card">
                <div class="flex items-center">
                    <input type="radio" id="credit-card" name="payment" class="h-4 w-4 text-blue-600" checked>
                    <label for="credit-card" class="ml-2 block text-sm font-medium text-gray-700">Credit/Debit Card</label>
                </div>
            </div>
            <div class="border rounded-lg p-4 cursor-pointer hover:border-blue-500 payment-method" data-method="bank_transfer">
                <div class="flex items-center">
                    <input type="radio" id="bank-transfer" name="payment" class="h-4 w-4 text-blue-600">
                    <label for="bank-transfer" class="ml-2 block text-sm font-medium text-gray-700">Bank Transfer</label>
                </div>
            </div>
            <div class="border rounded-lg p-4 cursor-pointer hover:border-blue-500 payment-method" data-method="e_wallet">
                <div class="flex items-center">
                    <input type="radio" id="e-wallet" name="payment" class="h-4 w-4 text-blue-600">
                    <label for="e-wallet" class="ml-2 block text-sm font-medium text-gray-700">E-Wallet</label>
                </div>
            </div>
        </div>
        
        <div class="mt-6">
            <button id="pay-button" class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded">
                Proceed to Payment
            </button>
        </div>
    </div>

    <!-- Seat Selection -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h2 class="text-xl font-bold mb-4">Select Your Seat</h2>
        
        <!-- Bus Visualization -->
        <div class="bus-layout mb-6">
            <div class="bus-driver-area text-center mb-6">
                <div class="driver-seat bg-gray-800 text-white px-4 py-2 rounded-t-lg inline-block font-semibold">
                    Driver
                </div>
            </div>
            
            <div class="bus-seats-area">
                <!-- Aisle Separator -->
                <div class="aisle-separator mb-6 flex justify-center">
                    <div class="w-3/4 h-1 bg-gray-300 rounded" style="background: linear-gradient(90deg, transparent, #9ca3af, transparent);"></div>
                </div>
                
                <!-- Seat Rows (2 rows x 3 columns) -->
                <div class="seat-grid grid grid-cols-3 gap-8 justify-items-center mb-6">
                    @for ($row = 0; $row < 2; $row++)
                        <div class="seat-row flex gap-6 mb-6">
                            @for ($col = 0; $col < 3; $col++)
                                @php
                                    $seatNumber = ($row * 3) + $col + 1;
                                    $isOccupied = in_array($seatNumber, $occupiedSeats ?? []);
                                    $isSelected = $booking->seat_numbers && in_array($seatNumber, explode(',', $booking->seat_numbers));
                                @endphp
                                <div 
                                    class="seat-item w-16 h-16 flex items-center justify-center rounded-lg cursor-pointer transition-all duration-200 transform hover:scale-105 shadow-md text-white font-bold text-lg relative
                                        {{ $isOccupied ? 'bg-red-500 cursor-not-allowed opacity-75' : ($isSelected ? 'bg-blue-500 ring-4 ring-blue-300' : 'bg-green-500 hover:bg-green-600') }}"
                                    data-seat="{{ $seatNumber }}"
                                    {{ $isOccupied ? 'title=This seat is already booked' : '' }}
                                >
                                    {{ $seatNumber }}
                                    @if($isOccupied)
                                        <div class="occupied-overlay absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 font-bold text-xl opacity-80">
                                            X
                                        </div>
                                    @endif
                                </div>
                            @endfor
                        </div>
                    @endfor
                </div>
            </div>
        </div>
        
        <!-- Seat Legend -->
        <div class="seat-legend flex justify-center space-x-8 mb-6">
            <div class="flex items-center">
                <div class="w-4 h-4 bg-green-500 rounded mr-2"></div>
                <span class="text-sm">Available</span>
            </div>
            <div class="flex items-center">
                <div class="w-4 h-4 bg-red-500 rounded mr-2"></div>
                <span class="text-sm">Occupied</span>
            </div>
            <div class="flex items-center">
                <div class="w-4 h-4 bg-blue-500 rounded mr-2"></div>
                <span class="text-sm">Selected</span>
            </div>
        </div>
        
        <!-- Selected Seats Info -->
        <div class="selected-seats-info text-center mb-6">
            <p class="text-gray-700">
                Selected Seats: 
                <span id="selected-seats-display" class="font-semibold">
                    @if($booking->seat_numbers)
                        {{ $booking->seat_numbers }}
                    @else
                        None
                    @endif
                </span>
            </p>
            <p class="text-gray-600 text-sm mt-1">
                <span id="selected-count">{{ $booking->seat_numbers ? count(explode(',', $booking->seat_numbers)) : 0 }}</span> of {{ $booking->number_of_seats }} seats selected
            </p>
        </div>
        
        <!-- Action Buttons -->
        <div class="action-buttons flex justify-center">
            <button id="save-seats" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded {{ $booking->seat_numbers ? '' : 'disabled:opacity-50 disabled:cursor-not-allowed' }}" {{ $booking->seat_numbers ? '' : 'disabled' }}>
                Save Seat Selection
            </button>
        </div>
    </div>
    
    <!-- Ticket Preview and Download -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold">Ticket Preview</h2>
            @if($booking->seat_numbers)
            <a href="{{ route('frontend.booking.download-ticket', $booking->id) }}" target="_blank" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                </svg>
                Download Ticket
            </a>
            @endif
        </div>
        
        @if($booking->seat_numbers)
            @include('frontend.booking.partials.ticket', [
                'passengerName' => $booking->passenger_name,
                'bookingCode' => $booking->booking_code,
                'origin' => $booking->schedule->route->origin,
                'destination' => $booking->schedule->route->destination,
                'departureDate' => $booking->schedule->departure_time->format('M j, Y'),
                'departureTime' => $booking->schedule->departure_time->format('H:i'),
                'seatNumber' => $booking->seat_numbers,
                'busType' => $booking->schedule->bus->bus_type ?? 'Standard',
                'price' => 'Rp. ' . number_format($booking->total_price, 0, ',', '.'),
                'boardingPoint' => 'Main Terminal'
            ])
        @else
            <div class="text-center py-8 text-gray-500">
                <p>Please select and save your seat to view and download your ticket.</p>
            </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Seat selection functionality
    const seats = document.querySelectorAll('.seat-item:not(.bg-red-500)');
    const selectedSeatsDisplay = document.getElementById('selected-seats-display');
    const selectedCount = document.getElementById('selected-count');
    const saveSeatsButton = document.getElementById('save-seats');
    let selectedSeats = [];
    
    // Initialize with already selected seats
    @if($booking->seat_numbers)
        selectedSeats = "{{ $booking->seat_numbers }}".split(',').map(Number);
        selectedCount.textContent = selectedSeats.length;
        if (selectedSeats.length === {{ $booking->number_of_seats }}) {
            saveSeatsButton.disabled = false;
        }
    @endif
    
    seats.forEach(seat => {
        seat.addEventListener('click', function() {
            const seatNumber = parseInt(this.getAttribute('data-seat'));
            
            // Toggle seat selection
            if (this.classList.contains('bg-blue-500')) {
                // Deselect seat
                this.classList.remove('bg-blue-500', 'ring-4', 'ring-blue-300');
                this.classList.add('bg-green-500');
                selectedSeats = selectedSeats.filter(num => num !== seatNumber);
            } else {
                // Select seat (limit to number of seats requested)
                if (selectedSeats.length < {{ $booking->number_of_seats }}) {
                    this.classList.remove('bg-green-500');
                    this.classList.add('bg-blue-500', 'ring-4', 'ring-blue-300');
                    selectedSeats.push(seatNumber);
                } else {
                    alert('You can only select up to {{ $booking->number_of_seats }} seats.');
                }
            }
            
            // Update display
            selectedSeatsDisplay.textContent = selectedSeats.length > 0 ? selectedSeats.join(', ') : 'None';
            selectedCount.textContent = selectedSeats.length;
            
            // Enable/disable save button
            saveSeatsButton.disabled = selectedSeats.length !== {{ $booking->number_of_seats }};
        });
    });
    
    // Save seat selection
    saveSeatsButton.addEventListener('click', function() {
        if (selectedSeats.length === 0) {
            alert('Please select at least one seat.');
            return;
        }
        
        // Validate that the number of selected seats matches the requested number
        if (selectedSeats.length != {{ $booking->number_of_seats }}) {
            alert('Please select exactly {{ $booking->number_of_seats }} seats.');
            return;
        }
        
        // Show processing message
        const originalText = saveSeatsButton.textContent;
        saveSeatsButton.disabled = true;
        saveSeatsButton.textContent = 'Saving...';
        
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
                alert('Seat selection saved successfully!');
                // Reload the page to show updated seat selection
                location.reload();
            } else {
                alert('Error: ' + data.message);
                // Reset button
                saveSeatsButton.disabled = false;
                saveSeatsButton.textContent = originalText;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error saving seat selection. Please try again.');
            // Reset button
            saveSeatsButton.disabled = false;
            saveSeatsButton.textContent = originalText;
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
        if (!'{{ $booking->seat_numbers }}' || '{{ $booking->seat_numbers }}'.length === 0) {
            alert('Please select and save your seats before proceeding to payment.');
            return;
        }
        
        // Show processing message
        const originalText = payButton.textContent;
        payButton.disabled = true;
        payButton.textContent = 'Processing...';
        
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
                alert('Error: ' + data.message);
                // Reset button
                payButton.disabled = false;
                payButton.textContent = originalText;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error processing payment: ' + error.message + '. Please try again.');
            // Reset button
            payButton.disabled = false;
            payButton.textContent = originalText;
        });
    });
});
</script>
@endsection