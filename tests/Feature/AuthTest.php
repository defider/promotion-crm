<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
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
        $response->assertJsonStructure(['access_token', 'token_type', 'expires_in']);
    }

    public function test_user_cannot_login_with_invalid_email_format()
    {
        User::factory()->create([
            'email' => 'user@example.com',
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => 'user',
            'password' => 'password',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email']);
    }

    public function test_user_can_logout()
    {
        $auth = $this->actingAsJwtUser();

        $response = $this->withHeaders($auth->asHeaders())
            ->postJson('/api/auth/logout');

        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Successfully logged out',
        ]);
    }

    public function test_logout_without_token_fails()
    {
        $response = $this->postJson('/api/auth/logout');

        $response->assertStatus(401);
    }

    public function test_get_user_returns_authenticated_user()
    {
        $auth = $this->actingAsJwtUser();

        $response = $this->withHeaders($auth->asHeaders())
            ->getJson('/api/auth/user');

        $response->assertStatus(200);
        $response->assertJsonFragment(['email' => $auth->user->email]);
    }

    public function test_get_user_without_token_fails()
    {
        $response = $this->getJson('/api/auth/user');

        $response->assertStatus(401);
    }

    public function test_refresh_token_returns_new_token()
    {
        $auth = $this->actingAsJwtUser();

        $response = $this->withHeaders($auth->asHeaders())
            ->postJson('/api/auth/refresh');

        $response->assertStatus(200);
        $response->assertJsonStructure(['access_token', 'token_type', 'expires_in']);
    }

    public function test_refresh_token_fails_without_token()
    {
        $response = $this->postJson('/api/auth/refresh');

        $response->assertStatus(401);
    }

    public function test_refresh_with_invalid_token_fails()
    {
        $response = $this->withJwtToken('this.is.a.broken.token')
            ->postJson('/api/auth/refresh');

        $response->assertStatus(401);
    }
}
