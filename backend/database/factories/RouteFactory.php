<?php

namespace Database\Factories;

use App\Models\Route;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Route>
 */
class RouteFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\App\Models\Route>
     */
    protected $model = Route::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $origin = fake()->city();
        $destination = fake()->city();

        return [
            'name' => "{$origin} - {$destination}",
            'origin' => $origin,
            'destination' => $destination,
            'origin_lat' => fake()->latitude(-6, -7),
            'origin_lng' => fake()->longitude(106, 107),
            'destination_lat' => fake()->latitude(-6, -7),
            'destination_lng' => fake()->longitude(106, 107),
            'waypoints' => null,
            'distance' => fake()->randomFloat(2, 10, 500),
            'duration' => fake()->randomFloat(2, 1, 24),
            'description' => fake()->sentence(),
        ];
    }
}
