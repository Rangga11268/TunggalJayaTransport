<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalBookings = Booking::count();
        $totalRevenue = Booking::where('payment_status', 'paid')->sum('total_price');
        $totalSchedules = Schedule::count();
        $totalUsers = User::count();
        
        // Ambil booking yg baru-baru aja
        $recentBookings = Booking::with('schedule.route', 'user')
            ->latest()
            ->take(5)
            ->get();
            
        // Ambil jadwal yg mau berangkat bentar lagi
        $upcomingSchedules = Schedule::with('route', 'bus')
            ->where('departure_time', '>', now())
            ->orderBy('departure_time')
            ->take(5)
            ->get();

        // ============ ANALYTICS DATA ============
        
        // 1. Revenue Trend (Last 30 Days)
        $revenueTrend = Booking::where('payment_status', 'paid')
            ->where('created_at', '>=', now()->subDays(30))
            ->selectRaw('DATE(created_at) as date, SUM(total_price) as revenue, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->map(fn($item) => [
                'date' => \Carbon\Carbon::parse($item->date)->format('d M'),
                'revenue' => (float) $item->revenue,
                'count' => (int) $item->count,
            ]);

        // 2. Popular Routes (Top 5 by booking count)
        $popularRoutes = Booking::with('schedule.route')
            ->where('payment_status', 'paid')
            ->whereHas('schedule.route')
            ->get()
            ->groupBy(fn($booking) => $booking->schedule->route->id)
            ->map(function ($bookings) {
                $route = $bookings->first()->schedule->route;
                return [
                    'route' => $route->origin . ' → ' . $route->destination,
                    'bookings' => $bookings->count(),
                    'revenue' => $bookings->sum('total_price'),
                    'passengers' => $bookings->sum('number_of_seats'),
                ];
            })
            ->sortByDesc('bookings')
            ->take(5)
            ->values();

        // 3. Peak Hours (Booking distribution by hour)
        $peakHours = Booking::where('payment_status', 'paid')
            ->selectRaw('HOUR(created_at) as hour, COUNT(*) as count')
            ->groupBy('hour')
            ->orderBy('hour')
            ->get()
            ->mapWithKeys(fn($item) => [$item->hour => (int) $item->count]);
        
        // Fill missing hours with 0
        $peakHoursData = collect(range(0, 23))->map(fn($hour) => [
            'hour' => sprintf('%02d:00', $hour),
            'count' => $peakHours->get($hour, 0),
        ]);

        // 4. Weekly Comparison (This week vs Last week revenue)
        $thisWeekRevenue = Booking::where('payment_status', 'paid')
            ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->sum('total_price');
        
        $lastWeekRevenue = Booking::where('payment_status', 'paid')
            ->whereBetween('created_at', [now()->subWeek()->startOfWeek(), now()->subWeek()->endOfWeek()])
            ->sum('total_price');

        $revenueGrowth = $lastWeekRevenue > 0 
            ? round((($thisWeekRevenue - $lastWeekRevenue) / $lastWeekRevenue) * 100, 1) 
            : ($thisWeekRevenue > 0 ? 100 : 0);

        // 5. Today's Stats
        $todayBookings = Booking::whereDate('created_at', today())->count();
        $todayRevenue = Booking::where('payment_status', 'paid')
            ->whereDate('created_at', today())
            ->sum('total_price');

        return \Inertia\Inertia::render('Admin/Dashboard', [
            'totalBookings' => $totalBookings,
            'totalRevenue' => $totalRevenue,
            'totalSchedules' => $totalSchedules,
            'totalUsers' => $totalUsers,
            'recentBookings' => $recentBookings,
            'upcomingSchedules' => $upcomingSchedules,
            // Analytics
            'revenueTrend' => $revenueTrend,
            'popularRoutes' => $popularRoutes,
            'peakHours' => $peakHoursData,
            'thisWeekRevenue' => $thisWeekRevenue,
            'lastWeekRevenue' => $lastWeekRevenue,
            'revenueGrowth' => $revenueGrowth,
            'todayBookings' => $todayBookings,
            'todayRevenue' => $todayRevenue,
        ]);
    }
    
    // Cek role user buat ngetes doang
    public function testRoles()
    {
        $user = auth()->user();
        $roles = $user->roles->pluck('name')->toArray();
        
        return response()->json([
            'user' => $user->name,
            'email' => $user->email,
            'roles' => $roles,
            'is_admin' => $user->hasRole('admin'),
            'is_schedule_manager' => $user->hasRole('schedule_manager')
        ]);
    }
}
