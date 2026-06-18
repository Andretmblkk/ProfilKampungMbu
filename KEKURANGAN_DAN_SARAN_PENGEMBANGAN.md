# Kekurangan dan Saran Pengembangan Web Profil Kampung

Project: `D:\profilkampung`

Dokumen ini merangkum kekurangan utama dari hasil pengujian web serta saran pengembangan agar aplikasi lebih layak dipakai, bukan cuma tampil bagus tapi tombolnya cosplay jadi fitur.

## Ringkasan Kondisi Saat Ini

Secara umum web sudah bisa dibuka dan tampilan awal sudah terbentuk. Halaman utama, transparansi, dan login dapat diakses. Namun masih ada beberapa bagian yang belum matang dari sisi fungsi, konsistensi data, aksesibilitas, dan kesiapan produksi.

Masalah paling penting saat ini:

1. Beberapa tombol dan link masih belum punya fungsi nyata.
2. Fitur filter dan unduh PDF belum berjalan.
3. Validasi login belum rapi dan belum konsisten Bahasa Indonesia.
4. Ada indikasi source lokal dan tampilan live pernah tidak sinkron.
5. Format data dan status proyek masih belum konsisten.
6. Aksesibilitas dasar masih perlu dibenahi.

## Kekurangan Utama

### 1. Source lokal dan tampilan live pernah tidak sinkron

Kekurangan:

File lokal yang dicek menunjukkan template `Profil Kampung`, tetapi halaman live yang tampil saat E2E adalah `Dana Kampung Mbu`. Ini berbahaya karena developer bisa mengedit file yang salah atau menjalankan server dari folder yang salah.

Dampak:

- Perubahan kode bisa tidak muncul di browser.
- Debugging jadi membingungkan.
- Risiko deploy versi yang salah.
- Testing bisa menguji aplikasi yang bukan versi terbaru.

Saran pengembangan:

- Pastikan server dijalankan dari folder `D:\profilkampung`.
- Matikan semua server PHP lama sebelum menjalankan ulang.
- Jalankan `php artisan optimize:clear` setelah perubahan route/view/config.
- Buat dokumentasi cara run project yang jelas di README.
- Gunakan satu port default, misalnya `8000`, agar tidak bingung.

Prioritas: Tinggi

### 2. Link Berita belum berfungsi

Kekurangan:

Menu atau link `Berita` masih mengarah ke `#`, sehingga ketika diklik tidak membuka halaman apa pun.

Dampak:

- User mengira web rusak.
- Navigasi terasa belum selesai.
- Website tampak seperti prototype.

Saran pengembangan:

- Buat route `/berita`.
- Buat halaman daftar berita.
- Tambahkan detail berita dengan route seperti `/berita/{slug}`.
- Jika fitur belum siap, tampilkan halaman `Segera Hadir`, bukan link mati.

Prioritas: Tinggi

### 3. Banyak link footer masih mati

Kekurangan:

Beberapa link footer masih memakai `#`, misalnya:

- Kebijakan Privasi
- Kontak Kami
- Peta Situs
- Portal Kabupaten

Dampak:

- Footer terlihat formal tapi tidak berguna.
- User tidak bisa menemukan informasi penting.
- Kredibilitas web turun.

Saran pengembangan:

- Buat halaman `/kebijakan-privasi`.
- Buat halaman `/kontak`.
- Buat halaman `/peta-situs`.
- Untuk Portal Kabupaten, arahkan ke link resmi jika tersedia.
- Kalau belum ada kontennya, jangan jadikan link aktif dulu.

Prioritas: Sedang

### 4. Tombol Filter Status belum bekerja

Kekurangan:

Tombol `Filter Status` tidak menampilkan dropdown, modal, atau perubahan data apa pun.

Dampak:

- User tidak bisa menyaring proyek berdasarkan status.
- Fitur transparansi menjadi kurang berguna.
- Tombol terlihat seperti pajangan. Cantik sih, tapi ya percuma kalau diklik diam saja.

Saran pengembangan:

- Buat dropdown Bootstrap berisi:
  - Semua
  - Selesai
  - Sedang Berjalan
  - Direncanakan
- Tambahkan logic filter pada tabel proyek.
- Tambahkan empty state jika tidak ada proyek pada status tertentu.
- Simpan filter di query string, misalnya `/transparansi?status=selesai`.
- Tambahkan test agar filter tidak rusak diam-diam.

Prioritas: Tinggi

### 5. Tombol Unduh Laporan PDF belum berfungsi

Kekurangan:

Tombol `Unduh Laporan (PDF)` tidak mengunduh file, tidak berpindah halaman, dan tidak memberi feedback.

Dampak:

- User mengira fitur download rusak.
- Transparansi data belum benar-benar bisa dipakai.
- Admin/pengunjung tidak bisa menyimpan laporan resmi.

Saran pengembangan:

- Buat route `/laporan/pdf`.
- Generate PDF menggunakan library Laravel seperti DomPDF atau Snappy.
- Pastikan response memiliki header `Content-Type: application/pdf`.
- Gunakan nama file jelas, misalnya `laporan-dana-kampung-2024.pdf`.
- Jika PDF belum tersedia, tampilkan pesan error yang jelas.

Prioritas: Tinggi

### 6. Link Kirim Laporan Warga belum tersedia

Kekurangan:

CTA `Kirim Laporan Warga` masih belum mengarah ke form laporan.

Dampak:

- Warga tidak bisa mengirim pengaduan/laporan.
- Web kehilangan fungsi interaksi publik.

Saran pengembangan:

- Buat route `/laporan-warga`.
- Buat form berisi:
  - Nama pelapor
  - Nomor kontak
  - Kategori laporan
  - Isi laporan
  - Lampiran opsional
- Tambahkan validasi backend.
- Simpan laporan ke database.
- Tambahkan status laporan: baru, diproses, selesai, ditolak.
- Tambahkan notifikasi ke admin.

Prioritas: Sedang

### 7. Validasi login belum rapi

Kekurangan:

Saat form login kosong dikirim, pesan validasi masih Bahasa Inggris seperti `The email field is required.` dan validasi password belum ditampilkan dengan jelas.

Dampak:

- UI Bahasa Indonesia tapi error Bahasa Inggris, jadinya tidak konsisten.
- User awam bisa bingung.
- Aplikasi terlihat belum dipoles.

Saran pengembangan:

- Gunakan pesan validasi Bahasa Indonesia.
- Tampilkan semua error sekaligus, bukan satu-satu secara membingungkan.
- Validasi minimal:
  - Email wajib diisi.
  - Format email harus valid.
  - Kata sandi wajib diisi.
- Tambahkan highlight field yang error.
- Tambahkan helper text di bawah input.

Prioritas: Sedang

### 8. Toggle password belum ramah aksesibilitas

Kekurangan:

Tombol show/hide password bisa bekerja, tetapi belum punya label aksesibilitas seperti `aria-label`.

Dampak:

- Screen reader tidak bisa menjelaskan fungsi tombol dengan baik.
- Pengguna keyboard atau alat bantu bisa kesulitan.

Saran pengembangan:

- Tambahkan `aria-label="Tampilkan kata sandi"`.
- Setelah diklik, ubah menjadi `aria-label="Sembunyikan kata sandi"`.
- Pastikan tombol bisa difokuskan dengan keyboard.
- Pastikan ada indikator focus yang terlihat.

Prioritas: Sedang

### 9. Fitur Lupa Sandi belum tersedia

Kekurangan:

Link `Lupa Sandi?` masih mengarah ke `#`.

Dampak:

- User yang lupa password tidak punya jalan pemulihan.
- Admin harus menangani reset manual.

Saran pengembangan:

- Buat route `/forgot-password`.
- Jika reset email belum siap, tampilkan instruksi menghubungi admin.
- Jika memakai Laravel auth lengkap, aktifkan fitur password reset.
- Tambahkan proteksi rate limit agar tidak disalahgunakan.

Prioritas: Sedang

### 10. Link IT Support belum nyata

Kekurangan:

Link `Hubungi IT Support Kampung` masih memakai `#`.

Dampak:

- User tidak tahu harus menghubungi siapa saat login bermasalah.
- Flow bantuan tidak lengkap.

Saran pengembangan:

- Gunakan link WhatsApp resmi.
- Atau gunakan `mailto:` ke email admin.
- Buat halaman `/support` berisi kontak dan jam layanan.

Prioritas: Sedang

### 11. Format nominal dana belum konsisten

Kekurangan:

Format nominal bercampur antara format penuh dan singkatan, contohnya:

- `Rp 2.450.000.000`
- `Rp 1.120M`

Dampak:

- Data terlihat tidak profesional.
- Pengunjung bisa salah memahami nominal.
- Untuk aplikasi transparansi dana, ini cukup fatal secara kepercayaan.

Saran pengembangan:

- Gunakan format Rupiah penuh untuk semua nominal.
- Buat helper format uang agar konsisten.
- Hindari singkatan `M` kecuali ada legenda yang jelas.
- Pastikan semua nominal berasal dari data yang sama, bukan hardcode campur aduk.

Prioritas: Sedang

### 12. Bahasa status proyek belum konsisten

Kekurangan:

Ada status seperti `On-Going`, sedangkan UI lainnya memakai Bahasa Indonesia.

Dampak:

- Tampilan terasa campur-campur.
- Kualitas UI terlihat kurang rapi.

Saran pengembangan:

- Ganti `On-Going` menjadi `Sedang Berjalan`.
- Gunakan daftar status baku:
  - Direncanakan
  - Sedang Berjalan
  - Selesai
  - Dibatalkan
- Simpan status sebagai enum atau konstanta agar tidak typo.

Prioritas: Rendah sampai Sedang

### 13. Status dan progress proyek tidak sinkron

Kekurangan:

Ada proyek dengan progress 22% tetapi statusnya `Direncanakan`. Logic-nya tabrakan. Kalau sudah 22%, ya bukan rencana lagi, bos.

Dampak:

- Data terlihat tidak valid.
- User bisa kehilangan kepercayaan pada informasi proyek.
- Admin sulit mempertanggungjawabkan laporan.

Saran pengembangan:

- Buat aturan status berdasarkan progress:
  - 0% = Direncanakan
  - 1% sampai 99% = Sedang Berjalan
  - 100% = Selesai
- Atau jika status diinput manual, tambahkan validasi agar tidak bertentangan.
- Tampilkan tanggal mulai dan tanggal selesai agar status lebih jelas.

Prioritas: Sedang

### 14. Button belum memakai type eksplisit

Kekurangan:

Beberapa tombol non-submit belum memakai `type="button"`.

Dampak:

Jika tombol masuk ke dalam form, browser bisa menganggapnya sebagai submit. Ini bug klasik HTML yang kelihatannya receh sampai form tiba-tiba submit sendiri dan semua orang pura-pura kaget.

Saran pengembangan:

- Tambahkan `type="button"` untuk tombol yang bukan submit.
- Gunakan `type="submit"` hanya untuk tombol submit form.
- Audit semua button di Blade component/view.

Prioritas: Rendah

## Saran Pengembangan Fitur

### 1. Dashboard Admin

Tambahkan dashboard admin untuk mengelola:

- Data profil kampung
- Berita
- Proyek pembangunan
- Laporan dana
- Laporan warga
- Pengguna/admin

Saran teknis:

- Gunakan authentication Laravel.
- Gunakan middleware `auth` untuk dashboard.
- Pisahkan route publik dan route admin.

### 2. Manajemen Berita

Fitur yang disarankan:

- CRUD berita
- Upload gambar utama
- Slug otomatis
- Status draft/publish
- Tanggal publikasi

Manfaat:

Web tidak cuma halaman statis, tapi bisa dipakai update informasi kampung.

### 3. Transparansi Dana Dinamis

Fitur yang disarankan:

- Input anggaran tahunan
- Kategori belanja
- Realisasi anggaran
- Progress fisik proyek
- Upload dokumen pendukung
- Export PDF dan Excel

Manfaat:

Halaman transparansi jadi benar-benar berguna, bukan tabel pajangan.

### 4. Laporan Warga

Fitur yang disarankan:

- Form laporan warga
- Nomor tiket laporan
- Status tindak lanjut
- Upload foto bukti
- Riwayat laporan
- Admin dapat memberi tanggapan

Manfaat:

Warga punya kanal resmi untuk pengaduan atau masukan.

### 5. Pencarian dan Filter

Tambahkan fitur:

- Search berita
- Filter proyek berdasarkan status
- Filter laporan berdasarkan tahun
- Filter dana berdasarkan kategori

Manfaat:

Data makin mudah dicari. Jangan sampai user harus scroll seperti baca kitab kuning digital.

### 6. Responsiveness Mobile

Perlu pengujian khusus untuk:

- Navbar mobile
- Tabel proyek di layar kecil
- Card statistik
- Form login
- CTA button

Saran:

- Untuk tabel di mobile, ubah menjadi card list.
- Pastikan button tidak terlalu kecil.
- Pastikan spacing antar elemen tidak mepet.

### 7. Aksesibilitas

Perbaikan yang disarankan:

- Tambahkan `aria-label` pada tombol ikon.
- Pastikan focus outline terlihat.
- Gunakan heading berurutan.
- Pastikan kontras warna cukup.
- Hindari link kosong `#`.

### 8. Testing Otomatis

Tambahkan pengujian otomatis agar bug tidak balik lagi kayak hantu.

Minimal test:

- Homepage bisa dibuka.
- Link utama mengarah ke route yang benar.
- Tidak ada link `#` di navbar/footer penting.
- Login kosong menampilkan error Bahasa Indonesia.
- Toggle password bekerja.
- Filter status bekerja.
- Download PDF mengembalikan file PDF.
- Route dashboard dilindungi auth.

Tools yang bisa dipakai:

- PHPUnit/Pest untuk backend.
- Laravel Dusk atau Playwright untuk E2E.

## Prioritas Pengerjaan

### Prioritas 1 - Wajib dibenahi dulu

1. Pastikan server dan source code sinkron.
2. Perbaiki semua link mati pada navigasi utama.
3. Buat fungsi filter status.
4. Buat fungsi download PDF.
5. Perbaiki validasi login Bahasa Indonesia.

### Prioritas 2 - Penting untuk kelayakan aplikasi

1. Buat halaman berita.
2. Buat form laporan warga.
3. Buat halaman kontak/support.
4. Rapikan format nominal Rupiah.
5. Sinkronkan status dan progress proyek.

### Prioritas 3 - Penyempurnaan

1. Perbaiki aksesibilitas.
2. Uji mobile/tablet.
3. Tambahkan dashboard admin.
4. Tambahkan test otomatis.
5. Buat halaman 404 custom.

## Rekomendasi Struktur Route

Route publik yang disarankan:

- `/` untuk beranda
- `/profil` untuk profil kampung
- `/berita` untuk daftar berita
- `/berita/{slug}` untuk detail berita
- `/transparansi` untuk transparansi dana/proyek
- `/laporan/pdf` untuk download PDF
- `/laporan-warga` untuk form laporan warga
- `/kontak` untuk kontak
- `/kebijakan-privasi` untuk kebijakan privasi
- `/login` untuk login admin

Route admin yang disarankan:

- `/dashboard`
- `/dashboard/berita`
- `/dashboard/proyek`
- `/dashboard/anggaran`
- `/dashboard/laporan-warga`
- `/dashboard/pengaturan`

## Kesimpulan

Web ini sudah punya dasar tampilan yang lumayan, tetapi dari sisi fungsi masih banyak yang perlu dibereskan. Masalah terbesar bukan warna, card, atau animasi. Masalah terbesar adalah beberapa elemen penting masih belum benar-benar bekerja.

Saran saya: jangan dulu sibuk mempercantik UI. Rapikan fungsi inti dulu: link hidup, filter jalan, PDF bisa diunduh, login validasinya benar, dan data konsisten. UI cantik tanpa fungsi itu cuma brosur digital yang kebetulan pakai Laravel.
