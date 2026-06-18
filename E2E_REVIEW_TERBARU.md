# E2E Review Terbaru - Profil Kampung / Dana Kampung Mbu

Tanggal pengujian: 2026-05-22 18:38:21 TST
Target utama: http://127.0.0.1:8000
Project: D:\profilkampung

## Ringkasan Eksekutif

Perbaikan sudah jauh lebih bagus dibanding temuan sebelumnya. Banyak masalah besar sudah dibereskan:

- Route publik utama sudah hidup.
- Link penting tidak lagi banyak mati.
- Endpoint PDF sudah benar-benar mengembalikan `application/pdf`.
- Login kosong sudah menampilkan validasi Bahasa Indonesia.
- Dashboard sudah diproteksi dan redirect ke login.
- Test otomatis Laravel sudah lulus.
- Console JavaScript bersih saat halaman diuji.

Tapi belum sempurna. Masih ada beberapa hal yang perlu dirapikan supaya web ini layak disebut siap publik, bukan cuma demo yang kelihatan cakep.

Total temuan terbaru: 7

- High: 1
- Medium: 4
- Low: 2

## Lingkup Pengujian

Yang diuji:

1. Runtime project dan server lokal.
2. Route publik utama.
3. Homepage.
4. Halaman transparansi.
5. PDF laporan.
6. Login admin.
7. Form laporan warga.
8. Proteksi dashboard.
9. Console JavaScript.
10. Pencarian link mati di source Blade.
11. Test otomatis Laravel.

## Hasil Verifikasi Runtime

Project aktif:

D:\profilkampung

Laravel:

Laravel Framework 12.60.2

Server yang merespons:

- http://127.0.0.1:8000 -> HTTP 200
- http://127.0.0.1:8001 -> HTTP 200

Catatan:

Port 8000 dan 8001 sama-sama menampilkan halaman `Beranda - Kampung Mbu`. Ini tidak langsung salah, tapi hati-hati. Dua server lokal aktif untuk project yang sama bisa bikin bingung saat debugging. Satu port saja cukup kalau bukan sedang testing paralel. Kalau tidak sengaja, matikan salah satunya.

## Hasil Test Otomatis Laravel

Command:

php artisan test

Hasil:

PASS
9 tests passed
24 assertions

Test yang lulus mencakup:

- Homepage bisa dibuka.
- Route publik utama tersedia.
- Filter status proyek bekerja di level feature test.
- PDF download mengembalikan response PDF.
- Login kosong menampilkan validasi Bahasa Indonesia.
- Dashboard redirect ke login.
- Laporan warga bisa disubmit di feature test.

Ini bagus. Akhirnya bukan cuma modal tampilan, ada test yang jagain fungsi. Nah gitu dong.

## Verifikasi Endpoint

| Endpoint | Status | Content-Type | Catatan |
|---|---:|---|---|
| / | 200 | text/html; charset=utf-8 | OK |
| /transparansi | 200 | text/html; charset=utf-8 | OK |
| /transparansi?status=selesai | 200 | text/html; charset=utf-8 | OK |
| /transparansi?status=berjalan | 200 | text/html; charset=utf-8 | OK |
| /transparansi?status=direncanakan | 200 | text/html; charset=utf-8 | OK |
| /berita | 200 | text/html; charset=utf-8 | OK |
| /laporan-warga | 200 | text/html; charset=utf-8 | OK |
| /login | 200 | text/html; charset=utf-8 | OK |
| /kebijakan-privasi | 200 | text/html; charset=utf-8 | OK |
| /kontak | 200 | text/html; charset=utf-8 | OK |
| /peta-situs | 200 | text/html; charset=utf-8 | OK |
| /support | 200 | text/html; charset=utf-8 | OK |
| /laporan/pdf | 200 | application/pdf | OK |
| /dashboard | 302 | text/html; charset=utf-8 | Redirect ke /login, OK |

## Yang Sudah Membaik

### 1. Route publik sudah lengkap

Route seperti `/berita`, `/transparansi`, `/laporan-warga`, `/kontak`, `/kebijakan-privasi`, `/peta-situs`, dan `/support` sudah ada dan HTTP 200.

Sebelumnya banyak link masih mati atau sekadar `#`. Sekarang jauh lebih waras.

### 2. PDF laporan sudah benar

Endpoint:

/laporan/pdf

Hasil:

HTTP 200
Content-Type: application/pdf

Ini sudah sesuai ekspektasi. Tombol PDF bukan pajangan lagi.

### 3. Login admin membaik

Submit login kosong menghasilkan pesan:

- Email wajib diisi.
- Kata sandi wajib diisi.

Bahasanya sudah Indonesia. Toggle password juga sudah punya label yang berubah:

- Tampilkan kata sandi
- Sembunyikan kata sandi

Ini perbaikan bagus dari sisi UX dan aksesibilitas.

### 4. Dashboard terlindungi

Endpoint:

/dashboard

Hasil:

302 redirect ke /login

Ini benar. Dashboard admin tidak kebuka bebas. Kalau dashboard admin kebuka tanpa login, itu bukan transparansi, itu kebocoran.

### 5. Console JavaScript bersih

Saat halaman utama, transparansi, login, dan laporan warga diuji, tidak ditemukan error JavaScript besar di console.

## Temuan Kekurangan Terbaru

### E2E-01 - Dua port server aktif bersamaan

Severity: Medium
Kategori: Environment / Operasional

Bukti:

- http://127.0.0.1:8000 -> HTTP 200
- http://127.0.0.1:8001 -> HTTP 200

Dampak:

Developer bisa salah menguji port. Nanti edit file sudah benar, tapi browser ternyata buka server lain. Ini jenis masalah yang bikin orang nyalahin Laravel padahal prosesnya sendiri dobel.

Saran:

- Pakai satu port resmi untuk development, misalnya 8000.
- Matikan server yang tidak dipakai.
- Kalau perlu, tulis di README: `php artisan serve --host=127.0.0.1 --port=8000`.

### E2E-02 - Dropdown Filter Status di homepage tidak terlihat berubah saat diklik di browser test

Severity: High
Kategori: Functional / UI Interaction

Lokasi:

Homepage `/`

Elemen:

Tombol `Filter Status`

Hasil pengujian:

Tombol ada dan markup `.dropdown-menu` juga ada. Item dropdown ditemukan di DOM:

- Semua
- Selesai
- Sedang Berjalan
- Direncanakan

Tapi saat diklik lewat browser E2E, dropdown tidak terbuka. Pemeriksaan runtime menunjukkan:

`window.bootstrap === false`

Jadi akar masalahnya bukan item dropdown hilang. Masalahnya Bootstrap JavaScript tidak aktif/ tidak termuat di halaman publik. Nah ini dia biang keroknya. Markup Bootstrap tanpa JS itu ya cuma HTML sok interaktif.

Dampak:

User bisa mengira filter tidak bekerja. Ini bahaya karena filter transparansi termasuk fungsi utama, bukan dekorasi.

Saran:

- Pastikan Bootstrap JS benar-benar dimuat di layout publik.
- Jika memakai Vite, pastikan `@vite(['resources/css/app.css', 'resources/js/app.js'])` ada di `resources/views/layouts/public.blade.php`.
- Pastikan `resources/js/app.js` mengimpor `bootstrap/dist/js/bootstrap.bundle.min.js`.
- Pastikan hasil build asset dipakai oleh halaman yang sedang berjalan.
- Tambahkan test browser untuk klik dropdown, bukan cuma feature test backend.

Catatan:

Feature test `project status filter works` lulus, jadi backend/query route kemungkinan aman. Yang rusak sekarang adalah interaksi dropdown di browser karena Bootstrap JS tidak tersedia.

### E2E-03 - Query filter transparansi menghasilkan ukuran HTML yang sama untuk semua status

Severity: Medium
Kategori: Functional / Data Filtering

Endpoint yang dicek:

- /transparansi
- /transparansi?status=selesai
- /transparansi?status=berjalan
- /transparansi?status=direncanakan

Semua mengembalikan 200 dan ukuran response sama: 7262 bytes.

Dampak:

Ini belum pasti bug, tapi mencurigakan. Kalau filter status benar-benar mengubah daftar proyek, biasanya output HTML berubah. Bisa saja data contoh kebetulan membuat ukuran sama, tapi ini jarang dan patut dicek.

Saran:

- Tambahkan indikator filter aktif di UI, misalnya `Menampilkan status: Selesai`.
- Tambahkan empty state jika tidak ada data.
- Pastikan item yang tampil benar-benar berubah sesuai query.
- Tambahkan assertion test yang mengecek konten proyek tertentu muncul/hilang, bukan cuma HTTP 200.

### E2E-04 - Form laporan warga sulit dipilih dropdown kategorinya via keyboard/browser automation

Severity: Medium
Kategori: Accessibility / Form UX

Lokasi:

/laporan-warga

Hasil pengujian:

Form tampil dengan baik. Field nama, kontak, dan isi laporan bisa diisi. Tapi pemilihan kategori melalui keyboard/browser automation tidak berhasil berubah dari `Pilih kategori` saat pengujian.

Dampak:

Kalau ini terjadi juga pada keyboard user tertentu, aksesibilitasnya kurang. Form laporan warga harus gampang dipakai, bukan bikin warga lomba klik dropdown.

Saran:

- Pastikan select kategori bisa dipilih dengan keyboard.
- Tambahkan validasi error yang jelas jika kategori belum dipilih.
- Tambahkan teks wajib seperti `Kategori wajib dipilih`.
- Pertimbangkan custom select hanya kalau aksesibilitasnya benar. Kalau custom select tapi keyboard-nya rusak, mending native select biasa saja.

### E2E-05 - File upload masih memakai teks bawaan browser Bahasa Inggris

Severity: Low
Kategori: Content / UX

Lokasi:

/laporan-warga

Tampilan:

- Choose File
- No file chosen

Dampak:

UI mayoritas Bahasa Indonesia, tapi file input masih Bahasa Inggris. Bukan fatal, tapi kelihatan kurang matang.

Saran:

Buat custom label upload:

- Pilih Berkas
- Belum ada berkas dipilih

Tetap pastikan input file asli masih accessible untuk screen reader.

### E2E-06 - Masih ada link `href="#"` di area admin

Severity: Medium
Kategori: Functional / Admin UX

Source:

resources/views/admin/funds.blade.php

Temuan:

Lihat Bukti masih mengarah ke `#`.

Dampak:

Di halaman admin, tombol bukti pembayaran/pengeluaran terlihat bisa diklik tapi tidak melakukan apa-apa. Tombol pajangan begini nyebelin dan bikin admin tidak percaya sistem.

Saran:

- Jika bukti belum tersedia, tampilkan badge `Belum ada bukti`.
- Jika tersedia, arahkan ke file bukti sebenarnya.
- Jangan gunakan `#` untuk aksi yang belum ada. Pakai disabled state atau route placeholder yang jelas.

### E2E-07 - File `welcome.blade.php` lama masih ada dengan brand Profil Kampung

Severity: Low
Kategori: Maintenance / Source Hygiene

Source:

resources/views/welcome.blade.php

Dampak:

Route utama sekarang pakai `PublicPageController@home`, jadi file ini mungkin tidak dipakai. Tapi file lama yang tidak terpakai bisa bikin bingung nanti. Kemarin sudah pernah ada mismatch tampilan vs source, jadi sampah legacy begini jangan dipelihara.

Saran:

- Hapus kalau memang tidak dipakai.
- Atau ubah supaya redirect/extend layout baru.
- Jangan biarkan view lama dengan identitas berbeda kalau app sudah pindah ke `Kampung Mbu`.

## Evidence Screenshot

Screenshot pengujian halaman laporan warga:

C:\Users\ACER\AppData\Local\hermes\cache\screenshots\browser_screenshot_810f5f3f7d864238ad8214484a71a626.png

## Rekomendasi Prioritas Berikutnya

### Prioritas 1 - Bereskan filter status secara visual dan fungsional

Ini paling penting karena transparansi adalah fitur utama.

Checklist:

- Dropdown homepage muncul saat diklik.
- Opsi filter terlihat.
- Klik status mengarah ke query yang benar.
- Halaman transparansi menunjukkan filter aktif.
- Data berubah sesuai status.
- Ada empty state.

### Prioritas 2 - Rapikan form laporan warga

Checklist:

- Select kategori nyaman dipakai.
- Required field diberi tanda.
- Error validasi kategori tampil jelas.
- File upload dilokalkan ke Bahasa Indonesia.
- Setelah submit sukses, user mendapat nomor tiket laporan.

### Prioritas 3 - Bersihkan admin dan source legacy

Checklist:

- Tidak ada `href="#"` di admin untuk aksi penting.
- View lama `welcome.blade.php` dihapus atau diselaraskan.
- Satu port development resmi dipakai.

## Kesimpulan

Perbaikan bos sudah terasa. Web ini sekarang jauh lebih layak dibanding sebelumnya. Route sudah hidup, PDF sudah bukan tombol palsu, login sudah lebih manusiawi, dan test otomatis sudah mulai ada.

Tapi bagian filter status dan form laporan warga masih perlu dipoles. Jangan sampai halaman transparansi yang harusnya jadi fitur utama malah dropdown-nya malu-malu tidak kebuka. Itu kayak bikin dashboard keuangan tapi tombol laporannya cuma hiasan. Sudah dekat, tinggal dirapikan bagian interaksi dan konsistensinya.
