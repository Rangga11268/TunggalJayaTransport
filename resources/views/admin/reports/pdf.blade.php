<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan {{ $reportType }}</title>
    <style>
        body {
            font-family: sans-serif;
            color: #333;
            line-height: 1.5;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #EF4444;
            padding-bottom: 20px;
        }
        .logo {
            color: #EF4444;
            font-size: 24px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 5px;
        }
        .subtitle {
            font-size: 14px;
            color: #666;
        }
        .meta {
            margin-bottom: 20px;
            font-size: 12px;
        }
        .meta table {
            width: 100%;
        }
        .meta td {
            padding: 2px 0;
        }
        .summary-cards {
            margin-bottom: 20px;
            width: 100%;
        }
        .card {
            display: inline-block;
            width: 30%;
            padding: 15px;
            background-color: #f3f4f6;
            border-radius: 5px;
            margin-right: 2%;
            vertical-align: top;
        }
        .card-title {
            font-size: 12px;
            color: #6b7280;
            margin-bottom: 5px;
            text-transform: uppercase;
        }
        .card-value {
            font-size: 18px;
            font-weight: bold;
            color: #111827;
        }
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
            margin-bottom: 20px;
        }
        table.data-table th, table.data-table td {
            border: 1px solid #e5e7eb;
            padding: 8px 12px;
            text-align: left;
        }
        table.data-table th {
            background-color: #EF4444;
            color: white;
            font-weight: 600;
        }
        table.data-table tr:nth-child(even) {
            background-color: #f9fafb;
        }
        .footer {
            margin-top: 50px;
            text-align: right;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">Tunggal Jaya Transport</div>
        <div class="subtitle">Laporan {{ ucfirst($reportType) }}</div>
    </div>

    <div class="meta">
        <table>
            <tr>
                <td width="100px"><strong>Periode:</strong></td>
                <td>{{ Carbon\Carbon::parse($startDate)->format('d M Y') }} - {{ Carbon\Carbon::parse($endDate)->format('d M Y') }}</td>
                <td width="100px"><strong>Dicetak:</strong></td>
                <td>{{ now()->format('d M Y H:i') }}</td>
            </tr>
            @if($routeId)
            <tr>
                <td><strong>Rute:</strong></td>
                <td colspan="3">{{ $items->first()->route->origin ?? '-' }} - {{ $items->first()->route->destination ?? '-' }}</td>
            </tr>
            @endif
            @if($busId)
            <tr>
                <td><strong>Bus:</strong></td>
                <td colspan="3">{{ $items->first()->bus->name ?? '-' }} ({{ $items->first()->bus->plate_number ?? '-' }})</td>
            </tr>
            @endif
        </table>
    </div>

    <!-- Summary Cards -->
    <div class="summary-cards">
        <div class="card">
            <div class="card-title">Total Pendapatan</div>
            <div class="card-value">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
        </div>
        <div class="card">
            <div class="card-title">Total Transaksi</div>
            <div class="card-value">{{ $totalBookings }}</div>
        </div>
        <div class="card">
            <div class="card-title">Total Penumpang</div>
            <div class="card-value">{{ $totalPassengers }}</div>
        </div>
    </div>

    <!-- Data Table -->
    <table class="data-table">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Kode Booking</th>
                @if(!$routeId) <th>Rute</th> @endif
                @if(!$busId) <th>Bus</th> @endif
                <th>Penumpang</th>
                <th>Kursi</th>
                <th style="text-align: right">Harga</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $item)
            <tr>
                <td>{{ Carbon\Carbon::parse($item->booking_date)->format('d/m/Y') }}</td>
                <td><span style="font-family: monospace">{{ $item->booking_code }}</span></td>
                @if(!$routeId)
                <td>{{ $item->schedule->route->origin }} - {{ $item->schedule->route->destination }}</td>
                @endif
                @if(!$busId)
                <td>{{ $item->schedule->bus->name }}</td>
                @endif
                <td>
                    {{ $item->passenger_name }}<br>
                    <span style="color: #666; font-size: 10px">{{ $item->passenger_phone }}</span>
                </td>
                <td>{{ $item->number_of_seats }}</td>
                <td style="text-align: right">Rp {{ number_format($item->total_price, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="{{ ($routeId && $busId) ? 4 : (($routeId || $busId) ? 5 : 6) }}" style="text-align: right; font-weight: bold">Total</td>
                <td style="text-align: right; font-weight: bold">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <p>Dicetak oleh: {{ auth()->user()->name }}</p>
    </div>
</body>
</html>
