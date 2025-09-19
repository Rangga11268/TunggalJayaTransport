<!DOCTYPE html>
<html>
<head>
    <title>Ticket - {{ $booking->booking_code }}</title>
    <meta charset="utf-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f8f9fa;
        }
        
        .ticket-container {
            display: flex;
            justify-content: center;
            width: 100%;
        }
        
        .ticket {
            width: 567px; /* 15cm */
            height: 265px; /* 7cm */
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            padding: 15px;
            font-family: Arial, sans-serif;
            position: relative;
            overflow: hidden;
        }
        
        .perforation-top,
        .perforation-bottom {
            position: absolute;
            left: 0;
            right: 0;
            height: 10px;
            background-image: radial-gradient(circle at 50% 50%, transparent 4px, #cbd5e1 4px, #cbd5e1 6px, transparent 6px);
            background-size: 12px 10px;
            background-repeat: repeat-x;
            z-index: 1;
        }
        
        .perforation-top {
            top: 0;
        }
        
        .perforation-bottom {
            bottom: 0;
        }
        
        .ticket-header {
            display: flex;
            align-items: center;
            border-bottom: 1px dashed #cbd5e1;
            padding-bottom: 10px;
            margin-bottom: 12px;
        }
        
        .company-logo {
            margin-right: 10px;
        }
        
        .logo-placeholder {
            font-size: 24px;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #dbeafe;
            border-radius: 50%;
        }
        
        .company-info {
            flex: 1;
        }
        
        .company-name {
            font-size: 16px;
            font-weight: bold;
            color: #1e3a8a;
            margin: 0;
            letter-spacing: 0.5px;
        }
        
        .company-tagline {
            font-size: 10px;
            color: #64748b;
            margin: 2px 0 0 0;
        }
        
        .ticket-body {
            margin-bottom: 12px;
        }
        
        .ticket-info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 8px;
        }
        
        .info-item {
            font-size: 11px;
        }
        
        .info-item label {
            display: block;
            font-weight: bold;
            color: #475569;
            margin-bottom: 2px;
        }
        
        .info-item .info-value {
            font-size: 12px;
            font-weight: 600;
            color: #1e293b;
            background: #f1f5f9;
            padding: 4px 6px;
            border-radius: 4px;
        }
        
        .info-item .seat-number {
            background: #dbeafe;
            color: #1e40af;
            font-size: 14px;
        }
        
        .info-item .price {
            color: #dc2626;
            font-size: 14px;
            background: #fef2f2;
        }
        
        .ticket-footer {
            border-top: 1px dashed #cbd5e1;
            padding-top: 12px;
        }
        
        .barcode-section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
        }
        
        .barcode-placeholder {
            flex: 1;
            padding: 8px;
            background: white;
            border: 1px solid #cbd5e1;
            border-radius: 4px;
            margin-right: 10px;
        }
        
        .barcode-lines {
            display: flex;
            align-items: flex-end;
            height: 30px;
            margin-bottom: 4px;
            justify-content: center;
        }
        
        .barcode-line {
            width: 2px;
            background: #1e293b;
            margin: 0 0.5px;
        }
        
        .barcode-number {
            font-size: 10px;
            font-family: 'Courier New', monospace;
            letter-spacing: 1px;
            text-align: center;
        }
        
        .qr-code-placeholder {
            width: 80px;
            padding: 5px;
            background: white;
            border: 1px solid #cbd5e1;
            border-radius: 4px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        
        .qr-grid {
            display: inline-block;
            border: 1px solid #94a3b8;
            padding: 2px;
        }
        
        .qr-row {
            display: flex;
        }
        
        .qr-cell {
            width: 2px;
            height: 2px;
            background: #e2e8f0;
        }
        
        .qr-cell.filled {
            background: #0f172a;
        }
        
        .qr-label {
            font-size: 8px;
            color: #64748b;
            margin-top: 4px;
            text-align: center;
        }
        
        .instructions {
            font-size: 9px;
            color: #64748b;
            margin-bottom: 8px;
            line-height: 1.3;
        }
        
        .instructions p {
            margin: 1px 0;
        }
        
        .contact-info {
            text-align: center;
            font-size: 9px;
            color: #475569;
        }
        
        .contact-info p {
            margin: 1px 0;
        }
    </style>
</head>
<body>
    <div class="ticket-container">
        <div class="ticket">
            <!-- Perforation Top -->
            <div class="perforation-top"></div>
            
            <!-- Ticket Header -->
            <div class="ticket-header">
                <div class="company-logo">
                    <div class="logo-placeholder">🚌</div>
                </div>
                <div class="company-info">
                    <h1 class="company-name">TUNGGAL JAYA TRANSPORT</h1>
                    <p class="company-tagline">Perjalanan Aman dan Nyaman</p>
                </div>
            </div>
            
            <!-- Ticket Body -->
            <div class="ticket-body">
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
                        <label>Route</label>
                        <div class="info-value">{{ $booking->schedule->route->origin }} → {{ $booking->schedule->route->destination }}</div>
                    </div>
                    
                    <div class="info-item">
                        <label>Departure</label>
                        <div class="info-value">
                            {{ $booking->schedule->getActualDepartureTime()->format('M j, Y') }} | {{ $booking->schedule->getActualDepartureTime()->format('H:i') }}
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
                <div class="barcode-section">
                    <div class="barcode-placeholder">
                        <!-- Barcode will be generated here -->
                        <div class="barcode-lines">
                            @php
                                use Milon\Barcode\DNS1D;
                                $dns1d = new DNS1D();
                                echo $dns1d->getBarcodeSVG($booking->booking_code, 'C128', 1.5, 30);
                            @endphp
                        </div>
                        <div class="barcode-number">{{ $booking->booking_code }}</div>
                    </div>
                    
                    <!-- QR Code Placeholder -->
                    <div class="qr-code-placeholder">
                        <div class="qr-grid">
                            @for ($row = 0; $row < 25; $row++)
                                <div class="qr-row">
                                    @for ($col = 0; $col < 25; $col++)
                                        <div class="qr-cell {{ (rand(0, 1) == 1) ? 'filled' : '' }}"></div>
                                    @endfor
                                </div>
                            @endfor
                        </div>
                        <div class="qr-label">SCAN QR CODE</div>
                    </div>
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
            
            <!-- Perforation Bottom -->
            <div class="perforation-bottom"></div>
        </div>
    </div>
</body>
</html>