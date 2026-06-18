# Sistem Informasi Transparansi Dana Kampung Mbu

Aplikasi Laravel untuk transparansi dana, proyek pembangunan, laporan keuangan, laporan warga, dan admin panel Kampung Mbu.

## Stack

- Laravel 12
- Filament v3
- Bootstrap 5 + Blade
- Alpine.js
- MySQL/SQLite

## Menjalankan Project

Setelah clone repository, masuk ke folder project:

```bash
git clone https://github.com/Andretmblkk/ProfilKampungMbu.git
cd ProfilKampungMbu
```

Jalankan setup otomatis:

```bash
composer run setup
```

Script setup akan membuat `.env`, membuat database SQLite jika diperlukan, install dependency, generate app key, menjalankan migration dan seeder, membuat storage link, build asset, dan membersihkan cache.

Jalankan server:

```bash
php artisan serve --host=127.0.0.1 --port=8000
```

Jika setup otomatis gagal karena konfigurasi database berbeda, sesuaikan `.env`, lalu jalankan ulang:

```bash
composer run setup
```

Bersihkan cache setelah perubahan route, view, config, atau Filament:

```bash
php artisan optimize:clear
```

Jika pernah menjalankan server lama di port lain, hentikan proses PHP lama terlebih dahulu agar tampilan browser sinkron dengan source lokal.

## URL Penting

- Beranda: `http://127.0.0.1:8000`
- Transparansi Publik: `http://127.0.0.1:8000/transparansi`
- Berita: `http://127.0.0.1:8000/berita`
- Laporan Warga: `http://127.0.0.1:8000/laporan-warga`
- Download PDF: `http://127.0.0.1:8000/laporan/pdf`
- Login custom: `http://127.0.0.1:8000/login`
- Filament Admin: `http://127.0.0.1:8000/admin`
- Dashboard UI preview: `http://127.0.0.1:8000/dashboard`

## Akun Admin Contoh

- Email: `admin@kampungmbu.go.id`
- Password: `password`

## Modul Filament

- Dana Masuk
- Pengeluaran
- Proyek Kampung
- Laporan Keuangan
- Kategori Anggaran
- Laporan Warga
- Manajemen Pengguna

## Pengujian

```bash
php artisan test
```

Test mencakup akses halaman publik, filter proyek, download PDF, validasi login Bahasa Indonesia, proteksi dashboard, dan penyimpanan laporan warga.
# ProfilKampungMbu
