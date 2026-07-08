<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test user can login with valid username and password.
     */
    public function test_user_can_login_with_valid_username_and_password(): void
    {
        $user = User::factory()->create([
            'username' => 'testuser',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/login', [
            'username' => 'testuser',
            'password' => 'password123',
        ]);

        $this->assertAuthenticatedAs($user);
    }

    /**
     * Test user fails login with non-existent username.
     */
    public function test_user_fails_login_if_username_not_registered(): void
    {
        $response = $this->post('/login', [
            'username' => 'nonexistent',
            'password' => 'password123',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors('username');
    }

    /**
     * Test user fails login with wrong password.
     */
    public function test_user_fails_login_with_wrong_password(): void
    {
        $user = User::factory()->create([
            'username' => 'testuser',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/login', [
            'username' => 'testuser',
            'password' => 'wrongpassword',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors('username');
    }

    /**
     * Test redirect after login goes to /dashboard.
     */
    public function test_redirect_after_login_must_go_to_dashboard(): void
    {
        $user = User::factory()->create([
            'username' => 'testuser',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/login', [
            'username' => 'testuser',
            'password' => 'password123',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect('/dashboard');
    }
}
