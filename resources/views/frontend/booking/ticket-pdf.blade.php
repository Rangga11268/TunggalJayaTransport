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
            font-family: 'Helvetica Neue', Arial, sans-serif;
            margin: 0;
            padding: 0;
            color: #1e293b;
            font-size: 8px;
            line-height: 1.2;
        }

        .ticket-container {
            width: 100%;
            margin: 0 auto;
            padding: 5mm;
            box-sizing: border-box;
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            border: 2px solid #bae6fd;
            border-radius: 8px;
            height: 250mm;
            display: flex;
            flex-direction: column;
            position: relative;
            overflow: hidden;
        }
        
        .ticket-container::after {
            content: "🚌";
            position: absolute;
            top: 10mm;
            right: 8mm;
            font-size: 20px;
            opacity: 0.1;
            z-index: 0;
            transform: rotate(15deg);
        }

        /* Background image */
        .ticket-container::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: url('{{ base_path('public/img/heroImg.jpg') }}');
            background-size: cover;
            background-position: center;
            opacity: 0.05;
            z-index: 0;
        }

        .ticket-content-wrapper {
            position: relative;
            z-index: 1;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        /* Header Section */
        .ticket-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 3mm;
            border-bottom: 1px solid #bae6fd;
            margin-bottom: 4mm;
            position: relative;
            z-index: 2;
        }
        
        .ticket-header::after {
            content: "";
            position: absolute;
            bottom: -2px;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(to right, transparent, #0ea5e9, transparent);
        }

        .logo-section {
            display: flex;
            align-items: center;
        }

        .logo {
            width: 20mm;
            height: auto;
            filter: drop-shadow(0 1px 1px rgba(0,0,0,0.1));
        }

        .ticket-title {
            font-size: 12px;
            font-weight: bold;
            color: #0369a1;
            text-align: center;
            text-shadow: 0 1px 1px rgba(255,255,255,0.5);
        }

        /* Route Section */
        .route-section {
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 4mm 0;
            padding: 5mm 0;
            background: linear-gradient(135deg, #e0f2fe 0%, #bae6fd 100%);
            border-radius: 8px;
            position: relative;
            z-index: 2;
            box-shadow: 0 2px 4px rgba(0,0,0,0.08);
            border: 1px solid #93c5fd;
        }
        
        .route-section::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxMDAiIGhlaWdodD0iMTAwIiB2aWV3Qm94PSIwIDAgMTAwIDEwMCI+PHBhdGggZD0iTTAgNTBhNTAsNTAgMCAwLDEgMTAwIDBhNTAsNTAgMCAwLDEgLTEwMCAweiIgZmlsbD0icmdiYSgyNTUsMjU1LDI1NSwwLjEpIi8+PC9zdmc+') repeat;
            border-radius: 8px;
            opacity: 0.2;
            z-index: -1;
        }

        .route-item {
            flex: 1;
            text-align: center;
        }

        .route-name {
            font-size: 13px;
            font-weight: bold;
            color: #0284c7;
            text-shadow: 0 1px 1px rgba(255,255,255,0.5);
        }

        .route-time {
            font-size: 9.5px;
            color: #0369a1;
            margin-top: 1.5mm;
        }

        .route-arrow {
            font-size: 16px;
            color: #0ea5e9;
            margin: 0 4mm;
            font-weight: bold;
        }

        /* Date */
        .date-section {
            text-align: center;
            font-size: 12px;
            font-weight: bold;
            color: #0284c7;
            margin: 3.5mm 0;
            background: rgba(224, 242, 254, 0.5);
            padding: 1.5mm 4mm;
            border-radius: 4px;
            display: inline-block;
            margin-left: auto;
            margin-right: auto;
        }

        /* Content area */
        .ticket-content {
            display: flex;
            flex: 1;
            gap: 4mm;
            margin-bottom: 4mm;
            position: relative;
            z-index: 2;
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
            background: rgba(255, 255, 255, 0.85);
            border: 1px solid #bae6fd;
            border-radius: 5px;
            padding: 3mm;
            flex: 1;
            box-shadow: 0 1px 2px rgba(0,0,0,0.03);
            backdrop-filter: blur(5px);
        }

        .card-title {
            font-size: 9.5px;
            font-weight: bold;
            color: #0369a1;
            margin: 0 0 2mm 0;
            padding-bottom: 1.5mm;
            border-bottom: 1px solid #bae6fd;
            background: linear-gradient(to right, #f0f9ff, transparent);
        }

        .info-row {
            display: flex;
            margin-bottom: 1.8mm;
            font-size: 8px;
        }

        .info-label {
            flex: 1.2;
            font-weight: 600;
            color: #02689e;
        }

        .info-value {
            flex: 1.8;
            color: #1e293b;
        }

        /* QR Code Section */
        .qr-section {
            text-align: center;
            background: rgba(255, 255, 255, 0.9);
            border: 1px solid #bae6fd;
            border-radius: 5px;
            padding: 3.5mm;
            display: flex;
            flex-direction: column;
            align-items: center;
            box-shadow: 0 1px 2px rgba(0,0,0,0.03);
        }

        .qr-code {
            width: 17mm;
            height: 17mm;
            margin: 0 auto;
            padding: 1mm;
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 3px;
        }

        .booking-code {
            font-size: 9px;
            font-weight: bold;
            color: #0284c7;
            margin-top: 2.5mm;
            letter-spacing: 0.5px;
        }

        /* Footer */
        .ticket-footer {
            margin-top: auto;
            padding-top: 5mm;
            border-top: 1px solid #bae6fd;
            text-align: center;
            font-size: 6.5px;
            color: #0369a1;
            position: relative;
            z-index: 2;
            background: linear-gradient(to top, #e0f2fe, transparent);
        }
        
        .ticket-footer::before {
            content: "";
            position: absolute;
            top: -2px;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(to right, transparent, #0ea5e9, transparent);
        }

        .terms {
            margin-bottom: 2.5mm;
        }

        .contact-info {
            font-weight: 600;
            color: #0284c7;
        }
    </style>
</head>
<body>
    <div class="ticket-container">
        <div class="ticket-content-wrapper">
            <!-- Header -->
            <div class="ticket-header">
                <div class="logo-section">
                    <img src="{{ base_path('public/img/logoNoBg.png') }}" alt="Tunggal Jaya Logo" class="logo" style="max-width: 100%; height: auto;">
                </div>
                <div class="ticket-title">
                    <div>E-Tiket Bus</div>
                    <div style="font-size: 7.5px;">Boarding Pass</div>
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
                            <div class="info-value" style="color: #059669; font-weight: bold;">LUNAS</div>
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
    </div>
</body>
</html>