<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Route as TransportRoute;
use App\Models\Bus;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CustomReportExport;

class ReportController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Reports/Index');
    }
    
    public function sales()
    {
        // Get sales data for the last 30 days
        $salesData = Booking::selectRaw('DATE(created_at) as date, SUM(total_price) as total')
            ->where('payment_status', 'paid')
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
            
        // Format the data for the chart
        $chartData = $salesData->map(function($item) {
            return [
                'date' => Carbon::parse($item->date)->format('M j'),
                'total' => (float) $item->total
            ];
        });
            
        // Get recent bookings for the table
        $recentBookings = Booking::with(['schedule.route', 'user', 'schedule.bus'])
            ->where('payment_status', 'paid')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($booking) {
                return [
                    'id' => $booking->id,
                    'booking_code' => $booking->booking_code,
                    'user_name' => $booking->user ? $booking->user->name : 'Guest',
                    'route' => $booking->schedule->route ? $booking->schedule->route->origin . ' - ' . $booking->schedule->route->destination : '-',
                    'total_price' => $booking->total_price,
                    'created_at' => $booking->created_at->format('d M Y H:i'),
                ];
            });
            
        return Inertia::render('Admin/Reports/Sales', [
            'salesData' => $salesData, 
            'chartData' => $chartData, 
            'recentBookings' => $recentBookings
        ]);
    }
    
    public function occupancy()
    {
        // Get occupancy data by fetching schedules with their bookings
        $schedules = Schedule::with(['bus', 'route', 'bookings' => function($query) {
            $query->where('booking_status', 'confirmed')
                  ->where('payment_status', 'paid');
        }])
        ->whereHas('bus') // Ensure bus exists
        ->whereHas('route') // Ensure route exists
        ->latest()
        ->take(50) // Limit to recent 50 schedules for performance
        ->get();
        
        // Calculate occupancy for each schedule
        $occupancyData = [];
        foreach ($schedules as $schedule) {
            $totalCapacity = $schedule->bus->capacity;
            $bookedSeats = $schedule->bookings->sum('number_of_seats');
            $occupancyRate = $totalCapacity > 0 ? ($bookedSeats / $totalCapacity) * 100 : 0;
            
            $occupancyData[] = [
                'id' => $schedule->id,
                'date' => Carbon::parse($schedule->departure_time)->format('d M Y H:i'),
                'bus_name' => $schedule->bus->name,
                'plate_number' => $schedule->bus->plate_number,
                'route' => $schedule->route->origin . ' - ' . $schedule->route->destination,
                'capacity' => (int) $totalCapacity,
                'booked_seats' => (int) $bookedSeats,
                'occupancy_rate' => round($occupancyRate, 2)
            ];
        }
        
        // Sort by occupancy rate descending
        usort($occupancyData, function($a, $b) {
            return $b['occupancy_rate'] <=> $a['occupancy_rate'];
        });
        
        return Inertia::render('Admin/Reports/Occupancy', [
            'occupancyData' => $occupancyData
        ]);
    }
    
    public function custom()
    {
        // Get data for the form
        $routes = TransportRoute::all()->map(function($route) {
            return [
                'id' => $route->id,
                'name' => $route->origin . ' - ' . $route->destination
            ];
        });

        $buses = Bus::all()->map(function($bus) {
            return [
                'id' => $bus->id,
                'name' => $bus->name . ' (' . $bus->plate_number . ')'
            ];
        });
        
        return Inertia::render('Admin/Reports/Custom', [
            'routes' => $routes, 
            'buses' => $buses
        ]);
    }
    
    public function generateCustom(Request $request)
    {
        // Validate the request
        $this->validateReportRequest($request);
        
        $data = $this->getReportData($request);
        
        // Get data for the form (dropdowns)
        $routes = TransportRoute::all()->map(function($route) {
            return [
                'id' => $route->id,
                'name' => $route->origin . ' - ' . $route->destination
            ];
        });
        
        $buses = Bus::all()->map(function($bus) {
            return [
                'id' => $bus->id,
                'name' => $bus->name . ' (' . $bus->plate_number . ')'
            ];
        });

        return Inertia::render('Admin/Reports/Custom', [
            'routes' => $routes, 
            'buses' => $buses, 
            'filters' => $request->all(),
            'selectedRoute' => $data['selectedRoute'],
            'selectedBus' => $data['selectedBus'], 
            'reportData' => $data['reportData']
        ]);
    }
    
    public function exportPdf(Request $request)
    {
        $this->validateReportRequest($request);
        $data = $this->getReportData($request);
        
        $pdf = Pdf::loadView('admin.reports.pdf', [
            'filters' => $request->all(),
            'selectedRoute' => $data['selectedRoute'],
            'selectedBus' => $data['selectedBus'], 
            'reportData' => $data['reportData'],
            'reportType' => $request->report_type
        ]);
        
        return $pdf->download('laporan_' . $request->report_type . '_' . now()->format('YmdHis') . '.pdf');
    }
    
    public function exportExcel(Request $request)
    {
        $this->validateReportRequest($request);
        $data = $this->getReportData($request);
        
        return Excel::download(new CustomReportExport(
            $request->report_type,
            $data['reportData'],
            $request->all(),
            $data['selectedRoute'],
            $data['selectedBus']
        ), 'laporan_' . $request->report_type . '_' . now()->format('YmdHis') . '.xlsx');
    }
    
    private function validateReportRequest(Request $request)
    {
        $request->validate([
            'report_type' => 'required|in:bookings,revenue,passengers',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'route_id' => 'nullable|exists:routes,id',
            'bus_id' => 'nullable|exists:buses,id',
        ]);
    }
    
    private function getReportData(Request $request)
    {
        $reportType = $request->input('report_type');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $routeId = $request->input('route_id');
        $busId = $request->input('bus_id');
        
        // Use Carbon to ensuring proper date comparison including time if needed, or set start/end of day
        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        $query = Booking::whereBetween('created_at', [$start, $end]);
        
        // Apply filters if provided
        if ($routeId) {
            $query->whereHas('schedule', function($q) use ($routeId) {
                $q->where('route_id', $routeId);
            });
        }
        
        if ($busId) {
            $query->whereHas('schedule', function($q) use ($busId) {
                $q->where('bus_id', $busId);
            });
        }
        
        // Get the selected route and bus if provided
        $selectedRouteObj = $routeId ? TransportRoute::find($routeId) : null;
        $selectedBusObj = $busId ? Bus::find($busId) : null;
        
        $selectedRoute = $selectedRouteObj ? ($selectedRouteObj->origin . ' - ' . $selectedRouteObj->destination) : null;
        $selectedBus = $selectedBusObj ? ($selectedBusObj->name . ' (' . $selectedBusObj->plate_number . ')') : null;

        // Generate report data based on type
        $reportData = [];
        
        switch ($reportType) {
            case 'bookings':
                // Get daily bookings data
                $dailyBookings = $query->selectRaw('DATE(created_at) as date, COUNT(*) as count, SUM(number_of_seats) as seats')
                    ->groupBy('date')
                    ->orderBy('date')
                    ->get()
                    ->keyBy('date');
                
                // Calculate totals
                $totalBookings = $dailyBookings->sum('count');
                $totalSeats = $dailyBookings->sum('seats');
                
                $reportData = [
                    'daily_bookings' => $dailyBookings,
                    'total_bookings' => $totalBookings,
                    'total_seats' => $totalSeats,
                ];
                break;
                
            case 'revenue':
                // Get daily revenue data
                $dailyRevenueQuery = clone $query; 
                $dailyRevenue = $dailyRevenueQuery->where('payment_status', 'paid')
                    ->selectRaw('DATE(created_at) as date, SUM(total_price) as revenue')
                    ->groupBy('date')
                    ->orderBy('date')
                    ->get()
                    ->keyBy('date');
                
                // Calculate totals
                $totalRevenue = $dailyRevenue->sum('revenue');
                $avgBookingValue = $dailyRevenue->count() > 0 ? $totalRevenue / $dailyRevenue->count() : 0;
                
                $reportData = [
                    'daily_revenue' => $dailyRevenue,
                    'total_revenue' => $totalRevenue,
                    'avg_booking_value' => $avgBookingValue,
                ];
                break;
                
            case 'passengers':
                // Get bookings with passenger details
                $bookings = $query->with(['schedule.route', 'schedule.bus'])->get();
                
                // Calculate passengers by route
                $routePassengers = [];
                $busPassengers = [];
                $totalPassengers = 0;
                
                foreach ($bookings as $booking) {
                    $passengerCount = $booking->number_of_seats;
                    $totalPassengers += $passengerCount;
                    
                    // Route passengers
                    $routeKey = $booking->schedule && $booking->schedule->route ? 
                        $booking->schedule->route->origin . ' - ' . $booking->schedule->route->destination : 
                        'Unknown Route';
                    if (!isset($routePassengers[$routeKey])) {
                        $routePassengers[$routeKey] = 0;
                    }
                    $routePassengers[$routeKey] += $passengerCount;
                    
                    // Bus passengers
                    $busKey = $booking->schedule && $booking->schedule->bus ? 
                        $booking->schedule->bus->name . ' (' . $booking->schedule->bus->plate_number . ')' : 
                        'Unknown Bus';
                    if (!isset($busPassengers[$busKey])) {
                        $busPassengers[$busKey] = 0;
                    }
                    $busPassengers[$busKey] += $passengerCount;
                }
                
                $reportData = [
                    'route_passengers' => $routePassengers,
                    'bus_passengers' => $busPassengers,
                    'total_passengers' => $totalPassengers,
                ];
                break;
        }
        
        return [
            'reportData' => $reportData,
            'selectedRoute' => $selectedRoute,
            'selectedBus' => $selectedBus
        ];
    }
}
