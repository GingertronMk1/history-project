<?php

namespace Database\Factories;

use App\Models\Show;
use App\Models\Year;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Show>
 */
class ShowFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(3),
            'year_id' => Year::factory(),
            'description' => fake()->paragraph(),
        ];
    }
}
