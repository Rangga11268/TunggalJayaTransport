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
        
        // Get recent bookings with their schedules
        $recentBookings = Booking::with('schedule.route', 'user')
            ->latest()
            ->take(5)
            ->get();
            
        // Get upcoming schedules
        $upcomingSchedules = Schedule::with('route', 'bus')
            ->where('departure_time', '>', now())
            ->orderBy('departure_time')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('totalBookings', 'totalRevenue', 'totalSchedules', 'totalUsers', 'recentBookings', 'upcomingSchedules'));
    }
    
    // Test method to verify role-based access control
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
