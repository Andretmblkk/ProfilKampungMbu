# Laporan Pengujian End-to-End (E2E)

Project: `D:\profilkampung`

URL diuji:

- `http://127.0.0.1:8000`
- `http://127.0.0.1:8001`

Tanggal pengujian: 2026-05-22 17:00:35 TST

Catatan penting: halaman live yang tampil saat diuji adalah aplikasi `Dana Kampung Mbu`, bukan template sederhana `Profil Kampung` yang sebelumnya pernah dibuat. Jadi ada indikasi source yang sedang diserve/cache/runtime tidak sepenuhnya sinkron dengan file Blade yang terbaca lokal. Ini perlu dicek, karena kalau source dan tampilan beda, debugging bisa jadi neraka kecil yang tidak perlu.

## Ringkasan Hasil

Status umum: aplikasi bisa dibuka dan halaman utama tampil.

Jumlah temuan: 14

Breakdown severity:

| Severity | Jumlah |
|---|---:|
| High | 3 |
| Medium | 7 |
| Low | 4 |

Area yang diuji:

- Halaman beranda
- Navigasi utama
- Halaman transparansi
- Tombol CTA utama
- Tombol filter status
- Tombol unduh PDF
- Halaman login
- Validasi login kosong dan kredensial salah
- Toggle show/hide password
- Keyboard navigation dasar
- Cek console JavaScript
- Cek atribut aksesibilitas dasar

Evidence screenshot:

- Beranda: `C:\Users\ACER\AppData\Local\hermes\cache\screenshots\browser_screenshot_4edc3a5f464f4e9ab3c2f0ad0ded1516.png`
- Login: `C:\Users\ACER\AppData\Local\hermes\cache\screenshots\browser_screenshot_606e78bde2994e33b2107e4e370dee7a.png`

## Skenario yang Berhasil

| Skenario | Hasil | Catatan |
|---|---|---|
| Buka homepage | PASS | HTTP 200, halaman tampil normal |
| Buka `/transparansi` | PASS | Halaman transparansi tampil |
| Klik CTA `Lihat Laporan Keuangan` | PASS | Mengarah ke `/transparansi` |
| Buka `/login` | PASS | Form login tampil |
| Submit login kosong | PASS sebagian | Error `The email field is required.` muncul |
| Submit login salah | PASS | Error `Email atau kata sandi tidak sesuai.` muncul |
| Toggle password visibility | PASS | Input berubah dari `password` ke `text` |
| Console JS homepage | PASS | Tidak ada error JavaScript terdeteksi |
| Console JS login | PASS | Tidak ada error JavaScript terdeteksi |

## Temuan Detail

### E2E-01 — Source lokal dan tampilan live tidak sinkron

Severity: High

Kategori: Environment / Build / Deployment

URL: `http://127.0.0.1:8000` dan `http://127.0.0.1:8001`

Deskripsi:

File lokal yang terbaca di `D:\profilkampung\resources\views\welcome.blade.php` berisi halaman `Profil Kampung` sederhana. Tapi yang muncul di browser adalah aplikasi `Dana Kampung Mbu` dengan route seperti `/transparansi`, `/login`, dan `/dashboard/laporan`.

Ini bahaya, bos. Kalau source code dan web yang jalan beda, nanti edit file A tapi browser menampilkan file B. Ujung-ujungnya nyalahin Laravel, padahal proses/runtime yang kacau.

Dampak:

- Developer bisa mengedit file yang salah.
- Bug susah dilacak.
- Hasil testing bisa tidak merepresentasikan project yang sedang diedit.
- Deploy bisa membawa versi yang tidak sesuai.

Rekomendasi:

- Cek proses server yang sedang berjalan dan pastikan document root mengarah ke `D:\profilkampung\public`.
- Jalankan `php artisan optimize:clear`.
- Matikan semua server PHP lama, lalu run ulang dari folder project yang benar.
- Pastikan tidak ada project Laravel lain yang memakai port sama.

### E2E-02 — Link `Berita` tidak menuju halaman berita

Severity: High

Kategori: Functional / Navigation

URL: Homepage

Hasil uji:

Link `Berita` memiliki href `#`, bukan route berita.

Expected:

Klik `Berita` membuka halaman berita, misalnya `/berita`.

Actual:

Klik tidak membawa user ke halaman berita. Secara UX ini tombol pajangan. Cantik boleh, tapi kalau diklik tidak ngapa-ngapain ya sama saja hiasan dashboard.

Rekomendasi:

- Buat route `/berita`.
- Buat view daftar berita.
- Ubah href navbar dari `#` ke route yang benar.
- Kalau fitur belum siap, tampilkan halaman placeholder yang jelas, bukan link mati.

### E2E-03 — Banyak footer link mati

Severity: Medium

Kategori: Functional / Navigation

URL: Homepage

Link mati yang ditemukan:

- `Kebijakan Privasi`
- `Kontak Kami`
- `Peta Situs`
- `Portal Kabupaten`

Actual:

Semua href menuju `#`.

Expected:

Masing-masing membuka halaman/informasi yang sesuai.

Rekomendasi:

- Buat route `/kebijakan-privasi`, `/kontak`, `/peta-situs`.
- Untuk `Portal Kabupaten`, arahkan ke URL resmi jika ada.
- Kalau belum ada, jangan pura-pura jadi link. Pakai teks biasa atau badge `Segera Hadir`.

### E2E-04 — Tombol `Filter Status` tidak berfungsi

Severity: High

Kategori: Functional

URL: Homepage, section `Transparansi Proyek Terbaru`

Langkah reproduksi:

1. Buka homepage.
2. Klik tombol `Filter Status`.

Actual:

Tidak muncul dropdown, modal, perubahan tabel, atau state aktif.

Expected:

Muncul filter status proyek, minimal pilihan:

- Semua
- Selesai
- Sedang Berjalan
- Direncanakan

Rekomendasi:

- Implement dropdown Bootstrap.
- Tambahkan logic filter table.
- Tambahkan empty state kalau tidak ada data.
- Tambahkan test E2E untuk setiap status.

### E2E-05 — Tombol `Unduh Laporan (PDF)` tidak mengunduh apa pun

Severity: High

Kategori: Functional / Export

URL: Homepage

Langkah reproduksi:

1. Buka homepage.
2. Klik `Unduh Laporan (PDF)`.

Actual:

Tidak ada file yang terunduh, tidak ada navigasi, tidak ada feedback.

Expected:

User mendapatkan file PDF atau minimal diarahkan ke endpoint laporan PDF.

Rekomendasi:

- Buat route download, misalnya `/laporan/pdf`.
- Response harus punya header `Content-Type: application/pdf`.
- Tambahkan nama file jelas, misalnya `laporan-dana-kampung-mbu-2024.pdf`.
- Kalau PDF belum tersedia, tampilkan toast/error yang manusiawi.

### E2E-06 — Link `Kirim Laporan Warga` masih mati

Severity: Medium

Kategori: Functional / Citizen Report

URL: Homepage CTA bawah

Actual:

Href masih `#`.

Expected:

Membuka form laporan warga atau halaman pengaduan publik.

Rekomendasi:

- Buat route `/laporan-warga`.
- Sediakan form minimal: nama, kontak, kategori, isi laporan, lampiran opsional.
- Tambahkan validasi backend dan CSRF.

### E2E-07 — Login kosong hanya menampilkan error email, password tidak ikut divalidasi jelas

Severity: Medium

Kategori: Validation / UX

URL: `/login`

Langkah reproduksi:

1. Buka `/login`.
2. Klik `Masuk ke Dashboard` tanpa isi apa pun.

Actual:

Error yang tampil: `The email field is required.`

Masalah:

- Pesan masih Bahasa Inggris.
- Password tidak ditampilkan sebagai field wajib di error yang sama.
- UI lain Bahasa Indonesia, tapi validasi pakai Inggris. Campur-campur begini bikin aplikasi kelihatan belum dipoles.

Expected:

Tampilkan pesan Bahasa Indonesia:

- `Email atau username wajib diisi.`
- `Kata sandi wajib diisi.`

Rekomendasi:

- Tambahkan validasi `password => required`.
- Tambahkan file bahasa Indonesia untuk validation messages.
- Tampilkan semua error validasi sekaligus.

### E2E-08 — Toggle password tidak punya accessible name

Severity: Medium

Kategori: Accessibility

URL: `/login`

Actual:

Tombol ikon mata bisa dipakai, tapi tidak punya `aria-label`. Screen reader akan membaca tombol kosong atau tidak jelas.

Expected:

Tombol punya label aksesibilitas:

- `Tampilkan kata sandi`
- berubah menjadi `Sembunyikan kata sandi` setelah diklik.

Rekomendasi:

- Tambahkan `aria-label` pada button toggle password.
- Update label saat state berubah.
- Pastikan bisa dipakai via keyboard.

### E2E-09 — Link `Lupa Sandi?` hanya menuju `#`

Severity: Medium

Kategori: Functional / Authentication

URL: `/login`

Actual:

Href `#`, tidak ada reset password flow.

Expected:

Membuka halaman reset password atau instruksi kontak admin.

Rekomendasi:

- Buat route `/forgot-password`.
- Kalau reset otomatis belum siap, tampilkan halaman bantuan resmi.

### E2E-10 — Link `Hubungi IT Support Kampung` hanya menuju `#`

Severity: Medium

Kategori: Functional / Support

URL: `/login`

Actual:

Href `#`.

Expected:

Membuka kontak WhatsApp/email/halaman support.

Rekomendasi:

- Isi dengan kontak nyata.
- Gunakan `mailto:` atau WhatsApp link jika memang belum ada halaman support.

### E2E-11 — Format angka dana tidak konsisten

Severity: Medium

Kategori: Content / Data Formatting

URL: Homepage

Contoh:

- `Rp 2.450.000.000`
- `Rp 1.120M`

Masalah:

Format campur antara format penuh dan singkatan `M`. Untuk halaman transparansi dana, angka harus konsisten. Ini bukan cuma kosmetik; kepercayaan user bisa turun kalau angka kelihatan asal tempel.

Rekomendasi:

- Pilih satu format.
- Untuk transparansi publik, lebih aman gunakan format penuh: `Rp 1.120.000.000`.
- Kalau mau pakai ringkas, semua harus ringkas dan diberi keterangan.

### E2E-12 — Bahasa campuran: `On-Going`

Severity: Low

Kategori: Content / Localization

URL: Homepage tabel proyek

Actual:

Status menggunakan `On-Going`.

Expected:

Gunakan Bahasa Indonesia konsisten, misalnya:

- `Sedang Berjalan`
- `Berjalan`

Rekomendasi:

- Normalisasi semua label status ke Bahasa Indonesia.

### E2E-13 — Status `Direncanakan` tetapi progress sudah 22%

Severity: Medium

Kategori: Data Consistency

URL: Homepage tabel proyek

Actual:

Proyek `Pengadaan Bibit Pertanian Utama` punya progress `22% Selesai`, tapi status `Direncanakan`.

Masalah:

Kalau sudah 22%, berarti bukan lagi direncanakan. Ini data logic-nya tabrakan sendiri.

Expected:

- Jika status `Direncanakan`, progress seharusnya 0% atau belum mulai.
- Jika progress 22%, status seharusnya `Sedang Berjalan`.

Rekomendasi:

- Buat mapping status otomatis berdasarkan progress.
- Validasi data sebelum ditampilkan.

### E2E-14 — Button tanpa explicit type berpotensi submit tidak sengaja

Severity: Low

Kategori: HTML Semantics

URL: Homepage

Actual:

Tombol `Filter Status` dan `Unduh Laporan (PDF)` terbaca sebagai button type `submit`.

Masalah:

Kalau nanti tombol ini masuk ke dalam form, dia bisa submit form tidak sengaja. Bug model begini sering muncul belakangan dan bikin developer nanya “kok form ke-submit sendiri?” Ya karena tombolnya dibiarkan default, bos.

Expected:

Tombol non-submit harus pakai:

```html
<button type="button">...</button>
```

Rekomendasi:

- Tambahkan `type="button"` untuk button yang bukan submit form.

## Catatan Aksesibilitas

Temuan utama:

- Toggle password tidak punya `aria-label`.
- Beberapa link hanya `#`, membuat keyboard user masuk ke elemen yang tidak berguna.
- Perlu cek kontras warna teks kecil di halaman login.
- Perlu pastikan focus outline terlihat jelas di semua elemen.
- Placeholder email terlihat seperti data terisi; bedakan warnanya atau tambahkan helper text.

## Catatan Responsiveness

Desktop terlihat cukup rapi.

Yang masih perlu diuji khusus mobile/tablet:

- Navbar apakah berubah jadi hamburger dengan benar.
- Tabel proyek apakah overflow atau perlu mode card.
- CTA button apakah stack rapi di layar kecil.
- Login card apakah tetap pas pada tinggi layar kecil.

## Prioritas Perbaikan

Prioritas 1:

1. Pastikan source code dan halaman live sinkron.
2. Implement fungsi `Filter Status`.
3. Implement download PDF.
4. Buat route/link nyata untuk `Berita`, footer, laporan warga, lupa sandi, dan support.

Prioritas 2:

1. Rapikan validasi login Bahasa Indonesia.
2. Tambahkan accessible label untuk toggle password.
3. Konsistenkan format nominal rupiah.
4. Perbaiki status/progress proyek.

Prioritas 3:

1. Uji mobile/tablet.
2. Tambahkan test otomatis Playwright/Pest Browser kalau project sudah mulai serius.
3. Tambahkan halaman 404 custom yang lebih ramah.

## Rekomendasi E2E Otomatis Berikutnya

Kalau mau dibuat test otomatis, minimal skenario Playwright:

1. Homepage loads and has main heading.
2. Navbar `Transparansi` opens `/transparansi`.
3. Navbar `Masuk` opens `/login`.
4. Link mati tidak boleh ada di navbar/footer.
5. Login empty shows Indonesian required errors.
6. Invalid login shows error and stays on `/login`.
7. Password toggle changes input type.
8. Filter status changes table rows.
9. Download PDF returns `application/pdf`.
10. Protected `/dashboard/laporan` redirects guest to `/login`.

## Kesimpulan

Web sudah tampil dan basic flow utamanya jalan, tapi masih banyak elemen yang sifatnya pajangan: link `#`, tombol filter tidak bekerja, PDF tidak benar-benar download, dan flow support/reset password belum ada. Secara visual lumayan, tapi secara E2E masih terasa prototype.

Yang paling wajib dibenerin dulu bukan warna atau card cantik, tapi fungsi. UI bagus tanpa fungsi itu cuma brosur interaktif yang kebetulan dibuka lewat browser.
