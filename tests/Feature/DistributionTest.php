<?php

namespace Tests\Feature;

use App\Models\Distribution;
use App\Models\Leaflet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DistributionTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_begin_distribution()
    {
        $auth = $this->actingAsJwtUser();

        extract($this->createTestDistributionDependencies());

        $response = $this->withJwtToken($auth->token)
            ->postJson('/api/distributions/begin', [
                'building_id' => $building->id,
                'leaflet_id' => $leaflet->id,
            ]);

        $response->assertStatus(201);
        $response->assertJsonFragment(['id' => $building->id]);

        $this->assertDatabaseHas('distributions', [
            'user_id' => $auth->user->id,
            'building_id' => $building->id,
            'leaflet_id' => $leaflet->id,
        ]);
    }

    public function test_user_cannot_begin_distribution_if_one_is_active()
    {
        $auth = $this->actingAsJwtUser();

        extract($this->createTestDistributionDependencies());

        Distribution::factory()->create([
            'building_id' => $building->id,
            'leaflet_id' => $leaflet->id,
            'began_at' => now(),
            'ended_at' => null,
        ]);

        $response = $this->withJwtToken($auth->token)
            ->postJson('/api/distributions/begin', [
                'building_id' => $building->id,
                'leaflet_id' => $leaflet->id,
            ]);

        $response->assertStatus(400);
        $response->assertJson([
            'message' => 'Active distribution already exists',
        ]);
    }

    public function test_user_can_get_current_distribution()
    {
        $auth = $this->actingAsJwtUser();

        extract($this->createTestDistributionDependencies());

        Distribution::factory()->create([
            'building_id' => $building->id,
            'leaflet_id' => $leaflet->id,
            'began_at' => now(),
            'ended_at' => null,
        ]);

        $response = $this->withJwtToken($auth->token)
            ->getJson('/api/distributions/current');

        $response->assertStatus(200);
        $response->assertJsonFragment(['id' => $building->id]);
    }

    public function test_user_gets_404_when_no_active_distribution()
    {
        $auth = $this->actingAsJwtUser();

        $response = $this->withJwtToken($auth->token)
            ->getJson('/api/distributions/current');

        $response->assertStatus(404);
        $response->assertJson([
            'message' => 'No active distribution',
        ]);
    }

    public function test_user_can_end_distribution()
    {
        $auth = $this->actingAsJwtUser();

        extract($this->createTestDistributionDependencies());

        $distribution = Distribution::factory()->create([
            'building_id' => $building->id,
            'leaflet_id' => $leaflet->id,
            'began_at' => now(),
            'ended_at' => null,
        ]);

        $response = $this->withJwtToken($auth->token)
            ->patchJson("/api/distributions/$distribution->id/end");

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Distribution ended']);

        $this->assertDatabaseHas('distributions', ['id' => $distribution->id]);
    }

    public function test_user_cannot_end_already_ended_distribution()
    {
        $auth = $this->actingAsJwtUser();

        extract($this->createTestDistributionDependencies());

        $distribution = Distribution::factory()->create([
            'building_id' => $building->id,
            'leaflet_id' => $leaflet->id,
            'began_at' => now(),
            'ended_at' => now(),
        ]);

        $response = $this->withJwtToken($auth->token)
            ->patchJson("/api/distributions/$distribution->id/end");

        $response->assertStatus(400);
        $response->assertJson([
            'message' => 'Distribution already ended',
        ]);
    }

    public function test_user_can_get_distribution_list()
    {
        $auth = $this->actingAsJwtUser();

        extract($this->createTestDistributionDependencies());

        Distribution::factory(3)->create([
            'building_id' => $building->id,
            'leaflet_id' => $leaflet->id,
        ]);

        $response = $this->withJwtToken($auth->token)
            ->getJson('/api/distributions');
        $response->assertStatus(200);
        $this->assertCount(3, $response->json('data'));
    }

    public function test_user_can_view_distribution_by_id()
    {
        $auth = $this->actingAsJwtUser();

        extract($this->createTestDistributionDependencies());

        $distribution = Distribution::factory()->create([
            'building_id' => $building->id,
            'leaflet_id' => $leaflet->id,
        ]);

        $response = $this->withJwtToken($auth->token)
            ->getJson("/api/distributions/$distribution->id");

        $response->assertStatus(200);
        $response->assertJsonFragment(['id' => $distribution->id]);
    }

    public function test_user_gets_404_if_distribution_not_found()
    {
        $auth = $this->actingAsJwtUser();

        $response = $this->withJwtToken($auth->token)
            ->getJson('/api/distributions/404');

        $response->assertStatus(404);
    }

    public function test_user_can_update_distribution()
    {
        $auth = $this->actingAsJwtUser();

        extract($this->createTestDistributionDependencies());

        $newLeaflet = Leaflet::factory()->create(['title' => 'Пекарня']);

        $distribution = Distribution::factory()->create([
            'building_id' => $building->id,
            'leaflet_id' => $leaflet->id,
        ]);

        $response = $this->withJwtToken($auth->token)
            ->putJson("/api/distributions/$distribution->id", [
                'leaflet_id' => $newLeaflet->id,
            ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('distributions', [
            'id' => $distribution->id,
            'leaflet_id' => $newLeaflet->id,
        ]);
    }

    public function test_user_cannot_update_distribution_with_invalid_data()
    {
        $auth = $this->actingAsJwtUser();

        extract($this->createTestDistributionDependencies());

        $distribution = Distribution::factory()->create([
            'building_id' => $building->id,
            'leaflet_id' => $leaflet->id,
        ]);

        $response = $this->withJwtToken($auth->token)
            ->putJson("/api/distributions/$distribution->id", [
                'building_id' => '422',
            ]);

        $response->assertStatus(422);
    }

    public function test_user_can_delete_distribution()
    {
        $auth = $this->actingAsJwtUser();

        extract($this->createTestDistributionDependencies());

        $distribution = Distribution::factory()->create([
            'building_id' => $building->id,
            'leaflet_id' => $leaflet->id,
        ]);

        $response = $this->withJwtToken($auth->token)
            ->deleteJson("/api/distributions/$distribution->id");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('distributions', ['id' => $distribution->id]);
    }

    public function test_user_cannot_delete_nonexistent_distribution()
    {
        $auth = $this->actingAsJwtUser();

        $response = $this->withJwtToken($auth->token)
            ->deleteJson('/api/distributions/404');

        $response->assertStatus(404);
    }
}
