<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        // Get current settings
        $settings = [
            'site_name' => config('app.name'),
            'site_logo' => config('app.logo'),
            'contact_email' => config('app.contact_email'),
            'contact_phone' => config('app.contact_phone'),
        ];
        
        return view('admin.settings.index', compact('settings'));
    }
    
    public function update(Request $request)
    {
        $request->validate([
            'site_name' => 'required|string|max:255',
            'contact_email' => 'required|email|max:255',
            'contact_phone' => 'required|string|max:20',
        ]);
        
        // In a real application, you would save these settings to the database
        // For now, we'll just return a success message
        return redirect()->route('admin.settings.index')->with('success', 'Settings updated successfully.');
    }
}
