<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\CharterBooking;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Database\Seeder;

class BookingSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'Rangga Putra',
                'password' => bcrypt('password'),
                'phone' => '081234567890',
                'is_verified' => true,
            ]
        );

        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin Tunggal Jaya',
                'password' => bcrypt('password'),
                'phone' => '081122222353',
                'is_verified' => true,
            ]
        );

        $schedule1 = Schedule::find(1);
        $schedule2 = Schedule::find(2);

        // Seed bookings for both user and admin
        foreach ([$user, $admin] as $u) {
            if ($schedule1 && Booking::where('user_id', $u->id)->where('schedule_id', 1)->count() == 0) {
                Booking::create([
                    'user_id' => $u->id,
                    'schedule_id' => 1,
                    'booking_date' => now()->addDays(2)->toDateString(),
                    'booking_code' => 'TJ-BK' . rand(1000, 9999),
                    'passenger_name' => $u->name,
                    'passenger_phone' => $u->phone ?? '081234567890',
                    'passenger_email' => $u->email,
                    'seat_numbers' => ['1A', '1B'],
                    'number_of_seats' => 2,
                    'total_price' => 260000,
                    'payment_status' => 'paid',
                    'booking_status' => 'confirmed',
                ]);
            }

            if ($schedule2 && Booking::where('user_id', $u->id)->where('schedule_id', 2)->count() == 0) {
                Booking::create([
                    'user_id' => $u->id,
                    'schedule_id' => 2,
                    'booking_date' => now()->subDays(4)->toDateString(),
                    'booking_code' => 'TJ-BK' . rand(1000, 9999),
                    'passenger_name' => $u->name,
                    'passenger_phone' => $u->phone ?? '081234567890',
                    'passenger_email' => $u->email,
                    'seat_numbers' => ['4C'],
                    'number_of_seats' => 1,
                    'total_price' => 140000,
                    'payment_status' => 'paid',
                    'booking_status' => 'completed',
                ]);
            }

            if (CharterBooking::where('user_id', $u->id)->count() == 0) {
                CharterBooking::create([
                    'charter_code' => 'CHRT-' . strtoupper(bin2hex(random_bytes(4))),
                    'user_id' => $u->id,
                    'bus_type_requested' => 'Jetbus 5 Super High Deck Single Glass',
                    'pickup_date' => now()->addDays(12)->toDateString(),
                    'return_date' => now()->addDays(15)->toDateString(),
                    'pickup_location' => 'Pool Cirendang, Kuningan',
                    'destination' => 'Yogyakarta & Pantai Parangtritis',
                    'notes' => 'Rombongan wisata keluarga 45 orang',
                    'total_price' => 7500000,
                    'payment_status' => 'fully_paid',
                    'status' => 'confirmed',
                    'bus_count' => 1,
                    'passenger_count' => 45,
                    'customer_name' => $u->name,
                    'customer_phone' => $u->phone ?? '081234567890',
                    'customer_email' => $u->email,
                ]);
            }
        }
    }
}
