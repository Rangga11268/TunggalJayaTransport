<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SettingController extends Controller
{
    public function index()
    {
        // Get current settings
        $settings = [
            'site_name' => config('app.name'),
            'site_logo' => config('app.logo'), // Assuming this config exists or is handled elsewhere
            'contact_email' => config('app.contact_email', 'admin@tunggaljaya.com'), // Default if not set
            'contact_phone' => config('app.contact_phone', '0812-3456-7890'), // Default if not set
        ];
        
        return Inertia::render('Admin/Settings/Index', [
             'settings' => $settings
        ]);
    }
    
    public function update(Request $request)
    {
        $request->validate([
            'site_name' => 'required|string|max:255',
            'contact_email' => 'required|email|max:255',
            'contact_phone' => 'required|string|max:20',
        ]);
        
        
        return redirect()->route('admin.settings.index')->with('success', 'Pengaturan berhasil diperbarui (Simulasi).');
    }
}
