<?php

namespace Tests\Feature;

use App\Models\Leaflet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LeafletTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_leaflet_list()
    {
        $auth = $this->actingAsJwtUser();

        Leaflet::factory(5)
            ->sequence(
                ['title' => 'Бургерная'],
                ['title' => 'Кондитерская'],
                ['title' => 'Кофейня'],
                ['title' => 'Пекарня'],
                ['title' => 'Пиццерия'],
            )
            ->create();

        $response = $this->withJwtToken($auth->token)
            ->getJson('/api/leaflets');

        $response->assertStatus(200);
        $this->assertCount(5, $response->json('data'));
    }

    public function test_guest_cannot_view_leaflet_list()
    {
        Leaflet::factory(5)
            ->sequence(
                ['title' => 'Бургерная'],
                ['title' => 'Кондитерская'],
                ['title' => 'Кофейня'],
                ['title' => 'Пекарня'],
                ['title' => 'Пиццерия'],
            )
            ->create();

        $response = $this->getJson('/api/leaflets');

        $response->assertStatus(401);
    }

    public function test_user_can_view_single_leaflet()
    {
        $auth = $this->actingAsJwtUser();

        $leaflet = Leaflet::factory()->create(['title' => 'Кофейня']);

        $response = $this->withJwtToken($auth->token)
            ->getJson("/api/leaflets/{$leaflet->id}");

        $response->assertStatus(200);
        $response->assertJsonFragment(['id' => $leaflet->id]);
    }

    public function test_user_gets_404_if_leaflet_not_found()
    {
        $auth = $this->actingAsJwtUser();

        $response = $this->withJwtToken($auth->token)
            ->getJson('/api/leaflets/404');

        $response->assertStatus(404);
    }

    public function test_user_can_create_leaflet()
    {
        $auth = $this->actingAsJwtUser();

        $leaflet = ['title' => 'Кофейня'];

        $response = $this->withJwtToken($auth->token)
            ->postJson('/api/leaflets', $leaflet);

        $response->assertStatus(201);
        $this->assertDatabaseHas('leaflets', $leaflet);
    }

    public function test_user_cannot_create_duplicate_leaflet()
    {
        $auth = $this->actingAsJwtUser();

        Leaflet::factory()->create(['title' => 'Кофейня']);

        $response = $this->withJwtToken($auth->token)
            ->postJson('/api/leaflets', [
                'title' => 'Кофейня',
            ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['title']);
    }

    public function test_user_can_update_leaflet()
    {
        $auth = $this->actingAsJwtUser();

        $leaflet = Leaflet::factory()->create(['title' => 'Кофейня']);

        $response = $this->withJwtToken($auth->token)
            ->putJson("/api/leaflets/{$leaflet->id}", [
                'title' => 'Кондитерская',
            ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('leaflets', [
            'id' => $leaflet->id,
            'title' => 'Кондитерская',
        ]);
    }

    public function test_user_cannot_update_leaflet_with_duplicate_title()
    {
        $auth = $this->actingAsJwtUser();

        $leaflet =Leaflet::factory()->create(['title' => 'Кофейня']);

        $response = $this->withJwtToken($auth->token)
            ->putJson("/api/leaflets/{$leaflet->id}", [
                'title' => 'Кофейня',
            ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['title']);
    }

    public function test_user_can_delete_leaflet()
    {
        $auth = $this->actingAsJwtUser();

        $leaflet =Leaflet::factory()->create(['title' => 'Кофейня']);

        $response = $this->withJwtToken($auth->token)
            ->deleteJson("/api/leaflets/$leaflet->id");

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Leaflet has been removed']);
        $this->assertDatabaseMissing('leaflets', ['id' => $leaflet->id]);
    }

    public function test_user_gets_404_when_deleting_nonexistent_leaflet()
    {
        $auth = $this->actingAsJwtUser();

        $response = $this->withJwtToken($auth->token)
            ->deleteJson('/api/leaflets/404');

        $response->assertStatus(404);
    }
}

