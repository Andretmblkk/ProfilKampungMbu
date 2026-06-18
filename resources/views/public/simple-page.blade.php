@extends('layouts.public')

@section('title', $title.' - Kampung Mbu')

@section('content')
<section class="section-block page-section narrow">
    <span class="section-kicker">Kampung Mbu</span>
    <h1>{{ $title }}</h1>
    <p class="lead">{{ $body }}</p>
    <a class="btn btn-primary" href="{{ route('home') }}">Kembali ke Beranda</a>
</section>
@endsection
