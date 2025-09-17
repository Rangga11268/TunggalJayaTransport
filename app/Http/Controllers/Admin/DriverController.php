<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;

class DriverController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $drivers = Driver::latest()->paginate(10);
        return view('admin.drivers.index', compact('drivers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.drivers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
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
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ], [
                'employee_id.unique' => 'Employee ID has already been taken.',
                'license_number.unique' => 'License Number has already been taken.'
            ]);

            $driver = Driver::create($request->except('image'));

            if ($request->hasFile('image')) {
                $driver->addMediaFromRequest('image')->toMediaCollection('drivers');
            }

            return redirect()->route('admin.drivers.index')->with('create_success', 'Driver berhasil dibuat.');
        } catch (ValidationException $e) {
            // Log validation errors for debugging
            \Log::info('Driver validation errors:', $e->errors());
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (QueryException $e) {
            // Handle database constraint violations
            $errors = [];
            
            if (strpos($e->getMessage(), 'drivers_employee_id_unique') !== false || 
                strpos($e->getMessage(), 'Duplicate entry') !== false && 
                strpos($e->getMessage(), 'employee_id') !== false) {
                $errors['employee_id'] = 'Employee ID has already been taken.';
            }
            
            if (strpos($e->getMessage(), 'drivers_license_number_unique') !== false || 
                strpos($e->getMessage(), 'Duplicate entry') !== false && 
                strpos($e->getMessage(), 'license_number') !== false) {
                $errors['license_number'] = 'License Number has already been taken.';
            }
            
            if (!empty($errors)) {
                return redirect()->back()->withErrors($errors)->withInput();
            }
            
            // Re-throw if it's not a duplicate entry error
            throw $e;
        } catch (\Exception $e) {
            // Log unexpected errors
            \Log::error('Unexpected error in DriverController@store:', ['error' => $e->getMessage()]);
            return redirect()->back()->withErrors(['error' => 'An unexpected error occurred. Please try again.'])->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $driver = Driver::findOrFail($id);
        return view('admin.drivers.show', compact('driver'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $driver = Driver::findOrFail($id);
        return view('admin.drivers.edit', compact('driver'));
    }

    /**
     * Update the specified resource in storage.
     */
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
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ], [
                'employee_id.unique' => 'Employee ID has already been taken.',
                'license_number.unique' => 'License Number has already been taken.'
            ]);

            $driver->update($request->except('image'));

            if ($request->hasFile('image')) {
                // Remove old image if exists
                $driver->clearMediaCollection('drivers');
                // Add new image
                $driver->addMediaFromRequest('image')->toMediaCollection('drivers');
            }

            return redirect()->route('admin.drivers.index')->with('update_success', 'Driver berhasil diperbarui.');
        } catch (ValidationException $e) {
            // Log validation errors for debugging
            \Log::info('Driver validation errors:', $e->errors());
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (QueryException $e) {
            // Handle database constraint violations
            $errors = [];
            
            if (strpos($e->getMessage(), 'drivers_employee_id_unique') !== false || 
                strpos($e->getMessage(), 'Duplicate entry') !== false && 
                strpos($e->getMessage(), 'employee_id') !== false) {
                $errors['employee_id'] = 'Employee ID has already been taken.';
            }
            
            if (strpos($e->getMessage(), 'drivers_license_number_unique') !== false || 
                strpos($e->getMessage(), 'Duplicate entry') !== false && 
                strpos($e->getMessage(), 'license_number') !== false) {
                $errors['license_number'] = 'License Number has already been taken.';
            }
            
            if (!empty($errors)) {
                return redirect()->back()->withErrors($errors)->withInput();
            }
            
            // Re-throw if it's not a duplicate entry error
            throw $e;
        } catch (\Exception $e) {
            // Log unexpected errors
            \Log::error('Unexpected error in DriverController@update:', ['error' => $e->getMessage()]);
            return redirect()->back()->withErrors(['error' => 'An unexpected error occurred. Please try again.'])->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $driver = Driver::findOrFail($id);
        $driver->delete();

        return redirect()->route('admin.drivers.index')->with('delete_success', 'Driver berhasil dihapus.');
    }
}
