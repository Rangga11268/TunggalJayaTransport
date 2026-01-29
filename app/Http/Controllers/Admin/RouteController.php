<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Route as BusRoute;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RouteController extends Controller
{
    
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

    
    public function create()
    {
        return Inertia::render('Admin/Routes/Create');
    }

    
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

    
    public function show(string $id)
    {
        // For now, redirect to edit as we don't have a dedicated show page yet
        return redirect()->route('admin.routes.edit', $id);
    }

    
    public function edit(string $id)
    {
        $busRoute = BusRoute::findOrFail($id);
        return Inertia::render('Admin/Routes/Edit', compact('busRoute'));
    }

    
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

    
    public function destroy(string $id)
    {
        $route = BusRoute::findOrFail($id);
        $route->delete();

        return redirect()->route('admin.routes.index')->with('success', 'Rute berhasil dihapus.');
    }
}
