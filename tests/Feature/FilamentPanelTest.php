<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FilamentPanelTest extends TestCase
{
    use RefreshDatabase;

    public function test_filament_login_page_can_render(): void
    {
        $this->get('/admin/login')->assertOk()->assertSee('Dana Kampung Mbu');
    }

    public function test_filament_dashboard_can_render_for_active_admin(): void
    {
        $user = User::factory()->create([
            'role' => 'administrator',
            'status' => 'aktif',
        ]);

        $this->actingAs($user)->get('/admin')->assertOk();
    }
}
