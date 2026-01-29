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
                 return [
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
