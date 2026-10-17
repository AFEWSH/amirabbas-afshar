<?php

namespace Tests\Feature;

use Filament\Enums\ThemeMode;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ThemeTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_pages_offer_localized_theme_controls_with_a_dark_default(): void
    {
        foreach (['/' => 'حالت روشن', '/en' => 'Light mode', '/projects' => 'حالت روشن', '/en/blog' => 'Light mode'] as $url => $label) {
            $this->get($url)->assertOk()
                ->assertSee('data-theme="dark"', false)
                ->assertSee('data-theme-toggle', false)
                ->assertSee('aria-label="'.$label.'"', false)
                ->assertSee("localStorage.getItem('portfolio-theme')", false);
        }
    }

    public function test_admin_supports_switching_themes_and_defaults_to_dark(): void
    {
        $panel = Filament::getPanel('admin');

        $this->assertTrue($panel->hasDarkMode());
        $this->assertTrue($panel->hasThemeSwitcher());
        $this->assertFalse($panel->hasDarkModeForced());
        $this->assertSame(ThemeMode::Dark, $panel->getDefaultThemeMode());
    }
}
