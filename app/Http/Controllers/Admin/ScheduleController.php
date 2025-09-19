<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\Bus;
use App\Models\Route as BusRoute;
use Illuminate\Http\Request;

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
        ];

        // Tambahkan validasi tambahan berdasarkan jenis jadwal
        if ($request->is_weekly == 1) {
            $rules['day_of_week'] = 'required|integer|min:0|max:6';
        } else if ($request->is_daily == 1) {
            // Daily recurring schedules don't need additional validation
        } else {
            $rules['departure_date'] = 'required|date';
        }

        $request->validate($rules);

        // Prepare data for creation
        $data = $request->only([
            'bus_id', 'route_id', 'departure_time', 'arrival_time', 'price', 'status'
        ]);

        // Handle departure date and time based on schedule type
        if ($request->is_weekly == 1) {
            // For weekly schedules, we store only the time part
            // The date part will be calculated dynamically when needed
            $data['is_weekly'] = true;
            $data['is_daily'] = false;
            $data['day_of_week'] = $request->day_of_week;
            
            // For weekly schedules, we store just the time without a specific date
            // Using a base date that won't interfere with calculations
            $baseDate = '2000-01-01';
            $data['departure_time'] = $baseDate . ' ' . $request->departure_time;
            $data['arrival_time'] = $baseDate . ' ' . $request->arrival_time;
        } else if ($request->is_daily == 1) {
            // For daily recurring schedules, we store only the time part
            $data['is_weekly'] = false;
            $data['is_daily'] = true;
            $data['day_of_week'] = null;
            
            // Using a base date that won't interfere with calculations
            $baseDate = '2000-01-01';
            $data['departure_time'] = $baseDate . ' ' . $request->departure_time;
            $data['arrival_time'] = $baseDate . ' ' . $request->arrival_time;
        } else {
            // For daily schedules, combine date and time
            $data['is_weekly'] = false;
            $data['is_daily'] = false;
            $data['day_of_week'] = null;
            $data['departure_time'] = $request->departure_date . ' ' . $request->departure_time;
            $data['arrival_time'] = $request->departure_date . ' ' . $request->arrival_time;
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
        if ($schedule->hasDeparted()) {
            return redirect()->route('admin.schedules.index')
                ->with('warning', 'This schedule has already departed. Please be careful when editing as it may affect existing bookings.');
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
        if ($schedule->hasDeparted()) {
            return redirect()->route('admin.schedules.index')
                ->with('warning', 'This schedule has already departed and cannot be updated.');
        }

        // Validasi dasar
        $rules = [
            'bus_id' => 'required|exists:buses,id',
            'route_id' => 'required|exists:routes,id',
            'is_weekly' => 'required|boolean',
            'is_daily' => 'required|boolean',
            'departure_time' => 'required|date_format:H:i',
            'arrival_time' => 'required|date_format:H:i',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:active,cancelled,delayed',
        ];

        // Tambahkan validasi tambahan berdasarkan jenis jadwal
        if ($request->is_weekly == 1) {
            $rules['day_of_week'] = 'required|integer|min:0|max:6';
        } else if ($request->is_daily == 1) {
            // Daily recurring schedules don't need additional validation
        } else {
            $rules['departure_date'] = 'required|date';
        }

        $request->validate($rules);

        // Prepare data for update
        $data = $request->only([
            'bus_id', 'route_id', 'departure_time', 'arrival_time', 'price', 'status'
        ]);

        // Handle departure date and time based on schedule type
        if ($request->is_weekly == 1) {
            // For weekly schedules, we store only the time part
            // The date part will be calculated dynamically when needed
            $data['is_weekly'] = true;
            $data['is_daily'] = false;
            $data['day_of_week'] = $request->day_of_week;
            
            // For weekly schedules, we store just the time without a specific date
            // Using a base date that won't interfere with calculations
            $baseDate = '2000-01-01';
            $data['departure_time'] = $baseDate . ' ' . $request->departure_time;
            $data['arrival_time'] = $baseDate . ' ' . $request->arrival_time;
        } else if ($request->is_daily == 1) {
            // For daily recurring schedules, we store only the time part
            $data['is_weekly'] = false;
            $data['is_daily'] = true;
            $data['day_of_week'] = null;
            
            // Using a base date that won't interfere with calculations
            $baseDate = '2000-01-01';
            $data['departure_time'] = $baseDate . ' ' . $request->departure_time;
            $data['arrival_time'] = $baseDate . ' ' . $request->arrival_time;
        } else {
            // For daily schedules, combine date and time
            $data['is_weekly'] = false;
            $data['is_daily'] = false;
            $data['day_of_week'] = null;
            $data['departure_time'] = $request->departure_date . ' ' . $request->departure_time;
            $data['arrival_time'] = $request->departure_date . ' ' . $request->arrival_time;
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
        $schedule->delete();

        return redirect()->route('admin.schedules.index')->with('delete_success', 'Jadwal berhasil dihapus.');
    }
}
