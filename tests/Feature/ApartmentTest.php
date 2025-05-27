<?php

namespace Tests\Feature;

use App\Models\Apartment;
use App\Models\Building;
use App\Models\Distribution;
use App\Models\Leaflet;
use App\Models\Reaction;
use App\Models\Region;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApartmentTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_apartment_list()
    {
        $auth = $this->actingAsJwtUser();

        $region = Region::factory()->create();

        $building = Building::factory()->for($region)->create();

        $reaction = Reaction::factory()->create([
            'number' => 1,
            'title' => 'Взяли листовку',
        ]);

        Apartment::factory(3)->create([
            'building_id' => $building->id,
            'reaction_id' => $reaction->number,
        ]);

        $response = $this->withJwtToken($auth->token)
            ->getJson('/api/apartments');

        $response->assertStatus(200);
        $this->assertCount(3, $response->json('data'));
    }

    public function test_guest_cannot_view_apartment_list()
    {
        $response = $this->getJson('/api/apartments');

        $response->assertStatus(401);
    }

    public function test_user_can_view_single_apartment()
    {
        $auth = $this->actingAsJwtUser();

        $region = Region::factory()->create();

        $building = Building::factory()->for($region)->create();

        $reaction = Reaction::factory()->create([
            'number' => 1,
            'title' => 'Взяли листовку',
        ]);

        $apartment = Apartment::factory()->create([
            'building_id' => $building->id,
            'reaction_id' => $reaction->number,
        ]);

        $response = $this->withJwtToken($auth->token)
            ->getJson("/api/apartments/$apartment->id");

        $response->assertStatus(200);
        $response->assertJsonFragment(['id' => $apartment->id]);
    }

    public function test_user_gets_404_if_apartment_not_found()
    {
        $auth = $this->actingAsJwtUser();

        $response = $this->withJwtToken($auth->token)
            ->getJson('/api/apartments/404');

        $response->assertStatus(404);
    }

    public function test_user_can_create_apartment()
    {
        $auth = $this->actingAsJwtUser();

        $region = Region::factory()->create();

        $building = Building::factory()->for($region)->create();

        $reaction = Reaction::factory()->create([
            'number' => 1,
            'title' => 'Взяли листовку',
        ]);

        $apartment = [
            'building_id' => $building->id,
            'reaction_id' => $reaction->number,
        ];

        $response = $this->withJwtToken($auth->token)
            ->postJson('/api/apartments', $apartment);

        $response->assertStatus(201);
        $this->assertDatabaseHas('apartments', $apartment);
    }

    public function test_user_cannot_create_apartment_with_invalid_data()
    {
        $auth = $this->actingAsJwtUser();

        $response = $this->withJwtToken($auth->token)
            ->postJson('/api/apartments', [
                'building_id' => 422,
                'number' => str_repeat('a', 256),
            ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['building_id', 'number']);
    }

    public function test_user_can_update_apartment()
    {
        $auth = $this->actingAsJwtUser();

        $region = Region::factory()->create();

        $building = Building::factory()->for($region)->create();

        $reaction = Reaction::factory()->create([
            'number' => 1,
            'title' => 'Взяли листовку',
        ]);

        $apartment = Apartment::factory()->create([
            'building_id' => $building->id,
            'reaction_id' => $reaction->number,
        ]);

        $response = $this->withJwtToken($auth->token)
            ->putJson("/api/apartments/$apartment->id", [
                'number' => '101',
            ]);

        $response->assertStatus(200);
        $response->assertJsonFragment(['number' => '101']);
    }

    public function test_user_cannot_update_apartment_with_invalid_data()
    {
        $auth = $this->actingAsJwtUser();

        $region = Region::factory()->create();

        $building = Building::factory()->for($region)->create();

        $reaction = Reaction::factory()->create([
            'number' => 1,
            'title' => 'Взяли листовку',
        ]);

        $apartment = Apartment::factory()->create([
            'building_id' => $building->id,
            'reaction_id' => $reaction->number,
        ]);

        $response = $this->withJwtToken($auth->token)
            ->putJson("/api/apartments/$apartment->id", [
                'number' => str_repeat('x', 256),
            ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['number']);
    }

    public function test_user_can_delete_apartment()
    {
        $auth = $this->actingAsJwtUser();

        $region = Region::factory()->create();

        $building = Building::factory()->for($region)->create();

        $reaction = Reaction::factory()->create([
            'number' => 1,
            'title' => 'Взяли листовку',
        ]);

        $apartment = Apartment::factory()->create([
            'building_id' => $building->id,
            'reaction_id' => $reaction->number,
        ]);

        $response = $this->withJwtToken($auth->token)
            ->deleteJson("/api/apartments/$apartment->id");

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Apartment has been removed']);
        $this->assertDatabaseMissing('apartments', ['id' => $apartment->id]);
    }

    public function test_user_gets_404_when_deleting_nonexistent_apartment()
    {
        $auth = $this->actingAsJwtUser();

        $response = $this->withJwtToken($auth->token)
            ->deleteJson('/api/apartments/404');

        $response->assertStatus(404);
    }

    public function test_user_can_react_to_apartment_with_active_distribution()
    {
        $auth = $this->actingAsJwtUser();

        $region = Region::factory()->create();

        $building = Building::factory()->for($region)->create();

        $reaction = Reaction::factory()->create([
            'number' => 1,
            'title' => 'Взяли листовку',
        ]);

        $apartment = Apartment::factory()->create([
            'building_id' => $building->id,
            'reaction_id' => $reaction->number,
        ]);

        $leaflet = Leaflet::factory()->create([
            'title' => 'Пекарня',
        ]);

        Distribution::factory()->create([
            'building_id' => $building->id,
            'leaflet_id' => $leaflet->id,
            'began_at' => now(),
            'ended_at' => null,
        ]);

        $response = $this->withJwtToken($auth->token)
            ->patchJson("/api/apartments/{$apartment->id}/react", [
                'reaction_id' => $reaction->number,
            ]);

        $response->assertStatus(200);
        $response->assertJsonFragment(['message' => 'Reaction updated']);
        $this->assertDatabaseHas('apartments', [
            'id' => $apartment->id,
            'reaction_id' => $reaction->number,
        ]);
    }

    public function test_user_cannot_react_if_apartment_not_in_active_distribution()
    {
        $auth = $this->actingAsJwtUser();

        $region = Region::factory()->create();

        $reaction = Reaction::factory()->create([
            'number' => 1,
            'title' => 'Взяли листовку',
        ]);

        $building = Building::factory()->for($region)->create();

        $otherBuilding = Building::factory()->for($region)->create();

        $apartment = Apartment::factory()->create([
            'building_id' => $otherBuilding->id,
            'reaction_id' => $reaction->number,
        ]);

        $leaflet = Leaflet::factory()->create([
            'title' => 'Пекарня',
        ]);

        Distribution::factory()->create([
            'building_id' => $building->id,
            'leaflet_id' => $leaflet->id,
            'began_at' => now(),
            'ended_at' => null,
        ]);

        $response = $this->withJwtToken($auth->token)
            ->patchJson("/api/apartments/$apartment->id/react", [
                'reaction_id' => $reaction->number,
            ]);

        $response->assertStatus(403);
        $response->assertJson([
            'message' => "You can't react to this apartment. It doesn't belong to your current distribution",
        ]);
    }
}
