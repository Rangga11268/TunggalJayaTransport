<div class="ticket-preview-container max-w-2xl mx-auto my-6 sm:my-8 mobile-ticket-container">
    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl shadow-xl overflow-hidden border border-blue-100 mobile-ticket-content">
        <!-- Header Tiket dengan Branding -->
        <div class="bg-gradient-to-r from-blue-600 to-indigo-700 p-3 sm:p-4 md:p-6 text-white">
            <div class="flex items-center justify-between flex-col sm:flex-row gap-3 sm:gap-4">
                <div class="flex items-center space-x-2 sm:space-x-3 md:space-x-4">
                    <div class="bg-white bg-opacity-20 p-1.5 sm:p-2 md:p-3 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-6 sm:w-6 md:h-8 md:w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                        </svg>
                    </div>
                    <div class="text-center sm:text-left">
                        <h2 class="text-base sm:text-lg md:text-2xl font-bold">Tunggal Jaya Transport</h2>
                        <p class="text-blue-100 text-xs sm:text-xs md:text-sm">Perjalanan Aman dan Nyaman</p>
                    </div>
                </div>
                <div class="text-center sm:text-right">
                    <span class="bg-white bg-opacity-20 px-2 py-1 sm:px-2 sm:py-1 md:px-3 md:py-1 rounded-full text-xs sm:text-xs md:text-sm font-semibold">E-Tiket</span>
                    <p class="text-blue-100 text-xs sm:text-xs md:text-sm mt-1">{{ now()->format('d M Y') }}</p>
                </div>
            </div>
        </div>

        <!-- Main Ticket Content -->
        <div class="p-3 sm:p-4 md:p-6">
            <!-- Informasi Rute -->
            <div class="mb-3 sm:mb-4 md:mb-6">
                <div class="flex items-center justify-between bg-white rounded-xl shadow-sm p-2 sm:p-3 md:p-4 border border-blue-50 flex-col sm:flex-row gap-2 sm:gap-3 md:gap-4">
                    <div class="text-center">
                        <p class="text-xs text-gray-500 uppercase font-semibold">Asal</p>
                        <p class="text-base sm:text-lg md:text-xl font-bold text-gray-800 mt-1">{{ $origin }}</p>
                        <p class="text-xs sm:text-xs md:text-sm text-gray-600">{{ $departureDate }}</p>
                    </div>
                    
                    <div class="flex flex-col items-center">
                        <div class="flex items-center">
                            <div class="w-1.5 h-1.5 sm:w-2 sm:h-2 md:w-3 md:h-3 rounded-full bg-blue-500"></div>
                            <div class="w-4 h-0.5 sm:w-6 sm:h-0.5 md:w-8 md:h-0.5 bg-blue-300 mx-1 sm:mx-1.5 md:mx-2"></div>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 sm:h-4 sm:w-4 md:h-6 md:w-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                            </svg>
                            <div class="w-4 h-0.5 sm:w-6 sm:h-0.5 md:w-8 md:h-0.5 bg-blue-300 mx-1 sm:mx-1.5 md:mx-2"></div>
                            <div class="w-1.5 h-1.5 sm:w-2 sm:h-2 md:w-3 md:h-3 rounded-full bg-blue-500"></div>
                        </div>
                        <p class="text-xs text-gray-500 mt-1 sm:mt-1.5 md:mt-2">{{ $departureTime }}</p>
                    </div>
                    
                    <div class="text-center">
                        <p class="text-xs text-gray-500 uppercase font-semibold">Tujuan</p>
                        <p class="text-base sm:text-lg md:text-xl font-bold text-gray-800 mt-1">{{ $destination }}</p>
                        <p class="text-xs sm:text-xs md:text-sm text-gray-600">{{ $arrivalTime ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <!-- Informasi Penumpang & Pemesanan -->
            <div class="grid grid-cols-1 gap-2 sm:gap-3 md:gap-4 mb-3 sm:mb-4 md:mb-6">
                <div class="bg-white rounded-xl shadow-sm p-2 sm:p-3 md:p-4 border border-blue-50 mobile-ticket-section">
                    <h3 class="text-xs sm:text-xs md:text-sm font-semibold text-gray-500 uppercase mb-1 md:mb-2 mobile-ticket-section-title">Informasi Penumpang</h3>
                    <p class="font-medium text-gray-800 text-sm sm:text-sm md:text-base">{{ $passengerName }}</p>
                    <p class="text-xs sm:text-xs md:text-sm text-gray-600 break-all">{{ $passengerEmail }}</p>
                    <p class="text-xs sm:text-xs md:text-sm text-gray-600">{{ $passengerPhone }}</p>
                </div>
                
                <div class="bg-white rounded-xl shadow-sm p-2 sm:p-3 md:p-4 border border-blue-50 mobile-ticket-section">
                    <h3 class="text-xs sm:text-xs md:text-sm font-semibold text-gray-500 uppercase mb-1 md:mb-2 mobile-ticket-section-title">Detail Pemesanan</h3>
                    <div class="flex justify-between text-xs sm:text-xs md:text-sm">
                        <span class="text-gray-600">Kode Pemesanan:</span>
                        <span class="font-medium text-gray-800 break-all">{{ $bookingCode }}</span>
                    </div>
                    <div class="flex justify-between mt-1 text-xs sm:text-xs md:text-sm">
                        <span class="text-gray-600">Tipe Bus:</span>
                        <span class="font-medium text-gray-800">{{ $busType }}</span>
                    </div>
                    <div class="flex justify-between mt-1 text-xs sm:text-xs md:text-sm">
                        <span class="text-gray-600">Nomor Kursi:</span>
                        <span class="font-bold text-blue-600">{{ $seatNumber }}</span>
                    </div>
                </div>
            </div>

            <!-- Harga -->
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl shadow-sm p-2 sm:p-3 md:p-4 border border-green-100 mb-3 sm:mb-4 md:mb-6">
                <div class="flex justify-between items-center flex-col sm:flex-row gap-2 sm:gap-3 md:gap-4">
                    <div class="text-center sm:text-left">
                        <h3 class="text-xs sm:text-xs md:text-sm font-semibold text-gray-600">Total Harga</h3>
                        <p class="text-lg sm:text-xl md:text-2xl font-bold text-green-700">{{ $price }}</p>
                    </div>
                    <div class="text-center sm:text-right">
                        <p class="text-xs sm:text-xs md:text-sm text-gray-600">Status Pembayaran</p>
                        <span class="inline-flex items-center px-2 py-1 sm:px-2 sm:py-1 md:px-3 md:py-1 rounded-full text-xs sm:text-xs md:text-sm font-medium bg-green-100 text-green-800">
                            <svg class="mr-0.5 h-2.5 w-2.5 sm:mr-1 sm:h-3 sm:w-3 md:mr-1 md:h-4 md:w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Lunas
                        </span>
                    </div>
                </div>
            </div>

            <!-- Bagian Barcode -->
            <div class="bg-white rounded-xl shadow-sm p-2 sm:p-3 md:p-4 border border-gray-200 mb-3 sm:mb-4 md:mb-6">
                <h3 class="text-xs sm:text-xs md:text-sm font-semibold text-gray-500 uppercase mb-1.5 sm:mb-2 md:mb-3 text-center">Pindai untuk Naik Bus</h3>
                <div class="flex flex-col items-center">
                    <div class="bg-gray-50 p-1.5 sm:p-2 md:p-4 rounded-lg mb-1.5 sm:mb-2 md:mb-3 max-w-full overflow-x-auto">
                        @php
                            use Milon\Barcode\DNS1D;
                            $dns1d = new DNS1D();
                            echo $dns1d->getBarcodeSVG($bookingCode, 'C128', 1, 25, '1.5px', true);
                        @endphp
                    </div>
                    <p class="font-mono text-sm sm:text-base md:text-lg font-bold text-gray-800 tracking-wider text-center break-all">{{ $bookingCode }}</p>
                </div>
            </div>

            <!-- Informasi Penting -->
            <div class="bg-amber-50 rounded-xl shadow-sm p-2 sm:p-3 md:p-4 border border-amber-100">
                <h3 class="text-xs sm:text-xs md:text-sm font-semibold text-amber-800 uppercase mb-1.5 sm:mb-2 md:mb-3 flex items-center">
                    <svg class="mr-0.5 h-2.5 w-2.5 sm:mr-1 sm:h-3 sm:w-3 md:mr-1 md:h-4 md:w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    Informasi Penting
                </h3>
                <ul class="text-xs sm:text-xs md:text-sm text-amber-700 space-y-1">
                    <li class="flex items-start">
                        <span class="mr-1 sm:mr-1.5 md:mr-2">•</span>
                        <span>Datang minimal 30 menit sebelum waktu keberangkatan</span>
                    </li>
                    <li class="flex items-start">
                        <span class="mr-1 sm:mr-1.5 md:mr-2">•</span>
                        <span>Bawa tiket ini dan ID yang sah saat naik bus</span>
                    </li>
                    <li class="flex items-start">
                        <span class="mr-1 sm:mr-1.5 md:mr-2">•</span>
                        <span>Titik keberangkatan: Terminal Utama {{ $origin }}</span>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Ticket Footer -->
        <div class="bg-gray-50 px-3 py-2 sm:px-4 sm:py-3 md:px-6 md:py-4 border-t border-gray-200">
            <div class="flex flex-col sm:flex-row justify-between items-center text-center gap-1">
                <div class="text-xs sm:text-xs md:text-sm text-gray-600">
                    Customer Service: +62 123 456 789
                </div>
                <div class="text-xs sm:text-xs md:text-sm text-gray-600">
                    www.tunggaljayatransport.com
                </div>
            </div>
        </div>
    </div>

    <!-- Tombol Aksi -->
    <div class="flex flex-col sm:flex-row justify-center gap-2 sm:gap-3 md:gap-4 mt-3 sm:mt-4 md:mt-6 mobile-button-group">
        <button onclick="window.print()" class="flex items-center justify-center px-3 py-2 sm:px-4 sm:py-2.5 md:px-6 md:py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg shadow-md transition duration-200 text-xs sm:text-sm md:text-base mobile-action-button">
            <svg class="mr-0.5 h-3 w-3 sm:mr-1 sm:h-4 sm:w-4 md:mr-2 md:h-5 md:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
            </svg>
            Cetak Tiket
        </button>
        <a href="{{ route('frontend.booking.download-ticket', $bookingId ?? 0) }}" class="flex items-center justify-center px-3 py-2 sm:px-4 sm:py-2.5 md:px-6 md:py-3 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg shadow-md transition duration-200 text-xs sm:text-sm md:text-base mobile-action-button">
            <svg class="mr-0.5 h-3 w-3 sm:mr-1 sm:h-4 sm:w-4 md:mr-2 md:h-5 md:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            Unduh PDF
        </a>
    </div>
</div>