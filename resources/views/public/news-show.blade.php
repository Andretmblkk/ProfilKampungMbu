@extends('layouts.public')

@section('title', $post->judul.' - Kampung Mbu')

@section('content')
<article class="section-block page-section narrow article-detail">
    <span class="section-kicker">{{ $post->published_at->translatedFormat('d F Y') }}</span>
    <h1>{{ $post->judul }}</h1>
    <p class="lead">{{ $post->ringkasan }}</p>
    <img class="article-image" src="{{ $post->gambar_path ? asset('storage/'.$post->gambar_path) : asset('images/kampung/pegunungan-tiom-lanny-jaya.jpg') }}" alt="{{ $post->judul }}">
    <div class="article-content">{!! $post->isi !!}</div>
    <a class="btn btn-light" href="{{ route('news.index') }}"><i class="fa-solid fa-arrow-left"></i> Kembali ke Berita</a>
</article>
@endsection
