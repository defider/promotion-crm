<?php

namespace Tests;

use App\Models\Building;
use App\Models\Leaflet;
use App\Models\Reaction;
use App\Models\Region;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Tests\Support\AuthResult;

abstract class TestCase extends BaseTestCase
{
    protected function actingAsJwtUser(array $attributes = []): AuthResult
    {
        $user = User::factory()->create($attributes);

        $response = $this->postJson('/api/auth/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(200);

        $token = $response->json('access_token');

        return new AuthResult($user, $token);
    }

    protected function withJwtToken(string $token): self
    {
        return $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ]);
    }

    protected function createTestDistributionDependencies(): array
    {
        $region = Region::factory()->create([
            'title' => 'город Москва',
        ]);

        $reaction = Reaction::factory()->create([
            'title' => 'Взяли листовку',
        ]);

        $building = Building::factory()
            ->for($region)
            ->hasApartments(3, ['reaction_id' => $reaction->number])
            ->create();

        $leaflet = Leaflet::factory()->create([
            'title' => 'Бургерная',
        ]);

        return compact('region', 'reaction', 'building', 'leaflet');
    }
}
