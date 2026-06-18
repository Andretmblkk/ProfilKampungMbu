<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Profil Kampung') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-success shadow-sm sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">Profil Kampung</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mainNavbar">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link active" href="#beranda">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link" href="#profil">Profil</a></li>
                    <li class="nav-item"><a class="nav-link" href="#potensi">Potensi</a></li>
                    <li class="nav-item"><a class="nav-link" href="#kontak">Kontak</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <main id="beranda">
        <section class="hero-section d-flex align-items-center">
            <div class="container py-5">
                <div class="row align-items-center">
                    <div class="col-lg-7">
                        <span class="badge text-bg-light text-success mb-3 px-3 py-2">Laravel 12 + Bootstrap 5</span>
                        <h1 class="display-4 fw-bold mb-3">Sistem Informasi Profil Kampung</h1>
                        <p class="lead mb-4">Template awal untuk menampilkan identitas kampung, data wilayah, potensi desa, layanan masyarakat, dan informasi kontak.</p>
                        <div class="d-flex gap-2 flex-wrap">
                            <a href="#profil" class="btn btn-light btn-lg">Lihat Profil</a>
                            <a href="#potensi" class="btn btn-outline-light btn-lg">Potensi Kampung</a>
                        </div>
                    </div>
                    <div class="col-lg-5 mt-5 mt-lg-0">
                        <div class="card border-0 shadow-lg">
                            <div class="card-body p-4">
                                <h2 class="h4 text-success fw-bold">Ringkasan Kampung</h2>
                                <div class="row g-3 mt-2">
                                    <div class="col-6">
                                        <div class="p-3 bg-light rounded text-center">
                                            <div class="h3 fw-bold text-success mb-0">12</div>
                                            <small>RT/RW</small>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="p-3 bg-light rounded text-center">
                                            <div class="h3 fw-bold text-success mb-0">1.250</div>
                                            <small>Penduduk</small>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="p-3 bg-light rounded text-center">
                                            <div class="h3 fw-bold text-success mb-0">8</div>
                                            <small>Dusun</small>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="p-3 bg-light rounded text-center">
                                            <div class="h3 fw-bold text-success mb-0">24</div>
                                            <small>UMKM</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="profil" class="py-5 bg-white">
            <div class="container">
                <div class="row align-items-center g-4">
                    <div class="col-lg-6">
                        <h2 class="section-title fw-bold mb-3">Profil Singkat</h2>
                        <p class="text-secondary">Halaman ini masih template awal. Nanti data kampung bisa dipindahkan ke database dan dikelola lewat admin panel, bukan diedit manual di Blade terus sampai mata perih.</p>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item px-0">Nama kampung, sejarah, visi dan misi.</li>
                            <li class="list-group-item px-0">Data wilayah, penduduk, fasilitas umum.</li>
                            <li class="list-group-item px-0">Informasi layanan administrasi masyarakat.</li>
                        </ul>
                    </div>
                    <div class="col-lg-6">
                        <div class="alert alert-success shadow-sm mb-0">
                            <h3 class="h5 fw-bold">Status Project</h3>
                            <p class="mb-0">Project sudah memakai Bootstrap via Vite, bukan Tailwind bawaan Laravel. Jadi styling berikutnya tinggal pakai class Bootstrap yang manusiawi.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="potensi" class="py-5">
            <div class="container">
                <div class="text-center mb-4">
                    <h2 class="section-title fw-bold">Potensi Kampung</h2>
                    <p class="text-secondary">Contoh blok konten awal. Nanti tinggal ganti dengan data asli.</p>
                </div>
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="card h-100 border-0 shadow-sm feature-card">
                            <div class="card-body">
                                <h3 class="h5 fw-bold text-success">Pertanian</h3>
                                <p class="text-secondary mb-0">Informasi komoditas unggulan dan kelompok tani.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card h-100 border-0 shadow-sm feature-card">
                            <div class="card-body">
                                <h3 class="h5 fw-bold text-success">UMKM</h3>
                                <p class="text-secondary mb-0">Daftar usaha lokal, produk, dan kontak pelaku usaha.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card h-100 border-0 shadow-sm feature-card">
                            <div class="card-body">
                                <h3 class="h5 fw-bold text-success">Wisata</h3>
                                <p class="text-secondary mb-0">Objek wisata, budaya lokal, dan agenda kampung.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer id="kontak" class="bg-dark text-white py-4">
        <div class="container d-flex flex-column flex-md-row justify-content-between gap-2">
            <div>&copy; {{ date('Y') }} Profil Kampung</div>
            <div class="text-white-50">Dibangun dengan Laravel 12 dan Bootstrap 5</div>
        </div>
    </footer>
</body>
</html>
