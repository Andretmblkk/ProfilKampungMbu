@extends('layouts.public')

@section('title', 'Transparansi Publik - Kampung Mbu')

@section('content')
<section class="hero transparency-hero">
    <div class="hero-copy">
        <span class="eyebrow">Laporan Tahun 2024</span>
        <h1>Transparansi Membangun Desa Lebih Maju</h1>
        <p>Setiap rupiah yang dikelola bertujuan meningkatkan kesejahteraan warga Kampung Mbu.</p>
        <a class="btn btn-primary" href="{{ route('reports.pdf') }}"><i class="fa-solid fa-download"></i> Unduh PDF Laporan</a>
    </div>
    <img class="hero-image" alt="Area pembangunan desa" src="https://images.unsplash.com/photo-1494526585095-c41746248156?auto=format&fit=crop&w=1400&q=80">
</section>

<section class="allocation-grid">
    <div class="chart-card">
        <h2>Alokasi Anggaran 2024</h2>
        <p>Distribusi dana berdasarkan sektor prioritas desa.</p>
        <div class="donut-wrap">
            <div class="donut"><strong>Rp 4.2M</strong><span>Total Dana</span></div>
            <div class="legend-list">
                <div><span class="dot blue"></span>Infrastruktur <strong>45%</strong></div>
                <div><span class="dot"></span>Pendidikan & Sos <strong>25%</strong></div>
                <div><span class="dot gold"></span>Pemberdayaan <strong>30%</strong></div>
            </div>
        </div>
    </div>
    <div class="blue-panel"><span>Penyerapan Anggaran</span><strong>{{ $stats['absorption'] }}%</strong><p>Progres penyerapan dana tahun berjalan yang telah diaudit secara internal.</p><div class="panel-progress"><span></span></div></div>
    <x-stat-card icon="fa-users" label="Penerima Manfaat" value="1,240 KK" meta="Warga terdaftar sebagai penerima bantuan sosial." />
</section>

<section class="timeline-section">
    <h2>Riwayat Realisasi Anggaran</h2>
    <div class="timeline">
        @foreach($timeline as $item)
            <article class="timeline-item {{ $item['side'] }}">
                <span class="timeline-dot"></span>
                <div class="timeline-card">
                    <small>{{ $item['month'] }}</small>
                    <h3>{{ $item['title'] }}</h3>
                    <p>Penyelesaian kegiatan prioritas untuk meningkatkan layanan dasar dan akses ekonomi warga.</p>
                    <strong>{{ $item['amount'] }}</strong><span class="status-pill">{{ $item['status'] }}</span>
                </div>
            </article>
        @endforeach
    </div>
</section>

<section class="section-block">
    <h2>Pembaruan Pembangunan</h2>
    <div class="project-grid public-projects">
        <article class="project-card"><img src="https://images.unsplash.com/photo-1587351021759-3e566b6af7cc?auto=format&fit=crop&w=900&q=80" alt="Puskesmas"><span>Kesehatan</span><h3>Puskesmas Pembantu Baru</h3><p>Pembangunan telah mencapai tahap akhir finishing interior dan pengadaan akses.</p><a>Selengkapnya <i class="fa-solid fa-arrow-right"></i></a></article>
        <article class="project-card"><img src="https://images.unsplash.com/photo-1497366754035-f200968a6e72?auto=format&fit=crop&w=900&q=80" alt="Literasi"><span>Pendidikan</span><h3>Pusat Literasi Digital</h3><p>Penyediaan 20 unit komputer dan akses internet gratis untuk pelajar Kampung Mbu.</p><a>Selengkapnya <i class="fa-solid fa-arrow-right"></i></a></article>
        <article class="project-card"><img src="https://images.unsplash.com/photo-1464226184884-fa280b87c399?auto=format&fit=crop&w=900&q=80" alt="Irigasi"><span>Pertanian</span><h3>Irigasi Tetes Modern</h3><p>Uji coba teknologi irigasi tetes untuk efisiensi air pada lahan kering warga.</p><a>Selengkapnya <i class="fa-solid fa-arrow-right"></i></a></article>
    </div>
</section>

<section class="cta-band">
    <h2>Punya Pertanyaan atau Masukan?</h2>
    <p>Partisipasi warga sangat kami butuhkan untuk pembangunan yang lebih baik.</p>
    <div><a class="btn btn-light" href="{{ route('citizen-reports.create') }}">Buat Laporan Publik</a><a class="btn btn-outline-light" href="{{ route('contact') }}">Hubungi Pengelola</a></div>
</section>
@endsection
