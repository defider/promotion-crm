<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Region>
 */
class RegionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    private static int $region_number = 1;

    public function definition(): array
    {
        return [
            'region_number' => self::$region_number++,
            'title' => fake()->unique()->region().' '.fake()->regionSuffix(),
        ];
    }
}
