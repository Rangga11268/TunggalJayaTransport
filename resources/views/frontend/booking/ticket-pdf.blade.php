<!DOCTYPE html>
<html>
<head>
    <title>E-Tiket Bus - {{ $booking->booking_code }}</title>
    <meta charset="utf-8">
    <style>
        @page {
            size: A4 portrait;
            margin: 10mm;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
            font-size: 8px;
            line-height: 1.1;
        }

        .ticket-container {
            width: 100%;
            margin: 0 auto;
            padding: 5mm;
            box-sizing: border-box;
            background-color: white;
            border: 1px solid #e5e7eb;
            border-radius: 3px;
            height: 250mm; /* Fixed height to ensure it fits in margins */
            display: flex;
            flex-direction: column;
        }

        /* Header Section */
        .ticket-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 3mm;
            border-bottom: 1px solid #e5e7eb;
            margin-bottom: 4mm;
        }

        .logo-section {
            display: flex;
            align-items: center;
        }

        .logo {
            width: 18mm;
            height: auto;
        }

        .ticket-title {
            font-size: 11px;
            font-weight: bold;
            color: #1e40af;
            text-align: center;
        }

        /* Route Section */
        .route-section {
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 4mm 0;
            padding: 3mm 0;
            background-color: #f8fafc;
            border-radius: 3px;
        }

        .route-item {
            flex: 1;
            text-align: center;
        }

        .route-name {
            font-size: 12px;
            font-weight: bold;
            color: #1e40af;
        }

        .route-time {
            font-size: 9px;
            color: #4b5563;
            margin-top: 1.5mm;
        }

        .route-arrow {
            font-size: 14px;
            color: #9ca3af;
            margin: 0 4mm;
        }

        /* Date */
        .date-section {
            text-align: center;
            font-size: 11px;
            font-weight: bold;
            color: #1e40af;
            margin: 3.5mm 0;
        }

        /* Content area */
        .ticket-content {
            display: flex;
            flex: 1;
            gap: 4mm;
            margin-bottom: 4mm;
        }

        .main-column {
            flex: 2;
            display: flex;
            flex-direction: column;
            gap: 4mm;
        }

        .side-column {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 4mm;
        }

        /* Information Cards */
        .info-card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 3px;
            padding: 2.5mm;
            flex: 1; /* Allows cards to grow and fill available space */
        }

        .card-title {
            font-size: 9px;
            font-weight: bold;
            color: #1e40af;
            margin: 0 0 2mm 0;
            padding-bottom: 1.5mm;
            border-bottom: 1px solid #cbd5e1;
        }

        .info-row {
            display: flex;
            margin-bottom: 1.8mm;
            font-size: 8px;
        }

        .info-label {
            flex: 1.2;
            font-weight: bold;
            color: #4b5563;
        }

        .info-value {
            flex: 1.8;
            color: #1f2937;
        }

        /* QR Code Section */
        .qr-section {
            text-align: center;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 3px;
            padding: 3mm;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .qr-code {
            width: 16mm;
            height: 16mm;
            margin: 0 auto;
        }

        .booking-code {
            font-size: 8.5px;
            font-weight: bold;
            color: #1e40af;
            margin-top: 2mm;
        }

        /* Footer */
        .ticket-footer {
            margin-top: auto; /* Pushes footer to the bottom when possible */
            padding-top: 4mm;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            font-size: 6.5px;
            color: #6b7280;
        }

        .terms {
            margin-bottom: 2.5mm;
        }

        .contact-info {
            font-weight: bold;
            color: #1e40af;
        }
    </style>
</head>
<body>
    <div class="ticket-container">
        <!-- Header -->
        <div class="ticket-header">
            <div class="logo-section">
                <img src="{{ base_path('public/img/logoNoBg.png') }}" alt="Tunggal Jaya Logo" class="logo" style="max-width: 100%; height: auto;">
            </div>
            <div class="ticket-title">
                <div>E-Tiket Bus</div>
                <div style="font-size: 7px; color: #4b5563;">Boarding Pass</div>
            </div>
        </div>

        <!-- Route Information -->
        <div class="route-section">
            <div class="route-item">
                <div class="route-name">{{ $booking->schedule->route->origin }}</div>
                <div class="route-time">{{ $booking->schedule->getDepartureTimeWIB()->format('H:i') }} WIB</div>
            </div>
            <div class="route-arrow">→</div>
            <div class="route-item">
                <div class="route-name">{{ $booking->schedule->route->destination }}</div>
                <div class="route-time">{{ $booking->schedule->getActualArrivalTime()->format('H:i') }} WIB</div>
            </div>
        </div>

        <!-- Date -->
        <div class="date-section">
            {{ $booking->schedule->getDepartureTimeWIB()->format('d F Y') }}
        </div>

        <!-- Main Content -->
        <div class="ticket-content">
            <!-- Main Column -->
            <div class="main-column">
                <!-- Journey Details -->
                <div class="info-card">
                    <div class="card-title">Detail Perjalanan</div>
                    <div class="info-row">
                        <div class="info-label">Kode Booking:</div>
                        <div class="info-value">{{ $booking->booking_code }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Tanggal:</div>
                        <div class="info-value">{{ $booking->schedule->getDepartureTimeWIB()->format('d F Y') }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Waktu Berangkat:</div>
                        <div class="info-value">{{ $booking->schedule->getDepartureTimeWIB()->format('H:i') }} WIB</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">PO:</div>
                        <div class="info-value">Tunggal Jaya</div>
                    </div>
                </div>

                <!-- Passenger Details -->
                <div class="info-card">
                    <div class="card-title">Detail Penumpang</div>
                    <div class="info-row">
                        <div class="info-label">Nama:</div>
                        <div class="info-value">{{ $booking->passenger_name }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Jumlah:</div>
                        <div class="info-value">{{ $booking->number_of_seats }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Kursi:</div>
                        <div class="info-value">{{ $booking->seat_numbers }}</div>
                    </div>
                </div>
            </div>

            <!-- Side Column -->
            <div class="side-column">
                <!-- Payment Details -->
                <div class="info-card">
                    <div class="card-title">Pembayaran</div>
                    <div class="info-row">
                        <div class="info-label">Harga:</div>
                        <div class="info-value">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Status:</div>
                        <div class="info-value" style="color: #10b981; font-weight: bold;">LUNAS</div>
                    </div>
                </div>

                <!-- QR Code -->
                <div class="qr-section">
                    <div class="qr-code">
                        @php
                            $qr_generator = new Milon\Barcode\DNS2D();
                            echo $qr_generator->getBarcodeSVG($booking->booking_code, 'QRCODE', 4, 4);
                        @endphp
                    </div>
                    <div class="booking-code">{{ $booking->booking_code }}</div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="ticket-footer">
            <div class="terms">
                <p style="margin: 0.5mm 0;"><strong>Perhatian:</strong></p>
                <p style="margin: 0.5mm 0;">• Tunjukkan tiket saat boarding</p>
                <p style="margin: 0.5mm 0;">• Tiket tidak dapat dikembalikan</p>
                <p style="margin: 0.5mm 0;">• Bawa identitas sesuai data</p>
            </div>
            <div class="contact-info">
                <p style="margin: 0.5mm 0;">CS: +62 123 456 789</p>
                <p style="margin: 0.5mm 0;">Email: info@tunggaljayatransport.com</p>
                <p style="margin: 0.5mm 0;">www.tunggaljayatransport.com</p>
            </div>
        </div>
    </div>
</body>
</html>