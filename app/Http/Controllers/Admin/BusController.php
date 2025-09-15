<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bus;
use App\Models\Driver;
use App\Models\Conductor;
use Illuminate\Http\Request;

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
        return view('admin.buses.create', compact('drivers', 'conductors'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'plate_number' => 'required|string|unique:buses',
            'bus_type' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'status' => 'required|in:active,maintenance,inactive',
            'drivers' => 'nullable|array',
            'drivers.*' => 'exists:drivers,id',
            'conductors' => 'nullable|array',
            'conductors.*' => 'exists:conductors,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

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
        return view('admin.buses.edit', compact('bus', 'drivers', 'conductors'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $bus = Bus::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'plate_number' => 'required|string|unique:buses,plate_number,' . $bus->id,
            'bus_type' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'status' => 'required|in:active,maintenance,inactive',
            'drivers' => 'nullable|array',
            'drivers.*' => 'exists:drivers,id',
            'conductors' => 'nullable|array',
            'conductors.*' => 'exists:conductors,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

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
}
