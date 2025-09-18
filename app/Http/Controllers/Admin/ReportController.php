<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Route as TransportRoute;
use App\Models\Bus;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        return view('admin.reports.index');
    }
    
    public function sales()
    {
        // Get sales data for the last 30 days
        $salesData = \App\Models\Booking::selectRaw('DATE(created_at) as date, SUM(total_price) as total')
            ->where('payment_status', 'paid')
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
            
        // Format the data for the chart
        $chartData = $salesData->map(function($item) {
            return [
                'date' => \Carbon\Carbon::parse($item->date)->format('M j'),
                'total' => (float) $item->total
            ];
        });
            
        // Get recent bookings for the table
        $recentBookings = \App\Models\Booking::with(['schedule.route', 'user'])
            ->where('payment_status', 'paid')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
            
        return view('admin.reports.sales', compact('salesData', 'chartData', 'recentBookings'));
    }
    
    public function occupancy()
    {
        // Get occupancy data by fetching schedules with their bookings
        $schedules = \App\Models\Schedule::with(['bus', 'route', 'bookings' => function($query) {
            $query->where('booking_status', 'confirmed')
                  ->where('payment_status', 'paid');
        }])->get();
        
        // Calculate occupancy for each schedule
        $occupancyData = [];
        foreach ($schedules as $schedule) {
            $totalCapacity = $schedule->bus->capacity;
            $bookedSeats = $schedule->bookings->sum('number_of_seats');
            $occupancyRate = $totalCapacity > 0 ? ($bookedSeats / $totalCapacity) * 100 : 0;
            
            $occupancyData[] = [
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
        
        return view('admin.reports.occupancy', compact('occupancyData'));
    }
    
    public function custom()
    {
        // Get data for the form
        $routes = TransportRoute::all();
        $buses = Bus::all();
        
        return view('admin.reports.custom', compact('routes', 'buses'));
    }
    
    public function generateCustom(Request $request)
    {
        // Validate the request
        $request->validate([
            'report_type' => 'required|in:bookings,revenue,passengers',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'route_id' => 'nullable|exists:routes,id',
            'bus_id' => 'nullable|exists:buses,id',
        ]);
        
        // Get data for the form
        $routes = TransportRoute::all();
        $buses = Bus::all();
        
        // Get the form data
        $reportType = $request->input('report_type');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $routeId = $request->input('route_id');
        $busId = $request->input('bus_id');
        
        // Initialize the query
        $query = Booking::whereBetween('created_at', [$startDate, $endDate]);
        
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
        $selectedRoute = $routeId ? TransportRoute::find($routeId) : null;
        $selectedBus = $busId ? Bus::find($busId) : null;
        
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
                $dailyRevenue = $query->where('payment_status', 'paid')
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
        
        // Return the view with all data
        return view('admin.reports.custom', compact(
            'routes', 
            'buses', 
            'reportType', 
            'startDate', 
            'endDate', 
            'routeId', 
            'busId', 
            'selectedRoute', 
            'selectedBus', 
            'reportData'
        ));
    }
}
