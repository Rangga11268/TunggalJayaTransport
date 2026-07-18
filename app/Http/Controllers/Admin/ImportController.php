<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Bus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ImportController extends Controller
{
    public function store(Request $request, $type)
    {
        $request->validate(['file' => 'required|file|mimes:csv,txt']);
        
        $path = $request->file('file')->getRealPath();
        $file = fopen($path, 'r');
        $header = fgetcsv($file); // Skip header or use it to map
        
        $count = 0;
        
        while (($row = fgetcsv($file)) !== false) {
            // Skip empty rows
            if (empty(array_filter($row))) continue;

            if ($type === 'users' && count($row) >= 3) {
                // Expected format: Name, Email, Phone, Password (optional)
                $email = $row[1] ?: (Str::slug($row[0]) . rand(100, 999) . '@offline.com');
                User::updateOrCreate(
                    ['email' => $email],
                    [
                        'name' => $row[0],
                        'phone' => $row[2] ?? null,
                        'password' => Hash::make($row[3] ?? Str::random(12)),
                    ]
                )->assignRole('user');
                $count++;
            } 
            elseif ($type === 'buses' && count($row) >= 4) {
                // Expected format: Name, Plate Number, Capacity, Bus Type
                Bus::updateOrCreate(
                    ['plate_number' => $row[1]],
                    [
                        'name' => $row[0],
                        'capacity' => (int) $row[2],
                        'bus_type' => $row[3],
                        'status' => 'active',
                    ]
                );
                $count++;
            }
            elseif ($type === 'drivers' && count($row) >= 2) {
                // Expected format: Name, Phone, License Number (optional)
                \App\Models\Driver::updateOrCreate(
                    ['phone' => $row[1]],
                    [
                        'name' => $row[0],
                        'license_number' => $row[2] ?? null,
                        'status' => 'active',
                    ]
                );
                $count++;
            }
            elseif ($type === 'conductors' && count($row) >= 2) {
                // Expected format: Name, Phone
                \App\Models\Conductor::updateOrCreate(
                    ['phone' => $row[1]],
                    [
                        'name' => $row[0],
                        'status' => 'active',
                    ]
                );
                $count++;
            }
        }
        
        fclose($file);
        
        return back()->with('success', "$count data $type berhasil diimpor.");
    }
}
