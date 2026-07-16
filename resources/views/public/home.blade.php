@extends('layouts.public')

@section('title', 'Beranda - Kampung Mbu')

@section('content')
<section class="hero public-hero">
    <div class="hero-copy">
        <span class="eyebrow"><i class="fa-regular fa-circle-check"></i> Pemerintahan Terbuka</span>
        <h1>Transparansi Dana untuk Kemajuan Kampung Mbu</h1>
        <p>Akses informasi penggunaan dana kampung, progres pembangunan, dan layanan warga dari Kampung Mbu, Distrik Melagi, Kabupaten Lanny Jaya.</p>
        <div class="hero-actions">
            <a class="btn btn-primary" href="{{ route('transparency', ['tahun' => $selectedYear]) }}">Lihat Laporan Keuangan</a>
            <a class="btn btn-light" href="#tentang">Tentang Kampung</a>
        </div>
    </div>
    <figure class="hero-media">
        <img class="hero-image" alt="Pegunungan Tiom di Kabupaten Lanny Jaya" src="{{ asset('images/kampung/pegunungan-tiom-lanny-jaya.jpg') }}">
        <figcaption>Panorama Pegunungan Tiom, Kabupaten Lanny Jaya · Gatot Sukarno Putra/CC BY-SA 4.0</figcaption>
    </figure>
</section>

<section class="stats-band">
    <x-stat-card icon="fa-money-bill-trend-up" label="Total Dana Masuk ({{ $selectedYear }})" value="Rp {{ number_format($stats['income'], 0, ',', '.') }}" meta="Transaksi terverifikasi" tone="blue" />
    <x-stat-card icon="fa-receipt" label="Total Pengeluaran" value="Rp {{ number_format($stats['expense'], 0, ',', '.') }}" meta="{{ number_format($stats['absorption'], 1, ',', '.') }}% dana terserap" tone="gold" />
    <x-stat-card icon="fa-screwdriver-wrench" label="Total Proyek" value="{{ $stats['projects'] }}" meta="{{ $stats['completed_projects'] }} selesai, {{ $stats['active_projects'] }} berjalan" tone="indigo" />
</section>

<section id="tentang" class="about-grid">
    <div class="photo-stack regional-photo">
        <img src="{{ asset('images/kampung/kampung-dataran-tinggi-papua.jpg') }}" alt="Ilustrasi permukiman dataran tinggi Papua">
        <div class="mini-card primary">Mbu<span>Distrik Melagi</span></div>
        <div class="mini-card">99588<span>Kode Pos Wilayah</span></div>
        <img class="regional-emblem" src="{{ asset('images/kampung/lambang-lanny-jaya.jpg') }}" alt="Lambang Kabupaten Lanny Jaya">
    </div>
    <div class="about-copy">
        <span class="section-kicker">Tentang Kampung Kami</span>
        <h2>Kampung Mbu di Pegunungan Lanny Jaya</h2>
        <p>Kampung Mbu merupakan kampung di Distrik Melagi, Kabupaten Lanny Jaya, Provinsi Papua Pegunungan. Portal ini membantu pemerintah kampung menyampaikan informasi dana dan pembangunan secara terbuka.</p>
        <p><i class="fa-regular fa-circle-check"></i> <strong>Transparansi:</strong> dana masuk dan pengeluaran publik berasal dari transaksi yang telah diverifikasi.</p>
        <p><i class="fa-regular fa-circle-check"></i> <strong>Partisipasi:</strong> warga dapat mengirim laporan dan menerima nomor tiket untuk tindak lanjut.</p>
        <small class="photo-note">Foto permukiman adalah ilustrasi regional Papua Pegunungan, bukan diklaim sebagai dokumentasi spesifik Kampung Mbu.</small>
    </div>
</section>

<section class="section-block">
    <div class="section-head">
        <div><h2>Transparansi Proyek</h2><p>Daftar proyek kampung berdasarkan data yang dikelola melalui panel admin.</p></div>
        <form method="get" action="{{ route('home') }}" class="report-filters">
            <select class="form-select" name="tahun" aria-label="Pilih tahun laporan">
                @foreach($availableYears as $year)
                    <option value="{{ $year }}" @selected($selectedYear === $year)>{{ $year }}</option>
                @endforeach
            </select>
            <select class="form-select" name="status" aria-label="Pilih status proyek">
                <option value="semua" @selected($selectedStatus === 'semua')>Semua status</option>
                <option value="selesai" @selected($selectedStatus === 'selesai')>Selesai</option>
                <option value="sedang-berjalan" @selected($selectedStatus === 'sedang-berjalan')>Sedang berjalan</option>
                <option value="direncanakan" @selected($selectedStatus === 'direncanakan')>Direncanakan</option>
            </select>
            <button class="btn btn-light" type="submit"><i class="fa-solid fa-filter"></i> Terapkan</button>
            <a class="btn btn-primary" href="{{ route('reports.pdf', ['tahun' => $selectedYear]) }}">Unduh PDF</a>
        </form>
    </div>
    <div id="proyek" class="table-card">
        <table class="modern-table">
            <thead><tr><th>Nama Proyek</th><th>Anggaran</th><th>Kategori</th><th>Progress</th><th>Status</th></tr></thead>
            <tbody>
            @forelse($projects as $project)
                <tr>
                    <td><strong>{{ $project->nama }}</strong><span>{{ $project->lokasi }}</span></td>
                    <td><strong>Rp {{ number_format((float) $project->anggaran, 0, ',', '.') }}</strong></td>
                    <td><span class="badge-soft">{{ $project->kategoriAnggaran?->nama ?? 'Belum dikategorikan' }}</span></td>
                    <td><div class="progress-line"><span style="width: {{ $project->progress }}%"></span></div>{{ $project->progress }}% selesai</td>
                    <td><span class="status-pill">{{ match($project->status) { 'berjalan' => 'Sedang Berjalan', 'selesai' => 'Selesai', default => 'Direncanakan' } }}</span></td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center py-5">Belum ada proyek pada tahun dan status ini.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</section>

<section class="cta-band">
    <h2>Wujudkan Transparansi Bersama Kami</h2>
    <p>Ada usulan pembangunan atau laporan terkait penggunaan dana kampung? Sampaikan melalui kanal resmi.</p>
    <div><a class="btn btn-light" href="{{ route('citizen-reports.create') }}">Kirim Laporan Warga</a><a class="btn btn-outline-light" href="{{ route('transparency', ['tahun' => $selectedYear]) }}">Lihat Rincian Dana</a></div>
</section>
@endsection
