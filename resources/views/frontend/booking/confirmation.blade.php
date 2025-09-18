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
        <h2 class="text-2xl font-bold text-gray-800 mb-4 flex items-center">
            <i class="fas fa-bus mr-2 text-blue-500"></i> Select Your Seat (2-3 Layout)
        </h2>
        
        <!-- Bus Visualization -->
        <div class="bus-layout mb-6">
            <div class="bus-driver-area text-center mb-4">
                <div class="driver-seat bg-gray-800 text-white px-6 py-2 rounded-t-lg inline-block font-semibold shadow-md">
                    <i class="fas fa-steering-wheel mr-2"></i>Driver
                </div>
            </div>
            
            <div class="bus-seats-area">
                <!-- Aisle Separator (main) -->
                <div class="aisle-separator my-4 flex justify-center">
                    <div class="w-3/4 h-1 bg-gray-300 rounded" style="background: linear-gradient(90deg, transparent, #9ca3af, transparent);"></div>
                </div>
                
                <!-- Seat Rows (based on bus capacity) -->
                <div class="seat-layout flex flex-col items-center">
                    @php
                        $totalSeats = $booking->schedule->bus->capacity;
                        // Calculate rows needed (2+3 seats per row = 5 seats per row)
                        $rows = ceil($totalSeats / 5);
                    @endphp
                    
                    @for ($row = 0; $row < $rows; $row++)
                        <div class="seat-row flex items-center justify-center mb-4 w-full max-w-md bg-gray-50 p-3 rounded-lg">
                            <!-- Left side (2 seats) -->
                            <div class="left-seats flex gap-3">
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
                                        class="seat-item w-12 h-12 flex items-center justify-center rounded cursor-pointer transition-all duration-200 transform hover:scale-105 shadow relative
                                            {{ $isOccupied ? 'bg-red-100 cursor-not-allowed opacity-70' : ($isSelected ? 'bg-blue-100 ring-2 ring-blue-500' : 'bg-green-100 hover:bg-green-200') }}"
                                        data-seat="{{ $seatNumber }}"
                                        {{ $isOccupied ? 'title=This seat is already booked' : 'title=Select seat '.$seatNumber }}
                                    >
                                        <!-- Seat Image -->
                                        <div class="seat-image w-9 h-9 flex items-center justify-center {{ $isOccupied ? 'opacity-50' : ($isSelected ? 'filter brightness-75' : '') }}">
                                            <img src="{{ asset('img/car-seat.png') }}" alt="Seat" class="w-full h-full object-contain">
                                        </div>
                                        
                                        <!-- Seat Number -->
                                        <div class="seat-number absolute -bottom-2 -right-1 text-[9px] font-bold {{ $isOccupied ? 'text-red-700' : ($isSelected ? 'text-blue-700' : 'text-green-700') }}">
                                            {{ $seatNumber }}
                                        </div>
                                        
                                        @if($isOccupied)
                                            <div class="occupied-overlay absolute inset-0 bg-red-500 bg-opacity-70 rounded flex items-center justify-center">
                                                <i class="fas fa-times text-white text-base"></i>
                                            </div>
                                        @endif
                                    </div>
                                @endfor
                            </div>
                            
                            <!-- Aisle separator -->
                            <div class="aisle w-8 flex items-center justify-center mx-3">
                                <div class="w-full h-1 bg-gray-400 rounded" style="background: linear-gradient(90deg, transparent, #9ca3af, transparent);"></div>
                            </div>
                            
                            <!-- Right side (3 seats) -->
                            <div class="right-seats flex gap-3">
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
                                        class="seat-item w-12 h-12 flex items-center justify-center rounded cursor-pointer transition-all duration-200 transform hover:scale-105 shadow relative
                                            {{ $isOccupied ? 'bg-red-100 cursor-not-allowed opacity-70' : ($isSelected ? 'bg-blue-100 ring-2 ring-blue-500' : 'bg-green-100 hover:bg-green-200') }}"
                                        data-seat="{{ $seatNumber }}"
                                        {{ $isOccupied ? 'title=This seat is already booked' : 'title=Select seat '.$seatNumber }}
                                    >
                                        <!-- Seat Image -->
                                        <div class="seat-image w-9 h-9 flex items-center justify-center {{ $isOccupied ? 'opacity-50' : ($isSelected ? 'filter brightness-75' : '') }}">
                                            <img src="{{ asset('img/car-seat.png') }}" alt="Seat" class="w-full h-full object-contain">
                                        </div>
                                        
                                        <!-- Seat Number -->
                                        <div class="seat-number absolute -bottom-2 -right-1 text-[9px] font-bold {{ $isOccupied ? 'text-red-700' : ($isSelected ? 'text-blue-700' : 'text-green-700') }}">
                                            {{ $seatNumber }}
                                        </div>
                                        
                                        @if($isOccupied)
                                            <div class="occupied-overlay absolute inset-0 bg-red-500 bg-opacity-70 rounded flex items-center justify-center">
                                                <i class="fas fa-times text-white text-base"></i>
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
        <div class="seat-legend flex flex-wrap justify-center gap-3 mb-6">
            <div class="flex items-center bg-gray-50 px-2 py-1 rounded-md shadow-sm">
                <div class="w-4 h-4 bg-green-100 rounded mr-1 flex items-center justify-center">
                    <img src="{{ asset('img/car-seat.png') }}" alt="Available Seat" class="w-3 h-3 object-contain">
                </div>
                <span class="text-xs font-medium">Available</span>
            </div>
            <div class="flex items-center bg-gray-50 px-2 py-1 rounded-md shadow-sm">
                <div class="w-4 h-4 bg-red-100 rounded mr-1 flex items-center justify-center">
                    <i class="fas fa-times text-red-500 text-[8px]"></i>
                </div>
                <span class="text-xs font-medium">Occupied</span>
            </div>
            <div class="flex items-center bg-gray-50 px-2 py-1 rounded-md shadow-sm">
                <div class="w-4 h-4 bg-blue-100 rounded mr-1 flex items-center justify-center">
                    <img src="{{ asset('img/car-seat.png') }}" alt="Selected Seat" class="w-3 h-3 object-contain filter brightness-75">
                </div>
                <span class="text-xs font-medium">Selected</span>
            </div>
            <div class="flex items-center bg-gray-50 px-2 py-1 rounded-md shadow-sm">
                <div class="w-6 h-4 flex items-center justify-center">
                    <div class="w-3 h-1 bg-gray-400 rounded"></div>
                </div>
                <span class="text-xs font-medium">Aisle</span>
            </div>
        </div>
        
        <!-- Selected Seats Info -->
        <div class="selected-seats-info text-center mb-6 bg-blue-50 rounded-lg p-3">
            <p class="text-gray-700">
                Selected Seats: 
                <span id="selected-seats-display" class="font-semibold text-blue-600 bg-white px-2 py-1 rounded text-sm">
                    @if($booking->seat_numbers)
                        {{ $booking->seat_numbers }}
                    @else
                        None
                    @endif
                </span>
            </p>
            <p class="text-gray-600 text-xs mt-1">
                <span id="selected-count" class="font-bold">{{ $booking->seat_numbers ? count(explode(',', $booking->seat_numbers)) : 0 }}</span> of {{ $booking->number_of_seats }} seats selected
            </p>
        </div>
        
        <!-- Action Buttons -->
        <div class="action-buttons flex justify-center">
            <button id="save-seats" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md flex items-center transition duration-200 shadow hover:shadow-md text-sm" title="Save your seat selection">
                <i class="fas fa-save mr-1"></i> Save Seats
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
                seatElement.classList.add('bg-blue-100', 'ring-2', 'ring-blue-500');
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
                this.classList.remove('bg-blue-100', 'ring-2', 'ring-blue-500');
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
                    this.classList.add('bg-blue-100', 'ring-2', 'ring-blue-500');
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
                saveSeatsButton.textContent = originalText;
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
                Swal.fire({
                    icon: 'error',
                    title: 'Payment Error',
                    text: 'Error: ' + data.message,
                    confirmButtonColor: '#3b82f6'
                });
                // Reset button
                payButton.disabled = false;
                payButton.textContent = originalText;
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
            payButton.textContent = originalText;
        });
    });
});
</script>
@endsection