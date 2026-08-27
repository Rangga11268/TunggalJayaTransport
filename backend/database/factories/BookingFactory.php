<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Booking>
 */
class BookingFactory extends Factory
{
    protected $model = Booking::class;

    public function definition(): array
    {
        $numberOfSeats = random_int(1, 5);

        return [
            'user_id' => User::factory(),
            'schedule_id' => Schedule::factory(),
            'booking_date' => now()->addDays(random_int(1, 30))->toDateString(),
            'booking_code' => 'BK' . strtoupper(uniqid()),
            'passenger_name' => fake()->name(),
            'passenger_phone' => fake()->phoneNumber(),
            'passenger_email' => fake()->safeEmail(),
            'number_of_seats' => $numberOfSeats,
            'seat_numbers' => implode(',', array_slice(range(1, 40), 0, $numberOfSeats)),
            'total_price' => fake()->randomFloat(2, 100000, 500000),
            'payment_status' => fake()->randomElement(['pending', 'paid', 'failed', 'refunded']),
            'booking_status' => fake()->randomElement(['pending', 'confirmed', 'cancelled', 'completed']),
            'payment_started_at' => now(),
            'midtrans_transaction_id' => null,
            'promo_code_id' => null,
            'discount_amount' => 0,
            'original_total_price' => null,
        ];
    }
}
