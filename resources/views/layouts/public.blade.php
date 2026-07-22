<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Kampung Mbu')</title>
    @vite(['resources/css/frontend.css', 'resources/js/frontend.js'])
    <link rel="preconnect" href="https://cdnjs.cloudflare.com">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body class="public-shell">
    <nav class="public-navbar">
        <a class="brand" href="{{ route('home') }}"><img src="{{ asset('images/kampung/lambang-lanny-jaya.jpg') }}" alt=""> Kampung Mbu</a>
        <div class="nav-links">
            <a class="{{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Beranda</a>
            <a class="{{ request()->routeIs('transparency') ? 'active' : '' }}" href="{{ route('transparency') }}">Transparansi</a>
            <a class="{{ request()->routeIs('news.*') ? 'active' : '' }}" href="{{ route('news.index') }}">Berita</a>
            <a class="{{ request()->routeIs('transparency') ? 'active' : '' }}" href="{{ route('transparency') }}">Laporan</a>
        </div>
        <div class="nav-actions">
            <i class="fa-regular fa-bell"></i>
            <i class="fa-solid fa-gear"></i>
            @auth
                <a class="btn btn-primary btn-sm" href="/admin">Panel {{ auth()->user()->role === 'administrator' ? 'Admin' : 'Operator' }}</a>
            @else
                <a class="btn btn-primary btn-sm" href="{{ route('login') }}">Masuk</a>
            @endauth
        </div>
    </nav>

    <main>@yield('content')</main>

    <footer class="site-footer">
        <div class="footer-main">
            <div class="footer-identity">
                <img src="{{ asset('images/kampung/lambang-lanny-jaya.jpg') }}" alt="Lambang Kabupaten Lanny Jaya">
                <div>
                    <strong>Kampung Mbu</strong>
                    <p>Platform resmi transparansi dana dan pembangunan Kampung Mbu, Distrik Melagi, Kabupaten Lanny Jaya, Papua Pegunungan.</p>
                </div>
            </div>
            <nav class="footer-links" aria-label="Tautan bagian bawah">
                <a href="{{ route('privacy') }}">Kebijakan Privasi</a>
                <a href="{{ route('contact') }}">Kontak Kami</a>
                <a href="{{ route('sitemap') }}">Peta Situs</a>
                <a href="https://www.lannyjayakab.go.id" target="_blank" rel="noopener">Portal Kabupaten</a>
            </nav>
        </div>
        <div class="footer-copy">
            <span>&copy; {{ now()->year }} Pemerintah Kampung Mbu. Transparansi untuk Kemajuan.</span>
            <a href="{{ asset('images/ATTRIBUTION.md') }}">Atribusi gambar</a>
        </div>
    </footer>
</body>
</html>
