<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccountProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login_when_accessing_account_page(): void
    {
        $response = $this->get('/account');

        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_update_profile_details(): void
    {
        $user = User::factory()->create([
            'name' => 'Old Name',
            'email' => 'old@example.com',
            'mobile' => '0500000000',
        ]);

        $this->actingAs($user);

        $response = $this->put('/account', [
            'name' => 'New Name',
            'email' => 'new@example.com',
            'mobile' => '0555555555',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'New Name',
            'email' => 'new@example.com',
            'mobile' => '0555555555',
        ]);
    }

    public function test_user_cannot_change_password_with_wrong_current_password(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->from('/account')->put('/account/password', [
            'current_password' => 'wrong-password',
            'password' => 'newsecret123',
            'password_confirmation' => 'newsecret123',
        ]);

        $response->assertRedirect('/account');
        $response->assertSessionHasErrors(['current_password']);
    }

    public function test_logout_redirects_to_login(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->post('/logout');

        $response->assertRedirect('/login');
        $this->assertGuest();
    }
}
