<?php

namespace Tests\Feature\E2E;

use App\Models\Berita;
use App\Models\DanaMasuk;
use App\Models\KategoriAnggaran;
use App\Models\LaporanKeuangan;
use App\Models\LaporanWarga;
use App\Models\Pengeluaran;
use App\Models\ProyekKampung;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicUserJourneyTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(DatabaseSeeder::class);
    }

    public function test_public_visitor_can_follow_the_main_information_journey(): void
    {
        $this->get('/?tahun=2024')
            ->assertOk()
            ->assertSee('Transparansi Dana untuk Kemajuan Kampung Mbu')
            ->assertSee('Renovasi Balai Kemasyarakatan')
            ->assertSee(now()->year.' Pemerintah Kampung Mbu');

        $this->get('/transparansi?tahun=2024&status=selesai')
            ->assertOk()
            ->assertSee('Renovasi Balai Kemasyarakatan')
            ->assertViewHas('projects', fn ($projects): bool => $projects->count() === 1
                && $projects->first()->slug === 'renovasi-balai-kemasyarakatan');

        $this->get('/berita')
            ->assertOk()
            ->assertSee('Musyawarah Transparansi Dana Kampung')
            ->assertSee('/berita/musyawarah-transparansi-dana-kampung', false);

        $this->get('/berita/musyawarah-transparansi-dana-kampung')
            ->assertOk()
            ->assertSee('Musyawarah Transparansi Dana Kampung')
            ->assertSee('ringkasan dana masuk');

        $this->get('/laporan/pdf?tahun=2024')->assertRedirect('/login');

        $operator = User::query()->where('email', 'operator@kampungmbu.go.id')->firstOrFail();

        $this->actingAs($operator)->get('/laporan/pdf?tahun=2024')
            ->assertOk()
            ->assertHeader('content-type', 'application/pdf')
            ->assertDownload('laporan-dana-kampung-mbu-2024.pdf');
    }

    public function test_citizen_can_submit_a_report_and_receive_a_ticket(): void
    {
        $this->from('/laporan-warga')->post('/laporan-warga', [
            'nama_pelapor' => 'Yuliana Matuan',
            'kontak' => '081234567890',
            'kategori' => 'Transparansi Dana',
            'isi_laporan' => 'Mohon rincian terbaru penggunaan dana pembangunan Kampung Mbu diumumkan.',
        ])->assertRedirect('/laporan-warga')->assertSessionHas('status');

        $report = LaporanWarga::query()
            ->where('nama_pelapor', 'Yuliana Matuan')
            ->firstOrFail();

        $this->assertStringStartsWith('LWK-', $report->nomor_tiket);
        $this->assertSame('baru', $report->status);
    }

    public function test_admin_access_is_protected_and_active_admin_can_open_dashboard(): void
    {
        $this->get('/admin')->assertRedirect('/admin/login');

        $admin = User::query()->where('email', 'admin@kampungmbu.go.id')->firstOrFail();

        $this->actingAs($admin)
            ->get('/admin')
            ->assertOk()
            ->assertSee('Dana Kampung Mbu');
    }

    public function test_database_seeder_is_idempotent(): void
    {
        $countsBefore = $this->seededRecordCounts();

        $this->seed(DatabaseSeeder::class);

        $this->assertSame($countsBefore, $this->seededRecordCounts());
    }

    private function seededRecordCounts(): array
    {
        return [
            User::query()->count(),
            KategoriAnggaran::query()->count(),
            DanaMasuk::query()->count(),
            Pengeluaran::query()->count(),
            ProyekKampung::query()->count(),
            LaporanKeuangan::query()->count(),
            LaporanWarga::query()->count(),
            Berita::query()->count(),
        ];
    }
}
