@extends('layouts.public')

@section('title', 'Laporan Warga - Kampung Mbu')

@section('content')
<section class="section-block page-section narrow">
    <span class="section-kicker">Partisipasi Warga</span>
    <h1>Kirim Laporan Warga</h1>
    <p class="lead">Laporkan aspirasi, pengaduan, atau temuan terkait pembangunan dan penggunaan dana kampung.</p>

    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <form class="form-card" method="post" action="{{ route('citizen-reports.store') }}" enctype="multipart/form-data">
        @csrf
        <label>Nama Pelapor</label>
        <input class="form-control @error('nama_pelapor') is-invalid @enderror" name="nama_pelapor" value="{{ old('nama_pelapor') }}">
        @error('nama_pelapor')<div class="invalid-feedback">{{ $message }}</div>@enderror

        <label>Nomor Kontak</label>
        <input class="form-control @error('kontak') is-invalid @enderror" name="kontak" value="{{ old('kontak') }}">
        @error('kontak')<div class="invalid-feedback">{{ $message }}</div>@enderror

        <label>Kategori Laporan</label>
        <select class="form-select @error('kategori') is-invalid @enderror" name="kategori">
            <option value="">Pilih kategori</option>
            @foreach(['Dana Kampung', 'Proyek Pembangunan', 'Bantuan Sosial', 'Layanan Administrasi', 'Lainnya'] as $kategori)
                <option value="{{ $kategori }}" @selected(old('kategori') === $kategori)>{{ $kategori }}</option>
            @endforeach
        </select>
        @error('kategori')<div class="invalid-feedback">{{ $message }}</div>@enderror

        <label>Isi Laporan</label>
        <textarea class="form-control @error('isi_laporan') is-invalid @enderror" name="isi_laporan" rows="6">{{ old('isi_laporan') }}</textarea>
        @error('isi_laporan')<div class="invalid-feedback">{{ $message }}</div>@enderror

        <label>Lampiran Opsional</label>
        <input class="form-control @error('lampiran') is-invalid @enderror" type="file" name="lampiran" accept=".jpg,.jpeg,.png,.pdf">
        @error('lampiran')<div class="invalid-feedback">{{ $message }}</div>@enderror

        <button type="submit" class="btn btn-primary">Kirim Laporan</button>
    </form>
</section>
@endsection
