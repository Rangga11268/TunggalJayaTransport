<?php

namespace Database\Factories;

use App\Models\Bus;
use App\Models\Route;
use App\Models\Schedule;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Schedule>
 */
class ScheduleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\App\Models\Schedule>
     */
    protected $model = Schedule::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'bus_id' => Bus::factory(),
            'route_id' => Route::factory(),
            'departure_time' => now()->addHours(random_int(1, 24))->format('Y-m-d H:i:s'),
            'arrival_time' => now()->addHours(random_int(25, 48))->format('Y-m-d H:i:s'),
            'price' => fake()->randomFloat(2, 50000, 200000),
            'status' => 'active',
            'is_daily' => false,
            'days_of_week' => null,
        ];
    }

    /**
     * Indicate that the schedule is daily recurring.
     */
    public function daily(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_daily' => true,
            'days_of_week' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'],
        ]);
    }

    /**
     * Indicate that the schedule operates on specific days.
     */
    public function onDays(array $days): static
    {
        return $this->state(fn(array $attributes) => [
            'is_daily' => true,
            'days_of_week' => $days,
        ]);
    }

    /**
     * Indicate that the schedule is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'inactive',
        ]);
    }
}
