<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MentorLink — @yield('title', 'Plateforme de mentorat')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        oxford: '#0B0829',
                        orange: '#FF8400',
                        vista:  '#8FA0D8',
                        almond: '#F9DFC6',
                        silver: '#D9D9D9',
                    },
                    fontFamily: { sans: ['Inter', 'sans-serif'] }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-silver min-h-screen flex flex-col">

    {{-- NAVBAR --}}
    <nav class="bg-oxford sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">

            <a href="/" class="flex items-center gap-2">
                <div class="w-9 h-9 bg-orange rounded-xl flex items-center justify-center">
                    <span class="text-oxford font-bold text-sm">ML</span>
                </div>
                <span class="text-white font-bold text-xl tracking-tight">
                    Mentor<span class="text-orange">Link</span>
                </span>
            </a>

            <div class="flex items-center gap-6">
                @auth
                    @if(auth()->user()->isMentee())
                        <a href="{{ route('mentors.index') }}" class="text-vista hover:text-white text-sm transition">Mentors</a>
                        <a href="{{ route('sessions.index') }}" class="text-vista hover:text-white text-sm transition">Mes sessions</a>
                    @elseif(auth()->user()->isMentor())
                        <a href="{{ route('mentor.profile') }}" class="text-vista hover:text-white text-sm transition">Mon profil</a>
                        <a href="{{ route('sessions.index') }}" class="text-vista hover:text-white text-sm transition">Mes sessions</a>
                        <a href="{{ route('availabilities.create') }}" class="text-vista hover:text-white text-sm transition">Disponibilités</a>
                    @endif

                    <div class="flex items-center gap-3 pl-4 border-l border-white/20">
                        <div class="w-9 h-9 rounded-full bg-orange flex items-center justify-center font-bold text-oxford text-sm">
                            {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                        </div>
                        <div>
                            <p class="text-white text-xs font-semibold">{{ auth()->user()->name }}</p>
                            <p class="text-vista text-xs capitalize">{{ auth()->user()->role }}</p>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="text-vista hover:text-red-400 text-xs transition ml-2">
                                <i class="fa-solid fa-right-from-bracket"></i>
                            </button>
                        </form>
                    </div>
                @endauth
            </div>
        </div>
    </nav>

    {{-- FLASH MESSAGES --}}
    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 text-green-700 px-6 py-3 text-sm flex items-center gap-2">
            <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-50 border-l-4 border-red-500 text-red-600 px-6 py-3 text-sm flex items-center gap-2">
            <i class="fa-solid fa-circle-exclamation"></i> {{ session('error') }}
        </div>
    @endif

    {{-- CONTENU --}}
    <main class="flex-1 max-w-7xl mx-auto w-full px-6 py-8">
        @yield('content')
    </main>

    {{-- FOOTER --}}
    <footer class="bg-oxford mt-auto">
        <div class="max-w-7xl mx-auto px-6 py-6 text-center">
            <span class="text-white font-bold">Mentor<span class="text-orange">Link</span></span>
            <p class="text-vista/50 text-xs mt-1">© 2026 — Plateforme de mentorat académique</p>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
