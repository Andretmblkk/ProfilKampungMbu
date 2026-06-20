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

### Opsi Docker

Cara paling mudah di laptop lain adalah memakai Docker:

```bash
docker compose up --build
```

Container akan menyiapkan Laravel, MySQL, phpMyAdmin, menjalankan migration dan seeder, membuat storage link, serta build asset jika `public/build` belum tersedia.

Setelah proses selesai, buka:

```text
http://localhost:8000
```

phpMyAdmin tersedia di:

```text
http://localhost:8081
```

Login phpMyAdmin:

```text
Server: mysql
Username: root
Password: root
Database: profilkampung
```

Untuk menjalankan command Artisan di dalam container:

```bash
docker compose exec app php artisan migrate --seed
```

### Opsi Lokal Tanpa Docker

Jalankan setup otomatis:

```bash
php scripts/setup.php
```

Di Windows bisa juga jalankan:

```bat
setup.bat
```

Script setup akan membuat `.env`, membuat database SQLite jika diperlukan, generate app key, menjalankan migration dan seeder, membuat storage link, dan membersihkan cache. Repository ini menyertakan `vendor/` dan `public/build/`, jadi pengguna tidak perlu menjalankan `composer install` atau `npm install` setelah clone/pull selama file tersebut tersedia.

Jalankan server:

```bash
php artisan serve --host=127.0.0.1 --port=8000
```

Jika setup otomatis gagal karena konfigurasi database berbeda, sesuaikan `.env`, lalu jalankan ulang:

```bash
php scripts/setup.php
```

Bersihkan cache setelah perubahan route, view, config, atau Filament:

```bash
php artisan optimize:clear
```

## Membuat ZIP Siap Jalan

Untuk membuat paket ZIP yang bisa diekstrak dan dijalankan tanpa Git:

```bash
composer run package
```

Hasil ZIP dibuat di:

```text
dist/ProfilKampungMbu-ready.zip
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
