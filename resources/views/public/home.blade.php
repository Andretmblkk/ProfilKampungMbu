@extends('layouts.public')

@section('title', 'Beranda - Kampung Mbu')

@section('content')
<section class="hero public-hero">
    <div class="hero-copy">
        <span class="eyebrow"><i class="fa-regular fa-circle-check"></i> Pemerintahan Terbuka</span>
        <h1>Transparansi Dana untuk Kemajuan Kampung Mbu</h1>
        <p>Akses informasi penggunaan dana desa secara real-time. Kami berkomitmen mewujudkan tata kelola keuangan yang jujur, akuntabel, dan partisipatif.</p>
        <div class="hero-actions"><a class="btn btn-primary" href="{{ route('transparency') }}">Lihat Laporan Keuangan</a><a class="btn btn-light" href="#tentang">Tentang Kami</a></div>
    </div>
    <img class="hero-image" alt="Kampung pegunungan" src="https://images.unsplash.com/photo-1500534314209-a25ddb2bd429?auto=format&fit=crop&w=1400&q=80">
</section>

<section class="stats-band">
    <x-stat-card icon="fa-money-bill-trend-up" label="Total Dana Masuk (2024)" value="Rp 2.450.000.000" meta="+12% vs Tahun Lalu" tone="blue" />
    <x-stat-card icon="fa-receipt" label="Total Pengeluaran" value="Rp 1.120.000.000" meta="45.7% Dana Terserap" tone="gold" />
    <x-stat-card icon="fa-screwdriver-wrench" label="Proyek Berjalan" value="14" meta="6 Selesai, 8 Sedang Pengerjaan" tone="indigo" />
</section>

<section id="tentang" class="about-grid">
    <div class="photo-stack">
        <img src="https://images.unsplash.com/photo-1518005020951-eccb494ad742?auto=format&fit=crop&w=800&q=80" alt="Balai kampung">
        <div class="mini-card primary">850+<span>Kepala Keluarga Sejahtera</span></div>
        <div class="mini-card">12<span>Penghargaan Nasional</span></div>
        <img src="https://images.unsplash.com/photo-1500382017468-9049fed747ef?auto=format&fit=crop&w=800&q=80" alt="Persawahan">
    </div>
    <div class="about-copy">
        <span class="section-kicker">Tentang Kampung Kami</span>
        <h2>Membangun Masa Depan Dari Akar Tradisi</h2>
        <p>Kampung Mbu bukan sekadar titik di peta, melainkan komunitas yang dinamis dengan sejarah panjang kemandirian. Kami percaya pembangunan transparan adalah kunci menjaga kepercayaan warga.</p>
        <p><i class="fa-regular fa-circle-check"></i> <strong>Visi Keberlanjutan:</strong> fokus pada infrastruktur ramah lingkungan dan ekonomi sirkular desa.</p>
        <p><i class="fa-regular fa-circle-check"></i> <strong>Digitalisasi Layanan:</strong> seluruh administrasi desa dapat diakses warga.</p>
        <a href="{{ route('transparency') }}">Pelajari Profil Desa <i class="fa-solid fa-arrow-right"></i></a>
    </div>
</section>

<section class="section-block">
    <div class="section-head">
        <div><h2>Transparansi Proyek Terbaru</h2><p>Daftar penggunaan anggaran untuk pembangunan fisik dan sosial.</p></div>
        <div class="d-flex gap-2 flex-wrap">
            <div class="dropdown">
                <button type="button" class="btn btn-light dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-solid fa-filter"></i> Filter Status</button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item {{ $selectedStatus === 'semua' ? 'active' : '' }}" href="{{ route('home') }}#proyek">Semua</a></li>
                    <li><a class="dropdown-item {{ $selectedStatus === 'selesai' ? 'active' : '' }}" href="{{ route('home', ['status' => 'selesai']) }}#proyek">Selesai</a></li>
                    <li><a class="dropdown-item {{ $selectedStatus === 'sedang-berjalan' ? 'active' : '' }}" href="{{ route('home', ['status' => 'sedang-berjalan']) }}#proyek">Sedang Berjalan</a></li>
                    <li><a class="dropdown-item {{ $selectedStatus === 'direncanakan' ? 'active' : '' }}" href="{{ route('home', ['status' => 'direncanakan']) }}#proyek">Direncanakan</a></li>
                </ul>
            </div>
            <a class="btn btn-primary" href="{{ route('reports.pdf') }}">Unduh Laporan (PDF)</a>
        </div>
    </div>
    <div id="proyek" class="table-card">
        <table class="modern-table">
            <thead><tr><th>Nama Proyek</th><th>Anggaran</th><th>Kategori</th><th>Progress</th><th>Status</th></tr></thead>
            <tbody>
            @forelse($projects as $project)
                <tr>
                    <td><strong>{{ $project['name'] }}</strong><span>Update: {{ $loop->first ? '2 hari yang lalu' : 'Baru saja' }}</span></td>
                    <td><strong>{{ $project['budget'] }}</strong></td>
                    <td><span class="badge-soft">{{ $project['category'] }}</span></td>
                    <td><div class="progress-line"><span style="width: {{ $project['progress'] }}%"></span></div>{{ $project['progress'] }}% Selesai</td>
                    <td><span class="status-pill">{{ $project['status'] }}</span></td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center py-5">Tidak ada proyek pada status ini.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</section>

<section class="cta-band">
    <h2>Wujudkan Transparansi Bersama Kami</h2>
    <p>Ada usulan pembangunan atau laporan terkait penggunaan dana desa? Sampaikan aspirasi Anda demi Kampung Mbu yang lebih baik.</p>
    <div><a class="btn btn-light" href="{{ route('citizen-reports.create') }}">Kirim Laporan Warga</a><a class="btn btn-outline-light" href="{{ route('transparency') }}">Lihat Panduan Dana</a></div>
</section>
@endsection
