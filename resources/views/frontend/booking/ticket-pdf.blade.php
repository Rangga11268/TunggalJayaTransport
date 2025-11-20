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
            margin: 10mm;
            size: A4 portrait;
        }

        body {
            font-family: 'DejaVu Sans', 'Arial', sans-serif;
            color: #1e293b;
            line-height: 1.5;
            font-size: 10pt;
        }

        .ticket-container {
            width: 100%;
            border: 3px solid #4F46E5;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        /* Header Section */
        .ticket-header {
            background: linear-gradient(135deg, #4F46E5 0%, #8B5CF6 100%);
            color: white;
            padding: 12px 20px 8px;
            text-align: center;
        }

        .header-logo {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-bottom: 4px;
        }

        .logo {
            height: 32px;
            width: auto;
        }

        .logo-text {
            font-size: 15px;
            font-weight: bold;
            letter-spacing: 0.5px;
        }

        .company-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 2px;
            letter-spacing: 1.5px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .ticket-title {
            font-size: 13px;
            font-weight: 600;
            opacity: 0.95;
            letter-spacing: 0.5px;
        }

        /* Booking Code Banner */
        .booking-code-banner {
            background: #FEF3C7;
            border-left: 5px solid #F59E0B;
            border-right: 5px solid #F59E0B;
            padding: 6px 20px;
            text-align: center;
        }

        .booking-code-label {
            font-size: 9px;
            color: #92400E;
            text-transform: uppercase;
            font-weight: bold;
            margin-bottom: 2px;
        }

        .booking-code-value {
            font-size: 16px;
            font-weight: bold;
            color: #92400E;
            letter-spacing: 3px;
            font-family: 'Courier New', monospace;
        }

        /* Route Section */
        .route-section {
            background: linear-gradient(to bottom, #F8FAFC, #F1F5F9);
            padding: 12px 20px;
            border-bottom: 2px dashed #CBD5E1;
        }

        .route-container {
            width: 100%;
            border-collapse: collapse;
        }

        .route-container td {
            vertical-align: top;
            padding: 0 15px;
        }

        .route-item {
            width: 40%;
        }

        .route-arrow {
            width: 20%;
            text-align: center;
            vertical-align: middle;
        }

        .route-arrow-icon {
            font-size: 32px;
            color: #8B5CF6;
            font-weight: bold;
        }

        .route-label {
            font-size: 9px;
            color: #64748B;
            text-transform: uppercase;
            font-weight: bold;
            margin-bottom: 6px;
        }

        .route-city {
            font-size: 16px;
            font-weight: bold;
            color: #4F46E5;
            margin: 4px 0;
        }

        .route-datetime {
            font-size: 10px;
            color: #475569;
            margin: 3px 0;
        }

        .route-time {
            font-size: 12px;
            font-weight: bold;
            color: #8B5CF6;
            margin-top: 3px;
        }

        /* Ticket Body */
        .ticket-body {
            padding: 10px 20px;
            background: white;
        }

        .section-title {
            font-size: 11px;
            font-weight: bold;
            color: #4F46E5;
            text-transform: uppercase;
            margin-bottom: 6px;
            padding-bottom: 3px;
            border-bottom: 2px solid #E0E7FF;
        }

        .info-grid {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }

        .info-grid td {
            padding: 5px 8px;
            border-bottom: 1px solid #F1F5F9;
            width: 50%;
            vertical-align: top;
        }

        .info-label {
            font-size: 9px;
            color: #64748B;
            text-transform: uppercase;
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }

        .info-value {
            font-size: 11px;
            color: #1e293b;
            font-weight: 600;
        }

        .seat-highlight {
            background: linear-gradient(135deg, #4F46E5, #8B5CF6);
            color: white;
            padding: 6px 14px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: bold;
            display: inline-block;
            box-shadow: 0 2px 4px rgba(79, 70, 229, 0.3);
        }

        .payment-badge {
            background: linear-gradient(135deg, #10B981, #059669);
            color: white;
            padding: 5px 14px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: bold;
            display: inline-block;
            box-shadow: 0 2px 4px rgba(16, 185, 129, 0.3);
        }

        .price-highlight {
            font-size: 14px;
            font-weight: bold;
            color: #4F46E5;
        }

        /* Barcode Section */
        .barcode-section {
            background: white;
            border: 2px solid #E0E7FF;
            border-radius: 8px;
            padding: 8px;
            margin: 8px 0;
            text-align: center;
        }

        .barcode-title {
            font-size: 9px;
            font-weight: bold;
            color: #4F46E5;
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        .barcode-container {
            background: #F8FAFC;
            padding: 8px;
            border-radius: 6px;
            display: inline-block;
            margin: 0 auto;
        }

        .barcode-image {
            max-width: 100%;
            height: auto;
        }

        .barcode-text {
            font-size: 9px;
            color: #64748B;
            margin-top: 6px;
            font-family: 'Courier New', monospace;
            letter-spacing: 1.5px;
        }

        /* Terms Section */
        .terms-section {
            background: #EFF6FF;
            border-left: 3px solid #3B82F6;
            padding: 8px 15px;
            margin-top: 10px;
            border-radius: 6px;
        }

        .terms-title {
            font-size: 10px;
            font-weight: bold;
            color: #1E40AF;
            margin-bottom: 6px;
            text-transform: uppercase;
        }

        .terms-list {
            font-size: 8px;
            color: #1E40AF;
            line-height: 1.5;
            margin: 0;
            padding-left: 15px;
        }

        .terms-list li {
            margin-bottom: 3px;
        }

        /* Footer */
        .ticket-footer {
            background: linear-gradient(to right, #1E293B, #334155);
            color: white;
            text-align: center;
            padding: 12px 20px;
            font-size: 8px;
            line-height: 1.6;
        }

        .footer-company {
            font-weight: bold;
            font-size: 10px;
            margin-bottom: 6px;
            letter-spacing: 1px;
        }

        .footer-contact {
            opacity: 0.9;
            margin-bottom: 4px;
        }

        .footer-copyright {
            opacity: 0.7;
            font-size: 8px;
            margin-top: 6px;
        }
    </style>
</head>

<body>
    <div class="ticket-container">
        <!-- Header -->
        <div class="ticket-header">
            <div class="header-logo">
                <img src="{{ base_path('public/img/logoNoBg.png') }}" alt="Logo" class="logo">
                <div class="logo-text">TUNGGAL JAYA TRANSPORT</div>
            </div>
            <div class="company-name">E-TIKET BUS</div>
            <div class="ticket-title">Boarding Pass - Travel Comfortable</div>
        </div>

        <!-- Booking Code Banner -->
        <div class="booking-code-banner">
            <div class="booking-code-label">Kode Booking</div>
            <div class="booking-code-value">{{ $booking->booking_code }}</div>
        </div>

        <!-- Route Information -->
        <div class="route-section">
            <table class="route-container">
                <tr>
                    <td class="route-item">
                        <div class="route-label">⬤ Keberangkatan</div>
                        <div class="route-city">{{ $booking->schedule->route->origin }}</div>
                        <div class="route-datetime">
                            📅 {{ $booking->schedule->getDepartureTimeWIB()->format('d F Y') }}
                        </div>
                        <div class="route-time">
                            🕐 {{ $booking->schedule->getDepartureTimeWIB()->format('H:i') }} WIB
                        </div>
                    </td>
                    <td class="route-arrow">
                        <div class="route-arrow-icon">→</div>
                    </td>
                    <td class="route-item">
                        <div class="route-label">⬤ Tujuan</div>
                        <div class="route-city">{{ $booking->schedule->route->destination }}</div>
                        <div class="route-datetime">
                            📅 {{ $booking->schedule->getActualArrivalTime()->format('d F Y') }}
                        </div>
                        <div class="route-time">
                            🕐 {{ $booking->schedule->getActualArrivalTime()->format('H:i') }} WIB
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Ticket Body -->
        <div class="ticket-body">
            <!-- Passenger Information -->
            <div class="section-title">👤 Informasi Penumpang</div>
            <table class="info-grid">
                <tr>
                    <td>
                        <span class="info-label">Nama Penumpang</span>
                        <span class="info-value">{{ $booking->passenger_name }}</span>
                    </td>
                    <td>
                        <span class="info-label">Email</span>
                        <span class="info-value">{{ $booking->email ?? $booking->user->email ?? '-' }}</span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <span class="info-label">No. Telepon</span>
                        <span class="info-value">{{ $booking->phone ?? $booking->user->phone ?? '-' }}</span>
                    </td>
                    <td>
                        <span class="info-label">Tanggal Booking</span>
                        <span class="info-value">{{ $booking->created_at->format('d M Y') }}</span>
                    </td>
                </tr>
            </table>

            <!-- Ticket & Payment Info (Combined) -->
            <div class="section-title">🎫 Informasi Tiket & Pembayaran</div>
            <table class="info-grid">
                <tr>
                    <td>
                        <span class="info-label">Nomor Kursi</span><br>
                        <span class="seat-highlight">{{ $booking->seat_numbers }}</span>
                    </td>
                    <td>
                        <span class="info-label">Tipe Bus</span><br>
                        <span class="info-value">Bus Eksekutif</span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <span class="info-label">Total Harga</span><br>
                        <span class="info-value price-highlight">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</span>
                    </td>
                    <td>
                        <span class="info-label">Status Pembayaran</span><br>
                        @if($booking->payment_status === 'paid')
                            <span class="payment-badge">LUNAS</span>
                        @else
                            <span class="payment-badge" style="background: #F59E0B; color: white;">PENDING</span>
                        @endif
                    </td>
                </tr>
            </table>

            <!-- Barcode Section -->
            <div class="barcode-section">
                <div class="barcode-title">📱 Pindai Barcode Untuk Boarding</div>
                <div class="barcode-container">
                    @php
                        use Milon\Barcode\DNS1D;
                        $dns1d = new DNS1D();
                        // Generate barcode as PNG base64 instead of SVG for better PDF compatibility
                        $barcode = $dns1d->getBarcodePNG($booking->booking_code, 'C128', 3, 60);
                        $barcodeImage = 'data:image/png;base64,' . $barcode;
                    @endphp
                    <img src="{{ $barcodeImage }}" alt="Barcode" class="barcode-image">
                </div>
                <div class="barcode-text">{{ $booking->booking_code }}</div>
            </div>

            <!-- Terms & Conditions -->
            <div class="terms-section">
                <div class="terms-title">📋 Syarat & Ketentuan</div>
                <ul class="terms-list">
                    <li>Tiket berlaku untuk tanggal dan kursi yang tercantum. Tunjukkan e-tiket dan identitas saat boarding.</li>
                    <li>Harap datang 30 menit sebelum keberangkatan. Tiket tidak dapat dipindahtangankan atau dikembalikan.</li>
                    <li>Bagasi maksimal 20kg/penumpang. Dilarang membawa barang berbahaya atau ilegal.</li>
                </ul>
            </div>
        </div>

        <!-- Footer -->
        <div class="ticket-footer">
            <div class="footer-company">PO TUNGGAL JAYA TRANSPORT</div>
            <div class="footer-contact">
                📞 Customer Service: +62 123 456 789 | ✉️ Email: info@tunggaljayatransport.com<br>
                🌐 Website: www.tunggaljayatransport.com
            </div>
            <div class="footer-copyright">
                &copy; {{ date('Y') }} Tunggal Jaya Transport. All rights reserved. | Terima kasih telah memilih layanan kami!
            </div>
        </div>
    </div>
</body>

</html>
