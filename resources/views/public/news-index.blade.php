@extends('layouts.public')

@section('title', 'Berita - Kampung Mbu')

@section('content')
<section class="section-block page-section">
    <div class="section-head">
        <div>
            <span class="section-kicker">Informasi Kampung</span>
            <h1>Berita Kampung Mbu</h1>
            <p>Informasi pembangunan, musyawarah, dan layanan publik yang diterbitkan oleh pengelola kampung.</p>
        </div>
    </div>
    <div class="project-grid public-projects">
        @forelse($posts as $post)
            <article class="project-card news-card">
                <img src="{{ $post->gambar_path ? asset('storage/'.$post->gambar_path) : asset('images/kampung/pegunungan-tiom-lanny-jaya.jpg') }}" alt="{{ $post->judul }}">
                <div class="project-body">
                    <small>{{ $post->published_at->translatedFormat('d F Y') }}</small>
                    <h3>{{ $post->judul }}</h3>
                    <p>{{ $post->ringkasan }}</p>
                    <a href="{{ route('news.show', $post->slug) }}">Baca Selengkapnya <i class="fa-solid fa-arrow-right"></i></a>
                </div>
            </article>
        @empty
            <div class="empty-state">Belum ada berita yang diterbitkan.</div>
        @endforelse
    </div>
    <div class="mt-4">{{ $posts->links() }}</div>
</section>
@endsection
