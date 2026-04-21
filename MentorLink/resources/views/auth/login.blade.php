<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - MentorLink</title>
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
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-silver min-h-screen">

    {{-- Navbar --}}
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
        </div>
    </nav>

    <div class="flex items-center justify-center py-12">
        <div class="w-full max-w-md px-6">

            {{-- Titre --}}
            <div class="text-center mb-8">
                <h1 class="text-3xl font-extrabold text-oxford mb-2">Connexion</h1>
                <p class="text-gray-600 text-sm">Accède à ton espace personnel</p>
            </div>

        {{-- Erreurs --}}
        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6">
                <ul class="text-red-600 text-sm space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Formulaire --}}
        <form method="POST" action="{{ route('login') }}" class="bg-white rounded-2xl border border-vista/20 p-8 shadow-sm">
            @csrf

            <div class="mb-5">
                <label for="email" class="text-xs font-semibold text-oxford mb-1 block">Adresse e-mail</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                       class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm text-oxford focus:outline-none focus:border-orange transition">
            </div>

            <div class="mb-5">
                <label for="password" class="text-xs font-semibold text-oxford mb-1 block">Mot de passe</label>
                <input id="password" type="password" name="password" required
                       class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm text-oxford focus:outline-none focus:border-orange transition">
            </div>

            <div class="mb-6 flex items-center justify-between">
                <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                    <input type="checkbox" name="remember" class="w-4 h-4 text-orange border-gray-300 rounded focus:ring-orange">
                    Se souvenir de moi
                </label>
                <a href="{{ route('password.request') }}" class="text-sm text-orange hover:underline">
                    Mot de passe oublié ?
                </a>
            </div>

            <button type="submit"
                    class="w-full bg-orange text-white font-semibold py-3 rounded-xl hover:opacity-90 transition text-sm">
                Se connecter
            </button>
        </form>

        {{-- Lien inscription --}}
        <p class="text-center text-sm text-gray-600 mt-6">
            Pas encore de compte ? 
            <a href="{{ route('register') }}" class="text-orange font-semibold hover:underline">S'inscrire</a>
        </p>

        </div>

    </div>

</body>
</html>
