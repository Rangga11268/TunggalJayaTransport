<div class="ticket-preview-container max-w-2xl mx-auto my-8 mobile-ticket-container">
    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl shadow-xl overflow-hidden border border-blue-100 mobile-ticket-content">
        <!-- Ticket Header with Branding -->
        <div class="bg-gradient-to-r from-blue-600 to-indigo-700 p-4 sm:p-6 text-white">
            <div class="flex items-center justify-between flex-col sm:flex-row gap-4">
                <div class="flex items-center space-x-3 sm:space-x-4">
                    <div class="bg-white bg-opacity-20 p-2 sm:p-3 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 sm:h-8 sm:w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg sm:text-2xl font-bold">Tunggal Jaya Transport</h2>
                        <p class="text-blue-100 text-xs sm:text-sm">Perjalanan Aman dan Nyaman</p>
                    </div>
                </div>
                <div class="text-center sm:text-right">
                    <span class="bg-white bg-opacity-20 px-2 py-1 sm:px-3 sm:py-1 rounded-full text-xs sm:text-sm font-semibold">E-Ticket</span>
                    <p class="text-blue-100 text-xs sm:text-sm mt-1">{{ now()->format('d M Y') }}</p>
                </div>
            </div>
        </div>

        <!-- Main Ticket Content -->
        <div class="p-4 sm:p-6">
            <!-- Route Information -->
            <div class="mb-4 sm:mb-6">
                <div class="flex items-center justify-between bg-white rounded-xl shadow-sm p-3 sm:p-4 border border-blue-50 flex-col sm:flex-row gap-3 sm:gap-4">
                    <div class="text-center">
                        <p class="text-xs text-gray-500 uppercase font-semibold">Origin</p>
                        <p class="text-lg sm:text-xl font-bold text-gray-800 mt-1">{{ $origin }}</p>
                        <p class="text-xs sm:text-sm text-gray-600">{{ $departureDate }}</p>
                    </div>
                    
                    <div class="flex flex-col items-center">
                        <div class="flex items-center">
                            <div class="w-2 h-2 sm:w-3 sm:h-3 rounded-full bg-blue-500"></div>
                            <div class="w-8 h-0.5 sm:w-12 sm:h-0.5 bg-blue-300 mx-1 sm:mx-2"></div>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-6 sm:w-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                            </svg>
                            <div class="w-8 h-0.5 sm:w-12 sm:h-0.5 bg-blue-300 mx-1 sm:mx-2"></div>
                            <div class="w-2 h-2 sm:w-3 sm:h-3 rounded-full bg-blue-500"></div>
                        </div>
                        <p class="text-xs text-gray-500 mt-1 sm:mt-2">{{ $departureTime }}</p>
                    </div>
                    
                    <div class="text-center">
                        <p class="text-xs text-gray-500 uppercase font-semibold">Destination</p>
                        <p class="text-lg sm:text-xl font-bold text-gray-800 mt-1">{{ $destination }}</p>
                        <p class="text-xs sm:text-sm text-gray-600">{{ $arrivalTime ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <!-- Passenger & Booking Details -->
            <div class="grid grid-cols-1 gap-3 sm:gap-4 mb-4 sm:mb-6">
                <div class="bg-white rounded-xl shadow-sm p-3 sm:p-4 border border-blue-50 mobile-ticket-section">
                    <h3 class="text-xs sm:text-sm font-semibold text-gray-500 uppercase mb-2 mobile-ticket-section-title">Passenger Information</h3>
                    <p class="font-medium text-gray-800 text-sm sm:text-base">{{ $passengerName }}</p>
                    <p class="text-xs sm:text-sm text-gray-600">{{ $passengerEmail }}</p>
                    <p class="text-xs sm:text-sm text-gray-600">{{ $passengerPhone }}</p>
                </div>
                
                <div class="bg-white rounded-xl shadow-sm p-3 sm:p-4 border border-blue-50 mobile-ticket-section">
                    <h3 class="text-xs sm:text-sm font-semibold text-gray-500 uppercase mb-2 mobile-ticket-section-title">Booking Details</h3>
                    <div class="flex justify-between text-xs sm:text-sm">
                        <span class="text-gray-600">Booking Code:</span>
                        <span class="font-medium text-gray-800">{{ $bookingCode }}</span>
                    </div>
                    <div class="flex justify-between mt-1 text-xs sm:text-sm">
                        <span class="text-gray-600">Bus Type:</span>
                        <span class="font-medium text-gray-800">{{ $busType }}</span>
                    </div>
                    <div class="flex justify-between mt-1 text-xs sm:text-sm">
                        <span class="text-gray-600">Seat Number:</span>
                        <span class="font-bold text-blue-600">{{ $seatNumber }}</span>
                    </div>
                </div>
            </div>

            <!-- Pricing -->
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl shadow-sm p-3 sm:p-4 border border-green-100 mb-4 sm:mb-6">
                <div class="flex justify-between items-center flex-col sm:flex-row gap-3 sm:gap-4">
                    <div>
                        <h3 class="text-xs sm:text-sm font-semibold text-gray-600">Total Amount</h3>
                        <p class="text-xl sm:text-2xl font-bold text-green-700">{{ $price }}</p>
                    </div>
                    <div class="text-center sm:text-right">
                        <p class="text-xs sm:text-sm text-gray-600">Payment Status</p>
                        <span class="inline-flex items-center px-2 py-1 sm:px-3 sm:py-1 rounded-full text-xs sm:text-sm font-medium bg-green-100 text-green-800">
                            <svg class="mr-1 h-3 w-3 sm:h-4 sm:w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Paid
                        </span>
                    </div>
                </div>
            </div>

            <!-- Barcode Section -->
            <div class="bg-white rounded-xl shadow-sm p-3 sm:p-4 border border-gray-200 mb-4 sm:mb-6">
                <h3 class="text-xs sm:text-sm font-semibold text-gray-500 uppercase mb-2 sm:mb-3 text-center">Scan for Boarding</h3>
                <div class="flex flex-col items-center">
                    <div class="bg-gray-50 p-2 sm:p-4 rounded-lg mb-2 sm:mb-3 max-w-full overflow-x-auto">
                        @php
                            use Milon\Barcode\DNS1D;
                            $dns1d = new DNS1D();
                            echo $dns1d->getBarcodeSVG($bookingCode, 'C128', 1.5, 40);
                        @endphp
                    </div>
                    <p class="font-mono text-base sm:text-lg font-bold text-gray-800 tracking-wider text-center break-all">{{ $bookingCode }}</p>
                </div>
            </div>

            <!-- Important Information -->
            <div class="bg-amber-50 rounded-xl shadow-sm p-3 sm:p-4 border border-amber-100">
                <h3 class="text-xs sm:text-sm font-semibold text-amber-800 uppercase mb-2 flex items-center">
                    <svg class="mr-1 h-3 w-3 sm:mr-2 sm:h-4 sm:w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    Important Information
                </h3>
                <ul class="text-xs sm:text-sm text-amber-700 space-y-1">
                    <li class="flex items-start">
                        <span class="mr-1 sm:mr-2">•</span>
                        <span>Arrive at least 30 minutes before departure time</span>
                    </li>
                    <li class="flex items-start">
                        <span class="mr-1 sm:mr-2">•</span>
                        <span>Bring this ticket and a valid ID during boarding</span>
                    </li>
                    <li class="flex items-start">
                        <span class="mr-1 sm:mr-2">•</span>
                        <span>Boarding point: Main Terminal {{ $origin }}</span>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Ticket Footer -->
        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:py-4 border-t border-gray-200">
            <div class="flex flex-col sm:flex-row justify-between items-center text-center">
                <div class="text-xs sm:text-sm text-gray-600 mb-1 sm:mb-0">
                    Customer Service: +62 123 456 789
                </div>
                <div class="text-xs sm:text-sm text-gray-600">
                    www.tunggaljayatransport.com
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="flex flex-col sm:flex-row justify-center gap-3 sm:gap-4 mt-4 sm:mt-6 mobile-button-group">
        <button onclick="window.print()" class="flex items-center justify-center px-4 py-2 sm:px-6 sm:py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg shadow-md transition duration-200 text-sm sm:text-base mobile-action-button">
            <svg class="mr-1 h-4 w-4 sm:mr-2 sm:h-5 sm:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
            </svg>
            Print Ticket
        </button>
        <a href="{{ route('frontend.booking.download-ticket', $bookingId ?? 0) }}" class="flex items-center justify-center px-4 py-2 sm:px-6 sm:py-3 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg shadow-md transition duration-200 text-sm sm:text-base mobile-action-button">
            <svg class="mr-1 h-4 w-4 sm:mr-2 sm:h-5 sm:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            Download PDF
        </a>
    </div>
</div>