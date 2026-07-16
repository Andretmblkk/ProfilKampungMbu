<?php

namespace Database\Seeders;

use App\Models\Berita;
use App\Models\DanaMasuk;
use App\Models\KategoriAnggaran;
use App\Models\LaporanKeuangan;
use App\Models\LaporanWarga;
use App\Models\Pengeluaran;
use App\Models\ProyekKampung;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class KampungMbuSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            $admin = User::query()->where('email', 'admin@kampungmbu.go.id')->firstOrFail();
            $categories = $this->categories();
            $projects = $this->projects($categories['infrastruktur']->id);

            foreach ([
                ['DM-2024-001', 'Dana Desa Tahap I', 650000000, '2024-02-05', 'infrastruktur'],
                ['DM-2024-002', 'Alokasi Dana Kampung', 550000000, '2024-05-20', 'operasional'],
                ['DM-2024-003', 'Bantuan Keuangan Kabupaten', 310000000, '2024-08-12', 'pendidikan'],
            ] as [$code, $source, $amount, $date, $category]) {
                DanaMasuk::query()->updateOrCreate(['kode_transaksi' => $code], [
                    'kategori_anggaran_id' => $categories[$category]->id,
                    'sumber_dana' => $source,
                    'nominal' => $amount,
                    'tanggal' => $date,
                    'keterangan' => 'Penerimaan resmi program Kampung Mbu tahun 2024.',
                    'status' => 'terverifikasi',
                    'created_by' => $admin->id,
                ]);
            }

            foreach ([
                ['PK-2024-001', 'Material normalisasi drainase', 273000000, '2024-04-18', 'CV Pegunungan Maju', 'infrastruktur', 'normalisasi-drainase-dusun-a'],
                ['PK-2024-002', 'Renovasi balai kemasyarakatan', 850000000, '2024-08-30', 'Tim Pelaksana Kegiatan Kampung', 'infrastruktur', 'renovasi-balai-kemasyarakatan'],
                ['PK-2024-003', 'Pengadaan lampu tenaga surya tahap awal', 25860000, '2024-06-14', 'Toko Terang Papua', 'infrastruktur', 'penerangan-jalan-umum-tenaga-surya'],
                ['PK-2024-004', 'BLT Dana Desa Tahap III', 45000000, '2024-09-20', 'Keluarga Penerima Manfaat', 'bantuan-sosial', null],
                ['PK-2024-005', 'Operasional listrik dan internet kantor', 2150000, '2024-10-01', 'Penyedia Layanan', 'operasional', null],
                ['PK-2024-006', 'Pengadaan buku pelajaran dasar', 37500000, '2024-10-16', 'Toko Buku Pendidikan Papua', 'pendidikan', 'pembangunan-rumah-baca-kampung'],
            ] as [$code, $description, $amount, $date, $recipient, $category, $project]) {
                Pengeluaran::query()->updateOrCreate(['kode_transaksi' => $code], [
                    'kategori_anggaran_id' => $categories[$category]->id,
                    'proyek_kampung_id' => $project ? $projects[$project]->id : null,
                    'uraian' => $description,
                    'nominal' => $amount,
                    'tanggal' => $date,
                    'penerima' => $recipient,
                    'status' => 'terverifikasi',
                    'created_by' => $admin->id,
                ]);
            }

            $this->reports($admin);
            $this->citizenReports();
            $this->news($admin);
        });
    }

    private function categories(): array
    {
        $result = [];

        foreach ([
            ['Infrastruktur', '#0d4aaa', 'screwdriver-wrench', 1250000000],
            ['Bantuan Sosial', '#13a34a', 'hand-holding-heart', 420000000],
            ['Pendidikan', '#4f46e5', 'graduation-cap', 310000000],
            ['Kesehatan', '#dc2626', 'heart-pulse', 240000000],
            ['Operasional', '#a66a07', 'receipt', 180000000],
        ] as [$name, $color, $icon, $budget]) {
            $slug = Str::slug($name);
            $result[$slug] = KategoriAnggaran::query()->updateOrCreate(['slug' => $slug], [
                'nama' => $name,
                'warna' => $color,
                'ikon' => $icon,
                'pagu_anggaran' => $budget,
                'deskripsi' => 'Kategori anggaran resmi Kampung Mbu tahun berjalan.',
            ]);
        }

        return $result;
    }

    private function projects(int $categoryId): array
    {
        $result = [];

        foreach ([
            ['Normalisasi Drainase Dusun A', 'Dusun A', 'Perbaikan saluran air untuk mengurangi genangan di sekitar permukiman warga.', 420000000, 273000000, 65, '2024-02-10', null],
            ['Renovasi Balai Kemasyarakatan', 'Pusat Kampung', 'Renovasi ruang musyawarah, pelayanan administrasi, dan kegiatan warga.', 850000000, 850000000, 100, '2024-01-15', '2024-08-30'],
            ['Penerangan Jalan Umum Tenaga Surya', 'Jalan Tani', 'Pemasangan lampu tenaga surya pada jalur aktivitas warga dan akses kebun.', 215500000, 25860000, 12, '2024-05-06', null],
            ['Pembangunan Rumah Baca Kampung', 'Kompleks Sekolah', 'Penyediaan ruang belajar dan koleksi bacaan untuk anak dan pemuda kampung.', 180000000, 0, 0, '2024-09-02', null],
        ] as [$name, $location, $description, $budget, $realization, $progress, $start, $finish]) {
            $slug = Str::slug($name);
            $result[$slug] = ProyekKampung::query()->updateOrCreate(['slug' => $slug], [
                'kategori_anggaran_id' => $categoryId,
                'nama' => $name,
                'lokasi' => $location,
                'deskripsi' => $description,
                'anggaran' => $budget,
                'realisasi' => $realization,
                'progress' => $progress,
                'tanggal_mulai' => $start,
                'tanggal_selesai' => $finish,
                'foto_path' => null,
            ]);
        }

        return $result;
    }

    private function reports(User $admin): void
    {
        foreach ([
            ['Laporan Semester I Tahun 2024', 'Semester I 2024', '2024-07-15', 'laporan/laporan-semester-1-2024.pdf'],
            ['Laporan Tahunan 2024', 'Tahun 2024', '2024-12-20', 'laporan/laporan-tahunan-2024.pdf'],
        ] as [$title, $period, $date, $path]) {
            LaporanKeuangan::query()->updateOrCreate(['judul' => $title], [
                'kategori' => 'Realisasi APB Kampung',
                'periode' => $period,
                'tanggal_laporan' => $date,
                'file_path' => $path,
                'file_type' => 'pdf',
                'file_size' => 2400000,
                'status' => 'terverifikasi',
                'uploaded_by' => $admin->id,
            ]);
        }
    }

    private function citizenReports(): void
    {
        foreach ([
            ['LWK-20240522-MBU01', 'Yohanes Matuan', '0812-0000-0000', 'Proyek Pembangunan', 'Mohon pembaruan dokumentasi progres pembangunan jalan tani agar warga dapat memantau realisasi pekerjaan.', 'baru'],
            ['LWK-20240618-MBU02', 'Maria Wenda', '0813-0000-0000', 'Pelayanan Publik', 'Mohon jadwal pelayanan administrasi kampung diumumkan secara rutin melalui portal informasi warga.', 'diproses'],
        ] as [$ticket, $name, $contact, $category, $content, $status]) {
            LaporanWarga::query()->updateOrCreate(['nomor_tiket' => $ticket], [
                'nama_pelapor' => $name,
                'kontak' => $contact,
                'kategori' => $category,
                'isi_laporan' => $content,
                'status' => $status,
            ]);
        }
    }

    private function news(User $admin): void
    {
        foreach ([
            ['Musyawarah Transparansi Dana Kampung', 'Pemerintah Kampung Mbu membuka ruang informasi publik mengenai realisasi dana dan kegiatan pembangunan.', '<p>Musyawarah transparansi menjadi ruang bagi pemerintah kampung dan warga untuk melihat ringkasan dana masuk, pengeluaran, serta perkembangan proyek.</p>', '2024-06-12 09:00:00'],
            ['Pembaruan Progres Pembangunan Kampung', 'Informasi progres proyek kini dapat dipantau melalui halaman transparansi berdasarkan periode dan status.', '<p>Warga dapat melihat anggaran, realisasi, lokasi, dan persentase kemajuan setiap proyek melalui portal.</p>', '2024-05-28 09:00:00'],
            ['Layanan Laporan Warga Dibuka', 'Warga dapat mengirim aspirasi atau pengaduan dan menerima nomor tiket secara otomatis.', '<p>Setiap laporan akan mendapatkan nomor tiket dan dapat ditindaklanjuti oleh admin kampung.</p>', '2024-05-06 09:00:00'],
        ] as [$title, $summary, $content, $publishedAt]) {
            Berita::query()->updateOrCreate(['slug' => Str::slug($title)], [
                'author_id' => $admin->id,
                'judul' => $title,
                'ringkasan' => $summary,
                'isi' => $content,
                'gambar_path' => null,
                'status' => 'terbit',
                'published_at' => $publishedAt,
            ]);
        }
    }
}
