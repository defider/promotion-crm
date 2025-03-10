<?php

namespace Database\Seeders;

use App\Models\Apartment;
use App\Models\Building;
use App\Models\Distribution;
use App\Models\Leaflet;
use App\Models\Reaction;
use App\Models\Region;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(2)->admin()->create();
        User::factory(8)->create();

        Region::factory(10)
            ->sequence(
                ['title' => 'Республика Адыгея (Адыгея)'],
                ['title' => 'Республика Башкортостан'],
                ['title' => 'Республика Бурятия'],
                ['title' => 'Республика Алтай'],
                ['title' => 'Республика Дагестан'],
                ['title' => 'Республика Ингушетия'],
                ['title' => 'Кабардино-Балкарская Республика'],
                ['title' => 'Республика Калмыкия'],
                ['title' => 'Карачаево-Черкесская Республика'],
                ['title' => 'Республика Карелия'],
            )
            ->create();

        Building::factory(20)->create();

        Reaction::factory(6)
            ->sequence(
                ['title' => '-'],
                ['title' => 'Не открыли дверь'],
                ['title' => 'Проявили агрессию'],
                ['title' => 'Не взяли листовку'],
                ['title' => 'Взяли листовку'],
                ['title' => 'Взяли листовку и согласны приобрести товар'],
            )
            ->create();

        Apartment::factory(100)->create();

        Leaflet::factory(5)
            ->sequence(
                ['title' => 'Бургерная'],
                ['title' => 'Кондитерская'],
                ['title' => 'Кофейня'],
                ['title' => 'Пекарня'],
                ['title' => 'Пиццерия'],
            )
            ->create();

        Distribution::factory(10)->create();
    }
}
