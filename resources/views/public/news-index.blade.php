@extends('layouts.public')

@section('title', 'Berita - Kampung Mbu')

@section('content')
<section class="section-block page-section">
    <div class="section-head">
        <div>
            <span class="section-kicker">Informasi Kampung</span>
            <h1>Berita Kampung Mbu</h1>
            <p>Update pembangunan, musyawarah, dan layanan publik Kampung Mbu.</p>
        </div>
    </div>
    <div class="project-grid public-projects">
        @foreach($posts as $post)
            <article class="project-card news-card">
                <div class="project-body">
                    <small>{{ $post['date'] }}</small>
                    <h3>{{ $post['title'] }}</h3>
                    <p>{{ $post['excerpt'] }}</p>
                    <a href="{{ route('news.show', $post['slug']) }}">Baca Selengkapnya <i class="fa-solid fa-arrow-right"></i></a>
                </div>
            </article>
        @endforeach
    </div>
</section>
@endsection
