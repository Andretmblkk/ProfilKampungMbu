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
        <a class="brand" href="{{ route('home') }}">Kampung Mbu</a>
        <div class="nav-links">
            <a class="{{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Beranda</a>
            <a class="{{ request()->routeIs('transparency') ? 'active' : '' }}" href="{{ route('transparency') }}">Transparansi</a>
            <a class="{{ request()->routeIs('news.*') ? 'active' : '' }}" href="{{ route('news.index') }}">Berita</a>
            <a href="{{ route('reports.pdf') }}">Laporan</a>
        </div>
        <div class="nav-actions">
            <i class="fa-regular fa-bell"></i>
            <i class="fa-solid fa-gear"></i>
            <a class="btn btn-primary btn-sm" href="{{ route('login') }}">Masuk</a>
        </div>
    </nav>

    <main>@yield('content')</main>

    <footer class="site-footer">
        <div>
            <strong>Kampung Mbu</strong>
            <p>Platform resmi transparansi dana dan pembangunan desa Kampung Mbu, Distrik Melagi, Kabupaten Lanny Jaya, Papua Pegunungan, Indonesia.</p>
        </div>
        <div class="footer-links">
            <a href="{{ route('privacy') }}">Kebijakan Privasi</a>
            <a href="{{ route('contact') }}">Kontak Kami</a>
            <a href="{{ route('sitemap') }}">Peta Situs</a>
            <a href="https://www.lannyjayakab.go.id" target="_blank" rel="noopener">Portal Kabupaten</a>
        </div>
        <div class="footer-copy">© 2024 Pemerintah Kampung Mbu. Transparansi untuk Kemajuan.</div>
    </footer>
</body>
</html>
