<?php

namespace Tests\Feature;

use App\Models\Building;
use App\Models\Region;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BuildingTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_building_list()
    {
        $auth = $this->actingAsJwtUser();

        $region = Region::factory()->create();

        Building::factory(3)->for($region)->create();

        $response = $this->withJwtToken($auth->token)
            ->getJson('/api/buildings');

        $response->assertStatus(200);
        $this->assertCount(3, $response->json('data'));
    }

    public function test_guest_cannot_view_building_list()
    {
        $region = Region::factory()->create();

        Building::factory(3)->for($region)->create();

        $response = $this->getJson('/api/buildings');
        $response->assertStatus(401);
    }

    public function test_user_can_view_single_building()
    {
        $auth = $this->actingAsJwtUser();

        $region = Region::factory()->create();

        $building = Building::factory()->for($region)->create();

        $response = $this->withJwtToken($auth->token)
            ->getJson("/api/buildings/$building->id");

        $response->assertStatus(200);
        $response->assertJsonFragment(['id' => $building->id]);
    }

    public function test_user_gets_404_if_building_not_found()
    {
        $auth = $this->actingAsJwtUser();

        $response = $this->withJwtToken($auth->token)
            ->getJson('/api/buildings/404');

        $response->assertStatus(404);
    }

    public function test_user_can_create_building()
    {
        $auth = $this->actingAsJwtUser();

        $region = Region::factory()->create();

        $building = [
            'region_id' => $region->code,
            'postcode' => '123456',
            'locality' => 'Москва',
            'street' => 'Арбат',
            'number' => '5',
        ];

        $response = $this->withJwtToken($auth->token)
            ->postJson('/api/buildings', $building);

        $response->assertStatus(201);
        $this->assertDatabaseHas('buildings', $building);
    }

    public function test_user_cannot_create_building_with_invalid_region()
    {
        $auth = $this->actingAsJwtUser();

        $building = [
            'region_id' => 404,
            'postcode' => '123456',
            'locality' => 'Москва',
        ];

        $response = $this->withJwtToken($auth->token)
            ->postJson('/api/buildings', $building);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['region_id']);
    }

    public function test_user_can_update_building()
    {
        $auth = $this->actingAsJwtUser();

        $region = Region::factory()->create();

        $building = Building::factory()->for($region)->create();

        $response = $this->withJwtToken($auth->token)
            ->putJson("/api/buildings/$building->id", [
                'region_id' => $region->code,
                'postcode' => '654321',
                'locality' => 'город Москва',
                'street' => 'Тверская улица',
                'number' => '10',
            ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('buildings', [
            'id' => $building->id,
            'postcode' => '654321',
            'locality' => 'город Москва',
        ]);
    }

    public function test_user_cannot_update_building_with_invalid_data()
    {
        $auth = $this->actingAsJwtUser();

        $region = Region::factory()->create();

        $building = Building::factory()->for($region)->create();

        $response = $this->withJwtToken($auth->token)
            ->putJson("/api/buildings/$building->id", [
                'region_id' => null,
                'postcode' => '1234567',
                'locality' => '',
            ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['region_id', 'postcode', 'locality']);
    }

    public function test_user_can_delete_building()
    {
        $auth = $this->actingAsJwtUser();

        $region = Region::factory()->create();

        $building = Building::factory()->for($region)->create();

        $response = $this->withJwtToken($auth->token)
            ->deleteJson("/api/buildings/$building->id");

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Building has been removed']);
        $this->assertDatabaseMissing('buildings', ['id' => $building->id]);
    }

    public function test_user_gets_404_when_deleting_nonexistent_building()
    {
        $auth = $this->actingAsJwtUser();

        $response = $this->withJwtToken($auth->token)
            ->deleteJson('/api/buildings/404');

        $response->assertStatus(404);
    }
}
