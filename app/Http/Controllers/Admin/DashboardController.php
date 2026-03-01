<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Quick aggregates (always fresh, low cost)
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

        // ============ ANALYTICS DATA (CACHED) ============

        // 1. Revenue Trend (Last 30 Days) - Cache 1 hour
        $revenueTrend = Cache::remember('dashboard:revenue_trend', 3600, function () {
            return Booking::where('payment_status', 'paid')
                ->where('created_at', '>=', now()->subDays(30))
                ->selectRaw('DATE(created_at) as date, SUM(total_price) as revenue, COUNT(*) as count')
                ->groupBy('date')
                ->orderBy('date')
                ->get()
                ->map(fn($item) => [
                    'date' => Carbon::parse($item->date)->format('d M'),
                    'revenue' => (float) $item->revenue,
                    'count' => (int) $item->count,
                ]);
        });

        // 2. Popular Routes (Top 5 by booking count) - OPTIMIZED SQL
        // Cache 2 hours
        $popularRoutes = Cache::remember('dashboard:popular_routes', 7200, function () {
            return Booking::select(
                'bookings.schedule_id',
                DB::raw('COUNT(bookings.id) as booking_count'),
                DB::raw('SUM(bookings.total_price) as total_revenue'),
                DB::raw('SUM(bookings.number_of_seats) as total_passengers'),
                'routes.origin',
                'routes.destination'
            )
                ->join('schedules', 'bookings.schedule_id', '=', 'schedules.id')
                ->join('routes', 'schedules.route_id', '=', 'routes.id')
                ->where('bookings.payment_status', 'paid')
                ->groupBy('schedules.route_id', 'routes.origin', 'routes.destination')
                ->orderByDesc('booking_count')
                ->limit(5)
                ->get()
                ->map(fn($item) => [
                    'route' => $item->origin . ' → ' . $item->destination,
                    'bookings' => (int) $item->booking_count,
                    'revenue' => (float) $item->total_revenue,
                    'passengers' => (int) $item->total_passengers,
                ])
                ->values();
        });

        // 3. Peak Hours (Booking distribution by hour) - Cache 2 hours
        $peakHoursData = Cache::remember('dashboard:peak_hours', 7200, function () {
            $peakHours = Booking::where('payment_status', 'paid')
                ->selectRaw('HOUR(created_at) as hour, COUNT(*) as count')
                ->groupBy('hour')
                ->orderBy('hour')
                ->pluck('count', 'hour');

            // Fill missing hours with 0
            return collect(range(0, 23))->map(fn($hour) => [
                'hour' => sprintf('%02d:00', $hour),
                'count' => (int) ($peakHours[$hour] ?? 0),
            ]);
        });

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

        // 5. Today's Stats (always fresh)
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
        $user = \Illuminate\Support\Facades\Auth::user();
        /** @var \App\Models\User $user */
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
