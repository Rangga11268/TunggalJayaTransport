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
            color: #1f2937;
            background-color: #f3f4f6;
            margin: 0;
            padding: 40px;
        }
        .container {
            max-width: 100%;
            margin: 0 auto;
            background: #fff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        
        /* Header */
        .header {
            background-color: #EF4444; /* Brand Red */
            color: white;
            padding: 30px;
            display: table;
            width: 100%;
        }
        .header-left {
            display: table-cell;
            vertical-align: middle;
            width: 60%;
        }
        .header-right {
            display: table-cell;
            vertical-align: middle;
            text-align: right;
            width: 40%;
        }
        .logo-text {
            font-size: 24px;
            font-weight: 800;
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        .sub-logo-text {
            font-size: 10px;
            letter-spacing: 2px;
            opacity: 0.9;
            margin-top: 2px;
        }
        .booking-label {
            font-size: 10px;
            text-transform: uppercase;
            opacity: 0.9;
        }
        .booking-code {
            font-size: 28px;
            font-weight: 800;
            letter-spacing: 2px;
            background: rgba(255,255,255,0.2);
            padding: 5px 15px;
            border-radius: 8px;
            display: inline-block;
            margin-top: 5px;
        }

        /* Journey Section */
        .journey {
            padding: 30px;
            border-bottom: 2px dashed #e5e7eb;
            display: table;
            width: 100%;
        }
        .city-box {
            display: table-cell;
            width: 40%;
            vertical-align: top;
        }
        .arrow-box {
            display: table-cell;
            width: 20%;
            text-align: center;
            vertical-align: middle;
        }
        .city-label {
            font-size: 10px;
            text-transform: uppercase;
            color: #6b7280;
            font-weight: 700;
            letter-spacing: 1px;
            margin-bottom: 5px;
        }
        .city-name {
            font-size: 22px;
            font-weight: 800;
            color: #EF4444;
            margin-bottom: 5px;
        }
        .city-time {
            font-size: 14px;
            color: #374151;
            font-weight: 600;
        }
        .city-date {
            font-size: 12px;
            color: #6b7280;
        }
        .arrow {
            font-size: 20px;
            color: #d1d5db;
        }
        .duration {
            font-size: 10px;
            background: #f3f4f6;
            padding: 4px 10px;
            border-radius: 20px;
            color: #4b5563;
            display: inline-block;
            margin-top: 5px;
        }

        /* Details Grid */
        .details {
            padding: 30px;
            display: table;
            width: 100%;
        }
        .detail-row {
            display: table-row;
        }
        .detail-cell {
            display: table-cell;
            padding-bottom: 20px;
            padding-right: 20px;
            width: 33%;
        }
        .detail-label {
            font-size: 10px;
            text-transform: uppercase;
            color: #6b7280;
            font-weight: 700;
            margin-bottom: 4px;
        }
        .detail-value {
            font-size: 14px;
            font-weight: 600;
            color: #111827;
        }
        .seat-badge {
            background-color: #111827;
            color: #fff;
            padding: 4px 12px;
            border-radius: 6px;
            font-weight: bold;
            display: inline-block;
        }
        .paid-badge {
            color: #059669;
            background: #d1fae5;
            padding: 4px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 800;
            border: 1px solid #059669;
        }
        .pending-badge {
            color: #d97706;
            background: #fef3c7;
            padding: 4px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 800;
            border: 1px solid #d97706;
        }

        /* QR Code Section */
        .footer-section {
            background-color: #111827;
            color: white;
            padding: 20px 30px;
            display: table;
            width: 100%;
        }
        .footer-left {
            display: table-cell;
            vertical-align: middle;
        }
        .footer-right {
            display: table-cell;
            text-align: right;
            vertical-align: middle;
        }
        .barcode-box {
            background: white;
            padding: 10px;
            border-radius: 8px;
            display: inline-block;
        }
        .scan-text {
            font-size: 10px;
            color: #9ca3af;
            margin-bottom: 5px;
            text-transform: uppercase;
        }
        .terms {
            font-size: 9px;
            color: #9ca3af;
            line-height: 1.4;
            max-width: 400px;
        }

        /* Cut Line */
        .cut-line {
            border-top: 2px dashed #d1d5db;
            margin: 40px 0;
            position: relative;
            text-align: center;
        }
        .cut-line::after {
            content: "✂ POTONG DI SINI / CUT HERE";
            background: #f3f4f6;
            padding: 0 10px;
            color: #9ca3af;
            font-size: 10px;
            position: relative;
            top: -8px;
        }

        .bus-info-box {
          background: #fef2f2;
          border: 1px solid #fee2e2;
          padding: 10px;
          border-radius: 8px;
          margin-top: 5px;
        }
        .text-red {
          color: #EF4444;
        }
    </style>
</head>
<body>

    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="header-left">
                <div class="logo-text">TUNGGAL JAYA</div>
                <div class="sub-logo-text">PREMIUM PLANET BUS</div>
            </div>
            <div class="header-right">
                <div class="booking-label">Kode Booking</div>
                <div class="booking-code">{{ $booking->booking_code }}</div>
            </div>
        </div>

        <!-- Journey -->
        <div class="journey">
            <div class="city-box">
                <div class="city-label">Dari / From</div>
                <div class="city-name">{{ $booking->schedule->route->origin }}</div>
                <div class="city-time">{{ $booking->schedule->getDepartureTimeWIB()->format('H:i') }} WIB</div>
                <div class="city-date">{{ $booking->schedule->getDepartureTimeWIB()->isoFormat('dddd, D MMMM Y') }}</div>
            </div>
            <div class="arrow-box">
                <div class="duration">
                    🕓 {{ $booking->schedule->route->formatted_duration ?? 'Direct' }}
                </div>
                <div class="arrow">⟶</div>
            </div>
            <div class="city-box" style="text-align: right;">
                <div class="city-label">Ke / To</div>
                <div class="city-name">{{ $booking->schedule->route->destination }}</div>
                <div class="city-time">{{ $booking->schedule->getActualArrivalTime()->format('H:i') }} WIB</div>
                <div class="city-date">{{ $booking->schedule->getActualArrivalTime()->isoFormat('dddd, D MMMM Y') }}</div>
            </div>
        </div>

        <!-- Details -->
        <div class="details">
            <div class="detail-row">
                <div class="detail-cell">
                    <div class="detail-label">Penumpang / Passenger</div>
                    <div class="detail-value">{{ $booking->passenger_name }}</div>
                    <div style="font-size: 10px; color: #6b7280; margin-top: 2px;">{{ $booking->passenger_phone }}</div>
                </div>
                <div class="detail-cell">
                    <div class="detail-label">Kursi / Seat</div>
                    <div class="seat-badge">{{ $booking->seat_numbers }}</div>
                </div>
                <div class="detail-cell" style="text-align: right;">
                    <div class="detail-label">Total Harga / Price</div>
                    <div class="detail-value text-red" style="font-size: 18px;">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</div>
                    <div style="margin-top: 4px;">
                        @if($booking->payment_status == 'paid')
                            <span class="paid-badge">LUNAS / PAID</span>
                        @else
                            <span class="pending-badge">PENDING</span>
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="detail-row">
                 <div class="detail-cell" colspan="2">
                     <div class="bus-info-box">
                        <div class="detail-label text-red">Armada / Bus Info</div>
                        <div class="detail-value" style="font-size: 12px;">{{ $booking->schedule->bus->name }} ({{ $booking->schedule->bus->plate_number }}) - {{ $booking->schedule->bus->type ?? 'Executive Class' }}</div>
                     </div>
                 </div>
                 <div class="detail-cell"></div>
            </div>
        </div>

        <!-- Footer / QR -->
        <div class="footer-section">
            <div class="footer-left">
                <div class="scan-text">Scan for e-boarding</div>
                <div class="barcode-box">
                    @php
                        $qrCode = new Milon\Barcode\DNS2D();
                        $qrImage = $qrCode->getBarcodePNG($booking->booking_code, 'QRCODE', 3, 3);
                    @endphp
                    <img src="data:image/png;base64,{{ $qrImage }}" alt="QR Code" width="80">
                </div>
            </div>
            <div class="footer-right">
                <div class="terms">
                    <strong>Syarat & Ketentuan / Terms</strong><br>
                    Harap datang 30 menit sebelum keberangkatan.<br>
                    Tunjukkan tiket ini kepada petugas.<br>
                    Barang bawaan maksimal 20Kg.<br>
                    Tiket yang sudah dibeli tidak dapat dikembalikan.<br>
                    Selamat menikmati perjalanan Anda bersama Tunggal Jaya Transport.
                </div>
            </div>
        </div>
    </div>

    <!-- Admin Stub (Optional for physical printing) -->
    <div class="cut-line"></div>

    <div style="text-align: center; color: #9ca3af; font-size: 10px;">
        Dicetak pada: {{ date('d/m/Y H:i') }} | Tunggal Jaya Transport System
    </div>

</body>
</html>
