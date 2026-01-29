<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Conductor;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Validation\ValidationException;

class ConductorController extends Controller
{
    
    public function index(Request $request)
    {
        $conductors = Conductor::when($request->search, function ($query, $search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('employee_id', 'like', "%{$search}%");
            });
        })
        ->latest()
        ->paginate(10)
        ->withQueryString();
        
        return Inertia::render('Admin/Conductors/Index', [
            'conductors' => $conductors,
            'filters' => $request->only(['search']),
        ]);
    }

    
    public function create()
    {
        return Inertia::render('Admin/Conductors/Create');
    }

    
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'employee_id' => 'required|string|unique:conductors',
                'phone' => 'required|string|max:20',
                'email' => 'nullable|email|max:255',
                'address' => 'nullable|string',
                'status' => 'required|in:active,inactive',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            ], [
                'employee_id.unique' => 'ID Karyawan sudah digunakan.',
            ]);

            $conductor = Conductor::create($request->except('image'));

            if ($request->hasFile('image')) {
                $conductor->addMediaFromRequest('image')->toMediaCollection('conductors');
            }

            return redirect()->route('admin.conductors.index')->with('success', 'Kondektur berhasil dibuat.');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
             \Log::error('Error creating conductor:', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data kondektur.')->withInput();
        }
    }

    
    public function show(string $id)
    {
        // Redirect to edit
        return redirect()->route('admin.conductors.edit', $id);
    }

    
    public function edit(string $id)
    {
        $conductor = Conductor::findOrFail($id);
        return Inertia::render('Admin/Conductors/Edit', [
            'conductor' => $conductor
        ]);
    }

    
    public function update(Request $request, string $id)
    {
        $conductor = Conductor::findOrFail($id);

        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'employee_id' => 'required|string|unique:conductors,employee_id,' . $conductor->id,
                'phone' => 'required|string|max:20',
                'email' => 'nullable|email|max:255',
                'address' => 'nullable|string',
                'status' => 'required|in:active,inactive',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            ], [
                 'employee_id.unique' => 'ID Karyawan sudah digunakan.',
            ]);

            $conductor->update($request->except('image'));

            if ($request->hasFile('image')) {
                $conductor->clearMediaCollection('conductors');
                $conductor->addMediaFromRequest('image')->toMediaCollection('conductors');
            }

            return redirect()->route('admin.conductors.index')->with('success', 'Kondektur berhasil diperbarui.');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
             \Log::error('Error updating conductor:', ['error' => $e->getMessage()]);
             return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui data kondektur.')->withInput();
        }
    }

    
    public function destroy(string $id)
    {
        try {
            $conductor = Conductor::findOrFail($id);
            $conductor->delete();
            return redirect()->route('admin.conductors.index')->with('success', 'Kondektur berhasil dihapus.');
        } catch (\Exception $e) {
             return redirect()->back()->with('error', 'Gagal menghapus kondektur.');
        }
    }
}
