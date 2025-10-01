<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Ticket - {{ $booking->booking_code }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
        }
        
        /* Landscape-friendly styles */
        @media screen and (min-width: 1024px) {
            .landscape-layout {
                transform: rotate(90deg);
                transform-origin: center center;
                width: 100vh;
                height: 100vw;
                margin: auto;
                overflow: hidden;
            }
        }
        
        @media print {
            .no-print {
                display: none !important;
            }
            
            body {
                background-color: white;
                padding: 0;
                margin: 0;
            }
            
            .ticket-container {
                box-shadow: none;
                border-radius: 0;
                max-width: 100% !important;
                margin: 0 !important;
            }
            
            /* Optimize layout for printing */
            .ticket-container {
                box-shadow: none;
            }
            
            .border, .shadow-xl {
                box-shadow: none !important;
                border-width: 1px !important;
            }
            
            /* Adjust grid layout for print */
            .grid.grid-cols-1.md\\:grid-cols-2 {
                display: grid !important;
                grid-template-columns: 1fr 1fr !important;
            }
            
            /* Ensure text is clear in print */
            * {
                -webkit-print-color-adjust: exact !important;
                color-adjust: exact !important;
            }
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="max-w-4xl mx-auto p-4 sm:p-6">
        <div class="ticket-container bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-200">
            <!-- Ticket Header with Branding -->
            <div class="bg-gradient-to-r from-blue-600 to-indigo-700 p-6 sm:p-8 text-white">
                <div class="flex flex-col sm:flex-row items-center justify-between">
                    <div class="flex items-center space-x-4 mb-4 sm:mb-0">
                        <div class="bg-white bg-opacity-20 p-3 rounded-full">
                            <i class="fas fa-bus fa-2x"></i>
                        </div>
                        <div>
                            <h1 class="text-2xl sm:text-3xl font-bold">Tunggal Jaya Transport</h1>
                            <p class="text-blue-100 text-sm sm:text-base">Perjalanan Aman dan Nyaman</p>
                        </div>
                    </div>
                    <div class="text-center sm:text-right">
                        <span class="bg-white bg-opacity-20 px-4 py-2 rounded-full text-sm sm:text-base font-semibold">E-Ticket</span>
                        <p class="text-blue-100 text-sm mt-2">{{ now()->format('d M Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- Main Ticket Content -->
            <div class="p-6 sm:p-8">
                <!-- Route Information -->
                <div class="mb-8">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Trip Details</h2>
                    <div class="flex flex-col sm:flex-row items-center justify-between bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl shadow-sm p-6 border border-blue-100">
                        <div class="text-center mb-4 sm:mb-0">
                            <p class="text-xs text-gray-500 uppercase font-semibold">Origin</p>
                            <p class="text-2xl font-bold text-gray-800 mt-2">{{ $booking->schedule->route->origin }}</p>
                            <p class="text-gray-600 mt-1">
                                {{ $booking->schedule->getDepartureTimeWIB()->format('d M Y') }}
                            </p>
                            <p class="text-lg font-semibold text-blue-600 mt-1">
                                {{ $booking->schedule->getDepartureTimeWIB()->format('H:i') }}
                                <span class="text-xs text-gray-500 ml-1">(WIB)</span>
                            </p>
                        </div>
                        
                        <div class="flex flex-col items-center my-4 sm:my-0">
                            <div class="flex items-center">
                                <div class="w-4 h-4 rounded-full bg-blue-500"></div>
                                <div class="w-20 sm:w-24 h-0.5 bg-blue-300 mx-3"></div>
                                <i class="fas fa-arrow-right text-blue-500 text-2xl mx-2"></i>
                                <div class="w-20 sm:w-24 h-0.5 bg-blue-300 mx-3"></div>
                                <div class="w-4 h-4 rounded-full bg-blue-500"></div>
                            </div>
                            <p class="text-xs text-gray-500 mt-3">{{ $booking->schedule->route->distance ?? 'N/A' }} km</p>
                        </div>
                        
                        <div class="text-center">
                            <p class="text-xs text-gray-500 uppercase font-semibold">Destination</p>
                            <p class="text-2xl font-bold text-gray-800 mt-2">{{ $booking->schedule->route->destination }}</p>
                            <p class="text-gray-600 mt-1">
                                {{ $booking->schedule->getActualArrivalTime()->format('d M Y') }}
                            </p>
                            <p class="text-lg font-semibold text-blue-600 mt-1">
                                {{ $booking->schedule->getActualArrivalTime()->format('H:i') }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Passenger & Booking Details - Changed to horizontal layout for landscape -->
                <div class="mb-8">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Passenger & Booking Details</h2>
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
                            <h3 class="text-md font-semibold text-gray-700 uppercase mb-4 pb-2 border-b border-gray-100">Passenger Information</h3>
                            <div class="space-y-3">
                                <div>
                                    <p class="text-xs text-gray-500 uppercase font-medium">Full Name</p>
                                    <p class="font-medium text-gray-800">{{ $booking->passenger_name }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 uppercase font-medium">Email Address</p>
                                    <p class="text-gray-700 break-words">{{ $booking->passenger_email }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 uppercase font-medium">Phone Number</p>
                                    <p class="text-gray-700">{{ $booking->passenger_phone }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
                            <h3 class="text-md font-semibold text-gray-700 uppercase mb-4 pb-2 border-b border-gray-100">Booking Details</h3>
                            <div class="space-y-3">
                                <div>
                                    <p class="text-xs text-gray-500 uppercase font-medium">Booking Code</p>
                                    <p class="font-medium text-gray-800">{{ $booking->booking_code }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 uppercase font-medium">Bus Information</p>
                                    <p class="text-gray-700">{{ $booking->schedule->bus->name ?? 'Standard Bus' }} ({{ $booking->schedule->bus->plate_number ?? 'N/A' }})</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 uppercase font-medium">Bus Type</p>
                                    <p class="text-gray-700">{{ $booking->schedule->bus->bus_type ?? 'Standard' }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 uppercase font-medium">Seat Numbers</p>
                                    <p class="font-medium text-blue-600">{{ $booking->seat_numbers }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 uppercase font-medium">Number of Seats</p>
                                    <p class="text-gray-700">{{ $booking->number_of_seats }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pricing -->
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-2xl shadow-sm p-6 border border-green-100 mb-8">
                    <div class="flex flex-col sm:flex-row justify-between items-center">
                        <div class="mb-4 sm:mb-0">
                            <h3 class="text-md font-semibold text-gray-700">Payment Summary</h3>
                            <p class="text-sm text-gray-600 mt-1">Total amount paid for this booking</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-600">Total Amount</p>
                            <p class="text-3xl font-bold text-green-700">Rp. {{ number_format($booking->total_price, 0, ',', '.') }}</p>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 mt-2">
                                <i class="fas fa-check-circle mr-1"></i>
                                Paid Successfully
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Barcode & QR Section -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                    <!-- Barcode Section -->
                    <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
                        <h3 class="text-md font-semibold text-gray-700 uppercase mb-4 pb-2 border-b border-gray-100 text-center">Boarding Pass - Scan at Terminal</h3>
                        <div class="flex flex-col items-center">
                            <div class="bg-gray-50 p-4 rounded-xl mb-4">
                                @php
                                    use Milon\Barcode\DNS1D;
                                    $dns1d = new DNS1D();
                                    echo $dns1d->getBarcodeSVG($booking->booking_code, 'C128', 2, 50);
                                @endphp
                            </div>
                            <p class="font-mono text-lg font-bold text-gray-800 tracking-wider break-all">{{ $booking->booking_code }}</p>
                            <p class="text-sm text-gray-600 mt-2 text-center">Present this barcode at the boarding gate</p>
                        </div>
                    </div>

                    <!-- QR Code Section -->
                    <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 flex flex-col items-center justify-center">
                        <h3 class="text-md font-semibold text-gray-700 uppercase mb-4 pb-2 border-b border-gray-100 text-center">QR Code</h3>
                        <div class="flex flex-col items-center">
                            @php
                                use Milon\Barcode\DNS2D;
                                $dns2d = new DNS2D();
                                echo $dns2d->getBarcodeSVG($booking->booking_code, 'QRCODE', 4, 4, ['fgcolor'=>array(0,0,0)]);
                            @endphp
                            <p class="text-sm text-gray-600 mt-2 text-center">Scan for quick verification</p>
                        </div>
                    </div>
                </div>

                <!-- Important Information -->
                <div class="bg-amber-50 rounded-2xl shadow-sm p-6 border border-amber-100">
                    <h3 class="text-md font-semibold text-amber-800 uppercase mb-4 pb-2 border-b border-amber-200 flex items-center">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        Important Travel Information
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="flex items-start">
                            <div class="bg-amber-100 p-2 rounded-lg mr-3">
                                <i class="fas fa-clock text-amber-600"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-amber-800">Arrival Time</h4>
                                <p class="text-sm text-amber-700 mt-1">Arrive at least 30 minutes before departure</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="bg-amber-100 p-2 rounded-lg mr-3">
                                <i class="fas fa-id-card text-amber-600"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-amber-800">Required Documents</h4>
                                <p class="text-sm text-amber-700 mt-1">Bring this ticket and a valid ID</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="bg-amber-100 p-2 rounded-lg mr-3">
                                <i class="fas fa-map-marker-alt text-amber-600"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-amber-800">Boarding Point</h4>
                                <p class="text-sm text-amber-700 mt-1">Main Terminal {{ $booking->schedule->route->origin }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ticket Footer -->
            <div class="bg-gray-50 px-6 sm:px-8 py-6 border-t border-gray-200">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <div class="text-sm text-gray-600 mb-4 md:mb-0">
                        <p><i class="fas fa-phone-alt mr-2"></i>Customer Service: +62 123 456 789</p>
                        <p><i class="fas fa-envelope mr-2"></i>Email: info@tunggaljayatransport.com</p>
                    </div>
                    <div class="text-sm text-gray-600">
                        <p>www.tunggaljayatransport.com</p>
                        <p class="text-center md:text-right mt-1">© {{ date('Y') }} Tunggal Jaya Transport. All rights reserved.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons (Hidden when printing) -->
        <div class="no-print flex flex-col sm:flex-row justify-center gap-4 mt-8">
            <button onclick="window.print()" class="flex items-center justify-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl shadow-md transition duration-200">
                <i class="fas fa-print mr-2"></i>
                Print Ticket
            </button>
            <a href="{{ route('frontend.booking.download-ticket', $booking->id) }}" class="flex items-center justify-center px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-medium rounded-xl shadow-md transition duration-200">
                <i class="fas fa-file-pdf mr-2"></i>
                Download PDF
            </a>
            <a href="{{ route('frontend.booking.index') }}" class="flex items-center justify-center px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-xl shadow-md transition duration-200">
                <i class="fas fa-bus mr-2"></i>
                Book Another Trip
            </a>
        </div>
    </div>
</body>
</html>