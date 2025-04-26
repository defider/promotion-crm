<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register()
    {
        $user = User::factory()->make()->toArray();
        $user['password'] = 'password';

        $response = $this->postJson('/api/auth/register', $user);

        $response->assertStatus(201);
        $this->assertDatabaseHas('users', ['email' => $user['email']]);
    }

    public function test_user_cannot_register_with_existing_email()
    {
        $existingUser = User::factory()->create([
            'email' => 'user@example.com',
        ]);

        $newUser = User::factory()->make([
            'email' => $existingUser->email,
        ])->toArray();

        $response = $this->postJson('/api/auth/register', $newUser);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email']);
    }

    public function test_user_cannot_register_with_short_password()
    {
        $user = User::factory()->make()->toArray();
        $user['password'] = '123';

        $response = $this->postJson('/api/auth/register', $user);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['password']);
    }

    public function test_user_can_login_with_valid_credentials()
    {
        $user = User::factory()->create();

        $response = $this->postJson('/api/auth/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['access_token']);

        $token = $response->json('access_token');
        $this->assertIsString($token);
    }

    public function test_user_cannot_login_with_invalid_email_format()
    {
        $user = User::factory()->create([
            'email' => 'user@example.com',
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => 'user',
            'password' => 'password',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email']);
    }
}
