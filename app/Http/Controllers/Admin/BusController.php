<?php

namespace App\Http\Controllers\Admin;

use App\Models\Bus;
use App\Models\Driver;
use App\Models\Conductor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class BusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $buses = Bus::with(['drivers', 'conductors'])->latest()->paginate(10);
        return view('admin.buses.index', compact('buses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $drivers = Driver::where('status', 'active')->get();
        $conductors = Conductor::where('status', 'active')->get();
        $assignedDrivers = $this->getAssignedDrivers();
        $assignedConductors = $this->getAssignedConductors();
        return view('admin.buses.create', compact('drivers', 'conductors', 'assignedDrivers', 'assignedConductors'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Add debugging for image upload
        if ($request->hasFile('image')) {
            Log::info('Image upload info:', [
                'file' => $request->file('image')->getClientOriginalName(),
                'extension' => $request->file('image')->getClientOriginalExtension(),
                'mimeType' => $request->file('image')->getMimeType(),
                'size' => $request->file('image')->getSize(),
                'isValid' => $request->file('image')->isValid()
            ]);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'plate_number' => 'required|string|unique:buses',
            'bus_type' => 'required|string|max:255',
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
                    'drivers' => 'The following drivers are already assigned to another bus: ' . implode(', ', $driverNames)
                ])->withInput();
            }
        }

        if ($request->has('conductors')) {
            $selectedConductors = $request->input('conductors');
            $conflictingConductors = array_intersect($selectedConductors, $assignedConductors);
            if (!empty($conflictingConductors)) {
                $conductorNames = Conductor::whereIn('id', $conflictingConductors)->pluck('name')->toArray();
                return redirect()->back()->withErrors([
                    'conductors' => 'The following conductors are already assigned to another bus: ' . implode(', ', $conductorNames)
                ])->withInput();
            }
        }

        try {
            $bus = Bus::create($request->except('image', 'drivers', 'conductors'));

            // Sync drivers
            if ($request->has('drivers')) {
                $bus->drivers()->sync($request->input('drivers'));
            }

            // Sync conductors
            if ($request->has('conductors')) {
                $bus->conductors()->sync($request->input('conductors'));
            }

            if ($request->hasFile('image')) {
                $bus->addMediaFromRequest('image')->toMediaCollection('buses');
            }

            return redirect()->route('admin.buses.index')->with('create_success', 'Bus berhasil dibuat.');
        } catch (\Exception $e) {
            // Handle duplicate entry error
            if (strpos($e->getMessage(), 'unique_driver_per_bus') !== false) {
                return redirect()->back()->withErrors([
                    'drivers' => 'One or more drivers are already assigned to another bus.'
                ])->withInput();
            }

            if (strpos($e->getMessage(), 'unique_conductor_per_bus') !== false) {
                return redirect()->back()->withErrors([
                    'conductors' => 'One or more conductors are already assigned to another bus.'
                ])->withInput();
            }

            // Re-throw the exception if it's not related to our constraints
            throw $e;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $bus = Bus::with(['drivers', 'conductors'])->findOrFail($id);
        return view('admin.buses.show', compact('bus'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $bus = Bus::with(['drivers', 'conductors'])->findOrFail($id);
        $drivers = Driver::where('status', 'active')->get();
        $conductors = Conductor::where('status', 'active')->get();
        $assignedDrivers = $this->getAssignedDrivers($bus->id);
        $assignedConductors = $this->getAssignedConductors($bus->id);
        return view('admin.buses.edit', compact('bus', 'drivers', 'conductors', 'assignedDrivers', 'assignedConductors'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $bus = Bus::findOrFail($id);

        // Add debugging for image upload
        if ($request->hasFile('image')) {
            Log::info('Image upload info:', [
                'file' => $request->file('image')->getClientOriginalName(),
                'extension' => $request->file('image')->getClientOriginalExtension(),
                'mimeType' => $request->file('image')->getMimeType(),
                'size' => $request->file('image')->getSize(),
                'isValid' => $request->file('image')->isValid()
            ]);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'plate_number' => 'required|string|unique:buses,plate_number,' . $bus->id,
            'bus_type' => 'required|string|max:255',
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
        $assignedDrivers = $this->getAssignedDrivers($bus->id);
        $assignedConductors = $this->getAssignedConductors($bus->id);

        if ($request->has('drivers')) {
            $selectedDrivers = $request->input('drivers');
            $conflictingDrivers = array_intersect($selectedDrivers, $assignedDrivers);
            if (!empty($conflictingDrivers)) {
                $driverNames = Driver::whereIn('id', $conflictingDrivers)->pluck('name')->toArray();
                return redirect()->back()->withErrors([
                    'drivers' => 'The following drivers are already assigned to another bus: ' . implode(', ', $driverNames)
                ])->withInput();
            }
        }

        if ($request->has('conductors')) {
            $selectedConductors = $request->input('conductors');
            $conflictingConductors = array_intersect($selectedConductors, $assignedConductors);
            if (!empty($conflictingConductors)) {
                $conductorNames = Conductor::whereIn('id', $conflictingConductors)->pluck('name')->toArray();
                return redirect()->back()->withErrors([
                    'conductors' => 'The following conductors are already assigned to another bus: ' . implode(', ', $conductorNames)
                ])->withInput();
            }
        }

        try {
            $bus->update($request->except('image', 'drivers', 'conductors'));

            // Sync drivers
            if ($request->has('drivers')) {
                $bus->drivers()->sync($request->input('drivers'));
            } else {
                $bus->drivers()->detach();
            }

            // Sync conductors
            if ($request->has('conductors')) {
                $bus->conductors()->sync($request->input('conductors'));
            } else {
                $bus->conductors()->detach();
            }

            if ($request->hasFile('image')) {
                // Remove old image if exists
                $bus->clearMediaCollection('buses');
                // Add new image
                $bus->addMediaFromRequest('image')->toMediaCollection('buses');
            }

            return redirect()->route('admin.buses.index')->with('update_success', 'Bus berhasil diperbarui.');
        } catch (\Exception $e) {
            // Handle duplicate entry error
            if (strpos($e->getMessage(), 'unique_driver_per_bus') !== false) {
                return redirect()->back()->withErrors([
                    'drivers' => 'One or more drivers are already assigned to another bus.'
                ])->withInput();
            }

            if (strpos($e->getMessage(), 'unique_conductor_per_bus') !== false) {
                return redirect()->back()->withErrors([
                    'conductors' => 'One or more conductors are already assigned to another bus.'
                ])->withInput();
            }

            // Re-throw the exception if it's not related to our constraints
            throw $e;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $bus = Bus::findOrFail($id);
        $bus->delete();

        return redirect()->route('admin.buses.index')->with('delete_success', 'Bus berhasil dihapus.');
    }

    /**
     * Get list of driver IDs that are already assigned to buses
     */
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

    /**
     * Get list of conductor IDs that are already assigned to buses
     */
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
