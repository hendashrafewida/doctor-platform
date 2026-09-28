<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ThemeSettingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_theme_settings_are_saved_and_rendered_from_database(): void
    {
        $user = User::factory()->create([
            'theme_mode' => 'light',
            'sidebar_theme' => 'light',
            'accent_color' => 'preset-1',
            'sidebar_caption' => true,
        ]);

        $this->actingAs($user);

        $response = $this->putJson('/settings/theme', [
            'theme_mode' => 'dark',
            'sidebar_theme' => 'dark',
            'accent_color' => 'preset-4',
            'sidebar_caption' => false,
        ]);

        $response->assertOk();
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'theme_mode' => 'dark',
            'sidebar_theme' => 'dark',
            'accent_color' => 'preset-4',
            'sidebar_caption' => false,
        ]);

        $page = $this->get('/dashboard');
        $page->assertSee('data-pc-theme="dark"', false);
        $page->assertSee('data-pc-sidebar-theme="dark"', false);
        $page->assertSee('data-pc-preset="preset-4"', false);
        $page->assertSee('data-pc-sidebar-caption="false"', false);
    }
}
