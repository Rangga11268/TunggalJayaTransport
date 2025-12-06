<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Route as BusRoute;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RouteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $routes = BusRoute::when($request->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('origin', 'like', "%{$search}%")
                      ->orWhere('destination', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Admin/Routes/Index', [
            'routes' => $routes,
            'filters' => $request->only(['search'])
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Admin/Routes/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'origin' => 'required|string|max:255',
            'destination' => 'required|string|max:255',
            'distance' => 'nullable|numeric|min:0',
            'duration' => 'nullable|integer|min:0',
            'description' => 'nullable|string',
        ]);

        BusRoute::create($request->all());

        return redirect()->route('admin.routes.index')->with('success', 'Rute berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // For now, redirect to edit as we don't have a dedicated show page yet
        return redirect()->route('admin.routes.edit', $id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $busRoute = BusRoute::findOrFail($id);
        return Inertia::render('Admin/Routes/Edit', compact('busRoute'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $route = BusRoute::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'origin' => 'required|string|max:255',
            'destination' => 'required|string|max:255',
            'distance' => 'nullable|numeric|min:0',
            'duration' => 'nullable|integer|min:0',
            'description' => 'nullable|string',
        ]);

        $route->update($request->all());

        return redirect()->route('admin.routes.index')->with('success', 'Rute berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $route = BusRoute::findOrFail($id);
        $route->delete();

        return redirect()->route('admin.routes.index')->with('success', 'Rute berhasil dihapus.');
    }
}
