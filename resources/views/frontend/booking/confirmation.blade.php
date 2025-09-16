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
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold mb-4">Select Your Seat</h2>
        <div class="mb-4">
            <p class="text-gray-600">
                <strong>Available Seats:</strong> 
                {{ $booking->schedule->getAvailableSeatsCount() }} / {{ $booking->schedule->bus->capacity }}
            </p>
        </div>
        <div class="flex flex-col items-center">
            <div class="mb-4">
                <div class="bg-gray-800 text-white px-4 py-2 rounded-t-lg inline-block">Driver</div>
            </div>
            <div class="grid grid-cols-4 gap-2">
                @for ($i = 1; $i <= 40; $i++)
                    <div class="bg-green-500 text-white w-12 h-12 flex items-center justify-center rounded cursor-pointer hover:bg-green-600 seat" data-seat="{{ $i }}">
                        {{ $i }}
                    </div>
                @endfor
            </div>
            <div class="mt-6 flex space-x-4">
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
            <div class="mt-6">
                <button id="save-seats" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    Save Seat Selection
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Seat selection functionality
    const seats = document.querySelectorAll('.seat');
    let selectedSeats = [];
    
    // If seats are already selected, mark them
    @if($booking->seat_numbers)
        const selectedSeatNumbers = "{{ $booking->seat_numbers }}".split(',').map(Number);
        selectedSeatNumbers.forEach(seatNumber => {
            const seatElement = document.querySelector(`.seat[data-seat="${seatNumber}"]`);
            if (seatElement) {
                seatElement.classList.remove('bg-green-500');
                seatElement.classList.add('bg-blue-500');
                selectedSeats.push(String(seatNumber));
            }
        });
    @endif
    
    // Mark occupied seats
    @if(isset($occupiedSeats))
        const occupiedSeatNumbers = @json($occupiedSeats);
        occupiedSeatNumbers.forEach(seatNumber => {
            const seatElement = document.querySelector(`.seat[data-seat="${seatNumber}"]`);
            if (seatElement && !seatElement.classList.contains('bg-blue-500')) {
                seatElement.classList.remove('bg-green-500');
                seatElement.classList.add('bg-red-500');
                seatElement.classList.remove('cursor-pointer');
                seatElement.title = 'This seat is already booked';
            }
        });
    @endif
    
    seats.forEach(seat => {
        // Only add click event to available seats (not occupied)
        if (!seat.classList.contains('bg-red-500')) {
            seat.addEventListener('click', function() {
                const seatNumber = this.getAttribute('data-seat');
                
                // Toggle seat selection
                if (this.classList.contains('bg-blue-500')) {
                    // Deselect seat
                    this.classList.remove('bg-blue-500');
                    this.classList.add('bg-green-500');
                    selectedSeats = selectedSeats.filter(seat => seat !== seatNumber);
                } else {
                    // Select seat (limit to number of seats requested)
                    if (selectedSeats.length < {{ $booking->number_of_seats }}) {
                        this.classList.remove('bg-green-500');
                        this.classList.add('bg-blue-500');
                        selectedSeats.push(seatNumber);
                    } else {
                        alert('You can only select up to {{ $booking->number_of_seats }} seats.');
                    }
                }
                
                console.log('Selected seats:', selectedSeats);
            });
        } else {
            // Add tooltip or visual indicator for occupied seats
            seat.title = 'This seat is already booked';
        }
    });
    
    // Save seat selection
    const saveSeatsButton = document.getElementById('save-seats');
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
        
        // Send selected seats to backend
        fetch('{{ route("frontend.booking.select-seats") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                booking_id: {{ $booking->id }},
                seat_numbers: selectedSeats.map(Number) // Convert to numbers
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
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error saving seat selection. Please try again.');
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
                window.location.href = '{{ route("frontend.booking.success", ["booking" => $booking->id]) }}';
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