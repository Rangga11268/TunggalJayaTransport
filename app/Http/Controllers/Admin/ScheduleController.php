<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\Bus;
use App\Models\Route as BusRoute;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;

class ScheduleController extends Controller
{
    
    public function index(Request $request)
    {
        $query = Schedule::with(['bus', 'route'])
            ->when($request->search, function ($query, $search) {
                $query->whereHas('bus', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('plate_number', 'like', "%{$search}%");
                })
                ->orWhereHas('route', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('origin', 'like', "%{$search}%")
                      ->orWhere('destination', 'like', "%{$search}%");
                });
            });
        
        // Apply specific filters if provided
        if ($request->filled('bus_id')) {
            $query->where('bus_id', $request->bus_id);
        }
        
        if ($request->filled('route_id')) {
            $query->where('route_id', $request->route_id);
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $schedules = $query->latest()->paginate(10)->withQueryString();

        // Transform data for frontend
        $schedules->getCollection()->transform(function ($schedule) {
            return [
                'id' => $schedule->id,
                'bus' => $schedule->bus,
                'route' => $schedule->route,
                'price' => $schedule->price,
                'status' => $schedule->status,
                'schedule_type' => $schedule->schedule_type,
                'departure_time' => $schedule->departure_time,
                'arrival_time' => $schedule->arrival_time,
                'is_daily' => $schedule->is_daily,
                // Add formatted fields for display
                'formatted_departure' => $schedule->getActualDepartureTime()->format('d M Y H:i'),
                'formatted_arrival' => $schedule->getActualArrivalTime()->format('d M Y H:i'),
                'time_only_departure' => $schedule->getActualDepartureTime()->format('H:i'),
                'time_only_arrival' => $schedule->getActualArrivalTime()->format('H:i'),
            ];
        });
        
        return Inertia::render('Admin/Schedules/Index', [
            'schedules' => $schedules,
            'filters' => $request->only(['search', 'bus_id', 'route_id', 'status']),
            'buses' => Bus::select('id', 'name')->get(),
            'routes' => BusRoute::select('id', 'name')->get(),
        ]);
    }

    
    public function create()
    {
        $buses = Bus::where('status', 'active')->select('id', 'name', 'plate_number', 'bus_type', 'capacity')->get();
        $routes = BusRoute::select('id', 'name', 'origin', 'destination', 'duration')->get();
        
        return Inertia::render('Admin/Schedules/Create', compact('buses', 'routes'));
    }

    
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
            'schedule_type' => 'required|in:daily,daily_recurring',
        ];

        // Tambahkan validasi tambahan berdasarkan jenis jadwal
        if ($request->schedule_type == 'daily') {
            $rules['departure_date'] = 'required|date';
        }

        $request->validate($rules);

        // Prepare data for creation
        $data = $request->only([
            'bus_id', 'route_id', 'departure_time', 'arrival_time', 'price', 'status'
        ]);

        // Handle departure date and time based on schedule type
        if ($request->schedule_type == 'daily_recurring') {
            // For daily recurring schedules, we store only the time part
            $data['is_daily'] = true;
            $data['schedule_type'] = 'daily_recurring';
            
            // Using today as base date to show current relevant time
            $baseDate = date('Y-m-d');
            // Store times in WIB directly without converting to UTC
            $data['departure_time'] = $baseDate . ' ' . $request->departure_time . ':00';
            $data['arrival_time'] = $baseDate . ' ' . $request->arrival_time . ':00';
        } else {
            // For daily schedules, combine date and time
            $data['is_daily'] = false;
            $data['schedule_type'] = 'daily';
            // Store datetime in WIB directly without converting to UTC
            $data['departure_time'] = $request->departure_date . ' ' . $request->departure_time . ':00';
            $data['arrival_time'] = $request->departure_date . ' ' . $request->arrival_time . ':00';
            
            // Handle arrival next day logic simply
            if ($request->arrival_time < $request->departure_time) {
                 $arrivalDate = Carbon::parse($request->departure_date)->addDay()->format('Y-m-d');
                 $data['arrival_time'] = $arrivalDate . ' ' . $request->arrival_time . ':00';
            }
        }

        Schedule::create($data);

        return redirect()->route('admin.schedules.index')->with('success', 'Jadwal berhasil dibuat.');
    }

    
    public function show(string $id)
    {
        // For now, redirect to edit
        return redirect()->route('admin.schedules.edit', $id);
    }

    
    public function edit(string $id)
    {
        $schedule = Schedule::findOrFail($id);
        
        // Prepare additional data for editing generic/recurring schedules vs specific dated ones
        $isRecurring = $schedule->schedule_type === 'daily_recurring';
        
        $editData = [
            'id' => $schedule->id,
            'bus_id' => $schedule->bus_id,
            'route_id' => $schedule->route_id,
            'price' => $schedule->price,
            'status' => $schedule->status,
            'schedule_type' => $schedule->schedule_type,
            'departure_time' => Carbon::parse($schedule->departure_time)->format('H:i'),
            'arrival_time' => Carbon::parse($schedule->arrival_time)->format('H:i'),
            'departure_date' => $isRecurring ? null : Carbon::parse($schedule->departure_time)->format('Y-m-d'),
        ];
        
        $buses = Bus::where('status', 'active')->select('id', 'name', 'plate_number', 'bus_type', 'capacity')->get();
        $routes = BusRoute::select('id', 'name', 'origin', 'destination', 'duration')->get();
        
        return Inertia::render('Admin/Schedules/Edit', [
            'schedule' => $editData,
            'buses' => $buses,
            'routes' => $routes
        ]);
    }

    
    public function update(Request $request, string $id)
    {
        $schedule = Schedule::findOrFail($id);

        // Validasi dasar
        $rules = [
            'bus_id' => 'required|exists:buses,id',
            'route_id' => 'required|exists:routes,id',
            'departure_time' => 'required|date_format:H:i',
            'arrival_time' => 'required|date_format:H:i',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:active,cancelled,delayed',
            'schedule_type' => 'required|in:daily,daily_recurring',
        ];

        if ($request->schedule_type == 'daily') {
            $rules['departure_date'] = 'required|date';
        }

        $request->validate($rules);

        // Prepare data for update
        $data = $request->only([
            'bus_id', 'route_id', 'price', 'status', 'schedule_type'
        ]);

        if ($request->schedule_type == 'daily_recurring') {
            $data['is_daily'] = true;
            $baseDate = date('Y-m-d'); // Keep using today for time-only storage convention
            $data['departure_time'] = $baseDate . ' ' . $request->departure_time . ':00';
            $data['arrival_time'] = $baseDate . ' ' . $request->arrival_time . ':00';
        } else {
            $data['is_daily'] = false;
            $data['departure_time'] = $request->departure_date . ' ' . $request->departure_time . ':00';
            $data['arrival_time'] = $request->departure_date . ' ' . $request->arrival_time . ':00';
            
            if ($request->arrival_time < $request->departure_time) {
                 $arrivalDate = Carbon::parse($request->departure_date)->addDay()->format('Y-m-d');
                 $data['arrival_time'] = $arrivalDate . ' ' . $request->arrival_time . ':00';
            }
        }

        $schedule->update($data);

        return redirect()->route('admin.schedules.index')->with('success', 'Jadwal berhasil diperbarui.');
    }

    
    public function destroy(string $id)
    {
        $schedule = Schedule::findOrFail($id);
        $schedule->delete();

        return redirect()->route('admin.schedules.index')->with('success', 'Jadwal berhasil dihapus.');
    }
}
