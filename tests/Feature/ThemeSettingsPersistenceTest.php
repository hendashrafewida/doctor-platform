<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ThemeSettingsPersistenceTest extends TestCase
{
    use RefreshDatabase;

    public function test_theme_settings_are_saved_and_rendered_on_follow_up_page_load(): void
    {
        $user = User::factory()->create([
            'theme_mode' => 'light',
            'sidebar_theme' => 'light',
            'accent_color' => 'preset-1',
            'sidebar_caption' => true,
        ]);

        $this->actingAs($user);

        $responseTheme = $this->putJson('/settings/theme', [
            'theme_mode' => 'dark',
        ]);

        $responseTheme->assertOk();
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'theme_mode' => 'dark',
        ]);

        $responseSidebar = $this->putJson('/settings/theme', [
            'sidebar_theme' => 'dark',
        ]);

        $responseSidebar->assertOk();
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'sidebar_theme' => 'dark',
        ]);

        $responseAccent = $this->putJson('/settings/theme', [
            'accent_color' => 'preset-4',
        ]);

        $responseAccent->assertOk();
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'accent_color' => 'preset-4',
        ]);

        $pageResponse = $this->get('/dashboard');
        $pageResponse->assertOk();
        $pageResponse->assertSee('data-pc-theme="dark"', false);
        $pageResponse->assertSee('data-pc-sidebar-theme="dark"', false);
        $pageResponse->assertSee('data-pc-preset="preset-4"', false);
    }
}
