<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>E-Tiket Bus - {{ $booking->booking_code }}</title>
    <style>
        @page {
            margin: 0;
            size: A4 portrait;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            width: 210mm;
            height: 297mm;
            margin: 0;
            padding: 4mm;
            background: white;
        }

        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 24px;
            color: rgba(102, 126, 234, 0.05);
            font-weight: bold;
            z-index: 1;
        }

        .content {
            position: relative;
            z-index: 2;
            width: 95%;
            margin: 0 auto;
        }

        .company-header {
            text-align: center;
            padding: 8px 0;
            border-bottom: 2px solid #0ea5e9;
            margin-bottom: 8px;
        }

        .company-logo {
            width: 45px;
            height: 45px;
            margin-right: 10px;
            vertical-align: middle;
        }

        .company-name {
            font-size: 20px;
            font-weight: bold;
            color: #0284c7;
            display: inline-block;
            vertical-align: middle;
        }

        .header {
            background: linear-gradient(135deg, #0284c7 0%, #0ea5e9 100%);
            color: white;
            padding: 10px;
            border-radius: 6px 6px 0 0;
            text-align: center;
        }

        .header h1 {
            font-size: 16px;
            margin-bottom: 4px;
            font-weight: bold;
        }

        .header .subtitle {
            font-size: 10px;
            opacity: 0.95;
        }

        .ticket-body {
            border: 1.5px solid #0ea5e9;
            border-top: none;
            border-radius: 0 0 6px 6px;
            padding: 12px;
            min-height: auto;
        }

        .ticket-number-banner {
            background: #f7f7f7;
            padding: 8px;
            text-align: center;
            border-radius: 4px;
            margin-bottom: 12px;
            border: 1px dashed #0ea5e9;
        }

        .ticket-number-banner .label {
            font-size: 10px;
            color: #666;
            margin-bottom: 3px;
            letter-spacing: 0.3px;
        }

        .ticket-number-banner .number {
            font-size: 16px;
            font-weight: bold;
            color: #0284c7;
            letter-spacing: 1.5px;
        }

        .event-name {
            font-size: 16px;
            font-weight: bold;
            color: #333;
            margin-bottom: 12px;
            text-align: center;
            padding-bottom: 8px;
            border-bottom: 1px solid #eee;
        }

        .detail-grid {
            width: 100%;
            margin-bottom: 12px;
        }

        .detail-row {
            width: 100%;
            margin-bottom: 4px;
        }

        .detail-item {
            display: inline-block;
            width: 48%;
            padding: 6px;
            background: #fafafa;
            border-radius: 3px;
            vertical-align: top;
        }

        .detail-item:nth-child(2n) {
            margin-left: 3%;
        }

        .detail-item .label {
            font-size: 9px;
            color: #888;
            text-transform: uppercase;
            margin-bottom: 2px;
            letter-spacing: 0.2px;
        }

        .detail-item .value {
            font-size: 11px;
            color: #333;
            font-weight: 600;
        }

        .venue-section {
            background: #f9f9f9;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 12px;
            border-left: 2px solid #0ea5e9;
        }

        .venue-section h3 {
            font-size: 11px;
            color: #0284c7;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .venue-section .venue-name {
            font-size: 11px;
            color: #333;
            font-weight: bold;
            margin-bottom: 3px;
        }

        .venue-section .venue-addr {
            font-size: 9px;
            color: #555;
            line-height: 1.3;
        }

        .seat-info {
            background: #0ea5e9;
            color: white;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 12px;
            text-align: center;
        }

        .seat-detail {
            display: inline-block;
            width: 32%;
            text-align: center;
        }

        .seat-detail .label {
            font-size: 9px;
            margin-bottom: 4px;
            letter-spacing: 0.3px;
        }

        .seat-detail .value {
            font-size: 13px;
            font-weight: bold;
        }

        .qr-barcode-section {
            background: #f9f9f9;
            padding: 12px;
            border-radius: 4px;
            margin-bottom: 12px;
            text-align: center;
        }

        .qr-code {
            display: inline-block;
            width: 45%;
            text-align: center;
            vertical-align: top;
        }

        .barcode {
            display: inline-block;
            width: 45%;
            text-align: center;
            vertical-align: top;
            margin-left: 8%;
        }

        .qr-code-box {
            width: 65px;
            height: 65px;
            background: white;
            border: 1px solid #ddd;
            margin: 0 auto 4px;
            border-radius: 3px;
        }

        .barcode-box {
            width: 130px;
            height: 42px;
            background: white;
            border: 1px solid #ddd;
            margin: 0 auto 4px;
            border-radius: 2px;
        }

        .code-label {
            font-size: 9px;
            color: #666;
        }

        .customer-info {
            background: #fff5e6;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 12px;
            border: 1px solid #ffe0b3;
        }

        .customer-info h3 {
            font-size: 11px;
            color: #ff9800;
            margin-bottom: 6px;
            font-weight: bold;
        }

        .customer-item {
            display: inline-block;
            width: 48%;
            margin-bottom: 5px;
            vertical-align: top;
        }

        .customer-item:nth-child(2n+1) {
            margin-right: 3%;
        }

        .customer-item .label {
            font-size: 9px;
            color: #888;
            margin-bottom: 1px;
        }

        .customer-item .value {
            font-size: 11px;
            color: #333;
            font-weight: 600;
        }

        .terms {
            padding-top: 8px;
            border-top: 1px dashed #ddd;
            margin-top: 12px;
        }

        .terms h3 {
            font-size: 11px;
            color: #333;
            margin-bottom: 6px;
            font-weight: bold;
        }

        .terms ul {
            list-style: none;
            padding: 0;
        }

        .terms li {
            font-size: 8px;
            color: #666;
            margin-bottom: 3px;
            padding-left: 8px;
            position: relative;
            line-height: 1.2;
        }

        .terms li:before {
            content: "•";
            position: absolute;
            left: 3px;
            color: #0ea5e9;
            font-weight: bold;
        }

        .footer {
            text-align: center;
            margin-top: 8px;
            padding-top: 8px;
            border-top: 1px solid #eee;
        }

        .footer p {
            font-size: 8px;
            color: #888;
            margin: 1px 0;
        }

        svg {
            width: 100%;
            height: 100%;
        }

        @media print {
            body {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                color-adjust: exact !important;
            }

            .header {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            .seat-info {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
        }
    </style>
</head>
<body>
    <div class="watermark">VALID TICKET</div>

    <div class="content">
        <div class="company-header">
            <img src="{{ base_path('public/img/logoNoBg.png') }}" alt="Tunggal Jaya Logo" class="company-logo">
            <div class="company-name">PO TUNGGAL JAYA</div>
        </div>
        
        <div class="header">
            <h1>🚌 E-TIKET BUS</h1>
            <p class="subtitle">Tunggal Jaya Transport - Boarding Pass</p>
        </div>

        <div class="ticket-body">
            <div class="ticket-number-banner">
                <div class="label">KODE TIKET</div>
                <div class="number">{{ $booking->booking_code }}</div>
            </div>

            <div class="event-name">{{ $booking->schedule->route->origin }} → {{ $booking->schedule->route->destination }}</div>

            <div class="detail-grid">
                <div class="detail-row">
                    <div class="detail-item">
                        <div class="label">📅 Tanggal Berangkat</div>
                        <div class="value">{{ $booking->schedule->getDepartureTimeWIB()->format('d F Y') }}</div>
                    </div>
                    <div class="detail-item">
                        <div class="label">⏰ Jam Berangkat</div>
                        <div class="value">{{ $booking->schedule->getDepartureTimeWIB()->format('H:i') }} WIB</div>
                    </div>
                </div>
                <div class="detail-row">
                    <div class="detail-item">
                        <div class="label">🏷️ Tipe Tiket</div>
                        <div class="value">Bus Eksekutif</div>
                    </div>
                    <div class="detail-item">
                        <div class="label">💰 Harga Tiket</div>
                        <div class="value">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>

            <div class="venue-section">
                <h3>📍 INFORMASI RUTE</h3>
                <div class="venue-name">{{ $booking->schedule->route->origin }} → {{ $booking->schedule->route->destination }}</div>
                <div class="venue-addr">
                    <p>Keberangkatan: {{ $booking->schedule->getDepartureTimeWIB()->format('d F Y, H:i') }} WIB</p>
                    <p>Perkiraan Tiba: {{ $booking->schedule->getActualArrivalTime()->format('d F Y, H:i') }} WIB</p>
                    <p>Pemesan: {{ $booking->passenger_name }}</p>
                </div>
            </div>

            <div class="seat-info">
                <div class="seat-detail">
                    <div class="label">KELAS</div>
                    <div class="value">EKSEKUTIF</div>
                </div>
                <div class="seat-detail">
                    <div class="label">JUMLAH KURSI</div>
                    <div class="value">{{ $booking->number_of_seats }}</div>
                </div>
                <div class="seat-detail">
                    <div class="label">NOMOR KURSI</div>
                    <div class="value">{{ $booking->seat_numbers }}</div>
                </div>
            </div>

            <div class="qr-barcode-section">
                <div class="qr-code">
                    <div class="qr-code-box">
                        @php
                            $qr_generator = new Milon\Barcode\DNS2D();
                            echo $qr_generator->getBarcodeSVG($booking->booking_code, 'QRCODE', 4, 4);
                        @endphp
                    </div>
                    <div class="code-label">Scan QR Code untuk Verifikasi</div>
                </div>

                <div class="barcode">
                    <div class="barcode-box">
                        <!-- Simple barcode representation -->
                        <svg viewBox="0 0 200 60" width="200" height="70">
                            @for($i = 0; $i < strlen($booking->booking_code); $i++)
                                @if($i % 2 == 0)
                                    <rect x="{{10 + $i*5}}" y="10" width="3" height="40" fill="black"/>
                                @else
                                    <rect x="{{10 + $i*5}}" y="10" width="2" height="40" fill="black"/>
                                @endif
                            @endfor
                        </svg>
                    </div>
                    <div class="code-label">Barcode: {{ $booking->booking_code }}</div>
                </div>
            </div>

            <div class="customer-info">
                <h3>👤 INFORMASI PENUMPANG</h3>
                <div>
                    <div class="customer-item">
                        <div class="label">Nama Penumpang</div>
                        <div class="value">{{ $booking->passenger_name }}</div>
                    </div>
                    <div class="customer-item">
                        <div class="label">Email</div>
                        <div class="value">{{ $booking->email }}</div>
                    </div>
                    <div class="customer-item">
                        <div class="label">Tanggal Pemesanan</div>
                        <div class="value">{{ $booking->created_at->format('d F Y') }}</div>
                    </div>
                    <div class="customer-item">
                        <div class="label">Status Pembayaran</div>
                        <div class="value" style="color: #059669; font-weight: bold;">LUNAS</div>
                    </div>
                </div>
            </div>

            <div class="terms">
                <h3>⚠️ SYARAT & KETENTUAN</h3>
                <ul>
                    <li>Tiket ini hanya berlaku untuk tanggal dan kursi yang tercantum di atas.</li>
                    <li>Tunjukkan e-tiket ini (cetak atau digital) beserta identitas valid saat boarding.</li>
                    <li>Tiket tidak dapat dipindahtangankan dan tidak dapat dikembalikan.</li>
                    <li>Masuk boarding tidak akan diperbolehkan jika tiket menunjukkan tanda-tanda pemalsuan.</li>
                    <li>Bus akan berangkat tepat waktu, pastikan Anda datang paling lambat 30 menit sebelum keberangkatan.</li>
                    <li>Barang bawaan maksimal 1 buah dengan ukuran tidak lebih dari 40x30x20 cm.</li>
                    <li>Keputusan PO Tunggal Jaya bersifat mutlak terkait pemesanan dan keberangkatan.</li>
                    <li>Barang tertinggal bukan menjadi tanggung jawab PO Tunggal Jaya.</li>
                </ul>
            </div>

            <div class="footer">
                <p>CS: +62 123 456 789 | Email: info@tunggaljayatransport.com</p>
                <p>www.tunggaljayatransport.com</p>
                <p>© {{ date('Y') }} PO Tunggal Jaya. Hak Cipta Dilindungi.</p>
            </div>
        </div>
    </div>
</body>
</html>