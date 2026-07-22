<?php

namespace Tests\Feature;

use App\Models\Berita;
use App\Models\DanaMasuk;
use App\Models\Pengeluaran;
use App\Models\ProyekKampung;
use App\Models\User;
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
        ProyekKampung::query()->create([
            'nama' => 'Proyek Selesai',
            'slug' => 'proyek-selesai',
            'lokasi' => 'Kampung Mbu',
            'anggaran' => 100000000,
            'realisasi' => 100000000,
            'progress' => 100,
            'tanggal_mulai' => '2026-01-10',
        ]);
        ProyekKampung::query()->create([
            'nama' => 'Proyek Berjalan',
            'slug' => 'proyek-berjalan',
            'lokasi' => 'Kampung Mbu',
            'anggaran' => 80000000,
            'realisasi' => 40000000,
            'progress' => 50,
            'tanggal_mulai' => '2026-02-10',
        ]);

        $this->get('/?tahun=2026&status=selesai')
            ->assertOk()
            ->assertSee('Proyek Selesai')
            ->assertDontSee('Proyek Berjalan');
    }

    public function test_pdf_download_requires_an_internal_user(): void
    {
        $this->get('/laporan/pdf?tahun=2026')->assertRedirect('/login');
    }

    public function test_active_operator_can_download_pdf_report(): void
    {
        DanaMasuk::query()->create([
            'kode_transaksi' => 'DM-TEST',
            'sumber_dana' => 'Dana Desa',
            'nominal' => 100000000,
            'tanggal' => '2026-01-10',
            'status' => 'terverifikasi',
        ]);
        Pengeluaran::query()->create([
            'kode_transaksi' => 'PK-TEST',
            'uraian' => 'Pembangunan',
            'nominal' => 25000000,
            'tanggal' => '2026-02-10',
            'status' => 'terverifikasi',
        ]);

        $operator = User::factory()->create([
            'role' => 'operator',
            'status' => 'aktif',
        ]);

        $this->actingAs($operator)->get('/laporan/pdf?tahun=2026')
            ->assertOk()
            ->assertHeader('content-type', 'application/pdf');
    }

    public function test_inactive_operator_cannot_download_pdf_report(): void
    {
        $operator = User::factory()->create([
            'role' => 'operator',
            'status' => 'nonaktif',
        ]);

        $this->actingAs($operator)->get('/laporan/pdf?tahun=2026')->assertForbidden();
    }

    public function test_public_financial_summary_comes_from_database(): void
    {
        DanaMasuk::query()->create([
            'kode_transaksi' => 'DM-PUBLIK',
            'sumber_dana' => 'Dana Desa',
            'nominal' => 150000000,
            'tanggal' => '2026-01-10',
            'status' => 'terverifikasi',
        ]);
        Pengeluaran::query()->create([
            'kode_transaksi' => 'PK-PUBLIK',
            'uraian' => 'Pengadaan',
            'nominal' => 50000000,
            'tanggal' => '2026-02-10',
            'status' => 'terverifikasi',
        ]);

        $this->get('/?tahun=2026')
            ->assertOk()
            ->assertSee('Rp 150.000.000')
            ->assertSee('Rp 50.000.000');
    }

    public function test_only_published_news_is_public(): void
    {
        Berita::query()->create([
            'judul' => 'Berita Terbit',
            'slug' => 'berita-terbit',
            'ringkasan' => 'Ringkasan berita terbit.',
            'isi' => '<p>Isi berita.</p>',
            'status' => 'terbit',
            'published_at' => now()->subHour(),
        ]);
        Berita::query()->create([
            'judul' => 'Berita Draft',
            'slug' => 'berita-draft',
            'ringkasan' => 'Ringkasan berita draft.',
            'isi' => '<p>Isi berita.</p>',
            'status' => 'draft',
        ]);

        $this->get('/berita')
            ->assertOk()
            ->assertSee('Berita Terbit')
            ->assertDontSee('Berita Draft');
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
