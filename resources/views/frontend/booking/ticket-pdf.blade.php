<!DOCTYPE html>
<html>
<head>
    <title>Ticket - {{ $booking->booking_code }}</title>
    <meta charset="utf-8">
    <style>
        @page {
            size: {{ $settings->paper_size ?? 'A4' }} portrait;
            margin: 0;
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
            height: 297mm; /* A4 portrait height */
            margin: 0 auto;
            padding: 0;
            box-sizing: border-box;
            position: relative;
        }

        .ticket {
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, {{ $settings->color_scheme['primary'] ?? '#1e40af' }} 0%, {{ $settings->color_scheme['secondary'] ?? '#3b82f6' }} 100%);
            border: 1px solid {{ $settings->color_scheme['secondary'] ?? '#3b82f6' }};
            border-radius: 0;
            padding: 12mm;
            font-family: '{{ $settings->font_settings['family'] ?? 'Arial, sans-serif' }}';
            position: relative;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            z-index: 1;
            color: white;
        }

        /* Background image is handled via HTML element for DomPDF compatibility */

        .ticket-content {
            position: relative;
            z-index: 2;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        /* Header Section */
        .ticket-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 4mm;
            border-bottom: 1px dashed rgba(255, 255, 255, 0.3);
            margin-bottom: 4mm;
        }

        .logo-section {
            display: flex;
            align-items: center;
        }

        .logo-placeholder {
            width: 25mm;
            height: 12mm;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 4px;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .logo-placeholder img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        .company-info {
            margin-left: 10mm;
            color: white;
        }

        .company-name {
            font-size: {{ $settings->font_settings['headings'] ?? 16 }}px;
            font-weight: bold;
            margin: 0;
            letter-spacing: 0.5px;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.5);
        }

        .company-tagline {
            font-size: 9px;
            margin: 3px 0 0 0;
            opacity: 0.9;
        }

        /* Main Content */
        .ticket-body {
            flex: 1;
            display: flex;
            flex-direction: column;
            color: white;
        }

        .route-section {
            text-align: center;
            margin: 6mm 0;
            padding: 4mm 0;
            border-top: 1px solid rgba(255, 255, 255, 0.3);
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
        }

        .route-text {
            font-weight: bold;
            font-size: 18px;
            margin: 0;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.5);
        }

        .route-arrow {
            font-size: 20px;
            margin: 4px 0;
        }

        .date-time-info {
            font-size: 12px;
            margin: 4px 0;
        }

        .ticket-details-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 8px;
            margin-top: 8mm;
        }

        .detail-item {
            background: rgba(255, 255, 255, 0.15);
            border-radius: 6px;
            padding: 8px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .detail-label {
            display: block;
            font-size: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            opacity: 0.9;
            margin-bottom: 3px;
            text-shadow: 0 1px 1px rgba(0, 0, 0, 0.3);
        }

        .detail-value {
            font-size: 12px;
            font-weight: bold;
            text-shadow: 0 1px 1px rgba(0, 0, 0, 0.3);
        }

        .detail-icon {
            margin-right: 4px;
            font-size: 12px;
        }

        .seat-info {
            background: {{ $settings->color_scheme['accent'] ?? '#10b981' }};
            color: white;
        }

        .price-info {
            background: #dc2626;
            color: white;
        }

        /* Footer with Barcode and QR */
        .ticket-footer {
            margin-top: 8mm;
            border-top: 1px solid rgba(255, 255, 255, 0.3);
            padding-top: 8mm;
        }

        .barcode-qr-section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 6mm;
            align-items: center;
        }

        .barcode-container {
            flex: 1;
            padding: 8px;
            background: white;
            border-radius: 4px;
            margin-right: 8mm;
        }

        .qr-code-container {
            width: 25mm;
            height: 25mm;
            padding: 4px;
            background: white;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .qr-content {
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .booking-code-large {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            color: white;
            margin: 4mm 0;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);
        }

        .instructions {
            font-size: 8px;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 4mm;
            line-height: 1.4;
        }

        .instructions p {
            margin: 2px 0;
        }

        .contact-info {
            text-align: center;
            font-size: 7px;
            color: rgba(255, 255, 255, 0.8);
            padding-top: 4mm;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
        }

        .contact-info p {
            margin: 1px 0;
        }

        /* Icons */
        .icon {
            margin-right: 4px;
            font-size: 12px;
        }

        /* Watermark */
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            opacity: 0.03;
            font-size: 36px;
            font-weight: bold;
            color: rgba(255, 255, 255, 0.7);
            z-index: 0;
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

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .ticket-details-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
</head>
<body>
    <div class="ticket-container">
        <!-- Background for DomPDF -->
        <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; 
            background-image: url('{{ base_path('public/img/heroImg.jpg') }}');
            background-size: cover; 
            background-position: center; 
            opacity: 0.05; 
            z-index: -1;"></div>
        
        <div class="ticket">
            @if($settings->enable_watermark && $settings->watermark_text)
                <div class="watermark">{{ $settings->watermark_text }}</div>
            @endif

            <div class="ticket-content">
                <!-- Ticket Header -->
                <div class="ticket-header">
                    <div class="logo-section">
                        <div class="logo-placeholder">
                            <img src="{{ base_path('public/img/logoNoBg.png') }}" alt="Tunggal Jaya Logo" style="max-height: 100%;">
                        </div>
                        <div class="company-info">
                            <h1 class="company-name">TUNGGAL JAYA TRANSPORT</h1>
                            <p class="company-tagline">Perjalanan Aman dan Nyaman</p>
                        </div>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="ticket-body">
                    <!-- Route Information -->
                    <div class="route-section">
                        <div class="route-text">{{ $booking->schedule->route->origin }}</div>
                        <div class="route-arrow">→</div>
                        <div class="route-text">{{ $booking->schedule->route->destination }}</div>
                        <div class="date-time-info">
                            {{ $booking->schedule->getDepartureTimeWIB()->format('M j, Y') }} | {{ $booking->schedule->getDepartureTimeWIB()->format('H:i') }} (WIB)
                        </div>
                    </div>

                    <!-- Ticket Details Grid -->
                    <div class="ticket-details-grid">
                        <div class="detail-item">
                            <span class="detail-label">Passenger</span>
                            <div class="detail-value">{{ $booking->passenger_name }}</div>
                        </div>

                        <div class="detail-item">
                            <span class="detail-label">Bus Type</span>
                            <div class="detail-value">{{ $booking->schedule->bus->bus_type ?? 'Standard' }}</div>
                        </div>

                        <div class="detail-item">
                            <span class="detail-label">Booking Code</span>
                            <div class="detail-value">{{ $booking->booking_code }}</div>
                        </div>

                        <div class="detail-item">
                            <span class="detail-label">Seat Numbers</span>
                            <div class="detail-value seat-info">{{ $booking->seat_numbers }}</div>
                        </div>

                        <div class="detail-item">
                            <span class="detail-label">Boarding Point</span>
                            <div class="detail-value">{{ $booking->schedule->route->origin ?? 'Main Terminal' }}</div>
                        </div>

                        <div class="detail-item">
                            <span class="detail-label">Price</span>
                            <div class="detail-value price-info">Rp. {{ number_format($booking->total_price, 0, ',', '.') }}</div>
                        </div>
                    </div>
                </div>

                <!-- Footer Section -->
                <div class="ticket-footer">
                    <div class="booking-code-large">{{ $booking->booking_code }}</div>
                    
                    <div class="barcode-qr-section">
                        @if($settings->show_barcode)
                        <div class="barcode-container">
                            @php
                                $generator = new Milon\Barcode\DNS1D();
                                echo $generator->getBarcodeSVG($booking->booking_code, 'C128', 1.5, 40);
                            @endphp
                        </div>
                        @endif

                        @if($settings->show_qr_code)
                        <div class="qr-code-container">
                            <div class="qr-content">
                                @php
                                    $qr_generator = new Milon\Barcode\DNS2D();
                                    echo $qr_generator->getBarcodeSVG($booking->booking_code, 'QRCODE', 4, 4);
                                @endphp
                            </div>
                        </div>
                        @endif
                    </div>

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
    </div>
</body>
</html>