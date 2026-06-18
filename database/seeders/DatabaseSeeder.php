<?php

namespace Database\Seeders;

use App\Models\DanaMasuk;
use App\Models\KategoriAnggaran;
use App\Models\LaporanKeuangan;
use App\Models\LaporanWarga;
use App\Models\Pengeluaran;
use App\Models\ProyekKampung;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::query()->updateOrCreate([
            'email' => 'admin@kampungmbu.go.id',
        ], [
            'name' => 'Admin Kampung',
            'password' => Hash::make('password'),
            'role' => 'administrator',
            'status' => 'aktif',
        ]);

        $categories = collect([
            ['nama' => 'Infrastruktur', 'warna' => '#0d4aaa', 'ikon' => 'screwdriver-wrench', 'pagu_anggaran' => 1250000000],
            ['nama' => 'Bantuan Sosial', 'warna' => '#13a34a', 'ikon' => 'hand-holding-heart', 'pagu_anggaran' => 420000000],
            ['nama' => 'Pendidikan', 'warna' => '#4f46e5', 'ikon' => 'graduation-cap', 'pagu_anggaran' => 310000000],
            ['nama' => 'Operasional', 'warna' => '#a66a07', 'ikon' => 'receipt', 'pagu_anggaran' => 180000000],
        ])->map(fn (array $item) => KategoriAnggaran::query()->updateOrCreate(
            ['slug' => Str::slug($item['nama'])],
            $item + ['deskripsi' => 'Kategori anggaran Kampung Mbu tahun berjalan.']
        ));

        DanaMasuk::query()->updateOrCreate(
            ['kode_transaksi' => 'TRS-DA-2024'],
            [
                'kategori_anggaran_id' => $categories[0]->id,
                'sumber_dana' => 'Dana Alokasi Desa',
                'nominal' => 1200000000,
                'tanggal' => '2024-11-22',
                'status' => 'terverifikasi',
                'created_by' => $admin->id,
            ]
        );

        foreach ([
            ['Pembangunan Jembatan Mbu II', 250000000, '2024-11-24', 0],
            ['BLT Dana Desa Tahap III', 45000000, '2024-11-20', 1],
            ['Operasional Listrik Desa', 2150000, '2024-03-01', 3],
        ] as $row) {
            Pengeluaran::query()->updateOrCreate(
                ['kode_transaksi' => Str::slug($row[0])],
                [
                    'kategori_anggaran_id' => $categories[$row[3]]->id,
                    'uraian' => $row[0],
                    'nominal' => $row[1],
                    'tanggal' => $row[2],
                    'status' => 'terverifikasi',
                    'created_by' => $admin->id,
                ]
            );
        }

        foreach ([
            ['Normalisasi Drainase Dusun A', 'Dusun A', 420000000, 273000000, 65, 'berjalan'],
            ['Renovasi Balai Kemasyarakatan', 'Pusat Kampung', 850000000, 850000000, 100, 'selesai'],
            ['Penerangan Jalan Umum Tenaga Surya', 'Jalan Tani', 215500000, 25860000, 12, 'berjalan'],
        ] as $row) {
            ProyekKampung::query()->updateOrCreate(
                ['slug' => Str::slug($row[0])],
                [
                    'kategori_anggaran_id' => $categories[0]->id,
                    'nama' => $row[0],
                    'lokasi' => $row[1],
                    'deskripsi' => 'Monitoring proyek fisik Kampung Mbu.',
                    'anggaran' => $row[2],
                    'realisasi' => $row[3],
                    'progress' => $row[4],
                    'status' => $row[5],
                ]
            );
        }

        LaporanKeuangan::query()->updateOrCreate(
            ['judul' => 'Laporan Bulanan Mei 2024'],
            [
                'kategori' => 'Administrasi',
                'periode' => 'Mei 2024',
                'tanggal_laporan' => '2024-05-15',
                'file_path' => 'laporan/laporan-bulanan-mei-2024.pdf',
                'file_type' => 'pdf',
                'file_size' => 2400000,
                'status' => 'terverifikasi',
                'uploaded_by' => $admin->id,
            ]
        );

        LaporanWarga::query()->updateOrCreate(
            ['nomor_tiket' => 'LWK-20240522-MBU01'],
            [
                'nama_pelapor' => 'Yohanes Matuan',
                'kontak' => '0812-0000-0000',
                'kategori' => 'Proyek Pembangunan',
                'isi_laporan' => 'Mohon pembaruan dokumentasi progres pembangunan jalan tani agar warga dapat memantau realisasi pekerjaan.',
                'status' => 'baru',
            ]
        );
    }
}
