<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;

class DriverController extends Controller
{
    
    public function index(Request $request)
    {
        $drivers = Driver::when($request->search, function ($query, $search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('employee_id', 'like', "%{$search}%")
                  ->orWhere('license_number', 'like', "%{$search}%");
            });
        })
        ->latest()
        ->paginate(10)
        ->withQueryString();
        
        if ($request->wantsJson()) {
            return response()->json([
                'drivers' => $drivers
            ]);
        }
        
        return Inertia::render('Admin/Drivers/Index', [
            'drivers' => $drivers,
            'filters' => $request->only(['search']),
        ]);
    }

    
    public function create()
    {
        return Inertia::render('Admin/Drivers/Create');
    }

    
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'employee_id' => 'required|string|unique:drivers',
                'license_number' => 'required|string|unique:drivers',
                'phone' => 'required|string|max:20',
                'email' => 'nullable|email|max:255',
                'address' => 'nullable|string',
                'status' => 'required|in:active,inactive',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            ], [
                'employee_id.unique' => 'ID Karyawan sudah digunakan.',
                'license_number.unique' => 'Nomor SIM sudah digunakan.'
            ]);

            $driver = Driver::create($request->except('image'));

            if ($request->hasFile('image')) {
                $driver->addMediaFromRequest('image')->toMediaCollection('drivers');
            }

            return redirect()->route('admin.drivers.index')->with('success', 'Driver berhasil dibuat.');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            \Log::error('Error creating driver:', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data driver.')->withInput();
        }
    }

    
    public function show(string $id)
    {
        // Redirect to edit as we likely don't need a standalone show page currently
        return redirect()->route('admin.drivers.edit', $id);
    }

    
    public function edit(string $id)
    {
        $driver = Driver::findOrFail($id);
        return Inertia::render('Admin/Drivers/Edit', [
            'driver' => $driver
        ]);
    }

    
    public function update(Request $request, string $id)
    {
        $driver = Driver::findOrFail($id);

        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'employee_id' => 'required|string|unique:drivers,employee_id,' . $driver->id,
                'license_number' => 'required|string|unique:drivers,license_number,' . $driver->id,
                'phone' => 'required|string|max:20',
                'email' => 'nullable|email|max:255',
                'address' => 'nullable|string',
                'status' => 'required|in:active,inactive',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            ], [
                'employee_id.unique' => 'ID Karyawan sudah digunakan.',
                'license_number.unique' => 'Nomor SIM sudah digunakan.'
            ]);

            $driver->update($request->except('image'));

            if ($request->hasFile('image')) {
                $driver->clearMediaCollection('drivers');
                $driver->addMediaFromRequest('image')->toMediaCollection('drivers');
            }

            return redirect()->route('admin.drivers.index')->with('success', 'Driver berhasil diperbarui.');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
             \Log::error('Error updating driver:', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui data driver.')->withInput();
        }
    }

    
    public function destroy(string $id)
    {
        try {
            $driver = Driver::findOrFail($id);
            $driver->delete();
            return redirect()->route('admin.drivers.index')->with('success', 'Driver berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus driver.');
        }
    }

    public function bulkDestroy(Request $request)
    {
        $ids = $request->input('ids', []);
        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'Tidak ada data dipilih.'], 400);
        }
        Driver::whereIn('id', $ids)->delete();
        return response()->json(['success' => true, 'message' => count($ids) . ' data berhasil dihapus.']);
    }
}
