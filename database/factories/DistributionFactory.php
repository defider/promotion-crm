<?php

namespace Database\Factories;

use App\Models\Building;
use App\Models\Leaflet;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Distribution>
 */
class DistributionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::all()->random()->id,
            'building_id' => Building::all()->random()->id,
            'leaflet_id' => Leaflet::all()->random()->id,
            'began_at' => fake()->dateTimeBetween('+1 hour', '+3 hours'),
            'ended_at' => fake()->dateTimeBetween('+3 hours', '+5 hours'),
        ];
    }
}
