<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CustomReportExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $reportType;
    protected $data;
    protected $filters;
    protected $selectedRoute;
    protected $selectedBus;

    public function __construct($reportType, $data, $filters, $selectedRoute, $selectedBus)
    {
        $this->reportType = $reportType;
        $this->data = $data;
        $this->filters = $filters;
        $this->selectedRoute = $selectedRoute;
        $this->selectedBus = $selectedBus;
    }

    public function collection()
    {
        switch ($this->reportType) {
            case 'bookings':
                return collect($this->data['daily_bookings']);
            case 'revenue':
                 return collect($this->data['daily_revenue']);
            case 'passengers':
                // For passengers, we need to flatten the structure slightly for Excel
                $routeData = collect($this->data['route_passengers'])->map(function($count, $route) {
                    return ['type' => 'Rute', 'name' => $route, 'count' => $count];
                });
                $busData = collect($this->data['bus_passengers'])->map(function($count, $bus) {
                    return ['type' => 'Armada', 'name' => $bus, 'count' => $count];
                });
                return $routeData->merge($busData);
            default:
                return collect([]);
        }
    }

    public function headings(): array
    {
        switch ($this->reportType) {
            case 'bookings':
                return ['Tanggal', 'Jumlah Transaksi', 'Kursi Terjual'];
            case 'revenue':
                return ['Tanggal', 'Pendapatan'];
            case 'passengers':
                return ['Kategori', 'Nama', 'Jumlah Penumpang'];
            default:
                return [];
        }
    }

    public function map($row): array
    {
        // $row is specific to how the collection was structured
        switch ($this->reportType) {
            case 'bookings':
                 // $row is the value from daily_bookings array, indexed by date. 
                 // Wait, calling collect() on an assoc array keyed by date might behave differently.
                 // Let's adjust the collection method to be explicit about rows.
                 return [
                     // Since we didn't include the date in the value itself in controller (it was the key), 
                     // We need to fix the collection structure above or handle it here if possible. 
                     // The FromCollection interface expects a collection of rows.
                     // The previous controller code: $dailyBookings = ...->get()->keyBy('date');
                     // The key is the date. The value is an object { date: ... count: ... seats: ... }
                     // So we can access $row->date if the SQL query selected it.
                     // Controller select: selectRaw('DATE(created_at) as date, ...')
                     // So $row should have 'date'.
                     $row->date,
                     $row->count,
                     $row->seats
                 ];
            case 'revenue':
                return [
                    $row->date,
                    $row->revenue
                ];
            case 'passengers':
                return [
                    $row['type'],
                    $row['name'],
                    $row['count']
                ];
            default:
                return [];
        }
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
