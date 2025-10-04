<!DOCTYPE html>
<html>
<head>
    <title>Ticket - {{ $booking->booking_code }}</title>
    <meta charset="utf-8">
    <style>
        @page {
            size: {{ $settings->paper_size ?? 'A4' }} portrait;
            margin: 0.4cm;
        }

        body {
            font-family: '{{ $settings->font_settings['family'] ?? 'Arial, sans-serif' }}';
            margin: 0;
            padding: 0;
            background-color: white;
            font-size: {{ $settings->font_settings['size'] ?? 12 }}px;
            color: #1e293b;
        }

        .ticket-container {
            width: 100%;
            max-width: 210mm; /* A4 portrait width */
            height: 297mm; /* A4 portrait height */
            margin: 0 auto;
            padding: 5mm;
            box-sizing: border-box;
        }

        .ticket {
            width: 100%;
            height: 100%;
            background: {{ $settings->color_scheme['background'] ?? '#ffffff' }};
            border: 1px solid {{ $settings->color_scheme['secondary'] ?? '#3b82f6' }};
            border-radius: 8px;
            padding: 10px;
            font-family: '{{ $settings->font_settings['family'] ?? 'Arial, sans-serif' }}';
            position: relative;
            overflow: hidden;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
        }

        .ticket-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px dashed {{ $settings->color_scheme['secondary'] ?? '#3b82f6' }};
            padding-bottom: 10px;
            margin-bottom: 10px;
        }

        .company-logo {
            display: flex;
            align-items: center;
        }

        .logo-placeholder {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: {{ $settings->color_scheme['secondary'] ?? '#3b82f6' }};
            border-radius: 4px;
            color: white;
            font-weight: bold;
            margin-right: 8px;
        }

        .company-info {
            flex: 1;
            margin-left: 8px;
        }

        .company-name {
            font-size: {{ $settings->font_settings['headings'] ?? 16 }}px;
            font-weight: bold;
            color: {{ $settings->color_scheme['primary'] ?? '#1e40af' }};
            margin: 0;
            letter-spacing: 0.5px;
        }

        .company-tagline {
            font-size: 9px;
            color: {{ $settings->color_scheme['secondary'] ?? '#3b82f6' }};
            margin: 1px 0 0 0;
        }

        .ticket-body {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .ticket-info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 8px;
            margin-bottom: 10px;
        }

        .info-item {
            font-size: 10px;
        }

        .info-item label {
            display: block;
            font-weight: bold;
            color: {{ $settings->color_scheme['primary'] ?? '#1e40af' }};
            margin-bottom: 2px;
            font-size: 9px;
        }

        .info-item .info-value {
            font-size: 11px;
            font-weight: 600;
            color: #1e293b;
            padding: 4px 6px;
            border-radius: 4px;
            word-break: break-word;
        }

        .info-item .seat-number {
            background: {{ $settings->color_scheme['accent'] ?? '#10b981' }}20;
            color: {{ $settings->color_scheme['accent'] ?? '#10b981' }};
            font-size: 12px;
        }

        .info-item .price {
            color: #dc2626;
            background: #fef2f2;
        }

        .route-section {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 10px 0;
            padding: 10px 0;
            background: {{ $settings->color_scheme['primary'] ?? '#1e40af' }}10;
            border-radius: 6px;
            margin-bottom: 15px;
        }

        .route-text {
            flex: 1;
            text-align: center;
            font-weight: bold;
            font-size: 16px;
            color: {{ $settings->color_scheme['primary'] ?? '#1e40af' }};
        }

        .route-arrow {
            font-size: 18px;
            color: {{ $settings->color_scheme['secondary'] ?? '#3b82f6' }};
            margin: 0 15px;
        }

        .ticket-footer {
            border-top: 1px dashed {{ $settings->color_scheme['secondary'] ?? '#3b82f6' }};
            padding-top: 10px;
            margin-top: 10px;
        }

        .barcode-section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            align-items: center;
        }

        .barcode-placeholder {
            flex: 1;
            padding: 8px;
            background: white;
            border: 1px solid {{ $settings->color_scheme['secondary'] ?? '#3b82f6' }};
            border-radius: 4px;
            margin-right: 10px;
        }

        .barcode-content {
            text-align: center;
        }

        .barcode-number {
            font-size: 10px;
            font-family: 'Courier New', monospace;
            letter-spacing: 1px;
        }

        .qr-code-placeholder {
            width: 60px;
            height: 60px;
            padding: 3px;
            background: white;
            border: 1px solid {{ $settings->color_scheme['secondary'] ?? '#3b82f6' }};
            border-radius: 4px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .qr-content {
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 7px;
            color: {{ $settings->color_scheme['primary'] ?? '#1e40af' }};
        }

        .instructions {
            font-size: 8px;
            color: #64748b;
            margin-bottom: 8px;
            line-height: 1.3;
        }

        .instructions p {
            margin: 2px 0;
        }

        .contact-info {
            text-align: center;
            font-size: 8px;
            color: #475569;
        }

        .contact-info p {
            margin: 2px 0;
        }

        /* Watermark */
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            opacity: 0.1;
            font-size: 48px;
            font-weight: bold;
            color: {{ $settings->color_scheme['primary'] ?? '#1e40af' }};
            z-index: -1;
            pointer-events: none;
        }

        /* Print-specific styles */
        @media print {
            body {
                background: white;
            }

            .ticket {
                box-shadow: none;
                border: 1px solid {{ $settings->color_scheme['secondary'] ?? '#3b82f6' }};
            }
        }
    </style>
</head>
<body>
    <div class="ticket-container">
        <div class="ticket">
            @if($settings->enable_watermark && $settings->watermark_text)
                <div class="watermark">{{ $settings->watermark_text }}</div>
            @endif

            <!-- Ticket Header -->
            @if($settings->show_company_logo)
            <div class="ticket-header">
                <div class="company-logo">
                    <div class="logo-placeholder">🚌</div>
                    <div class="company-info">
                        <h1 class="company-name">TUNGGAL JAYA TRANSPORT</h1>
                        <p class="company-tagline">Perjalanan Aman dan Nyaman</p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Ticket Body -->
            <div class="ticket-body">
                <!-- Route Information -->
                <div class="route-section">
                    <div class="route-text">{{ $booking->schedule->route->origin }}</div>
                    <div class="route-arrow">→</div>
                    <div class="route-text">{{ $booking->schedule->route->destination }}</div>
                </div>

                <div class="ticket-info-grid">
                    <div class="info-item">
                        <label>Passenger Name</label>
                        <div class="info-value">{{ $booking->passenger_name }}</div>
                    </div>

                    <div class="info-item">
                        <label>Booking Code</label>
                        <div class="info-value">{{ $booking->booking_code }}</div>
                    </div>

                    <div class="info-item">
                        <label>Departure</label>
                        <div class="info-value">
                            {{ $booking->schedule->getDepartureTimeWIB()->format('M j, Y') }} | {{ $booking->schedule->getDepartureTimeWIB()->format('H:i') }} (WIB)
                        </div>
                    </div>

                    <div class="info-item">
                        <label>Arrival</label>
                        <div class="info-value">
                            {{ $booking->schedule->getActualArrivalTime()->format('M j, Y') }} | {{ $booking->schedule->getActualArrivalTime()->format('H:i') }} (WIB)
                        </div>
                    </div>

                    <div class="info-item">
                        <label>Seat Number</label>
                        <div class="info-value seat-number">{{ $booking->seat_numbers }}</div>
                    </div>

                    <div class="info-item">
                        <label>Bus Type</label>
                        <div class="info-value">{{ $booking->schedule->bus->bus_type ?? 'Standard' }}</div>
                    </div>

                    <div class="info-item">
                        <label>Price</label>
                        <div class="info-value price">Rp. {{ number_format($booking->total_price, 0, ',', '.') }}</div>
                    </div>

                    <div class="info-item">
                        <label>Boarding Point</label>
                        <div class="info-value">Main Terminal</div>
                    </div>
                </div>
            </div>

            <!-- Ticket Footer -->
            <div class="ticket-footer">
                @if($settings->show_barcode || $settings->show_qr_code)
                <div class="barcode-section">
                    @if($settings->show_barcode)
                    <div class="barcode-placeholder">
                        <div class="barcode-content">
                            <!-- Barcode will be generated here -->
                            @php
                                \Milon\Barcode\DNS1D;
                                $dns1d = new DNS1D();
                                echo $dns1d->getBarcodeSVG($booking->booking_code, 'C128', 1.5, 30);
                            @endphp
                        </div>
                        <div class="barcode-number">{{ $booking->booking_code }}</div>
                    </div>
                    @endif

                    @if($settings->show_qr_code)
                    <!-- QR Code -->
                    <div class="qr-code-placeholder">
                        <div class="qr-content">
                            @php
                                \Milon\Barcode\DNS2D;
                                $dns2d = new DNS2D();
                                echo $dns2d->getBarcodeSVG($booking->booking_code, 'QRCODE', 4, 4, ['fgcolor'=>array(0,0,0)]);
                            @endphp
                        </div>
                    </div>
                    @endif
                </div>
                @endif

                <div class="instructions">
                    <p>• Please arrive at least 30 minutes before departure</p>
                    <p>• Bring this ticket and a valid ID during boarding</p>
                    <p>• Keep this ticket safe until the end of your journey</p>
                </div>

                <div class="contact-info">
                    <p>Customer Service: +62 123 456 789</p>
                    <p>www.tunggaljayatransport.com</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>