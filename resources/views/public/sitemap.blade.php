@extends('layouts.public')

@section('title', 'Peta Situs - Kampung Mbu')

@section('content')
<section class="section-block page-section narrow">
    <span class="section-kicker">Navigasi</span>
    <h1>Peta Situs</h1>
    <div class="table-card sitemap-list">
        <a href="{{ route('home') }}">Beranda</a>
        <a href="{{ route('transparency') }}">Transparansi Publik</a>
        <a href="{{ route('news.index') }}">Berita</a>
        <a href="{{ route('reports.pdf') }}">Unduh Laporan PDF</a>
        <a href="{{ route('citizen-reports.create') }}">Laporan Warga</a>
        <a href="{{ route('contact') }}">Kontak</a>
        <a href="{{ route('privacy') }}">Kebijakan Privasi</a>
    </div>
</section>
@endsection
