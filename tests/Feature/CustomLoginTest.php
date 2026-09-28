<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_page_renders_custom_blade_view(): void
    {
        $response = $this->get('/login');

        $response->assertOk();
        $response->assertSee('name="email"', false);
        $response->assertSee('name="password"', false);
        $response->assertSee('method="POST"', false);
    }

    public function test_valid_user_can_login_and_redirect_to_dashboard(): void
    {
        $user = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => bcrypt('secret123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'admin@example.com',
            'password' => 'secret123',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user);
    }

    public function test_invalid_login_shows_validation_error(): void
    {
        $response = $this->from('/login')->post('/login', [
            'email' => 'wrong@example.com',
            'password' => 'bad-password',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors(['email']);
    }
}
