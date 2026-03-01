<?php

namespace Database\Factories;

use App\Models\Bus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Bus>
 */
class BusFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\App\Models\Bus>
     */
    protected $model = Bus::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => 'Bus ' . fake()->numerify('###'),
            'plate_number' => strtoupper(fake()->regexify('[A-Z]{2}[0-9]{4}[A-Z]{2}')),
            'bus_type' => fake()->randomElement(['Mewah', 'Standar', 'Ekonomi']),
            'capacity' => fake()->randomElement([32, 40, 45, 50]),
            'description' => fake()->sentence(),
            'status' => 'active',
            'year' => fake()->randomElement([2020, 2021, 2022, 2023, 2024]),
        ];
    }

    /**
     * Indicate that the bus is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'inactive',
        ]);
    }
}
