<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>E-Ticket - {{ $booking->booking_code }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        @page {
            margin: 13mm;
            size: A4 portrait;
        }

        body {
            font-family: 'DejaVu Sans', 'Arial', sans-serif;
            color: #333;
            line-height: 1.4;
            font-size: 10pt;
        }

        .ticket-container {
            width: 100%;
            border: 3px solid #667eea;
            border-radius: 10px;
            overflow: hidden;
        }

        /* Logo Section */
        .logo-section {
            padding: 0 25px 0;
            text-align: center;
            border-bottom: none;
        }

        .logo-container {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .logo {
            height: 32px;
            width: auto;
        }

        .logo-text {
            font-size: 15px;
            font-weight: bold;
            color: #2c3e50;
        }

        /* Header */
        .ticket-header {
            background: linear-gradient(to right, #667eea, #764ba2);
            color: white;
            padding: 0 25px 5px;
            text-align: center;
        }

        .company-name {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 5px;
            letter-spacing: 1px;
        }

        .ticket-title {
            font-size: 15px;
            font-weight: 600;
            margin-bottom: 3px;
        }

        .ticket-subtitle {
            font-size: 10px;
            opacity: 0.95;
        }

        /* Route Section */
        .route-section {
            background: #f8f9fa;
            padding: 2px 25px 18px;
            border-bottom: 2px dashed #dee2e6;
        }

        .route-container {
            width: 100%;
            border-collapse: collapse;
        }

        .route-container td {
            vertical-align: top;
            padding: 0 10px;
        }

        .route-item {
            width: 42%;
        }

        .route-arrow {
            width: 16%;
            text-align: center;
            font-size: 26px;
            color: #667eea;
            font-weight: bold;
            vertical-align: middle;
        }

        .route-label {
            font-size: 9px;
            color: #6c757d;
            text-transform: uppercase;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .route-city {
            font-size: 17px;
            font-weight: bold;
            color: #2c3e50;
            margin: 5px 0;
        }

        .route-datetime {
            font-size: 10px;
            color: #495057;
            margin: 3px 0;
        }

        .route-time {
            font-size: 12px;
            font-weight: bold;
            color: #667eea;
            margin-top: 3px;
        }

        /* Body */
        .ticket-body {
            padding: 18px 25px;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 18px;
        }

        .info-table td {
            padding: 9px 10px;
            border-bottom: 1px solid #e9ecef;
            width: 50%;
            vertical-align: top;
        }

        .info-label {
            font-size: 9px;
            color: #6c757d;
            text-transform: uppercase;
            font-weight: bold;
            display: block;
            margin-bottom: 4px;
        }

        .info-value {
            font-size: 11px;
            color: #2c3e50;
            font-weight: 600;
        }

        .seat-highlight {
            background: #667eea;
            color: white;
            padding: 5px 12px;
            border-radius: 5px;
            font-size: 12px;
            font-weight: bold;
            display: inline-block;
        }

        .payment-badge {
            background: #28a745;
            color: white;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 9px;
            font-weight: bold;
            display: inline-block;
        }

        /* Barcode Section */
        .bg-white {
            background: white !important;
        }
        
        .rounded-xl {
            border-radius: 8px !important;
        }
        
        .shadow-sm {
            box-shadow: 0 1px 2px rgba(0,0,0,0.05) !important;
        }
        
        .p-2 {
            padding: 8px !important;
        }
        
        .border {
            border: 1px solid #dee2e6 !important;
        }
        
        .border-gray-200 {
            border-color: #dee2e6 !important;
        }
        
        .mb-3 {
            margin-bottom: 12px !important;
        }
        
        .text-xs {
            font-size: 9px !important;
        }
        
        .font-semibold {
            font-weight: 600 !important;
        }
        
        .text-gray-500 {
            color: #6c757d !important;
        }
        
        .uppercase {
            text-transform: uppercase !important;
        }
        
        .mb-1-5 {
            margin-bottom: 6px !important;
        }
        
        .text-center {
            text-align: center !important;
        }
        
        .flex {
            display: flex !important;
        }
        
        .flex-col {
            flex-direction: column !important;
        }
        
        .items-center {
            align-items: center !important;
        }
        
        .bg-gray-50 {
            background-color: #f8f9fa !important;
        }
        
        .p-1-5 {
            padding: 6px !important;
        }
        
        .rounded-lg {
            border-radius: 8px !important;
        }
        
        .max-w-full {
            max-width: 100% !important;
        }
        
        .overflow-x-auto {
            overflow-x: auto !important;
        }
        
        .font-mono {
            font-family: monospace !important;
        }
        
        .text-sm {
            font-size: 14px !important;
        }
        
        .font-bold {
            font-weight: bold !important;
        }
        
        .text-gray-800 {
            color: #495057 !important;
        }
        
        .tracking-wider {
            letter-spacing: 2px !important;
        }
        
        .break-all {
            word-break: break-all !important;
        }

        /* Terms */
        .terms-section {
            background: #fff3cd;
            padding: 16px 20px;
            border-left: 4px solid #ffc107;
            margin-top: 16px;
        }

        .terms-title {
            font-size: 10px;
            font-weight: bold;
            color: #856404;
            margin-bottom: 8px;
        }

        .terms-list {
            font-size: 8.5px;
            color: #856404;
            line-height: 1.6;
            margin: 0;
            padding-left: 15px;
        }

        .terms-list li {
            margin-bottom: 4px;
        }

        /* Footer */
        .ticket-footer {
            text-align: center;
            padding: 18px 20px;
            background: #f8f9fa;
            border-top: 2px solid #dee2e6;
            margin-top: 16px;
            font-size: 8.5px;
            color: #6c757d;
            line-height: 1.6;
        }

        .footer-company {
            font-weight: bold;
            font-size: 9.5px;
            color: #2c3e50;
            margin-bottom: 5px;
        }
    </style>
</head>

<body>
    <div class="ticket-container">
        <!-- Logo Section -->
        <div class="logo-section">
            <div class="logo-container">
                <img src="{{ base_path('public/img/logoNoBg.png') }}" alt="Tunggal Jaya Transport Logo" class="logo">
                <div class="logo-text">Tunggal Jaya Transport</div>
            </div>
        </div>

        <!-- Header -->
        <div class="ticket-header">
            <div class="company-name">BUS PO TUNGGAL JAYA</div>
            <div class="ticket-title">E-TIKET BUS</div>
            <div class="ticket-subtitle">Tunggal Jaya Transport - Boarding Pass</div>
        </div>

        <!-- Route Information -->
        <div class="route-section">
            <table class="route-container">
                <tr>
                    <td class="route-item">
                        <div class="route-label">KEBERANGKATAN</div>
                        <div class="route-city">{{ $booking->schedule->route->origin }}</div>
                        <div class="route-datetime">Tanggal:
                            {{ $booking->schedule->getDepartureTimeWIB()->format('d F Y') }}</div>
                        <div class="route-time">Jam: {{ $booking->schedule->getDepartureTimeWIB()->format('H:i') }} WIB
                        </div>
                    </td>
                    <td class="route-arrow">&#8594;</td>
                    <td class="route-item">
                        <div class="route-label">TUJUAN</div>
                        <div class="route-city">{{ $booking->schedule->route->destination }}</div>
                        <div class="route-datetime">Tanggal:
                            {{ $booking->schedule->getActualArrivalTime()->format('d F Y') }}</div>
                        <div class="route-time">Jam: {{ $booking->schedule->getActualArrivalTime()->format('H:i') }} WIB
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Ticket Body -->
        <div class="ticket-body">
            <!-- Passenger Info -->
            <table class="info-table">
                <tr>
                    <td>
                        <span class="info-label">Nama Penumpang</span>
                        <span class="info-value">{{ $booking->passenger_name }}</span>
                    </td>
                    <td>
                        <span class="info-label">Email</span>
                        <span class="info-value">{{ $booking->email }}</span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <span class="info-label">Kode Booking</span>
                        <span class="info-value">{{ $booking->booking_code }}</span>
                    </td>
                    <td>
                        <span class="info-label">Tanggal Pemesanan</span>
                        <span class="info-value">{{ $booking->created_at->format('d F Y') }}</span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <span class="info-label">Nomor Kursi</span>
                        <span class="seat-highlight">{{ $booking->seat_numbers }}</span>
                    </td>
                    <td>
                        <span class="info-label">Jumlah Kursi</span>
                        <span class="info-value">{{ $booking->number_of_seats }} Kursi</span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <span class="info-label">Tipe Bus</span>
                        <span class="info-value">Bus Eksekutif</span>
                    </td>
                    <td>
                        <span class="info-label">Total Harga</span>
                        <span class="info-value">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <span class="info-label">Status Pembayaran</span>
                        <span class="payment-badge">LUNAS</span>
                    </td>
                    <td></td>
                </tr>
            </table>

            <!-- Barcode section with proper scannable barcode -->
            <div class="bg-white rounded-xl shadow-sm p-2 border border-gray-200 mb-3" style="background: white; border: 1px solid #dee2e6; border-radius: 8px; padding: 12px; margin: 16px 0;">
                <h3 class="text-xs font-semibold text-gray-500 uppercase mb-1.5 text-center" style="font-size: 9px; color: #6c757d; text-transform: uppercase; font-weight: bold; margin-bottom: 10px;">Pindai untuk Naik Bus</h3>
                <div class="flex flex-col items-center" style="display: flex; flex-direction: column; align-items: center;">
                    <div class="bg-gray-50 p-1.5 rounded-lg mb-1.5 max-w-full overflow-x-auto" style="background-color: #f8f9fa; padding: 12px; border-radius: 8px; margin-bottom: 8px; max-width: 100%; overflow-x: auto; display: flex; justify-content: center;">
                        @php
                            use Milon\Barcode\DNS1D;
                            $dns1d = new DNS1D();
                            // Generate a proper scannable barcode: code, type, width, height, color (as string), code_with_text
                            echo $dns1d->getBarcodeSVG($booking->booking_code, 'C128', 2, 60, 'black', true);
                        @endphp
                    </div>
                    <p class="text-xs text-center mt-2" style="font-size: 9px; margin-top: 8px;">{{ $booking->booking_code }}</p>
                </div>
            </div>

            <!-- Terms -->
            <div class="terms-section">
                <div class="terms-title">SYARAT & KETENTUAN</div>
                <ul class="terms-list">
                    <li>Tiket ini hanya berlaku untuk tanggal dan kursi yang tercantum di atas.</li>
                    <li>Tunjukkan e-tiket ini (cetak atau digital) beserta identitas valid saat boarding.</li>
                    <li>Tiket tidak dapat dipindahtangankan dan tidak dapat dikembalikan.</li>
                    <li>Bus akan berangkat tepat waktu, pastikan datang paling lambat 30 menit sebelum keberangkatan.
                    </li>
                    <li>Barang bawaan maksimal 1 buah dengan ukuran tidak lebih dari 40x30x20 cm.</li>
                    <li>Barang tertinggal bukan menjadi tanggung jawab PO Tunggal Jaya.</li>
                </ul>
            </div>
        </div>

        <!-- Footer -->
        <div class="ticket-footer">
            <div class="footer-company">PO TUNGGAL JAYA TRANSPORT</div>
            Customer Service: +62 123 456 789 | Email: info@tunggaljayatransport.com<br>
            &copy; {{ date('Y') }} Tunggal Jaya Transport. All rights reserved.
        </div>
    </div>
</body>

</html>
