@extends('frontend.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 page-spacing">
    <!-- Progres Stepper dengan Estimasi Waktu -->
    <div class="mb-8 overflow-x-auto">
        <div class="flex items-center justify-center min-w-max">
            <div class="flex flex-col items-center">
                <div class="flex items-center text-blue-600 relative">
                    <div class="rounded-full h-8 w-8 md:h-10 md:w-10 py-2 md:py-3 border-2 border-blue-600 flex items-center justify-center">
                        <span class="text-xs md:text-sm font-bold">1</span>
                    </div>
                    <div class="text-center ml-1 md:ml-2">
                        <div class="text-xs md:text-xs font-medium uppercase tracking-wide">Jadwal</div>
                        <div class="text-xs text-gray-500">&lt; 1 min</div>
                    </div>
                </div>
            </div>
            <div class="hidden md:flex flex-auto border-t-2 border-blue-600 mx-1 md:mx-4"></div>
            <div class="md:hidden flex flex-col items-center mx-1">
                <div class="text-gray-300 text-lg">↓</div>
            </div>
            <div class="flex flex-col items-center">
                <div class="flex items-center text-gray-500 relative">
                    <div class="rounded-full h-8 w-8 md:h-10 md:w-10 py-2 md:py-3 border-2 border-blue-600 flex items-center justify-center">
                        <span class="text-xs md:text-sm font-bold">2</span>
                    </div>
                    <div class="text-center ml-1 md:ml-2">
                        <div class="text-xs md:text-xs font-medium uppercase tracking-wide">Penumpang</div>
                        <div class="text-xs text-gray-500">&lt; 1 min</div>
                    </div>
                </div>
            </div>
            <div class="hidden md:flex flex-auto border-t-2 border-blue-600 mx-1 md:mx-4"></div>
            <div class="md:hidden flex flex-col items-center mx-1">
                <div class="text-gray-300 text-lg">↓</div>
            </div>
            <div class="flex flex-col items-center">
                <div class="flex items-center text-blue-500 relative">
                    <div class="rounded-full h-8 w-8 md:h-10 md:w-10 py-2 md:py-3 border-2 border-blue-600 flex items-center justify-center">
                        <span class="text-xs md:text-sm font-bold">3</span>
                    </div>
                    <div class="text-center ml-1 md:ml-2">
                        <div class="text-xs md:text-xs font-medium uppercase tracking-wide">Kursi</div>
                        <div class="text-xs text-gray-500">1-2 min</div>
                    </div>
                </div>
            </div>
            <div class="hidden md:flex flex-auto border-t-2 border-gray-300 mx-1 md:mx-4"></div>
            <div class="md:hidden flex flex-col items-center mx-1">
                <div class="text-gray-300 text-lg">↓</div>
            </div>
            <div class="flex flex-col items-center">
                <div class="flex items-center text-gray-500 relative">
                    <div class="rounded-full h-8 w-8 md:h-10 md:w-10 py-2 md:py-3 border-2 border-gray-300 flex items-center justify-center">
                        <span class="text-xs md:text-sm font-bold">4</span>
                    </div>
                    <div class="text-center ml-1 md:ml-2">
                        <div class="text-xs md:text-xs font-medium uppercase tracking-wide">Pembayaran</div>
                        <div class="text-xs text-gray-500">&lt; 1 min</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Header -->
    <div class="text-center mb-8">
        <h1 class="text-4xl font-bold text-gray-800 mb-3">Konfirmasi Pemesanan</h1>
        <p class="text-lg text-gray-600">Silakan pilih kursi Anda untuk menyelesaikan pemesanan</p>
    </div>
    
    <!-- Departure Reminder -->
    @php
        $departureTime = $booking->schedule->getActualDepartureTime();
        $now = now('Asia/Jakarta');
        $hoursUntilDeparture = $now->diffInHours($departureTime, false);
        $minutesUntilDeparture = $now->diffInMinutes($departureTime, false);
        $daysUntilDeparture = floor($hoursUntilDeparture / 24);
        
        // Create a clean time display
        if ($daysUntilDeparture > 0) {
            $timeDisplay = $daysUntilDeparture . ' hari';
        } elseif ($hoursUntilDeparture >= 1) {
            $timeDisplay = round($hoursUntilDeparture) . ' jam';
        } else {
            // If less than 1 hour, show in minutes (rounded to whole number)
            $timeDisplay = round($minutesUntilDeparture) . ' menit';
        }
    @endphp
    
    @if($hoursUntilDeparture >= 0 && $hoursUntilDeparture <= 2)
        <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded mb-8">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-triangle text-red-500"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-red-700">
                        <strong>Penting:</strong> Bus anda akan berangkat dalam {{ $timeDisplay }}! 
                        Silakan segera ke terminal.
                    </p>
                </div>
            </div>
        </div>
    @elseif($hoursUntilDeparture >= 0 && $hoursUntilDeparture <= 6)
        <div class="bg-orange-50 border-l-4 border-orange-500 p-4 rounded mb-8">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-circle text-orange-500"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-orange-700">
                        <strong>Peringatan:</strong> Bus anda akan berangkat dalam {{ $timeDisplay }}. 
                        Silakan segera ke terminal.
                    </p>
                </div>
            </div>
        </div>
    @elseif($hoursUntilDeparture >= 0 && $hoursUntilDeparture <= 24)
        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded mb-8">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-info-circle text-blue-500"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-blue-700">
                        <strong>Informasi:</strong> Bus anda akan berangkat dalam {{ $timeDisplay }}. 
                        Silakan datang ke terminal minimal 30 menit sebelum keberangkatan.
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
                        <strong>Peringatan:</strong> Jadwal ini sudah berangkat. 
                        Silakan hubungi layanan pelanggan untuk bantuan.
                    </p>
                </div>
            </div>
        </div>
    @endif
    
    <!-- Ringkasan Pemesanan -->
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl shadow-lg p-6 mb-8 mobile-booking-summary">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Detail Pemesanan</h2>
            <div class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">
                <i class="fas fa-receipt mr-1"></i>Pemesanan #{{ $booking->booking_code }}
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="bg-white p-6 rounded-lg shadow-sm mobile-info-card">
                <h3 class="text-lg font-medium mb-4 text-gray-800 border-b border-gray-200 pb-2 mobile-info-card-title">Informasi Rute</h3>
                <div class="space-y-4">
                    <div class="flex items-center">
                        <div class="bg-blue-100 p-3 rounded-full mr-4">
                            <i class="fas fa-route text-blue-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Rute</p>
                            <p class="font-medium text-lg">{{ $booking->schedule->route->origin }} <i class="fas fa-arrow-right mx-2 text-blue-500"></i> {{ $booking->schedule->route->destination }}</p>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <div class="bg-blue-100 p-3 rounded-full mr-4">
                            <i class="fas fa-calendar-alt text-blue-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Tanggal</p>
                            <p class="font-medium">
                                {{ $booking->schedule->getActualDepartureTime()->format('l, F j, Y') }}
                            </p>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="flex items-center">
                            <div class="bg-blue-100 p-2 rounded-full mr-3">
                                <i class="fas fa-sign-out-alt text-blue-600"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Keberangkatan</p>
                                <p class="font-medium">
                                    {{ $booking->schedule->getActualDepartureTime()->format('H:i') }}
                                    <span class="text-xs text-gray-500 ml-1">(WIB)</span>
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <div class="bg-blue-100 p-2 rounded-full mr-3">
                                <i class="fas fa-sign-in-alt text-blue-600"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Kedatangan</p>
                                <p class="font-medium">
                                    {{ $booking->schedule->getActualArrivalTime()->format('H:i') }}
                                    <span class="text-xs text-gray-500 ml-1">(WIB)</span>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <div class="bg-blue-100 p-2 rounded-full mr-3">
                            <i class="fas fa-bus text-blue-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Tipe Bus</p>
                            <p class="font-medium">{{ $booking->schedule->bus->bus_type ?? 'Standar' }}</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-white p-6 rounded-lg shadow-sm mobile-info-card">
                <h3 class="text-lg font-medium mb-4 text-gray-800 border-b border-gray-200 pb-2 mobile-info-card-title">Informasi Penumpang</h3>
                <div class="space-y-4">
                    <div class="flex items-center">
                        <div class="bg-blue-100 p-3 rounded-full mr-4">
                            <i class="fas fa-user text-blue-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Nama</p>
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
                            <p class="text-sm text-gray-500">Telepon</p>
                            <p class="font-medium">{{ $booking->passenger_phone }}</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="flex items-center">
                            <div class="bg-blue-100 p-2 rounded-full mr-3">
                                <img src="{{ asset('img/car-seat.png') }}" alt="Seat" class="w-5 h-5 text-blue-600">
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Jumlah Kursi</p>
                                <p class="font-medium">{{ $booking->number_of_seats }}</p>
                            </div>
                        </div>
                        @if($booking->seat_numbers)
                        <div class="flex items-center">
                            <div class="bg-blue-100 p-2 rounded-full mr-3">
                                <i class="fas fa-hashtag text-blue-600"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Nomor Kursi</p>
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
                    <p class="text-gray-600">Total Harga ({{ $booking->number_of_seats }} kursi)</p>
                    <p class="text-sm text-gray-500">+ Biaya Layanan: Rp. 5.000</p>
                </div>
                <div class="text-right">
                    <p class="text-3xl font-bold text-blue-600">Rp. {{ number_format($booking->total_price + 5000, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Pilihan Pembayaran -->
    <div class="bg-white rounded-xl shadow-lg p-4 sm:p-6 mb-8 mobile-booking-card">
        <h2 class="text-2xl font-bold text-gray-800 mb-6 mobile-info-card-title">Metode Pembayaran</h2>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mobile-payment-options">
            <div class="border-2 border-gray-200 rounded-lg p-4 sm:p-5 cursor-pointer hover:border-blue-500 payment-method transition duration-200 hover:shadow-md" data-method="credit_card">
                <div class="flex items-center">
                    <input type="radio" id="credit-card" name="payment" class="h-5 w-5 text-blue-600">
                    <label for="credit-card" class="ml-3 block text-base sm:text-lg font-medium text-gray-700">Kartu Kredit/Debit</label>
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
                    <label for="bank-transfer" class="ml-3 block text-base sm:text-lg font-medium text-gray-700">Transfer Bank</label>
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
                <i class="fas fa-lock mr-2"></i> Proses ke Pembayaran Aman
            </button>
        </div>
    </div>

    <!-- Seat Selection -->
    <div class=\"bg-white rounded-xl shadow-lg p-4 sm:p-6 mb-8 mobile-booking-card\">
        <h2 class=\"text-xl sm:text-2xl font-bold text-gray-800 mb-4 sm:mb-6 flex items-center mobile-info-card-title\">
            <i class=\"fas fa-bus mr-2 sm:mr-3 text-blue-500\"></i> Select Your Seat (2-3 Layout)
        </h2>
        
        <!-- Bus Amenities Indicators -->
        <div class="bus-amenities mb-4 sm:mb-6 grid grid-cols-2 md:grid-cols-4 gap-2 sm:gap-4">
            <div class="flex items-center bg-blue-50 p-2 sm:p-3 rounded-lg shadow-sm">
                <div class="w-6 h-6 sm:w-8 sm:h-8 bg-blue-100 rounded mr-2 flex items-center justify-center">
                    <i class="fas fa-snowflake text-blue-500"></i>
                </div>
                <span class="text-xs sm:text-sm font-medium">AC</span>
            </div>
            <div class="flex items-center bg-blue-50 p-2 sm:p-3 rounded-lg shadow-sm">
                <div class="w-6 h-6 sm:w-8 sm:h-8 bg-blue-100 rounded mr-2 flex items-center justify-center">
                    <i class="fas fa-toilet text-blue-500"></i>
                </div>
                <span class="text-xs sm:text-sm font-medium">Toilet</span>
            </div>
            <div class="flex items-center bg-blue-50 p-2 sm:p-3 rounded-lg shadow-sm">
                <div class="w-6 h-6 sm:w-8 sm:h-8 bg-blue-100 rounded mr-2 flex items-center justify-center">
                    <i class="fas fa-cookie-bite text-blue-500"></i>
                </div>
                <span class="text-xs sm:text-sm font-medium">Snacks</span>
            </div>
            <div class="flex items-center bg-blue-50 p-2 sm:p-3 rounded-lg shadow-sm">
                <div class="w-6 h-6 sm:w-8 sm:h-8 bg-blue-100 rounded mr-2 flex items-center justify-center">
                    <i class="fas fa-plug text-blue-500"></i>
                </div>
                <span class="text-xs sm:text-sm font-medium">Power</span>
            </div>
        </div>
        
        <!-- Bus Visualization -->
        <div class="bus-layout mb-6 sm:mb-8">
            <div class="bus-driver-area text-center mb-4 sm:mb-6">
                <div class="driver-seat bg-gradient-to-r from-gray-800 to-gray-900 text-white px-4 sm:px-8 py-2 sm:py-3 rounded-t-xl inline-block font-bold shadow-lg text-sm sm:text-base">
                    <i class="fas fa-steering-wheel mr-1 sm:mr-2"></i>Driver
                </div>
            </div>
            
            <div class="bus-seats-area">
                <!-- Aisle Separator (main) -->
                <div class="aisle-separator my-4 sm:my-6 flex justify-center">
                    <div class="w-4/5 h-1 bg-gradient-to-r from-transparent via-gray-400 to-transparent rounded-full"></div>
                </div>
                
                <!-- Seat Rows (based on bus capacity) -->
                <div class="seat-layout flex flex-col items-center relative">
                    <!-- Add entrance and toilet indicators -->
                    <div class="bus-features mb-4 flex justify-between w-full max-w-lg">
                        <div class="text-sm font-medium text-gray-600">
                            <i class="fas fa-door-open mr-1 text-blue-500"></i> Entrance
                        </div>
                        <div class="text-sm font-medium text-gray-600">
                            <i class="fas fa-toilet mr-1 text-blue-500"></i> Toilet
                        </div>
                    </div>
                    
                    @php
                        $totalSeats = $booking->schedule->bus->capacity;
                        // Calculate rows needed (2+3 seats per row = 5 seats per row)
                        $rows = ceil($totalSeats / 5);
                    @endphp
                    
                    @for ($row = 0; $row < $rows; $row++)
                        <div class="seat-row flex items-center justify-center mb-4 sm:mb-6 w-full max-w-lg bg-gray-50 p-4 sm:p-5 rounded-xl shadow-sm relative">
                            <!-- Left side (2 seats) -->
                            <div class="left-seats flex gap-3 sm:gap-4 mr-3">
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
                                        class="seat-item w-11 h-11 sm:w-16 sm:h-16 flex items-center justify-center rounded-xl cursor-pointer transition-all duration-300 transform hover:scale-105 hover:shadow-xl shadow-lg relative mobile-seat-item
                                            {{ $isOccupied ? 'bg-red-100 cursor-not-allowed opacity-80' : ($isSelected ? 'bg-gradient-to-br from-blue-400 to-blue-600 ring-4 ring-blue-300' : 'bg-gradient-to-br from-green-400 to-green-600 hover:from-green-500 hover:to-green-700') }}"
                                        data-seat="{{ $seatNumber }}"
                                        {{ $isOccupied ? 'title=This seat is already booked' : 'title=Select seat '.$seatNumber }}
                                    >
                                        <!-- Seat Image with enhanced effect -->
                                        <div class="seat-image w-7 h-7 sm:w-11 sm:h-11 flex items-center justify-center {{ $isOccupied ? 'opacity-70' : ($isSelected ? 'filter brightness-125' : 'filter brightness-90') }} mobile-seat-image">
                                            <img src="{{ asset('img/car-seat.png') }}" alt="Seat" class="w-full h-full object-contain">
                                        </div>
                                        
                                        <!-- Seat Number -->
                                        <div class="seat-number absolute -bottom-2 -right-1 sm:-bottom-2 sm:-right-1 text-xs sm:text-sm font-bold {{ $isOccupied ? 'text-red-700' : ($isSelected ? 'text-white' : 'text-green-700') }}">
                                            {{ $seatNumber }}
                                        </div>
                                        
                                        @if($isOccupied)
                                            <div class="occupied-overlay absolute inset-0 bg-red-500 bg-opacity-60 rounded-xl flex items-center justify-center">
                                                <i class="fas fa-times text-white sm:text-lg"></i>
                                            </div>
                                        @endif
                                    </div>
                                @endfor
                            </div>
                            
                            <!-- Aisle separator with better styling -->
                            <div class="aisle w-6 sm:w-12 flex flex-col items-center justify-center mx-3">
                                <div class="w-1 h-full bg-gradient-to-b from-transparent via-gray-400 to-transparent rounded-full"></div>
                                <div class="text-xs font-medium mt-1 text-gray-500">AISLE</div>
                                <div class="w-1 h-full bg-gradient-to-b from-transparent via-gray-400 to-transparent rounded-full"></div>
                            </div>
                            
                            <!-- Right side (3 seats) -->
                            <div class="right-seats flex gap-3 sm:gap-4 ml-3">
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
                                        class="seat-item w-11 h-11 sm:w-16 sm:h-16 flex items-center justify-center rounded-xl cursor-pointer transition-all duration-300 transform hover:scale-105 hover:shadow-xl shadow-lg relative mobile-seat-item
                                            {{ $isOccupied ? 'bg-red-100 cursor-not-allowed opacity-80' : ($isSelected ? 'bg-gradient-to-br from-blue-400 to-blue-600 ring-4 ring-blue-300' : 'bg-gradient-to-br from-green-400 to-green-600 hover:from-green-500 hover:to-green-700') }}"
                                        data-seat="{{ $seatNumber }}"
                                        {{ $isOccupied ? 'title=This seat is already booked' : 'title=Select seat '.$seatNumber }}
                                    >
                                        <!-- Seat Image with enhanced effect -->
                                        <div class="seat-image w-7 h-7 sm:w-11 sm:h-11 flex items-center justify-center {{ $isOccupied ? 'opacity-70' : ($isSelected ? 'filter brightness-125' : 'filter brightness-90') }} mobile-seat-image">
                                            <img src="{{ asset('img/car-seat.png') }}" alt="Seat" class="w-full h-full object-contain">
                                        </div>
                                        
                                        <!-- Seat Number -->
                                        <div class="seat-number absolute -bottom-2 -right-1 sm:-bottom-2 sm:-right-1 text-xs sm:text-sm font-bold {{ $isOccupied ? 'text-red-700' : ($isSelected ? 'text-white' : 'text-green-700') }}">
                                            {{ $seatNumber }}
                                        </div>
                                        
                                        @if($isOccupied)
                                            <div class="occupied-overlay absolute inset-0 bg-red-500 bg-opacity-60 rounded-xl flex items-center justify-center">
                                                <i class="fas fa-times text-white sm:text-lg"></i>
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
        <div class="flex flex-col sm:flex-row sm:justify-center sm:space-x-4 gap-3">
            <button id="recommend-seats" class="bg-gradient-to-r from-green-600 to-emerald-700 hover:from-green-700 hover:to-emerald-800 text-white font-bold py-2 px-4 rounded-lg transition duration-300 shadow-lg text-sm sm:text-base">
                <i class="fas fa-lightbulb mr-2"></i> Recommend Seats
            </button>
            <button id="save-seats" class="save-seats-btn text-sm sm:text-base" title="Save your seat selection">
                <i class="fas fa-save mr-2"></i> Save Seats
            </button>
        </div>
    </div>
    
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Seat selection functionality
    const seats = document.querySelectorAll('.seat-item:not(.bg-red-100)');
    const selectedSeatsDisplay = document.getElementById('selected-seats-display');
    const selectedCount = document.getElementById('selected-count');
    const saveSeatsButton = document.getElementById('save-seats');
    const recommendSeatsButton = document.getElementById('recommend-seats');
    const windowSeatCheckbox = document.getElementById('window-seat');
    const aisleSeatCheckbox = document.getElementById('aisle-seat');
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
    
    // Seat preference functionality
    function recommendSeats() {
        // Clear current selections
        selectedSeats = [];
        seats.forEach(seat => {
            if (seat.classList.contains('bg-blue-100')) {
                seat.classList.remove('bg-blue-100', 'ring-4', 'ring-blue-500');
                seat.classList.add('bg-green-100');
                // Remove filter from seat image
                const seatImage = seat.querySelector('.seat-image');
                if (seatImage) {
                    seatImage.classList.remove('filter', 'brightness-75');
                }
                // Update seat number color
                const seatNumberEl = seat.querySelector('.seat-number');
                if (seatNumberEl) {
                    seatNumberEl.classList.remove('text-blue-700');
                    seatNumberEl.classList.add('text-green-700');
                }
            }
        });
        
        // Get available seats with preferences
        const availableSeats = Array.from(seats).map(seat => parseInt(seat.getAttribute('data-seat'))).filter(seat => !selectedSeats.includes(seat));
        
        // Filter by preferences (only if checkboxes exist)
        let preferredSeats = availableSeats;
        if (windowSeatCheckbox && windowSeatCheckbox.checked) {
            preferredSeats = preferredSeats.filter(seat => {
                // Window seats are typically 1st and 3rd in each row (2-3 layout)
                const seatPositionInRow = ((seat - 1) % 5) + 1;
                return seatPositionInRow === 1 || seatPositionInRow === 3;
            });
        }
        
        if (aisleSeatCheckbox && aisleSeatCheckbox.checked) {
            preferredSeats = preferredSeats.filter(seat => {
                // Aisle seats are typically 2nd and 5th in each row (2-3 layout)
                const seatPositionInRow = ((seat - 1) % 5) + 1;
                return seatPositionInRow === 2 || seatPositionInRow === 5;
            });
        }
        
        // If no seats match preferences, fall back to all available seats
        if (preferredSeats.length === 0) {
            preferredSeats = availableSeats;
        }
        
        // Select recommended seats
        for (let i = 0; i < Math.min({{ $booking->number_of_seats }}, preferredSeats.length); i++) {
            const seatNumber = preferredSeats[i];
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
                
                selectedSeats.push(seatNumber);
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
    }
    
    // Add event listener for recommend button
    recommendSeatsButton.addEventListener('click', recommendSeats);
    
    // Show estimated time for each step
    function showStepTimeEstimates() {
        // This is a simplified version - in a real app, you might want to load actual data
        const stepTimes = {
            schedule: { time: '< 1 min', desc: 'Select your preferred schedule' },
            passenger: { time: '< 1 min', desc: 'Enter your personal information' },
            seat: { time: '1-2 min', desc: 'Choose your preferred seat' },
            payment: { time: '< 1 min', desc: 'Complete your payment' }
        };
        
        // In a real implementation, you might want to show these times near the stepper
        console.log('Step time estimates:', stepTimes);
    }
    
    showStepTimeEstimates();
    
    // Enhanced drag and drop functionality for seat selection
    let isDragging = false;
    let dragStartSeat = null;
    
    seats.forEach(seat => {
        // Add drag events for drag selection
        seat.addEventListener('mousedown', function(e) {
            if (!this.classList.contains('bg-red-100')) { // Only for available seats
                isDragging = true;
                dragStartSeat = this;
                
                // Toggle the starting seat
                if (this.classList.contains('bg-blue-100')) {
                    // Deselect
                    this.classList.remove('bg-blue-100', 'ring-4', 'ring-blue-500');
                    this.classList.add('bg-green-500');
                    const seatImage = this.querySelector('.seat-image');
                    if (seatImage) seatImage.classList.remove('filter', 'brightness-125');
                    const seatNumberEl = this.querySelector('.seat-number');
                    if (seatNumberEl) seatNumberEl.classList.remove('text-white');
                    
                    const seatNumber = parseInt(this.getAttribute('data-seat'));
                    selectedSeats = selectedSeats.filter(num => num !== seatNumber);
                } else {
                    // Select if we haven't reached the max
                    if (selectedSeats.length < {{ $booking->number_of_seats }}) {
                        this.classList.remove('bg-green-500');
                        this.classList.add('bg-blue-500', 'ring-4', 'ring-blue-300');
                        const seatImage = this.querySelector('.seat-image');
                        if (seatImage) seatImage.classList.add('filter', 'brightness-125');
                        const seatNumberEl = this.querySelector('.seat-number');
                        if (seatNumberEl) seatNumberEl.classList.add('text-white');
                        
                        const seatNumber = parseInt(this.getAttribute('data-seat'));
                        selectedSeats.push(seatNumber);
                    }
                }
                
                // Update display
                selectedSeatsDisplay.textContent = selectedSeats.length > 0 ? selectedSeats.join(', ') : 'None';
                selectedCount.textContent = selectedSeats.length;
                saveSeatsButton.disabled = selectedSeats.length !== {{ $booking->number_of_seats }};
                
                if (selectedSeats.length > 0) {
                    selectedSeatsDisplay.classList.add('bg-blue-100', 'text-blue-800');
                    selectedSeatsDisplay.classList.remove('text-gray-500');
                } else {
                    selectedSeatsDisplay.classList.remove('bg-blue-100', 'text-blue-800');
                    selectedSeatsDisplay.classList.add('text-gray-500');
                }
            }
        });
        
        seat.addEventListener('mouseenter', function(e) {
            if (isDragging && !this.classList.contains('bg-red-100')) {
                // For drag selection
                if (dragStartSeat !== this) {
                    if (this.classList.contains('bg-blue-500')) {
                        // Deselect
                        this.classList.remove('bg-blue-500', 'ring-4', 'ring-blue-300');
                        this.classList.add('bg-green-500');
                        const seatImage = this.querySelector('.seat-image');
                        if (seatImage) seatImage.classList.remove('filter', 'brightness-125');
                        const seatNumberEl = this.querySelector('.seat-number');
                        if (seatNumberEl) seatNumberEl.classList.remove('text-white');
                        
                        const seatNumber = parseInt(this.getAttribute('data-seat'));
                        selectedSeats = selectedSeats.filter(num => num !== seatNumber);
                    } else if (selectedSeats.length < {{ $booking->number_of_seats }}) {
                        // Select
                        this.classList.remove('bg-green-500');
                        this.classList.add('bg-blue-500', 'ring-4', 'ring-blue-300');
                        const seatImage = this.querySelector('.seat-image');
                        if (seatImage) seatImage.classList.add('filter', 'brightness-125');
                        const seatNumberEl = this.querySelector('.seat-number');
                        if (seatNumberEl) seatNumberEl.classList.add('text-white');
                        
                        const seatNumber = parseInt(this.getAttribute('data-seat'));
                        selectedSeats.push(seatNumber);
                    }
                    
                    // Update display
                    selectedSeatsDisplay.textContent = selectedSeats.length > 0 ? selectedSeats.join(', ') : 'None';
                    selectedCount.textContent = selectedSeats.length;
                    saveSeatsButton.disabled = selectedSeats.length !== {{ $booking->number_of_seats }};
                }
            }
        });
    });
    
    document.addEventListener('mouseup', function() {
        isDragging = false;
        dragStartSeat = null;
    });
    
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