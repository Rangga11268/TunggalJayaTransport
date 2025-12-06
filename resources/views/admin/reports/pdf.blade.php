<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan {{ ucfirst($reportType) }}</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #ef4444; /* Brand Red */
            padding-bottom: 10px;
        }
        .header h1 {
            color: #ef4444;
            margin: 0;
            font-size: 24px;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        .meta {
            margin-bottom: 20px;
            background-color: #f9fafb;
            padding: 10px;
            border-radius: 5px;
        }
        .meta table {
            width: 100%;
        }
        .meta td {
            padding: 2px 0;
        }
        .meta .label {
            font-weight: bold;
            width: 120px;
        }
        .summary-cards {
            width: 100%;
            margin-bottom: 20px;
        }
        .card {
            background-color: #ef4444;
            color: white;
            padding: 10px;
            border-radius: 5px;
            width: 30%;
            display: inline-block;
            text-align: center;
            margin-right: 2%;
        }
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table.data-table th, table.data-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        table.data-table th {
            background-color: #ef4444;
            color: white;
            font-weight: bold;
        }
        table.data-table tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #999;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Tunggal Jaya Transport</h1>
        <p>Laporan {{ ucfirst($reportType) }}</p>
    </div>

    <div class="meta">
        <table>
            <tr>
                <td class="label">Periode:</td>
                <td>{{ \Carbon\Carbon::parse($filters['start_date'])->format('d M Y') }} - {{ \Carbon\Carbon::parse($filters['end_date'])->format('d M Y') }}</td>
            </tr>
            <tr>
                <td class="label">Rute:</td>
                <td>{{ $selectedRoute ?? 'Semua Rute' }}</td>
            </tr>
            <tr>
                <td class="label">Armada:</td>
                <td>{{ $selectedBus ?? 'Semua Armada' }}</td>
            </tr>
            <tr>
                <td class="label">Tanggal Cetak:</td>
                <td>{{ now()->format('d M Y H:i') }}</td>
            </tr>
        </table>
    </div>

    @if($reportType === 'bookings')
        <div class="summary-cards">
            <div class="card">
                <div>Total Pemesanan</div>
                <div style="font-size: 16px; font-weight: bold;">{{ $reportData['total_bookings'] }}</div>
            </div>
            <div class="card">
                <div>Kursi Terjual</div>
                <div style="font-size: 16px; font-weight: bold;">{{ $reportData['total_seats'] }}</div>
            </div>
        </div>

        <table class="data-table">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th style="text-align: center;">Jumlah Transaksi</th>
                    <th style="text-align: center;">Kursi Terjual</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reportData['daily_bookings'] as $date => $data)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($date)->format('d M Y') }}</td>
                    <td style="text-align: center;">{{ $data->count }}</td>
                    <td style="text-align: center;">{{ $data->seats }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" style="text-align: center;">Tidak ada data.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

    @elseif($reportType === 'revenue')
        <div class="summary-cards">
            <div class="card" style="background-color: #10b981;">
                <div>Total Pendapatan</div>
                <div style="font-size: 16px; font-weight: bold;">Rp {{ number_format($reportData['total_revenue'], 0, ',', '.') }}</div>
            </div>
            <div class="card" style="background-color: #10b981;">
                <div>Rata-rata</div>
                <div style="font-size: 16px; font-weight: bold;">Rp {{ number_format($reportData['avg_booking_value'], 0, ',', '.') }}</div>
            </div>
        </div>

        <table class="data-table">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th style="text-align: right;">Pendapatan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reportData['daily_revenue'] as $date => $data)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($date)->format('d M Y') }}</td>
                    <td style="text-align: right;">Rp {{ number_format($data->revenue, 0, ',', '.') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="2" style="text-align: center;">Tidak ada data.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

    @elseif($reportType === 'passengers')
        <div class="summary-cards">
            <div class="card" style="background-color: #8b5cf6;">
                <div>Total Penumpang</div>
                <div style="font-size: 16px; font-weight: bold;">{{ $reportData['total_passengers'] }}</div>
            </div>
        </div>

        <h3>Penumpang per Rute</h3>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Rute</th>
                    <th style="text-align: right;">Jumlah Penumpang</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reportData['route_passengers'] as $route => $count)
                <tr>
                    <td>{{ $route }}</td>
                    <td style="text-align: right;">{{ $count }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="2" style="text-align: center;">Tidak ada data.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <h3>Penumpang per Armada</h3>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Armada</th>
                    <th style="text-align: right;">Jumlah Penumpang</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reportData['bus_passengers'] as $bus => $count)
                <tr>
                    <td>{{ $bus }}</td>
                    <td style="text-align: right;">{{ $count }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="2" style="text-align: center;">Tidak ada data.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    @endif

    <div class="footer">
        <p>&copy; {{ date('Y') }} Tunggal Jaya Transport. All rights reserved.</p>
    </div>
</body>
</html>
