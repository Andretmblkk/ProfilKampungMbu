@extends('layouts.public')

@section('title', $post['title'].' - Kampung Mbu')

@section('content')
<section class="section-block page-section narrow">
    <span class="section-kicker">{{ $post['date'] }}</span>
    <h1>{{ $post['title'] }}</h1>
    <p class="lead">{{ $post['excerpt'] }}</p>
    <p>Informasi ini diterbitkan sebagai bagian dari komitmen Pemerintah Kampung Mbu untuk menyediakan komunikasi publik yang terbuka dan mudah diakses warga.</p>
    <a class="btn btn-light" href="{{ route('news.index') }}"><i class="fa-solid fa-arrow-left"></i> Kembali ke Berita</a>
</section>
@endsection
