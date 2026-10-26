<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="theme-color" content="#ffffff">

        <title>Login | AuthenSIX Computer</title>

        @fonts
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="login-page">
        <main class="login-stage">
            <div class="stage-divider" aria-hidden="true"></div>

            <section class="login-card" aria-labelledby="brand-name">
                <a class="close-button" href="{{ route('dashboard') }}" aria-label="Tutup halaman login">
                    <svg viewBox="0 0 16 16" aria-hidden="true">
                        <path d="m4 4 8 8M12 4l-8 8" />
                    </svg>
                </a>

                <div class="brand" id="brand-name" aria-label="AuthenSIX Computer">
                    <div class="brand-name">
                        <span class="brand-authen">Authen</span><span class="brand-six">SIX</span>
                    </div>
                    <span class="brand-caption">COMPUTER</span>
                </div>

                <form class="login-form" method="POST" action="{{ route('login.store') }}">
                        @csrf
                        <label class="input-wrap">
                            <span class="sr-only">Username</span>
                            <input
                                type="text"
                                name="username"
                                placeholder="Username"
                                autocomplete="username"
                                aria-label="Username"
                                value="{{ old('username') }}"
                                required
                            >
                            <svg class="field-icon" viewBox="0 0 24 24" aria-hidden="true">
                                <rect x="3.5" y="5.5" width="17" height="13" rx="1" />
                                <path d="m4.5 7 7.5 5.5L19.5 7" />
                            </svg>
                        </label>

                        <label class="input-wrap">
                            <span class="sr-only">Password</span>
                            <input
                                id="login-password"
                                type="password"
                                name="password"
                                placeholder="Password"
                                autocomplete="current-password"
                                aria-label="Password"
                                required
                            >
                            <button
                                class="field-icon password-toggle"
                                type="button"
                                aria-label="Tampilkan password"
                                aria-pressed="false"
                                data-password-toggle
                            >
                                <svg viewBox="0 0 24 24" aria-hidden="true">
                                    <rect x="6.5" y="10" width="11" height="10" rx="1.5" />
                                    <path d="M9 10V7.5a3 3 0 0 1 6 0V10M12 14v2" />
                                </svg>
                            </button>
                        </label>

                        @if ($errors->has('username') || $errors->has('password'))
                            <p class="login-error" role="alert">
                                {{ $errors->first('username') ?: $errors->first('password') }}
                            </p>
                        @endif

                        <p class="register-prompt">
                            Belum Punya akun? <a href="#" aria-label="Registrasi akun">Registrasi</a>
                        </p>

                        <button class="continue-button" type="submit">Continue</button>
                </form>

                <button class="google-button" type="button">
                    <svg class="google-mark" viewBox="0 0 18 18" aria-hidden="true">
                        <path fill="#4285F4" d="M17.64 9.2c0-.64-.06-1.25-.16-1.84H9v3.48h4.84a4.14 4.14 0 0 1-1.8 2.72v2.27h2.92c1.7-1.56 2.68-3.86 2.68-6.63Z" />
                        <path fill="#34A853" d="M9 18c2.43 0 4.47-.8 5.96-2.17l-2.92-2.27c-.81.54-1.84.86-3.04.86-2.34 0-4.32-1.58-5.03-3.71H.96v2.34A9 9 0 0 0 9 18Z" />
                        <path fill="#FBBC05" d="M3.97 10.71a5.4 5.4 0 0 1 0-3.42V4.95H.96a9 9 0 0 0 0 8.1l3.01-2.34Z" />
                        <path fill="#EA4335" d="M9 3.58c1.32 0 2.5.45 3.44 1.35l2.58-2.58A8.63 8.63 0 0 0 9 0a9 9 0 0 0-8.04 4.95l3.01 2.34C4.68 5.16 6.66 3.58 9 3.58Z" />
                    </svg>
                    <span>Continue With Google</span>
                </button>

                <div class="demo-accounts-box">
                    <span class="demo-accounts-title">Akun Pengujian Demo (Klik untuk Isi):</span>
                    <div class="demo-accounts-list">
                        <button type="button" class="demo-account-pill" data-demo-user="ahmad" data-demo-pass="password" title="Klik untuk mengisi akun Ahmad Hafizh">
                            <span class="demo-account-badge customer">Customer</span>
                            <span class="demo-account-name">Ahmad Hafizh</span>
                        </button>
                        <button type="button" class="demo-account-pill" data-demo-user="admin" data-demo-pass="admin123" title="Klik untuk mengisi akun Admin Store">
                            <span class="demo-account-badge admin">Admin</span>
                            <span class="demo-account-name">Admin Store</span>
                        </button>
                        <button type="button" class="demo-account-pill" data-demo-user="budi" data-demo-pass="password" title="Klik untuk mengisi akun Budi Santoso">
                            <span class="demo-account-badge customer">Customer</span>
                            <span class="demo-account-name">Budi Santoso</span>
                        </button>
                    </div>
                </div>
            </section>
        </main>
    </body>
</html>
