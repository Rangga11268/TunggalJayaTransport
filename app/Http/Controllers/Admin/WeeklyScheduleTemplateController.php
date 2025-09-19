<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WeeklyScheduleTemplate;
use App\Models\Bus;
use App\Models\Route as BusRoute;
use Illuminate\Http\Request;

class WeeklyScheduleTemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = WeeklyScheduleTemplate::with(['bus', 'route']);
        
        // Apply filters
        if ($request->filled('bus_id')) {
            $query->where('bus_id', $request->bus_id);
        }
        
        if ($request->filled('route_id')) {
            $query->where('route_id', $request->route_id);
        }
        
        if ($request->filled('day_of_week')) {
            $query->where('day_of_week', $request->day_of_week);
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $templates = $query->latest()->paginate(10);
        $buses = Bus::all();
        $routes = BusRoute::all();
        
        return view('admin.weekly-schedule-templates.index', compact('templates', 'buses', 'routes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $buses = Bus::all();
        $routes = BusRoute::all();
        return view('admin.weekly-schedule-templates.create', compact('buses', 'routes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'bus_id' => 'required|exists:buses,id',
            'route_id' => 'required|exists:routes,id',
            'departure_time' => 'required|date_format:H:i',
            'arrival_time' => 'required|date_format:H:i',
            'price' => 'required|numeric|min:0',
            'day_of_week' => 'required|integer|min:0|max:6',
            'status' => 'required|in:active,inactive',
        ];

        $request->validate($rules);

        // Prepare data for creation
        $data = $request->only([
            'name', 'bus_id', 'route_id', 'departure_time', 'arrival_time', 'price', 'day_of_week', 'status'
        ]);

        // For templates, we store just the time without a specific date
        // Using a base date that won't interfere with calculations
        $baseDate = '2000-01-01';
        $data['departure_time'] = $baseDate . ' ' . $request->departure_time;
        $data['arrival_time'] = $baseDate . ' ' . $request->arrival_time;

        WeeklyScheduleTemplate::create($data);

        return redirect()->route('admin.weekly-schedule-templates.index')->with('create_success', 'Template jadwal mingguan berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $template = WeeklyScheduleTemplate::with(['bus', 'route'])->findOrFail($id);
        return view('admin.weekly-schedule-templates.show', compact('template'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $template = WeeklyScheduleTemplate::findOrFail($id);
        $buses = Bus::all();
        $routes = BusRoute::all();
        return view('admin.weekly-schedule-templates.edit', compact('template', 'buses', 'routes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $template = WeeklyScheduleTemplate::findOrFail($id);

        $rules = [
            'name' => 'required|string|max:255',
            'bus_id' => 'required|exists:buses,id',
            'route_id' => 'required|exists:routes,id',
            'departure_time' => 'required|date_format:H:i',
            'arrival_time' => 'required|date_format:H:i',
            'price' => 'required|numeric|min:0',
            'day_of_week' => 'required|integer|min:0|max:6',
            'status' => 'required|in:active,inactive',
        ];

        $request->validate($rules);

        // Prepare data for update
        $data = $request->only([
            'name', 'bus_id', 'route_id', 'departure_time', 'arrival_time', 'price', 'day_of_week', 'status'
        ]);

        // For templates, we store just the time without a specific date
        $baseDate = '2000-01-01';
        $data['departure_time'] = $baseDate . ' ' . $request->departure_time;
        $data['arrival_time'] = $baseDate . ' ' . $request->arrival_time;

        $template->update($data);

        return redirect()->route('admin.weekly-schedule-templates.index')->with('update_success', 'Template jadwal mingguan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $template = WeeklyScheduleTemplate::findOrFail($id);
        $template->delete();

        return redirect()->route('admin.weekly-schedule-templates.index')->with('delete_success', 'Template jadwal mingguan berhasil dihapus.');
    }

    /**
     * Show the form for generating schedules from template.
     */
    public function showGenerateForm(string $id)
    {
        $template = WeeklyScheduleTemplate::findOrFail($id);
        return view('admin.weekly-schedule-templates.generate', compact('template'));
    }

    /**
     * Generate schedules from template for a date range.
     */
    public function generateSchedules(Request $request, string $id)
    {
        $template = WeeklyScheduleTemplate::findOrFail($id);
        
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $createdSchedules = $template->createSchedulesForDateRange(
            $request->start_date, 
            $request->end_date
        );

        return redirect()->route('admin.schedules.index')
            ->with('create_success', 'Berhasil membuat ' . count($createdSchedules) . ' jadwal dari template.');
    }
}