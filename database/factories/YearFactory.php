<?php

namespace Database\Factories;

use App\Models\Year;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Year>
 */
class YearFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startYear = fake()->unique()->numberBetween(1950, 2048);

        return [
            'name' => sprintf('%02d_%02d', $startYear % 100, ($startYear + 1) % 100),
            'year_start' => $startYear,
            'description' => fake()->paragraph(),
        ];
    }
}
