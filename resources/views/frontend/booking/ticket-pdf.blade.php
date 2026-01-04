<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>E-Ticket - {{ $booking->booking_code }}</title>
    <style>
        @page {
            margin: 0;
            size: A4 landscape; /* User's design is wide (1790px), landscape fits better */
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif; /* Fallback for fonts */
            background-color: #f0f2f5;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }


        .printable {
            width: 100%;
            max-width: 1000px; /* Safe printable area */
            height: 335px; /* Proportional height */
            position: relative;
            background-color: #f3f4f6; /* Light gray bg like design */
            margin: 0 auto;
            overflow: hidden;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        /* Left Side (Blue Stub) - "ticket-title" in User's code */
        .stub-left {
            position: absolute;
            left: 0;
            top: 0;
            width: 90px; /* Scaled down from 160px */
            height: 100%;
            background-color: #3d5684;
            display: flex; /* Flex doesn't work well in dompdf old versions, but we try */
            align-items: center;
            justify-content: center;
        }
        
        .stub-text {
            color: #ffffff;
            font-family: 'Courier New', Courier, monospace;
            font-size: 20px;
            font-weight: 400;
            letter-spacing: 2px;
            transform: rotate(-90deg);
            white-space: nowrap;
            /* Centering for non-flex PDF renderers */
            position: absolute;
            top: 50%;
            left: 50%;
            transform-origin: center;
            width: 300px; /* ensure width for rotation */
            margin-left: -150px; /* half width */
            margin-top: -12px; /* half height approx */
            text-align: center;
        }

        /* Main Body - "ticket-body" */
        .ticket-body {
            position: absolute;
            left: 90px; /* After stub */
            top: 0;
            width: 65%; /* Rest of space minus stub and right stub */
            height: 100%;
            background-color: #f3f4f6;
            padding: 40px;
            box-sizing: border-box;
        }

        .ticket-main-title {
            font-family: 'Courier New', Courier, monospace;
            font-size: 36px;
            font-weight: 700;
            color: #000000;
            margin-bottom: 40px;
        }

        .info-group {
            margin-bottom: 25px;
            display: inline-block;
            vertical-align: top;
            margin-right: 30px;
        }

        .label {
            font-family: 'Courier New', Courier, monospace;
            font-size: 14px;
            color: #000000cc;
            letter-spacing: 1px;
            margin-bottom: 5px;
            text-transform: uppercase;
        }

        .value-box {
            background-color: #e5e7eb; /* Gray-200 equivalent */
            padding: 10px 15px;
            /* border-radius: 4px; Not in design but looks nice */
            font-family: 'Courier New', Courier, monospace;
            font-size: 18px;
            font-weight: 700;
            color: #000000;
            display: inline-block;
            box-shadow: 2px 2px 0px rgba(0,0,0,0.05);
        }

        .address-group {
            margin-top: 20px;
            width: 100%;
        }

        .address-box {
            background-color: #e5e7eb;
            padding: 15px;
            font-family: 'Courier New', Courier, monospace;
            font-size: 16px;
            font-weight: 700;
            color: #000000;
            width: 90%;
        }

        /* Right Stub - "ticket-stub" */
        .stub-right {
            position: absolute;
            right: 0;
            top: 0;
            width: 25%;
            height: 100%;
            background-color: #f3f4f6; /* Same bg */
            border-left: 2px dashed #cbd5e1; /* Separation Line */
            text-align: center;
            padding-top: 60px;
        }

        /* Cut circles for "separation-line" effect */
        .cut-circle-top {
            position: absolute;
            right: 25%; /* At the border line */
            top: -15px;
            width: 30px;
            height: 30px;
            background-color: #f0f2f5; /* Match body bg */
            border-radius: 50%;
            margin-right: -15px;
        }
        .cut-circle-bottom {
            position: absolute;
            right: 25%;
            bottom: -15px;
            width: 30px;
            height: 30px;
            background-color: #f0f2f5;
            border-radius: 50%;
            margin-right: -15px;
        }

        .scan-text {
            font-family: 'Courier New', Courier, monospace;
            font-size: 12px;
            color: #3d5684;
            letter-spacing: 0.5px;
            margin-bottom: 15px;
        }

        .barcode-container {
            border: 4px solid #000;
            padding: 10px;
            display: inline-block;
            background: #fff;
        }

        .id-text {
            font-family: 'Courier New', Courier, monospace;
            font-size: 14px;
            color: #3d5684;
            letter-spacing: 1px;
            margin-top: 15px;
            font-weight: 700;
        }

        /* Helper utilities */
        .text-uppercase { text-transform: uppercase; }
    </style>
</head>
<body>

    <div class="printable">
        <!-- Left Stub (Blue Strip) -->
        <div class="stub-left">
            <div class="stub-text">E - TICKET BUS</div>
        </div>

        <!-- Main Body -->
        <div class="ticket-body">
            <div class="ticket-main-title" style="display: flex; align-items: center; gap: 15px;">
                <img src="{{ public_path('img/logoNoBg.png') }}" alt="Logo" style="height: 50px; width: auto;">
                <span>Tunggal Jaya Transport</span>
            </div>

            <div class="info-group">
                <div class="label">PASSENGER</div>
                <div class="value-box">
                    {{ Str::limit(strtoupper($booking->passenger_name), 15) }}
                </div>
            </div>

            <div class="info-group">
                <div class="label">DATE</div>
                <div class="value-box">
                    {{ $booking->departure_time ? $booking->departure_time->format('d • m • Y') : '-' }}
                </div>
            </div>
            
            <div class="info-group">
                <div class="label">SEAT</div>
                <div class="value-box">
                    {{ $booking->seat_numbers }}
                </div>
            </div>

            <div class="info-group">
                <div class="label">TIME</div>
                <div class="value-box">
                    {{ $booking->departure_time ? $booking->departure_time->format('H:i') : '00:00' }} WIB
                </div>
            </div>

            <div class="info-group">
                <div class="label">CLASS</div>
                <div class="value-box">
                    {{ strtoupper($booking->schedule->bus->bus_type ?? 'Executive') }}
                </div>
            </div>

            <div class="address-group">
                <div class="label">ROUTE</div>
                <div class="address-box">
                    {{ $booking->schedule->route->origin }} <span style="color: #ef4444; font-weight: 900;">>></span> {{ $booking->schedule->route->destination }}
                </div>
            </div>
        </div>

        <!-- Decorative Cut Circles -->
        <div class="cut-circle-top"></div>
        <div class="cut-circle-bottom"></div>

        <!-- Right Stub (Barcode Area) -->
        <div class="stub-right">
            <div class="scan-text">Scan to check in</div>

            <div class="barcode-container">
                @php
                    // Using QR Code as primary "Barcode" visual requested by user (User said "qr nya ganti barcode", 
                    // usually means vertical bars C128/C39. But the design frame is square.
                    // Square frame + "Barcode" usually implies QR or Aztec in modern context, 
                    // BUT user explicitly said "ganti barcode". 
                    // So I will use a 1D Barcode (C128) but fit it into the container carefully,
                    // OR I will assume they want the visual style of a barcode.
                    // Let's use standard 1D Barcode as requested textually.
                    
                    $dns1d = new Milon\Barcode\DNS1D();
                    $barcode = $dns1d->getBarcodePNG($booking->booking_code, 'C128', 3, 100); 
                @endphp
                <img src="data:image/png;base64,{{ $barcode }}" alt="Barcode" style="width: 150px; height: 150px; object-fit: contain;"> 
                <!-- Note: 1D barcodes are wide. To fit square, it might need to be rotated or small. 
                     I will force contain to fit the square look. -->
            </div>

            <div class="id-text">
                ID {{ $booking->booking_code }}
            </div>
        </div>
    </div>
    
    <!-- Footer Note -->
    <div style="text-align: center; margin-top: 20px; font-family: 'Courier New'; font-size: 10px; color: #9ca3af;">
        Generated on {{ date('d F Y H:i:s') }}
        <br>Simpan tiket ini sebagai bukti pembayaran yang sah.
    </div>

</body>
</html>
