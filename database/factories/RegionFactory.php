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

    private static int $code = 1;

    public function definition(): array
    {
        return [
            'code' => self::$code++,
            'title' => fake()->unique()->region().' '.fake()->regionSuffix(),
        ];
    }
}
