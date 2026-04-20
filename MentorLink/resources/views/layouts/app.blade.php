 
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MentorLink — @yield('title', 'Plateforme de mentorat')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-almond min-h-screen flex flex-col font-sans">

    {{-- NAVBAR --}}
    <nav class="bg-oxford sticky top-0 z-50 border-b border-white/10">
        <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">

            <a href="/" class="flex items-center gap-2">
                <div class="w-9 h-9 bg-orange rounded-xl flex items-center justify-center">
                    <span class="text-oxford font-bold text-sm">ML</span>
                </div>
                <span class="text-white font-bold text-lg tracking-tight">
                    Mentor<span class="text-orange">Link</span>
                </span>
            </a>

            <div class="flex items-center gap-6">
                @auth
                    @if(auth()->user()->role === 'mentore')
                        <a href="{{ route('mentors.index') }}" class="text-vista hover:text-white text-sm transition">Trouver un mentor</a>
                        <a href="{{ route('sessions.index') }}" class="text-vista hover:text-white text-sm transition">Mes sessions</a>

                    @elseif(auth()->user()->role === 'mentor')
                        <a href="{{ route('mentor.sessions') }}" class="text-vista hover:text-white text-sm transition">Mes demandes</a>
                        <a href="{{ route('availabilities.index') }}" class="text-vista hover:text-white text-sm transition">Mes créneaux</a>

                    @elseif(auth()->user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="text-vista hover:text-white text-sm transition">Dashboard</a>
                        <a href="{{ route('admin.mentors') }}" class="text-vista hover:text-white text-sm transition">Validation</a>
                    @endif

                    <div class="flex items-center gap-3 pl-4 border-l border-white/20">
                        <div class="w-9 h-9 rounded-full bg-orange flex items-center justify-center font-bold text-oxford text-sm">
                            {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                        </div>
                        <div class="flex flex-col">
                            <span class="text-white text-xs font-medium">{{ auth()->user()->name }}</span>
                            <span class="text-vista text-xs capitalize">{{ auth()->user()->role }}</span>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="text-vista hover:text-red-400 text-xs transition ml-2">Déconnexion</button>
                        </form>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="text-vista hover:text-white text-sm transition">Connexion</a>
                    <a href="{{ route('register') }}" class="bg-orange text-oxford font-semibold text-sm px-5 py-2 rounded-lg hover:opacity-90 transition">S'inscrire</a>
                @endauth
            </div>
        </div>
    </nav>

    {{-- FLASH MESSAGES --}}
    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 text-green-800 px-6 py-3 text-sm">
            ✓ {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-50 border-l-4 border-red-500 text-red-800 px-6 py-3 text-sm">
            ✗ {{ session('error') }}
        </div>
    @endif

    {{-- CONTENU --}}
    <main class="flex-1">
        @yield('content')
    </main>

    {{-- FOOTER --}}
    <footer class="bg-oxford border-t border-white/10 mt-auto">
        <div class="max-w-7xl mx-auto px-6 py-6 flex items-center justify-between">
            <span class="text-white font-bold">Mentor<span class="text-orange">Link</span></span>
            <span class="text-vista/60 text-sm">© 2024 — Plateforme de mentorat académique</span>
        </div>
    </footer>

</body>
</html>