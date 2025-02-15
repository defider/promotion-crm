<?php

namespace Database\Factories;

use App\Models\Region;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Building>
 */
class BuildingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'region_id' => Region::all()->random()->code,
            'postcode' => fake()->postcode(),
            'locality' => fake()->cityPrefix().' '.fake()->city(),
            'street' => fake()->streetPrefix().' '.fake()->street(),
            'number' => fake()->buildingNumber(),
        ];
    }
}
