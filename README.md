# Sistem Informasi Transparansi Dana Kampung Mbu

Aplikasi Laravel untuk transparansi dana, proyek pembangunan, laporan keuangan, laporan warga, dan admin panel Kampung Mbu.

## Stack

- Laravel 12
- Filament v3
- Bootstrap 5 + Blade
- Alpine.js
- MySQL/SQLite

## Menjalankan Project

Gunakan folder project ini sebagai sumber tunggal:

```bash
cd D:\profilkampung
```

Bersihkan cache setelah perubahan route, view, config, atau Filament:

```bash
php artisan optimize:clear
```

Install dependency dan build asset:

```bash
composer install
npm install
npm run build
```

Migrasi dan seed data contoh:

```bash
php artisan migrate --force
php artisan db:seed --force
```

Jalankan server:

```bash
php artisan serve --host=127.0.0.1 --port=8000
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
