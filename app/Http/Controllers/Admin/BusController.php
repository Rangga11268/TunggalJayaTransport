<?php

namespace App\Http\Controllers\Admin;

use App\Models\Bus;
use App\Models\Driver;
use App\Models\Conductor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Inertia\Inertia;

class BusController extends Controller
{
    
    public function index(Request $request)
    {
        $buses = Bus::with(['drivers', 'conductors'])
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('plate_number', 'like', "%{$search}%")
                      ->orWhere('bus_type', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        // Append image_url to each bus for easier access in frontend
        $buses->getCollection()->transform(function ($bus) {
            $bus->append('image_url');
            return $bus;
        });

        // Return JSON for axios calls (search/pagination)
        if ($request->wantsJson()) {
            return response()->json([
                'buses' => $buses
            ]);
        }

        return Inertia::render('Admin/Buses/Index', [
            'buses' => $buses,
            'filters' => $request->only(['search'])
        ]);
    }

    
    public function create()
    {
        $drivers = Driver::where('status', 'active')->get();
        $conductors = Conductor::where('status', 'active')->get();
        $assignedDrivers = $this->getAssignedDrivers();
        $assignedConductors = $this->getAssignedConductors();
        
        return Inertia::render('Admin/Buses/Create', compact('drivers', 'conductors', 'assignedDrivers', 'assignedConductors'));
    }

    
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'plate_number' => 'required|string|unique:buses',
            'bus_type' => 'required|string|max:255',
            'bus_category' => 'required|in:akap,pariwisata',
            'capacity' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'year' => 'nullable|integer|min:1900|max:' . (date('Y') + 2),
            'status' => 'required|in:active,maintenance,inactive',
            'drivers' => 'nullable|array',
            'drivers.*' => 'exists:drivers,id',
            'conductors' => 'nullable|array',
            'conductors.*' => 'exists:conductors,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        // Check if any selected drivers or conductors are already assigned
        $assignedDrivers = $this->getAssignedDrivers();
        $assignedConductors = $this->getAssignedConductors();

        if ($request->has('drivers')) {
            $selectedDrivers = $request->input('drivers');
            $conflictingDrivers = array_intersect($selectedDrivers, $assignedDrivers);
            if (!empty($conflictingDrivers)) {
                $driverNames = Driver::whereIn('id', $conflictingDrivers)->pluck('name')->toArray();
                return redirect()->back()->withErrors([
                    'drivers' => 'Driver berikut sudah ditugaskan ke bus lain: ' . implode(', ', $driverNames)
                ])->withInput();
            }
        }

        if ($request->has('conductors')) {
            $selectedConductors = $request->input('conductors');
            $conflictingConductors = array_intersect($selectedConductors, $assignedConductors);
            if (!empty($conflictingConductors)) {
                $conductorNames = Conductor::whereIn('id', $conflictingConductors)->pluck('name')->toArray();
                return redirect()->back()->withErrors([
                    'conductors' => 'Kondektur berikut sudah ditugaskan ke bus lain: ' . implode(', ', $conductorNames)
                ])->withInput();
            }
        }

        try {
            $bus = Bus::create($request->except('image', 'drivers', 'conductors'));

            if ($request->has('drivers')) {
                $bus->drivers()->sync($request->input('drivers'));
            }

            if ($request->has('conductors')) {
                $bus->conductors()->sync($request->input('conductors'));
            }

            if ($request->hasFile('image')) {
                $bus->addMediaFromRequest('image')->toMediaCollection('buses');
            }

            return redirect()->route('admin.buses.index')->with('success', 'Bus berhasil dibuat.');
        } catch (\Exception $e) {
            // Log error for debugging
            Log::error('Error creating bus: ' . $e->getMessage());
            
            return redirect()->back()->with('error', 'Terjadi kesalahan saat membuat bus: ' . $e->getMessage())->withInput();
        }
    }

    
    public function show(string $id)
    {
        // For now, redirect to edit as we don't have a dedicated show page yet
        return redirect()->route('admin.buses.edit', $id);
    }

    
    public function edit(string $id)
    {
        $bus = Bus::with(['drivers', 'conductors'])->findOrFail($id);
        $bus->append('image_url');
        
        $drivers = Driver::where('status', 'active')->get();
        $conductors = Conductor::where('status', 'active')->get();
        $assignedDrivers = $this->getAssignedDrivers($bus->id);
        $assignedConductors = $this->getAssignedConductors($bus->id);
        
        return Inertia::render('Admin/Buses/Edit', [
            'bus' => $bus,
            'drivers' => $drivers,
            'conductors' => $conductors,
            'assignedDrivers' => $assignedDrivers,
            'assignedConductors' => $assignedConductors
        ]);
    }

    
    public function update(Request $request, string $id)
    {
        $bus = Bus::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'plate_number' => 'required|string|unique:buses,plate_number,' . $bus->id,
            'bus_type' => 'required|string|max:255',
            'bus_category' => 'required|in:akap,pariwisata',
            'capacity' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'year' => 'nullable|integer|min:1900|max:' . (date('Y') + 2),
            'status' => 'required|in:active,maintenance,inactive',
            'drivers' => 'nullable|array',
            'drivers.*' => 'exists:drivers,id',
            'conductors' => 'nullable|array',
            'conductors.*' => 'exists:conductors,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $assignedDrivers = $this->getAssignedDrivers($bus->id);
        $assignedConductors = $this->getAssignedConductors($bus->id);

        if ($request->has('drivers')) {
            $selectedDrivers = $request->input('drivers');
            $conflictingDrivers = array_intersect($selectedDrivers, $assignedDrivers);
            if (!empty($conflictingDrivers)) {
                $driverNames = Driver::whereIn('id', $conflictingDrivers)->pluck('name')->toArray();
                return redirect()->back()->withErrors([
                    'drivers' => 'Driver berikut sudah ditugaskan ke bus lain: ' . implode(', ', $driverNames)
                ])->withInput();
            }
        }

        if ($request->has('conductors')) {
            $selectedConductors = $request->input('conductors');
            $conflictingConductors = array_intersect($selectedConductors, $assignedConductors);
            if (!empty($conflictingConductors)) {
                $conductorNames = Conductor::whereIn('id', $conflictingConductors)->pluck('name')->toArray();
                return redirect()->back()->withErrors([
                    'conductors' => 'Kondektur berikut sudah ditugaskan ke bus lain: ' . implode(', ', $conductorNames)
                ])->withInput();
            }
        }

        try {
            $bus->update($request->except('image', 'drivers', 'conductors'));

            if ($request->has('drivers')) {
                $bus->drivers()->sync($request->input('drivers'));
            } else {
                $bus->drivers()->detach();
            }

            if ($request->has('conductors')) {
                $bus->conductors()->sync($request->input('conductors'));
            } else {
                $bus->conductors()->detach();
            }

            if ($request->hasFile('image')) {
                $bus->clearMediaCollection('buses');
                $bus->addMediaFromRequest('image')->toMediaCollection('buses');
            }

            return redirect()->route('admin.buses.index')->with('success', 'Bus berhasil diperbarui.');
        } catch (\Exception $e) {
             Log::error('Error updating bus: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui bus: ' . $e->getMessage())->withInput();
        }
    }

    
    public function destroy(string $id)
    {
        $bus = Bus::findOrFail($id);
        $bus->delete();

        return redirect()->route('admin.buses.index')->with('success', 'Bus berhasil dihapus.');
    }

    
    private function getAssignedDrivers($excludeBusId = null)
    {
        $query = Bus::with('drivers');

        if ($excludeBusId) {
            $query->where('id', '!=', $excludeBusId);
        }

        $buses = $query->get();
        $assignedDriverIds = [];

        foreach ($buses as $bus) {
            foreach ($bus->drivers as $driver) {
                $assignedDriverIds[] = $driver->id;
            }
        }

        return array_unique($assignedDriverIds);
    }

    
    private function getAssignedConductors($excludeBusId = null)
    {
        $query = Bus::with('conductors');

        if ($excludeBusId) {
            $query->where('id', '!=', $excludeBusId);
        }

        $buses = $query->get();
        $assignedConductorIds = [];

        foreach ($buses as $bus) {
            foreach ($bus->conductors as $conductor) {
                $assignedConductorIds[] = $conductor->id;
            }
        }

        return array_unique($assignedConductorIds);
    }
}
