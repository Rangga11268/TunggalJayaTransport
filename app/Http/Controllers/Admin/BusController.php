<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bus;
use Illuminate\Http\Request;

class BusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $buses = Bus::latest()->paginate(10);
        return view('admin.buses.index', compact('buses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.buses.create');
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
        ]);

        Bus::create($request->all());

        return redirect()->route('admin.buses.index')->with('create_success', 'Bus berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $bus = Bus::findOrFail($id);
        return view('admin.buses.show', compact('bus'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $bus = Bus::findOrFail($id);
        return view('admin.buses.edit', compact('bus'));
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
        ]);

        $bus->update($request->all());

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
