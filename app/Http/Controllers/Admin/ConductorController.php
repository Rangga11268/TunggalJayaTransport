<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Conductor;
use Illuminate\Http\Request;

class ConductorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $conductors = Conductor::latest()->paginate(10);
        return view('admin.conductors.index', compact('conductors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.conductors.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'employee_id' => 'required|string|unique:conductors',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $conductor = Conductor::create($request->except('image'));

        if ($request->hasFile('image')) {
            $conductor->addMediaFromRequest('image')->toMediaCollection('conductors');
        }

        return redirect()->route('admin.conductors.index')->with('create_success', 'Conductor berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $conductor = Conductor::findOrFail($id);
        return view('admin.conductors.show', compact('conductor'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $conductor = Conductor::findOrFail($id);
        return view('admin.conductors.edit', compact('conductor'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $conductor = Conductor::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'employee_id' => 'required|string|unique:conductors,employee_id,' . $conductor->id,
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $conductor->update($request->except('image'));

        if ($request->hasFile('image')) {
            // Remove old image if exists
            $conductor->clearMediaCollection('conductors');
            // Add new image
            $conductor->addMediaFromRequest('image')->toMediaCollection('conductors');
        }

        return redirect()->route('admin.conductors.index')->with('update_success', 'Conductor berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $conductor = Conductor::findOrFail($id);
        $conductor->delete();

        return redirect()->route('admin.conductors.index')->with('delete_success', 'Conductor berhasil dihapus.');
    }
}
