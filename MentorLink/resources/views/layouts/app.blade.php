<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="theme-color" content="#0f766e">

        <title>{{ $pageTitle ?? $platform['name'] }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=manrope:400,500,600,700,800|sora:400,600,700,800&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body>
        <div class="page-shell" @isset($platformEndpoint) data-platform-endpoint="{{ $platformEndpoint }}" @endisset>
            <div class="ambient ambient-left"></div>
            <div class="ambient ambient-right"></div>

            <header class="site-header">
                <a href="{{ route('landing') }}" class="brand">
                    <span class="brand-mark">ML</span>
                    <span>
                        <strong>{{ $platform['name'] }}</strong>
                        <small>{{ $platform['tagline'] }}</small>
                    </span>
                </a>

                <nav class="site-nav" aria-label="Navigation principale">
                    <a href="{{ route('landing') }}" @class(['is-active' => request()->routeIs('landing')])>Accueil</a>
                    <a href="{{ route('mentors.index') }}" @class(['is-active' => request()->routeIs('mentors.index')])>Mentors</a>
                    <a href="{{ route('dashboard.preview') }}" @class(['is-active' => request()->routeIs('dashboard.preview')])>Suivi</a>
                    <a href="{{ route('access.index') }}" @class(['is-active' => request()->routeIs('access.index')])>Commencer</a>
                </nav>

                <div class="header-actions">
                    @isset($headerAction)
                        {!! $headerAction !!}
                    @else
                        <a href="{{ route('dashboard.preview') }}" class="button button-secondary">Voir le suivi</a>
                    @endisset
                </div>
            </header>

            <main>
                @yield('content')
            </main>

            <footer class="site-footer">
                <p>{{ $platform['footer'] }}</p>
                <span>&copy; {{ now()->year }} {{ $platform['name'] }}</span>
            </footer>
        </div>
    </body>
</html>
