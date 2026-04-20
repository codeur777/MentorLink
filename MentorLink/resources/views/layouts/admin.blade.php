<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin — @yield('title', 'MentorLink')</title>
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
        .sidebar-link.active { background: rgba(255,132,0,0.15); color: #FF8400; }
        .sidebar-link.active i { color: #FF8400; }
    </style>
</head>
<body class="bg-silver min-h-screen flex">

    {{-- ===== SIDEBAR ===== --}}
    <aside class="w-64 bg-oxford min-h-screen flex flex-col fixed top-0 left-0 z-40">

        {{-- Logo --}}
        <div class="px-6 py-6 border-b border-white/10">
            <a href="/" class="flex items-center gap-2">
                <div class="w-9 h-9 bg-orange rounded-xl flex items-center justify-center">
                    <span class="text-oxford font-bold text-sm">ML</span>
                </div>
                <span class="text-white font-bold text-xl tracking-tight">
                    Mentor<span class="text-orange">Link</span>
                </span>
            </a>
            <div class="mt-3 flex items-center gap-2">
                <span class="bg-orange/20 text-orange text-xs font-semibold px-2 py-0.5 rounded-full">Admin</span>
                <span class="text-vista text-xs">{{ auth()->user()->name }}</span>
            </div>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 px-4 py-6 flex flex-col gap-1">
            <p class="text-vista/40 text-xs font-semibold uppercase tracking-widest px-3 mb-2">Menu</p>

            <a href="{{ route('admin.dashboard') }}"
               class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-vista hover:text-white hover:bg-white/5 transition text-sm {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fa-solid fa-gauge-high w-4 text-center"></i>
                Dashboard
            </a>

            <a href="{{ route('admin.stats') }}"
               class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-vista hover:text-white hover:bg-white/5 transition text-sm {{ request()->routeIs('admin.stats') ? 'active' : '' }}">
                <i class="fa-solid fa-chart-bar w-4 text-center"></i>
                Statistiques
            </a>

            <a href="{{ route('admin.pending-mentors') }}"
               class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-vista hover:text-white hover:bg-white/5 transition text-sm {{ request()->routeIs('admin.pending-mentors') ? 'active' : '' }}">
                <i class="fa-solid fa-user-check w-4 text-center"></i>
                Validation mentors
                @php $pending = \App\Models\MentorProfile::where('is_validated', false)->count(); @endphp
                @if($pending > 0)
                    <span class="ml-auto bg-orange text-oxford text-xs font-bold px-2 py-0.5 rounded-full">{{ $pending }}</span>
                @endif
            </a>

            <a href="{{ route('admin.reports') }}"
               class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-vista hover:text-white hover:bg-white/5 transition text-sm {{ request()->routeIs('admin.reports') ? 'active' : '' }}">
                <i class="fa-solid fa-flag w-4 text-center"></i>
                Signalements
                @php $openReports = \App\Models\Report::where('status', 'open')->count(); @endphp
                @if($openReports > 0)
                    <span class="ml-auto bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">{{ $openReports }}</span>
                @endif
            </a>

            <div class="border-t border-white/10 my-4"></div>

            <a href="{{ route('dashboard') }}"
               class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-vista hover:text-white hover:bg-white/5 transition text-sm">
                <i class="fa-solid fa-arrow-left w-4 text-center"></i>
                Retour au site
            </a>
        </nav>

        {{-- Déconnexion --}}
        <div class="px-4 py-4 border-t border-white/10">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-vista hover:text-red-400 hover:bg-white/5 transition text-sm">
                    <i class="fa-solid fa-right-from-bracket w-4 text-center"></i>
                    Déconnexion
                </button>
            </form>
        </div>

    </aside>

    {{-- ===== CONTENU PRINCIPAL ===== --}}
    <div class="ml-64 flex-1 flex flex-col min-h-screen">

        {{-- Topbar --}}
        <header class="bg-white border-b border-gray-100 px-8 py-4 flex items-center justify-between sticky top-0 z-30">
            <div>
                <h1 class="text-oxford font-bold text-lg">@yield('title', 'Dashboard')</h1>
                <p class="text-gray-400 text-xs">@yield('subtitle', 'Gérez la plateforme MentorLink')</p>
            </div>
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-full bg-orange flex items-center justify-center font-bold text-oxford text-sm">
                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                </div>
                <div>
                    <p class="text-oxford text-sm font-semibold">{{ auth()->user()->name }}</p>
                    <p class="text-gray-400 text-xs">Administrateur</p>
                </div>
            </div>
        </header>

        {{-- Flash messages --}}
        @if(session('success'))
            <div class="mx-8 mt-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm flex items-center gap-2">
                <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mx-8 mt-6 bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-xl text-sm flex items-center gap-2">
                <i class="fa-solid fa-circle-exclamation"></i> {{ session('error') }}
            </div>
        @endif

        {{-- Page content --}}
        <main class="flex-1 px-8 py-8">
            @yield('content')
        </main>

    </div>

</body>
</html>
