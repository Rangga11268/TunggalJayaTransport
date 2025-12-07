<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>E-Ticket - {{ $booking->booking_code }}</title>
    <style>
        @page {
            margin: 0;
            size: A4 portrait;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            padding: 40px;
            color: #1e293b;
        }
        
        .ticket-container {
            max-width: 100%;
            margin: 0 auto;
            background: #fff;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            position: relative;
        }

        /* Decorative Top Bar */
        .top-bar {
            height: 12px;
            background: linear-gradient(90deg, #06b6d4 0%, #0891b2 100%);
            width: 100%;
        }

        /* Header Section */
        .header {
            padding: 30px 40px;
            border-bottom: 2px dashed #e2e8f0;
            display: table;
            width: 100%;
            background: #fff;
        }
        
        .logo-section {
            display: table-cell;
            vertical-align: middle;
            width: 60%;
        }
        
        .brand-name {
            font-size: 26px;
            font-weight: 900;
            color: #0e7490; /* Cyan 800 */
            letter-spacing: -0.5px;
            text-transform: uppercase;
            margin: 0;
            line-height: 1;
        }
        
        .brand-tagline {
            font-size: 11px;
            color: #64748b;
            letter-spacing: 3px;
            text-transform: uppercase;
            margin-top: 6px;
            font-weight: 600;
        }

        .booking-info {
            display: table-cell;
            vertical-align: middle;
            text-align: right;
            width: 40%;
        }

        .code-label {
            font-size: 10px;
            font-weight: 700;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 4px;
        }

        .code-value {
            font-size: 32px;
            font-weight: 800;
            color: #0e7490;
            letter-spacing: 1px;
            font-family: 'Courier New', Courier, monospace;
        }

        /* Main Route Display */
        .route-section {
            padding: 40px;
            background: linear-gradient(to bottom, #ffffff, #f8fafc);
            display: table;
            width: 100%;
        }

        .location-group {
            display: table-cell;
            width: 40%;
            vertical-align: bottom;
        }
        
        .location-label {
            font-size: 11px;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 8px;
        }

        .location-name {
            font-size: 28px;
            font-weight: 800;
            color: #0f172a;
            line-height: 1.1;
            margin-bottom: 6px;
        }

        .location-meta {
            font-size: 13px;
            color: #475569;
            font-weight: 500;
        }

        .connector {
            display: table-cell;
            width: 20%;
            vertical-align: middle;
            text-align: center;
            position: relative;
        }

        .connector-line {
            border-top: 2px solid #cbd5e1;
            width: 100%;
            position: relative;
            top: -10px;
        }

        .connector-icon {
            background: #fff;
            padding: 0 10px;
            color: #06b6d4;
            font-size: 24px;
            position: relative;
            top: -24px;
        }
        
        .duration-badge {
            background: #ecfeff;
            color: #0891b2;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            display: inline-block;
            margin-top: -10px;
        }

        /* Grid Info */
        .info-grid {
            padding: 0 40px 40px;
            display: table;
            width: 100%;
            border-spacing: 0 20px;
        }

        .info-row {
            display: table-row;
        }

        .info-item {
            display: table-cell;
            width: 33%;
            vertical-align: top;
            padding-right: 20px;
        }

        .info-label {
            font-size: 10px;
            color: #64748b;
            text-transform: uppercase;
            font-weight: 700;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
        }

        .info-value {
            font-size: 15px;
            font-weight: 600;
            color: #0f172a;
        }
        
        .price-tag {
            font-size: 20px;
            font-weight: 800;
            color: #ef4444;
        }

        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .status-paid { background: #dcfce7; color: #166534; }
        .status-pending { background: #fef9c3; color: #854d0e; }

        /* Footer */
        .footer {
            background: #1e293b;
            color: #fff;
            padding: 30px 40px;
            display: table;
            width: 100%;
        }

        .qr-section {
            display: table-cell;
            width: 120px;
            vertical-align: middle;
        }

        .qr-box {
            background: #fff;
            padding: 8px;
            border-radius: 12px;
            display: inline-block;
        }

        .terms-section {
            display: table-cell;
            padding-left: 30px;
            vertical-align: middle;
        }

        .terms-title {
            font-size: 12px;
            font-weight: 700;
            color: #94a3b8;
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        .terms-list {
            font-size: 10px;
            color: #cbd5e1;
            line-height: 1.6;
            margin: 0;
            padding-left: 15px;
        }

        /* Cut Line */
        .cut-mark {
            margin-top: 40px;
            text-align: center;
            border-top: 2px dashed #cbd5e1;
            position: relative;
        }
        .cut-text {
            background: #f0f2f5;
            padding: 0 15px;
            color: #94a3b8;
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
            position: relative;
            top: -8px;
            display: inline-block;
        }

    </style>
</head>
<body>

    <div class="ticket-container">
        <div class="top-bar"></div>
        
        <!-- Header -->
        <div class="header">
            <div class="logo-section">
                <img src="{{ public_path('img/logoNoBg.png') }}" alt="Tunggal Jaya" style="height: 50px; display: block;">
            </div>
            <div class="booking-info">
                <div class="code-label">Booking Reference</div>
                <div class="code-value">{{ $booking->booking_code }}</div>
                <div style="margin-top: 8px;">
                    @php
                        $dns1d = new Milon\Barcode\DNS1D();
                        $barcode = $dns1d->getBarcodePNG($booking->booking_code, 'C128', 2, 40);
                    @endphp
                    <img src="data:image/png;base64,{{ $barcode }}" alt="Barcode" style="height: 35px; width: auto;">
                </div>
            </div>
        </div>

        <!-- Route Visualization -->
        <div class="route-section">
            <div class="location-group">
                <div class="location-label">Departure / Berangkat</div>
                <div class="location-name">{{ $booking->schedule->route->origin }}</div>
                <div class="location-meta">
                    {{ $booking->schedule->getDepartureTimeWIB()->isoFormat('dddd, D MMMM Y') }}<br>
                    <span style="font-size: 18px; font-weight: 700; color: #06b6d4;">{{ $booking->schedule->getDepartureTimeWIB()->format('H:i') }} WIB</span>
                </div>
            </div>
            
            <div class="connector">
                <div class="connector-line"></div>
                <!-- Universal arrow character that renders safely in PDFs -->
                <div class="connector-icon" style="top: -20px; font-size: 30px; color: #06b6d4; line-height: 1; font-family: 'DejaVu Sans', sans-serif;">&rarr;</div>
                <div class="duration-badge">{{ $booking->schedule->route->formatted_duration ?? 'Direct' }}</div>
            </div>
            
            <div class="location-group" style="text-align: right;">
                <div class="location-label">Arrival / Tiba</div>
                <div class="location-name">{{ $booking->schedule->route->destination }}</div>
                <div class="location-meta">
                    {{ $booking->schedule->getActualArrivalTime()->isoFormat('dddd, D MMMM Y') }}<br>
                    <span style="font-size: 18px; font-weight: 700; color: #0f172a;">{{ $booking->schedule->getActualArrivalTime()->format('H:i') }} WIB</span>
                </div>
            </div>
        </div>

        <!-- Details Grid -->
        <div class="info-grid">
            <div class="info-row">
                <div class="info-item">
                    <div class="info-label">Passenger Name</div>
                    <div class="info-value">{{ $booking->passenger_name }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Seat Number</div>
                    <div class="info-value" style="font-size: 18px; color: #06b6d4;">{{ $booking->seat_numbers }}</div>
                </div>
                <div class="info-item" style="text-align: right; padding-right: 0;">
                    <div class="info-label">Bus Class</div>
                    <div class="info-value">{{ $booking->schedule->bus->bus_type ?? 'Executive' }}</div>
                </div>
            </div>

            <div class="info-row">
                 <div class="info-item">
                     <div class="info-label">Bus Info</div>
                     <div class="info-value">{{ $booking->schedule->bus->name }} ({{ $booking->schedule->bus->plate_number }})</div>
                 </div>
                 <div class="info-item">
                     <div class="info-label">Contact</div>
                     <div class="info-value">{{ $booking->passenger_phone }}</div>
                 </div>
                 <div class="info-item" style="text-align: right; padding-right: 0;">
                     <div class="info-label">Total Amount</div>
                     <div class="price-tag">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</div>
                     <div style="margin-top: 5px;">
                        @if($booking->payment_status == 'paid')
                            <span class="status-badge status-paid">PAID / LUNAS</span>
                        @else
                            <span class="status-badge status-pending">PENDING</span>
                        @endif
                     </div>
                 </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <div class="qr-section">
                <div class="qr-box">
                    @php
                        $qrCode = new Milon\Barcode\DNS2D();
                        $qrImage = $qrCode->getBarcodePNG($booking->booking_code, 'QRCODE', 3, 3);
                    @endphp
                    <img src="data:image/png;base64,{{ $qrImage }}" alt="QR Code" width="100">
                </div>
            </div>
            <div class="terms-section">
                <div class="terms-title">Important Information</div>
                <ul class="terms-list">
                    <li>Mohon hadir di titik keberangkatan 30 menit sebelum jadwal.</li>
                    <li>Tunjukkan e-tiket ini kepada petugas saat boarding.</li>
                    <li>Dilarang membawa barang berbahaya, senjata tajam, atau narkoba.</li>
                    <li>Tiket yang sudah dibeli bersifat non-refundable (tidak dapat dikembalikan).</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="cut-mark">
        <span class="cut-text">POTONG DI SINI / CUT HERE</span>
    </div>

    <div style="text-align: center; margin-top: 20px; font-size: 10px; color: #94a3b8;">
        Generated by Tunggal Jaya Transport System on {{ date('d F Y H:i:s') }}
        <br>Support: +62 812-3456-7890 | support@tunggaljaya.com
    </div>

</body>
</html>
