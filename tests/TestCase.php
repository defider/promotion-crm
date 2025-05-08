<?php

namespace Tests;

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
}
