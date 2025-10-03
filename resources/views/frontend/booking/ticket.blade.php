<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bus Ticket - {{ $booking->booking_code }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0f2f5;
            padding: 20px;
            display: flex;
            justify-content: center;
            min-height: 100vh;
        }
        
        .ticket-wrapper {
            width: 100%;
            max-width: 820px;
            margin: 0 auto;
        }
        
        .bus-ticket {
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            position: relative;
            border: 1px solid #e0e0e0;
        }
        
        /* Header section */
        .header {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
            color: white;
            padding: 25px 30px;
            position: relative;
            text-align: center;
        }
        
        .logo-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .logo {
            font-size: 28px;
            font-weight: 800;
            letter-spacing: 1px;
        }
        
        .logo-text {
            font-size: 22px;
            font-weight: 700;
        }
        
        .document-type {
            text-align: right;
            font-size: 16px;
            opacity: 0.9;
        }
        
        .company-name {
            font-size: 28px;
            font-weight: 700;
            letter-spacing: 1px;
            margin: 10px 0;
        }
        
        .tagline {
            font-size: 16px;
            opacity: 0.9;
        }
        
        /* Route section */
        .route-section {
            display: flex;
            background: #fef2f2;
            border-bottom: 2px solid #dc2626;
            padding: 25px 30px;
        }
        
        .origin-box, .destination-box {
            flex: 1;
            text-align: center;
        }
        
        .location-code {
            font-size: 42px;
            font-weight: 700;
            color: #dc2626;
            line-height: 1;
        }
        
        .location-name {
            font-size: 18px;
            color: #4b5563;
            margin-top: 8px;
            font-weight: 600;
        }
        
        .route-arrow {
            align-self: center;
            font-size: 28px;
            color: #dc2626;
            padding: 0 20px;
        }
        
        /* Trip details */
        .trip-details {
            display: flex;
            background: #f9fafb;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .trip-detail-item {
            flex: 1;
            padding: 20px 30px;
            border-right: 1px solid #e5e7eb;
            text-align: center;
        }
        
        .trip-detail-item:last-child {
            border-right: none;
        }
        
        .detail-label {
            font-size: 13px;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
        }
        
        .detail-value {
            font-size: 20px;
            font-weight: 700;
            color: #111827;
        }
        
        .date-value {
            font-size: 18px;
        }
        
        /* Passenger and booking info */
        .passenger-info {
            display: flex;
            flex-wrap: wrap;
            padding: 25px 30px;
        }
        
        .info-group {
            flex: 1;
            min-width: 200px;
            padding: 0 15px;
            margin-bottom: 20px;
        }
        
        .info-group h3 {
            font-size: 13px;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }
        
        .info-value {
            font-size: 18px;
            font-weight: 600;
            color: #111827;
        }
        
        /* Bus info */
        .bus-info {
            background: #fffbeb;
            padding: 20px 30px;
            border-top: 1px solid #e5e7eb;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .bus-details {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 15px;
        }
        
        .bus-item {
            flex: 1;
            min-width: 150px;
        }
        
        .bus-label {
            font-size: 13px;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
        }
        
        .bus-value {
            font-size: 18px;
            font-weight: 700;
            color: #111827;
        }
        
        /* Barcode section */
        .barcode-section {
            background: #f3f4f6;
            padding: 25px 30px;
            text-align: center;
            border-top: 1px dashed #d1d5db;
            border-bottom: 1px dashed #d1d5db;
        }
        
        .barcode-label {
            font-size: 14px;
            color: #6b7280;
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .barcode-container {
            background: white;
            padding: 20px;
            border-radius: 6px;
            display: inline-block;
            margin: 15px 0;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        
        .booking-code-large {
            font-size: 24px;
            font-weight: 800;
            letter-spacing: 3px;
            color: #dc2626;
            margin-top: 15px;
            font-family: 'Courier New', monospace;
        }
        
        /* Boarding info */
        .boarding-info {
            display: flex;
            padding: 25px 30px;
            background: #fef2f2;
        }
        
        .boarding-item {
            flex: 1;
            text-align: center;
            padding: 0 15px;
        }
        
        .boarding-label {
            font-size: 13px;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }
        
        .boarding-value {
            font-size: 22px;
            font-weight: 700;
            color: #dc2626;
        }
        
        .boarding-warning {
            background: #fffbeb;
            color: #d97706;
            padding: 15px 20px;
            text-align: center;
            font-size: 14px;
            font-weight: 500;
            border-radius: 6px;
            margin: 20px 30px;
            border-left: 4px solid #f59e0b;
        }
        
        /* Footer */
        .footer {
            padding: 20px 30px;
            background: #f9fafb;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            font-size: 12px;
            color: #6b7280;
        }
        
        .contact-info {
            margin-bottom: 10px;
        }
        
        /* Print button */
        .print-button {
            display: block;
            width: 220px;
            margin: 30px auto;
            padding: 16px;
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
            color: white;
            text-align: center;
            text-decoration: none;
            border-radius: 30px;
            font-weight: 600;
            font-size: 16px;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(220, 38, 38, 0.3);
            transition: all 0.3s ease;
            border: none;
        }
        
        .print-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(220, 38, 38, 0.4);
        }
        
        .print-button:active {
            transform: translateY(0);
        }
        
        /* Print styles */
        @media print {
            body {
                background: white;
                padding: 0;
                margin: 0;
            }
            
            .ticket-wrapper {
                max-width: 100%;
                padding: 0;
            }
            
            .print-button {
                display: none;
            }
            
            .bus-ticket {
                box-shadow: none;
                border-radius: 0;
                border: none;
            }
        }
        
        /* Responsive styles */
        @media (min-width: 1024px) {
            .trip-details {
                display: flex;
            }
            
            .trip-detail-item {
                border-right: 1px solid #e5e7eb;
            }
            
            .trip-detail-item:last-child {
                border-right: none;
            }
            
            .passenger-info {
                flex-direction: row;
            }
            
            .boarding-info {
                flex-direction: row;
            }
            
            .route-section {
                flex-direction: row;
            }
            
            .route-arrow {
                transform: none;
                padding: 0 20px;
            }
        }
        
        @media (min-width: 768px) and (max-width: 1023px) {
            .trip-details {
                display: flex;
            }
            
            .trip-detail-item {
                border-right: 1px solid #e5e7eb;
                padding: 15px 20px;
            }
            
            .trip-detail-item:last-child {
                border-right: none;
            }
            
            .detail-value {
                font-size: 18px;
            }
            
            .passenger-info {
                flex-direction: row;
            }
            
            .boarding-info {
                flex-direction: row;
            }
        }
        
        @media (max-width: 767px) {
            .route-section {
                flex-direction: column;
                text-align: center;
            }
            
            .route-arrow {
                transform: rotate(90deg);
                padding: 15px 0;
            }
            
            .trip-details {
                flex-direction: column;
            }
            
            .trip-detail-item {
                border-right: none;
                border-bottom: 1px solid #e5e7eb;
            }
            
            .trip-detail-item:last-child {
                border-bottom: none;
            }
            
            .passenger-info {
                flex-direction: column;
            }
            
            .boarding-info {
                flex-direction: column;
                gap: 20px;
            }
            
            .bus-details {
                flex-direction: column;
            }
        }
        
        @media (max-width: 480px) {
            .header {
                padding: 20px;
            }
            
            .logo-section {
                flex-direction: column;
                gap: 10px;
                text-align: center;
            }
            
            .document-type {
                text-align: center;
            }
            
            .route-section, .passenger-info, .barcode-section, .boarding-info, .footer {
                padding: 20px;
            }
            
            .info-group {
                min-width: 100%;
                padding: 0;
            }
        }
    </style>
</head>
<body>
    <div class="ticket-wrapper">
        <div class="bus-ticket">
            <!-- Header -->
            <div class="header">
                <div class="logo-section">
                    <div class="logo">TJT</div>
                    <div class="document-type">TIKET BUS</div>
                </div>
                <div class="company-name">TUNGGAL JAYA TRANSPORT</div>
                <div class="tagline">Reliable. Comfortable. On-Time.</div>
            </div>
            
            <!-- Route Section -->
            <div class="route-section">
                <div class="origin-box">
                    <div class="location-code">{{ strtoupper(substr($booking->schedule->route->origin, 0, 3)) }}</div>
                    <div class="location-name">{{ $booking->schedule->route->origin }}</div>
                </div>
                <div class="route-arrow">→</div>
                <div class="destination-box">
                    <div class="location-code">{{ strtoupper(substr($booking->schedule->route->destination, 0, 3)) }}</div>
                    <div class="location-name">{{ $booking->schedule->route->destination }}</div>
                </div>
            </div>
            
            <!-- Trip Details -->
            <div class="trip-details">
                <div class="trip-detail-item">
                    <div class="detail-label">Date</div>
                    <div class="detail-value date-value">{{ $booking->schedule->getDepartureTimeWIB()->format('d M Y') }}</div>
                </div>
                <div class="trip-detail-item">
                    <div class="detail-label">Booking Code</div>
                    <div class="detail-value">{{ $booking->booking_code }}</div>
                </div>
                <div class="trip-detail-item">
                    <div class="detail-label">Passenger</div>
                    <div class="detail-value">{{ $booking->passenger_name }}</div>
                </div>
            </div>
            
            <!-- Passenger Info -->
            <div class="passenger-info">
                <div class="info-group">
                    <h3>Contact Information</h3>
                    <div class="info-value">{{ $booking->passenger_phone }}</div>
                    <div class="info-value">{{ $booking->passenger_email }}</div>
                </div>
                <div class="info-group">
                    <h3>Seat Numbers</h3>
                    <div class="info-value">{{ $booking->seat_numbers }}</div>
                </div>
                <div class="info-group">
                    <h3>Number of Seats</h3>
                    <div class="info-value">{{ $booking->number_of_seats }}</div>
                </div>
            </div>
            
            <!-- Bus Info -->
            <div class="bus-info">
                <div class="bus-details">
                    <div class="bus-item">
                        <div class="bus-label">Bus Number</div>
                        <div class="bus-value">{{ $booking->schedule->bus->name ?? 'Standard Bus' }}</div>
                    </div>
                    <div class="bus-item">
                        <div class="bus-label">Plate Number</div>
                        <div class="bus-value">{{ $booking->schedule->bus->plate_number ?? '-' }}</div>
                    </div>
                    <div class="bus-item">
                        <div class="bus-label">Bus Type</div>
                        <div class="bus-value">{{ $booking->schedule->bus->bus_type ?? 'Standard' }}</div>
                    </div>
                </div>
            </div>
            
            <!-- Barcode Section -->
            <div class="barcode-section">
                <div class="barcode-label">Barcode Tiket - Pindai di Titik Keberangkatan</div>
                <div class="barcode-container">
                    @php
                        use Milon\Barcode\DNS1D;
                        $dns1d = new DNS1D();
                        echo $dns1d->getBarcodeSVG($booking->booking_code, 'C128', 2.5, 50);
                    @endphp
                </div>
                <div class="booking-code-large">{{ $booking->booking_code }}</div>
            </div>
            
            <!-- Boarding Info -->
            <div class="boarding-info">
                <div class="boarding-item">
                    <div class="boarding-label">Departure Time</div>
                    <div class="boarding-value">{{ $booking->schedule->getDepartureTimeWIB()->format('H:i') }}</div>
                </div>
                <div class="boarding-item">
                    <div class="boarding-label">Arrival Time</div>
                    <div class="boarding-value">{{ $booking->schedule->getArrivalTimeWIB()->format('H:i') }}</div>
                </div>
                <div class="boarding-item">
                    <div class="boarding-label">Boarding Point</div>
                    <div class="boarding-value">{{ $booking->schedule->route->origin }}</div>
                </div>
            </div>
            
            <div class="boarding-warning">
                Please arrive at the boarding point at least 15 minutes before departure time
            </div>
            
            <!-- Footer -->
            <div class="footer">
                <div class="contact-info">
                    Customer Service: {{ config('app.contact_phone', '+62 123 456 789') }} • 
                    Email: {{ config('app.contact_email', 'info@tunggaljayatransport.com') }}
                </div>
                <div>© {{ date('Y') }} Tunggal Jaya Transport. All rights reserved. • Non-refundable and non-transferable</div>
                <div style="margin-top: 10px; font-weight: 600;">Have a safe journey!</div>
            </div>
        </div>
        
        <button onclick="window.print()" class="print-button">🖨️ Cetak Tiket Bus</button>
        
        <div style="display: flex; justify-content: center; gap: 15px; margin-top: 20px;">
            <a href="{{ route('frontend.booking.download-ticket', $booking->id) }}" 
               style="display: inline-block; padding: 12px 20px; background: #10b981; color: white; text-decoration: none; border-radius: 30px; font-weight: 600; font-size: 16px; box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3); transition: all 0.3s ease;">
                📥 Download PDF
            </a>
            <a href="{{ route('frontend.booking.index') }}" 
               style="display: inline-block; padding: 12px 20px; background: #3b82f6; color: white; text-decoration: none; border-radius: 30px; font-weight: 600; font-size: 16px; box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3); transition: all 0.3s ease;">
                🚌 Pesan Tiket Lainnya
            </a>
        </div>
    </div>
    
    <script>
        // Auto-focus on print button for accessibility
        document.querySelector('.print-button')?.focus();
    </script>
</body>
</html>