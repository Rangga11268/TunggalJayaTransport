<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\Bus;
use App\Models\Route as BusRoute;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
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
        
        $schedules = $query->latest()->paginate(10);
        
        return view('admin.schedules.index', compact('schedules'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $buses = Bus::all();
        $routes = BusRoute::all();
        return view('admin.schedules.create', compact('buses', 'routes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi dasar
        $rules = [
            'bus_id' => 'required|exists:buses,id',
            'route_id' => 'required|exists:routes,id',
            'departure_time' => 'required|date_format:H:i',
            'arrival_time' => 'required|date_format:H:i',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:active,cancelled,delayed',
            'schedule_type' => 'required|in:daily,weekly,daily_recurring',
        ];

        // Tambahkan validasi tambahan berdasarkan jenis jadwal
        if ($request->schedule_type == 'weekly') {
            $rules['day_of_week'] = 'required|integer|min:0|max:6';
        } else if ($request->schedule_type == 'daily') {
            $rules['departure_date'] = 'required|date';
        }

        $request->validate($rules);

        // Prepare data for creation
        $data = $request->only([
            'bus_id', 'route_id', 'departure_time', 'arrival_time', 'price', 'status'
        ]);

        // Handle departure date and time based on schedule type
        if ($request->schedule_type == 'weekly') {
            // For weekly schedules, we store only the time part
            // The date part will be calculated dynamically when needed
            $data['is_weekly'] = true;
            $data['is_daily'] = false;
            $data['day_of_week'] = $request->day_of_week;
            
            // For weekly schedules, we store just the time without a specific date
            // Using a base date that won't interfere with calculations
            $baseDate = '2000-01-01';
            // Store times in WIB directly without converting to UTC
            $data['departure_time'] = $baseDate . ' ' . $request->departure_time . ':00';
            $data['arrival_time'] = $baseDate . ' ' . $request->arrival_time . ':00';
        } else if ($request->schedule_type == 'daily_recurring') {
            // For daily recurring schedules, we store only the time part
            $data['is_weekly'] = false;
            $data['is_daily'] = true;
            $data['day_of_week'] = null;
            
            // Using a base date that won't interfere with calculations
            $baseDate = '2000-01-01';
            // Store times in WIB directly without converting to UTC
            $data['departure_time'] = $baseDate . ' ' . $request->departure_time . ':00';
            $data['arrival_time'] = $baseDate . ' ' . $request->arrival_time . ':00';
        } else {
            // For daily schedules, combine date and time
            $data['is_weekly'] = false;
            $data['is_daily'] = false;
            $data['day_of_week'] = null;
            // Store datetime in WIB directly without converting to UTC
            $data['departure_time'] = $request->departure_date . ' ' . $request->departure_time . ':00';
            $data['arrival_time'] = $request->departure_date . ' ' . $request->arrival_time . ':00';
        }

        Schedule::create($data);

        return redirect()->route('admin.schedules.index')->with('create_success', 'Jadwal berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $schedule = Schedule::with(['bus', 'route', 'bookings'])->findOrFail($id);
        return view('admin.schedules.show', compact('schedule'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $schedule = Schedule::findOrFail($id);
        
        // Check if schedule has already departed
        if ($schedule->hasDeparted() && !$schedule->is_weekly) {
            return redirect()->route('admin.schedules.index')
                ->with('warning', 'This schedule has already departed and cannot be edited. You can create a new schedule for tomorrow or delete this one.');
        }
        
        $buses = Bus::all();
        $routes = BusRoute::all();
        return view('admin.schedules.edit', compact('schedule', 'buses', 'routes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $schedule = Schedule::findOrFail($id);

        // Check if schedule has already departed
        if ($schedule->hasDeparted() && !$schedule->is_weekly) {
            return redirect()->route('admin.schedules.index')
                ->with('warning', 'This schedule has already departed and cannot be updated. You can create a new schedule for tomorrow or delete this one.');
        }

        // Validasi dasar
        $rules = [
            'bus_id' => 'required|exists:buses,id',
            'route_id' => 'required|exists:routes,id',
            'departure_time' => 'required|date_format:H:i',
            'arrival_time' => 'required|date_format:H:i',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:active,cancelled,delayed',
            'schedule_type' => 'required|in:daily,weekly,daily_recurring',
        ];

        // Tambahkan validasi tambahan berdasarkan jenis jadwal
        if ($request->schedule_type == 'weekly') {
            $rules['day_of_week'] = 'required|integer|min:0|max:6';
        } else if ($request->schedule_type == 'daily') {
            $rules['departure_date'] = 'required|date';
        }

        $request->validate($rules);

        // Prepare data for update
        $data = $request->only([
            'bus_id', 'route_id', 'departure_time', 'arrival_time', 'price', 'status'
        ]);

        // Handle departure date and time based on schedule type
        if ($request->schedule_type == 'weekly') {
            // For weekly schedules, we store only the time part
            // The date part will be calculated dynamically when needed
            $data['is_weekly'] = true;
            $data['is_daily'] = false;
            $data['day_of_week'] = $request->day_of_week;
            
            // For weekly schedules, we store just the time without a specific date
            // Using a base date that won't interfere with calculations
            $baseDate = '2000-01-01';
            // Store times in WIB directly without converting to UTC
            $data['departure_time'] = $baseDate . ' ' . $request->departure_time . ':00';
            $data['arrival_time'] = $baseDate . ' ' . $request->arrival_time . ':00';
        } else if ($request->schedule_type == 'daily_recurring') {
            // For daily recurring schedules, we store only the time part
            $data['is_weekly'] = false;
            $data['is_daily'] = true;
            $data['day_of_week'] = null;
            
            // Using a base date that won't interfere with calculations
            $baseDate = '2000-01-01';
            // Store times in WIB directly without converting to UTC
            $data['departure_time'] = $baseDate . ' ' . $request->departure_time . ':00';
            $data['arrival_time'] = $baseDate . ' ' . $request->arrival_time . ':00';
        } else {
            // For daily schedules, combine date and time
            $data['is_weekly'] = false;
            $data['is_daily'] = false;
            $data['day_of_week'] = null;
            // Store datetime in WIB directly without converting to UTC
            $data['departure_time'] = $request->departure_date . ' ' . $request->departure_time . ':00';
            $data['arrival_time'] = $request->departure_date . ' ' . $request->arrival_time . ':00';
        }

        $schedule->update($data);

        return redirect()->route('admin.schedules.index')->with('update_success', 'Jadwal berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $schedule = Schedule::findOrFail($id);
        
        // Check if schedule has already departed
        if ($schedule->hasDeparted() && !$schedule->is_weekly) {
            // For non-weekly schedules that have departed, we can delete them
            $schedule->delete();
            return redirect()->route('admin.schedules.index')->with('delete_success', 'Jadwal berhasil dihapus.');
        } else if ($schedule->is_weekly) {
            // For weekly schedules, allow deletion
            $schedule->delete();
            return redirect()->route('admin.schedules.index')->with('delete_success', 'Jadwal mingguan berhasil dihapus.');
        } else {
            // For daily schedules that haven't departed yet
            $schedule->delete();
            return redirect()->route('admin.schedules.index')->with('delete_success', 'Jadwal berhasil dihapus.');
        }
    }
    
    /**
     * Create a new daily schedule for tomorrow based on an existing daily recurring schedule
     */
    public function createNextDaySchedule(string $id)
    {
        $schedule = Schedule::findOrFail($id);
        
        // Only allow this for daily recurring schedules
        if (!$schedule->is_daily) {
            return redirect()->route('admin.schedules.index')->with('error', 'Hanya jadwal harian berulang yang bisa digunakan untuk membuat jadwal hari berikutnya.');
        }
        
        // For daily recurring schedules, we don't need to check if they've departed
        // as they are available every day. Instead, we'll create a specific daily schedule
        // for tomorrow based on this recurring schedule.
        
        // Create a new daily schedule for tomorrow
        $tomorrow = Carbon::tomorrow('Asia/Jakarta');
        
        $newSchedule = new Schedule();
        $newSchedule->bus_id = $schedule->bus_id;
        $newSchedule->route_id = $schedule->route_id;
        $newSchedule->departure_time = $tomorrow->format('Y-m-d') . ' ' . $schedule->departure_time->format('H:i:s');
        $newSchedule->arrival_time = $tomorrow->format('Y-m-d') . ' ' . $schedule->arrival_time->format('H:i:s');
        $newSchedule->price = $schedule->price;
        $newSchedule->status = 'active';
        $newSchedule->is_weekly = false;
        $newSchedule->is_daily = false; // This is a specific daily schedule, not recurring
        $newSchedule->day_of_week = null;
        $newSchedule->save();
        
        return redirect()->route('admin.schedules.index')->with('create_success', 'Jadwal untuk hari besok berhasil dibuat.');
    }
}
