<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\Bus;
use App\Models\Route as BusRoute;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ScheduleManagementController extends Controller
{
    /**
     * Display a comprehensive schedule management table
     */
    public function index(Request $request)
    {
        // Get all schedules with their relations
        $query = Schedule::with(['bus', 'route']);
        
        // Apply filters
        if ($request->filled('bus_id')) {
            $query->where('bus_id', $request->bus_id);
        }
        
        if ($request->filled('route_id')) {
            $query->where('route_id', $request->route_id);
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('type')) {
            if ($request->type == 'weekly') {
                $query->where('is_weekly', true);
            } elseif ($request->type == 'daily_recurring') {
                $query->where('is_daily', true)->where('is_weekly', false);
            } elseif ($request->type == 'daily') {
                $query->where('is_daily', false)->where('is_weekly', false);
            }
        }
        
        if ($request->filled('date')) {
            $query->whereDate('departure_time', $request->date);
        }
        
        // Order by departure time
        $query->orderBy('departure_time', 'asc');
        
        $schedules = $query->paginate(20);
        
        // Get filter options
        $buses = Bus::all();
        $routes = BusRoute::all();
        
        return view('admin.schedule-management.index', compact('schedules', 'buses', 'routes'));
    }
    
    /**
     * Get detailed information about a specific schedule
     */
    public function show($id)
    {
        $schedule = Schedule::with(['bus', 'route', 'bookings.user'])->findOrFail($id);
        
        // Calculate additional information
        $availableSeats = $schedule->getAvailableSeatsCount();
        $bookedSeats = $schedule->getBookedSeatsCount();
        $hasDeparted = $schedule->hasDeparted();
        $isAvailable = $schedule->isAvailableForBooking();
        
        return view('admin.schedule-management.show', compact('schedule', 'availableSeats', 'bookedSeats', 'hasDeparted', 'isAvailable'));
    }
}