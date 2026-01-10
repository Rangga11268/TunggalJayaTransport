<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>E-Ticket - {{ $booking->booking_code }}</title>
    <style>
        @page {
            margin: 0;
            /* Size is set in TicketPdfService */
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            padding: 5px; /* Tighter padding */
        }

        .printable {
            width: 100%;
            height: 410px; /* Increased height for content */
            position: relative;
            background-color: #f3f4f6;
            margin: 0;
            overflow: hidden;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        /* Left Side (Blue Strip) */
        .stub-left {
            position: absolute;
            left: 0;
            top: 0;
            width: 80px;
            height: 100%;
            background-color: #3d5684;
        }
        
        .stub-text {
            color: #ffffff;
            font-family: 'Courier New', Courier, monospace;
            font-size: 18px;
            font-weight: 400;
            letter-spacing: 2px;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-90deg);
            white-space: nowrap;
            width: 300px;
            text-align: center;
        }

        /* Main Body */
        .ticket-body {
            position: absolute;
            left: 80px;
            top: 0;
            width: 500px; /* Explicit width for body area */
            height: 100%;
            background-color: #f3f4f6;
            padding: 30px 40px;
            box-sizing: border-box;
        }

        .ticket-logo-header {
            margin-bottom: 30px;
            display: block;
        }

        .ticket-main-title {
            font-family: 'Courier New', Courier, monospace;
            font-size: 24px;
            font-weight: 700;
            color: #000000;
            vertical-align: middle;
        }

        .info-grid {
            width: 100%;
        }

        .info-group {
            margin-bottom: 15px;
            display: inline-block;
            vertical-align: top;
            width: 48%; /* Default for 2-col rows */
            margin-right: 5px;
        }

        .info-group-3col {
            margin-bottom: 15px;
            display: inline-block;
            vertical-align: top;
            width: 31%; /* For 3-col rows */
            margin-right: 5px;
        }

        .label {
            font-family: 'Courier New', Courier, monospace;
            font-size: 11px;
            color: #64748b;
            letter-spacing: 1px;
            margin-bottom: 3px;
            text-transform: uppercase;
        }

        .value-box {
            background-color: #e2e8f0;
            padding: 8px 12px;
            font-family: 'Courier New', Courier, monospace;
            font-size: 15px;
            font-weight: 700;
            color: #000000;
            display: block;
            min-height: 20px;
        }

        .address-group {
            margin-top: 15px;
            width: 100%;
            display: block;
        }

        .address-box {
            background-color: #e2e8f0;
            padding: 12px 15px;
            font-family: 'Courier New', Courier, monospace;
            font-size: 16px;
            font-weight: 700;
            color: #3d5684;
            width: 95%;
            border-left: 4px solid #ef4444;
        }

        /* Right Stub (Barcode Area) */
        .stub-right {
            position: absolute;
            right: 0;
            top: 0;
            width: 220px;
            height: 100%;
            background-color: #f8fafc;
            border-left: 2px dashed #cbd5e1;
            text-align: center;
            padding: 40px 20px;
            box-sizing: border-box;
        }

        /* Cut circles */
        .cut-circle-top {
            position: absolute;
            right: 205px; /* Adjust based on right stub width + half circle */
            top: -15px;
            width: 30px;
            height: 30px;
            background-color: #f0f2f5;
            border-radius: 50%;
            z-index: 20;
        }
        .cut-circle-bottom {
            position: absolute;
            right: 205px;
            bottom: -15px;
            width: 30px;
            height: 30px;
            background-color: #f0f2f5;
            border-radius: 50%;
            z-index: 20;
        }

        .scan-text {
            font-family: 'Courier New', Courier, monospace;
            font-size: 10px;
            color: #3d5684;
            letter-spacing: 0.5px;
            margin-bottom: 20px;
            text-transform: uppercase;
        }

        .barcode-container {
            border: 2px solid #000;
            padding: 15px;
            display: inline-block;
            background: #fff;
            margin-bottom: 15px;
        }

        .id-text {
            font-family: 'Courier New', Courier, monospace;
            font-size: 14px;
            color: #3d5684;
            letter-spacing: 2px;
            font-weight: 700;
        }

        .footer-note {
            text-align: center;
            margin-top: 5px;
            font-family: 'Courier New', Courier, monospace;
            font-size: 8px;
            color: #94a3b8;
            width: 100%;
        }
    </style>
</head>
<body>

    <div class="printable">
        <!-- Left Stub -->
        <div class="stub-left">
            <div class="stub-text">E-TICKET TUJAGO</div>
        </div>

        <!-- Main Body -->
        <div class="ticket-body">
            <div class="ticket-logo-header">
                <img src="{{ public_path('img/logoNoBg.png') }}" alt="Logo" style="height: 35px; width: auto; vertical-align: middle; margin-right: 10px;">
                <span class="ticket-main-title">Tunggal Jaya Transport</span>
            </div>

            <div class="info-grid">
                <!-- Row 1 -->
                <div class="info-group-3col">
                    <div class="label">PASSENGER</div>
                    <div class="value-box">
                        {{ Str::limit(strtoupper($booking->passenger_name), 16) }}
                    </div>
                </div>

                <div class="info-group-3col">
                    <div class="label">BUS NAME</div>
                    <div class="value-box">
                        {{ strtoupper($booking->schedule->bus->name ?? '-') }}
                    </div>
                </div>

                <div class="info-group-3col">
                    <div class="label">DATE • TIME</div>
                    <div class="value-box">
                        {{ $booking->departure_time ? $booking->departure_time->format('d.m.y • H:i') : '-' }}
                    </div>
                </div>

                <!-- Row 2 -->
                <div class="info-group-3col">
                    <div class="label">SEAT</div>
                    <div class="value-box">
                        {{ $booking->seat_numbers }}
                    </div>
                </div>
                
                <div class="info-group-3col">
                    <div class="label">CLASS</div>
                    <div class="value-box">
                        {{ strtoupper($booking->schedule->bus->bus_type ?? 'EXE') }}
                    </div>
                </div>

                <div class="info-group-3col">
                    <div class="label">STATUS</div>
                    <div class="value-box">
                        @if($booking->payment_status === 'paid')
                            <span style="color: #16a34a;">PAID</span>
                        @else
                            <span style="color: #d97706;">PENDING</span>
                        @endif
                    </div>
                </div>

                <!-- Row 3: Total Payment -->
                <div class="info-group" style="width: 98%;">
                    <div class="label">TOTAL PAYMENT</div>
                    <div class="value-box" style="color: #be123c; font-size: 18px;">
                        Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                        @if($booking->discount_amount > 0)
                            <span style="font-size: 12px; color: #16a34a; margin-left: 10px;">
                                (Hemat Rp {{ number_format($booking->discount_amount, 0, ',', '.') }})
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="address-group">
                <div class="label">ROUTE ORIGIN >> DESTINATION</div>
                <div class="address-box">
                    {{ strtoupper($booking->schedule->route->origin) }} >> {{ strtoupper($booking->schedule->route->destination) }}
                </div>
            </div>
        </div>

        <!-- Decorative Cut Circles -->
        <div class="cut-circle-top"></div>
        <div class="cut-circle-bottom"></div>

        <!-- Right Stub -->
        <div class="stub-right">
            <div class="scan-text">Scan for Check-in</div>

            <div class="barcode-container">
                @php
                    $dns1d = new Milon\Barcode\DNS1D();
                    $barcode = $dns1d->getBarcodePNG($booking->booking_code, 'C128', 2, 80); 
                @endphp
                <img src="data:image/png;base64,{{ $barcode }}" alt="Barcode" style="width: 140px; height: 80px; object-fit: contain;"> 
            </div>

            <div class="id-text">
                KODE: {{ $booking->booking_code }}
            </div>
        </div>
    </div>
    
    <div class="footer-note">
        Tiket sah per {{ date('d/m/Y H:i') }} • TUJAGO (Tunggal Jaya Go) • Simpan sebagai bukti transaksi resmi.
    </div>

</body>
</html>

</body>
</html>

</body>
</html>
