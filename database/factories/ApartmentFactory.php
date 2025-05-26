<?php

namespace Database\Factories;

use App\Models\Building;
use App\Models\Reaction;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Apartment>
 */
class ApartmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'building_id' => Building::all()->random()->id,
            'number' => fake()->unique()->randomNumber(2),
            'reaction_id' => Reaction::all()->random()?->number,
            'reaction_time' => fake()->dateTimeBetween('+1 hour', '+5 hours'),
        ];
    }
}
