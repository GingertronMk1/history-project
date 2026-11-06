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
        $startYear = fake()->unique()->numberBetween(0, 98);

        return [
            'name' => sprintf('%02d_%02d', $startYear, $startYear + 1),
            'description' => fake()->paragraph(),
        ];
    }
}
