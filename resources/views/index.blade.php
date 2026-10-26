<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="theme-color" content="#ffffff">

        <title>AuthenSIX Computer</title>

        @fonts
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="home-page">
        <header class="home-header">
            <a class="store-brand" href="{{ route('home') }}" aria-label="AuthenSIX Computer - Beranda">
                <span class="brand-name">
                    <span class="brand-authen">Authen</span><span class="brand-six">SIX</span>
                </span>
                <span class="brand-caption">COMPUTER</span>
            </a>

            <nav class="home-nav" aria-label="Navigasi utama">
                <a href="{{ route('dashboard') }}">Dashboard</a>
                @auth
                    <span class="home-greeting">Hai, {{ auth()->user()->nama_lengkap }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit">Keluar</button>
                    </form>
                @else
                    <a class="home-login-link" href="{{ route('login') }}">Login</a>
                @endauth
            </nav>
        </header>

        <main class="home-main">
            <section class="home-hero" aria-labelledby="home-title">
                <p class="hero-eyebrow">AUTHENSIX COMPUTER</p>
                <h1 id="home-title">Selamat datang di<br>AuthenSIX Computer.</h1>
                <p>Temukan komponen komputer sesuai kebutuhanmu.</p>
                <a href="{{ route('dashboard') }}" class="hero-button">
                    Buka dashboard <span aria-hidden="true">→</span>
                </a>
            </section>
        </main>
    </body>
</html>