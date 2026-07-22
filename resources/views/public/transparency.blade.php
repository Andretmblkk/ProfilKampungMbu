@extends('layouts.public')

@section('title', 'Transparansi Publik - Kampung Mbu')

@section('content')
<section class="hero transparency-hero">
    <div class="hero-copy">
        <span class="eyebrow">Laporan Tahun {{ $selectedYear }}</span>
        <h1>Transparansi Dana Kampung Mbu</h1>
        <p>Ringkasan dana masuk, pengeluaran, alokasi anggaran, dan perkembangan proyek berdasarkan data terverifikasi.</p>
        @if(auth()->check() && in_array(auth()->user()->role, ['administrator', 'operator'], true) && auth()->user()->status === 'aktif')
            <a class="btn btn-primary" href="{{ route('reports.pdf', ['tahun' => $selectedYear]) }}"><i class="fa-solid fa-download"></i> Unduh PDF Laporan</a>
        @else
            <a class="btn btn-light" href="{{ route('login') }}"><i class="fa-solid fa-lock"></i> Masuk untuk Mengunduh</a>
        @endif
    </div>
    <figure class="hero-media">
        <img class="hero-image" alt="Pegunungan Tiom di Kabupaten Lanny Jaya" src="{{ asset('images/kampung/pegunungan-tiom-lanny-jaya.jpg') }}">
        <figcaption>Wilayah Pegunungan Tiom, Kabupaten Lanny Jaya · CC BY-SA 4.0</figcaption>
    </figure>
</section>

<section class="section-block compact-filter-section">
    <form method="get" action="{{ route('transparency') }}" class="report-filters">
        <select class="form-select" name="tahun" aria-label="Pilih tahun laporan">
            @foreach($availableYears as $year)
                <option value="{{ $year }}" @selected($selectedYear === $year)>{{ $year }}</option>
            @endforeach
        </select>
        <select class="form-select" name="status" aria-label="Pilih status proyek">
            <option value="semua" @selected($selectedStatus === 'semua')>Semua status proyek</option>
            <option value="selesai" @selected($selectedStatus === 'selesai')>Selesai</option>
            <option value="sedang-berjalan" @selected($selectedStatus === 'sedang-berjalan')>Sedang berjalan</option>
            <option value="direncanakan" @selected($selectedStatus === 'direncanakan')>Direncanakan</option>
        </select>
        <button class="btn btn-primary" type="submit">Tampilkan</button>
    </form>
</section>

<section class="allocation-grid">
    <div class="chart-card">
        <h2>Alokasi Anggaran {{ $selectedYear }}</h2>
        <p>Pagu dan realisasi berdasarkan kategori anggaran.</p>
        <div class="allocation-list">
            @forelse($allocations as $allocation)
                @php
                    $pagu = (float) $allocation->pagu_anggaran;
                    $realisasi = (float) ($allocation->realisasi ?? 0);
                    $percentage = $pagu > 0 ? min(100, round(($realisasi / $pagu) * 100, 1)) : 0;
                @endphp
                <div class="allocation-item">
                    <div><span class="dot" style="background: {{ $allocation->warna }}"></span>{{ $allocation->nama }} <strong>{{ $percentage }}%</strong></div>
                    <small>Realisasi Rp {{ number_format($realisasi, 0, ',', '.') }} dari pagu Rp {{ number_format($pagu, 0, ',', '.') }}</small>
                    <div class="progress-line"><span style="width: {{ $percentage }}%; background: {{ $allocation->warna }}"></span></div>
                </div>
            @empty
                <p>Belum ada kategori anggaran.</p>
            @endforelse
        </div>
    </div>
    <div class="blue-panel">
        <span>Penyerapan Anggaran</span>
        <strong>{{ number_format($stats['absorption'], 1, ',', '.') }}%</strong>
        <p>Rp {{ number_format($stats['expense'], 0, ',', '.') }} dari Rp {{ number_format($stats['income'], 0, ',', '.') }} dana masuk terverifikasi.</p>
        <div class="panel-progress"><span style="width: {{ min(100, $stats['absorption']) }}%"></span></div>
    </div>
    <x-stat-card icon="fa-screwdriver-wrench" label="Proyek Kampung" value="{{ $stats['projects'] }}" meta="{{ $stats['active_projects'] }} sedang berjalan" />
</section>

<section class="timeline-section">
    <h2>Riwayat Proyek Kampung</h2>
    <div class="timeline">
        @forelse($timeline as $item)
            <article class="timeline-item {{ $loop->odd ? 'right' : 'left' }}">
                <span class="timeline-dot"></span>
                <div class="timeline-card">
                    <small>{{ $item->tanggal_mulai?->translatedFormat('F Y') ?? 'Tanggal belum ditetapkan' }}</small>
                    <h3>{{ $item->nama }}</h3>
                    <p>{{ $item->deskripsi ?: 'Informasi perkembangan proyek Kampung Mbu.' }}</p>
                    <strong>Rp {{ number_format((float) $item->anggaran, 0, ',', '.') }}</strong>
                    <span class="status-pill">{{ $item->progress }}% · {{ ucfirst($item->status) }}</span>
                </div>
            </article>
        @empty
            <p>Belum ada riwayat proyek pada periode ini.</p>
        @endforelse
    </div>
</section>

<section class="section-block">
    <h2>Daftar Proyek {{ $selectedYear }}</h2>
    <div class="project-grid public-projects">
        @forelse($projects as $project)
            <article class="project-card">
                <img src="{{ $project->foto_path ? asset('storage/'.$project->foto_path) : asset('images/kampung/kampung-dataran-tinggi-papua.jpg') }}" alt="{{ $project->nama }}">
                <div class="project-body">
                    <span>{{ $project->kategoriAnggaran?->nama ?? 'Pembangunan' }}</span>
                    <h3>{{ $project->nama }}</h3>
                    <p>{{ $project->deskripsi ?: 'Proyek pembangunan Kampung Mbu.' }}</p>
                    <div class="progress-line"><span style="width: {{ $project->progress }}%"></span></div>
                    <strong>{{ $project->progress }}% · Rp {{ number_format((float) $project->anggaran, 0, ',', '.') }}</strong>
                </div>
            </article>
        @empty
            <p>Belum ada proyek sesuai filter yang dipilih.</p>
        @endforelse
    </div>
</section>

<section class="cta-band">
    <h2>Punya Pertanyaan atau Masukan?</h2>
    <p>Partisipasi warga membantu memastikan dana kampung digunakan secara bertanggung jawab.</p>
    <div><a class="btn btn-light" href="{{ route('citizen-reports.create') }}">Buat Laporan Publik</a><a class="btn btn-outline-light" href="{{ route('contact') }}">Hubungi Pengelola</a></div>
</section>
@endsection
