<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create roles
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $scheduleManagerRole = Role::firstOrCreate(['name' => 'schedule_manager']);
        
        // Create permissions
        $permissions = [
            // Booking management permissions
            'view bookings',
            'create bookings',
            'edit bookings',
            'delete bookings',
            
            // Schedule management permissions
            'view schedules',
            'create schedules',
            'edit schedules',
            'delete schedules',
            
            // Route management permissions
            'view routes',
            'create routes',
            'edit routes',
            'delete routes',
            
            // Bus management permissions
            'view buses',
            'create buses',
            'edit buses',
            'delete buses',
            
            // Report permissions
            'view reports',
            
            // User management permissions
            'view users',
            'create users',
            'edit users',
            'delete users',
        ];
        
        // Create permissions if they don't exist
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
        
        // Assign all permissions to admin role
        $adminRole->syncPermissions($permissions);
        
        // Assign schedule-related permissions to schedule manager role
        $schedulePermissions = [
            'view schedules',
            'create schedules',
            'edit schedules',
            'delete schedules',
            'view routes',
            'create routes',
            'edit routes',
            'delete routes',
            'view buses',
            'create buses',
            'edit buses',
            'delete buses',
        ];
        
        $scheduleManagerRole->syncPermissions($schedulePermissions);
    }
}
