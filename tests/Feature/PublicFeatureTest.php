<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_can_be_opened(): void
    {
        $this->get('/')->assertOk()->assertSee('Transparansi Dana untuk Kemajuan Kampung Mbu');
    }

    public function test_main_public_routes_are_available(): void
    {
        foreach (['/transparansi', '/berita', '/kontak', '/kebijakan-privasi', '/peta-situs', '/laporan-warga'] as $path) {
            $this->get($path)->assertOk();
        }
    }

    public function test_project_status_filter_works(): void
    {
        $this->get('/?status=selesai')
            ->assertOk()
            ->assertSee('Rehabilitasi Balai Desa')
            ->assertDontSee('Pembangunan Jembatan Dusun A');
    }

    public function test_pdf_download_returns_pdf_response(): void
    {
        $this->get('/laporan/pdf')
            ->assertOk()
            ->assertHeader('content-type', 'application/pdf');
    }

    public function test_login_shortcut_redirects_to_filament_login(): void
    {
        $this->get('/login')->assertRedirect('/admin/login');
    }

    public function test_custom_dashboard_routes_are_removed(): void
    {
        $this->get('/dashboard')->assertNotFound();
    }

    public function test_citizen_report_can_be_submitted(): void
    {
        $this->post('/laporan-warga', [
            'nama_pelapor' => 'Maria Mbu',
            'kontak' => '08123456789',
            'kategori' => 'Dana Kampung',
            'isi_laporan' => 'Mohon publikasi dokumen pendukung untuk laporan dana kampung terbaru.',
        ])->assertRedirect('/laporan-warga');

        $this->assertDatabaseHas('laporan_wargas', [
            'nama_pelapor' => 'Maria Mbu',
            'status' => 'baru',
        ]);
    }
}
