<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_and_login()
    {
        // Register
        $resp = $this->postJson('/api/register', [
            'name' => 'Alice',
            'email' => 'alice@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'user',
        ]);

        $resp->assertStatus(201)
             ->assertJsonStructure(['user', 'token']);

        // Login
        $login = $this->postJson('/api/login', [
            'email' => 'alice@example.com',
            'password' => 'password123',
        ]);

        $login->assertStatus(200)
              ->assertJsonStructure(['access_token','token_type','expires_in']);
    }

    public function test_logout_revokes_token()
    {
        $user = User::factory()->create(['password'=>'password123']);
        $token = auth('api')->login($user);

        $this->withHeader('Authorization', "Bearer $token")
             ->postJson('/api/logout')
             ->assertStatus(200);

        // Using token again should fail
        $this->withHeader('Authorization', "Bearer $token")
             ->getJson('/api/me')
             ->assertStatus(401);
    }
}

