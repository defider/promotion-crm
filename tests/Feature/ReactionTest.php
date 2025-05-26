<?php

namespace Tests\Feature;

use App\Models\Reaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReactionTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_reaction_list()
    {
        $auth = $this->actingAsJwtUser();

        Reaction::factory(2)
            ->sequence(
                ['title' => 'Не взяли листовку'],
                ['title' => 'Взяли листовку'],
            )
            ->create();

        $response = $this->withJwtToken($auth->token)
            ->getJson('/api/reactions');

        $response->assertStatus(200);
        $this->assertCount(2, $response->json('data'));
    }

    public function test_guest_cannot_view_reaction_list()
    {
        Reaction::factory(2)
            ->sequence(
                ['title' => 'Не взяли листовку'],
                ['title' => 'Взяли листовку'],
            )
            ->create();

        $response = $this->getJson('/api/reactions');

        $response->assertStatus(401);
    }

    public function test_user_can_view_single_reaction()
    {
        $auth = $this->actingAsJwtUser();

        $reaction = Reaction::factory()->create(['title' => 'Взяли листовку']);

        $response = $this->withJwtToken($auth->token)
            ->getJson("/api/reactions/$reaction->id");

        $response->assertStatus(200);
        $response->assertJsonFragment(['id' => $reaction->id]);
    }

    public function test_user_gets_404_if_reaction_not_found()
    {
        $auth = $this->actingAsJwtUser();

        $response = $this->withJwtToken($auth->token)
            ->getJson('/api/reactions/404');

        $response->assertStatus(404);
    }

    public function test_user_can_create_reaction()
    {
        $auth = $this->actingAsJwtUser();

        $reaction = [
            'number' => '1',
            'title' => 'Взяли листовку',
        ];

        $response = $this->withJwtToken($auth->token)
            ->postJson('/api/reactions', $reaction);

        $response->assertStatus(201);
        $this->assertDatabaseHas('reactions', $reaction);
    }

    public function test_user_cannot_create_duplicate_reaction()
    {
        $auth = $this->actingAsJwtUser();

        Reaction::factory()->create(['title' => 'Взяли листовку']);

        $response = $this->withJwtToken($auth->token)
            ->postJson('/api/reactions', [
                'title' => 'Взяли листовку',
            ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['title']);
    }

    public function test_user_can_update_reaction()
    {
        $auth = $this->actingAsJwtUser();

        $reaction = Reaction::factory()->create(['title' => 'Взяли листовку']);

        $response = $this->withJwtToken($auth->token)
            ->putJson("/api/reactions/$reaction->id", [
                'title' => 'Не взяли листовку',
            ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('reactions', [
            'id' => $reaction->id,
            'title' => 'Не взяли листовку',
        ]);
    }

    public function test_user_cannot_update_reaction_with_duplicate_title()
    {
        $auth = $this->actingAsJwtUser();

        $reaction = Reaction::factory()->create(['title' => 'Взяли листовку']);

        $response = $this->withJwtToken($auth->token)
            ->putJson("/api/reactions/$reaction->id", [
                'title' => 'Взяли листовку',
            ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['title']);
    }

    public function test_user_can_delete_reaction()
    {
        $auth = $this->actingAsJwtUser();

        $reaction = Reaction::factory()->create(['title' => 'Взяли листовку']);

        $response = $this->withJwtToken($auth->token)
            ->deleteJson("/api/reactions/$reaction->id");

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Reaction has been removed']);
        $this->assertDatabaseMissing('reactions', ['id' => $reaction->id]);
    }

    public function test_user_gets_404_when_deleting_nonexistent_reaction()
    {
        $auth = $this->actingAsJwtUser();

        $response = $this->withJwtToken($auth->token)
            ->deleteJson('/api/reactions/404');

        $response->assertStatus(404);
    }
}
