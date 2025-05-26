<?php

namespace Tests\Feature;

use App\Models\Region;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegionTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_region_list()
    {
        $auth = $this->actingAsJwtUser();

        Region::factory(3)->create();

        $response = $this->withJwtToken($auth->token)
            ->getJson('/api/regions');

        $response->assertStatus(200);
        $this->assertCount(3, $response->json('data'));
    }

    public function test_guest_cannot_view_region_list()
    {
        Region::factory(3)->create();

        $response = $this->getJson('/api/regions');
        $response->assertStatus(401);
    }

    public function test_user_can_view_single_region()
    {
        $auth = $this->actingAsJwtUser();

        $region = Region::factory()->create();

        $response = $this->withJwtToken($auth->token)
            ->getJson("/api/regions/$region->id");

        $response->assertStatus(200);
        $response->assertJsonFragment(['id' => $region->id]);
    }

    public function test_user_gets_404_if_region_not_found()
    {
        $auth = $this->actingAsJwtUser();

        $response = $this->withJwtToken($auth->token)
            ->getJson('/api/regions/404');

        $response->assertStatus(404);
    }

    public function test_user_can_create_region()
    {
        $auth = $this->actingAsJwtUser();

        $region = [
            'code' => '77',
            'title' => 'город Москва',
        ];

        $response = $this->withJwtToken($auth->token)
            ->postJson('/api/regions', $region);

        $response->assertStatus(201);
        $this->assertDatabaseHas('regions', $region);
    }

    public function test_user_cannot_create_duplicate_region()
    {
        $auth = $this->actingAsJwtUser();

        Region::factory()->create([
            'code' => '77',
            'title' => 'город Москва',
        ]);

        $response = $this->withJwtToken($auth->token)
            ->postJson('/api/regions', [
                'code' => '77',
                'title' => 'город Москва',
            ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['code', 'title']);
    }

    public function test_user_can_update_region()
    {
        $auth = $this->actingAsJwtUser();

        $region = Region::factory()->create([
            'code' => '77',
            'title' => 'город Москва',
        ]);

        $response = $this->withJwtToken($auth->token)
            ->putJson("/api/regions/$region->id", [
                'code' => '78',
                'title' => 'город Санкт-Петербург',
            ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('regions', [
            'id' => $region->id,
            'title' => 'город Санкт-Петербург',
        ]);
    }

    public function test_user_cannot_update_region_with_duplicate_title()
    {
        $auth = $this->actingAsJwtUser();

        Region::factory()->create(['title' => 'город Москва']);

        $region = Region::factory()->create();

        $response = $this->withJwtToken($auth->token)
            ->putJson("/api/regions/{$region->id}", [
                'title' => 'город Москва',
                'code' => $region->code,
            ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['title']);
    }

    public function test_user_can_delete_region()
    {
        $auth = $this->actingAsJwtUser();

        $region = Region::factory()->create();

        $response = $this->withJwtToken($auth->token)
            ->deleteJson("/api/regions/$region->id");

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Region has been removed']);
        $this->assertDatabaseMissing('regions', ['id' => $region->id]);
    }

    public function test_user_gets_404_when_deleting_nonexistent_region()
    {
        $auth = $this->actingAsJwtUser();

        $response = $this->withJwtToken($auth->token)
            ->deleteJson('/api/regions/404');

        $response->assertStatus(404);
    }
}
