# Sistem Informasi Transparansi Dana Kampung Mbu

Aplikasi Laravel untuk transparansi dana, proyek pembangunan, laporan keuangan, laporan warga, dan admin panel Kampung Mbu.

## Stack

- Laravel 12
- Filament v3
- Bootstrap 5 + Blade
- Alpine.js
- MySQL

## Instalasi dan Menjalankan Project

Project ini disiapkan untuk berjalan paling mudah memakai Docker Desktop. Docker akan menjalankan 3 service:

- `app`: aplikasi Laravel di port `8000`
- `mysql`: database MySQL di port `3306`
- `phpmyadmin`: phpMyAdmin di port `8081`

Pastikan Docker Desktop sudah terbuka dan statusnya `Engine running`.

### A. Untuk Laptop yang Baru Clone

```bash
git clone https://github.com/Andretmblkk/ProfilKampungMbu.git
cd ProfilKampungMbu
```

```bash
docker compose up -d --build
```

Tunggu sampai semua container selesai dibuat. Pada proses pertama, Docker akan menyiapkan Laravel, MySQL, phpMyAdmin, menjalankan migration dan seeder, membuat storage link, serta build asset jika `public/build` belum tersedia.

Buka aplikasi:

```text
http://localhost:8000
```

Buka phpMyAdmin:

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

Login admin aplikasi:

```text
URL: http://localhost:8000/admin
Email: admin@kampungmbu.go.id
Password: password
```

### B. Untuk Laptop yang Sudah Pernah Clone

Masuk ke folder project yang sudah ada:

```bash
cd ProfilKampungMbu
```

Ambil update terbaru dari GitHub:

```bash
git pull origin main
```

Kalau sebelumnya container lama masih jalan, hentikan dulu:

```bash
docker compose down
```

Jalankan ulang dengan build agar perubahan Docker/MySQL/phpMyAdmin ikut terpakai:

```bash
docker compose up -d --build
```

Setelah itu buka lagi:

```text
http://localhost:8000
http://localhost:8081
```

Jika `git pull` gagal karena ada perubahan lokal, jangan langsung hapus file. Simpan atau commit dulu perubahan lokalnya, lalu ulangi `git pull`.

### C. Menjalankan Project Setelah Setup Pertama

Kalau Docker image dan container sudah pernah dibuat, cukup jalankan:

```bash
docker compose up -d
```

Di Windows bisa juga double-click:

```text
jalan-docker.bat
```

File tersebut menjalankan Docker lalu membuka aplikasi di browser.

Untuk membuka aplikasi tanpa mengetik URL:

```text
buka-web.bat
```

Untuk membuka phpMyAdmin tanpa mengetik URL:

```text
buka-phpmyadmin.bat
```

Untuk mematikan semua container:

```bash
docker compose down
```

Di Windows bisa juga double-click:

```text
stop-docker.bat
```

Gunakan `--build` lagi kalau ada perubahan di `Dockerfile`, `docker-compose.yml`, dependency, atau setelah menarik update besar dari GitHub:

```bash
docker compose up -d --build
```

### D. Command Laravel di Dalam Docker

Menjalankan migration dan seeder:

```bash
docker compose exec app php artisan migrate --seed
```

Membersihkan cache Laravel:

```bash
docker compose exec app php artisan optimize:clear
```

Masuk ke shell container Laravel:

```bash
docker compose exec app sh
```

### E. Cara Pull dan Push Update ke GitHub

Sebelum mulai kerja, ambil update terbaru:

```bash
git pull origin main
```

Setelah mengubah file, cek status:

```bash
git status
```

Tambahkan file yang ingin dikirim:

```bash
git add .
```

Buat commit:

```bash
git commit -m "Tulis pesan perubahan"
```

Kirim ke GitHub:

```bash
git push origin main
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

Script setup akan membuat `.env`, generate app key, menjalankan migration dan seeder, membuat storage link, dan membersihkan cache. Untuk penggunaan normal, gunakan Docker agar database MySQL dan phpMyAdmin otomatis tersedia.

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
