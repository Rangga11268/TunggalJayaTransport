<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first user (assuming this is the admin user)
        $user = User::first();
        
        if ($user) {
            // Assign admin role to the first user
            $adminRole = Role::where('name', 'admin')->first();
            if ($adminRole) {
                $user->assignRole($adminRole);
            }
        }
        
        // Create a schedule manager user if it doesn't exist
        $scheduleManager = User::where('email', 'scheduler@example.com')->first();
        if (!$scheduleManager) {
            $scheduleManager = User::create([
                'name' => 'Schedule Manager',
                'email' => 'scheduler@example.com',
                'password' => bcrypt('password123'),
            ]);
        }
        
        // Assign schedule manager role to the schedule manager user
        $scheduleManagerRole = Role::where('name', 'schedule_manager')->first();
        if ($scheduleManagerRole) {
            $scheduleManager->assignRole($scheduleManagerRole);
        }
    }
}
