<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MentorLink — Plateforme de mentorat académique</title>
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
        html { scroll-behavior: smooth; }
    </style>
</head>
<body class="bg-white text-oxford">

{{-- ===== NAVBAR ===== --}}
<nav class="bg-oxford sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">

        {{-- Logo --}}
        <a href="/" class="flex items-center gap-2">
            <div class="w-9 h-9 bg-orange rounded-xl flex items-center justify-center">
                <span class="text-oxford font-bold text-sm">ML</span>
            </div>
            <span class="text-white font-bold text-xl tracking-tight">
                Mentor<span class="text-orange">Link</span>
            </span>
        </a>

        {{-- Menu central --}}
        <div class="flex items-center gap-8">
            <a href="#apropos"
               style="color:#DBD4CF"
               class="text-sm hover:text-white transition">
               À propos
            </a>
            <a href="#contact"
               style="color:#DBD4CF"
               class="text-sm hover:text-white transition">
               Contact
            </a>
            <a href="#"
               style="color:#DBD4CF"
               class="text-sm hover:text-white transition">
               Conditions d'utilisation
            </a>
        </div>

        {{-- Bouton connexion --}}
        <a href="{{ route('login') }}"
           style="background:#F4F5FB; color:#FE8503;"
           class="font-semibold text-sm px-5 py-2 rounded-lg hover:opacity-90 transition">
           Connexion
        </a>

    </div>
</nav>

{{-- ===== SECTION HERO ===== --}}
<section class="bg-silver flex items-center">
    <div class="max-w-7xl mx-auto px-4 py-18 grid grid-cols-2 gap-16 items-center">

        {{-- Texte gauche --}}
        <div>
            
            <h1 class="text-5xl font-bold text-oxford leading-tight mb-6">
                Trouve ton
                <span class="text-orange text-7xl">mentor</span> <br>
                et progresse plus vite
            </h1>
            <p class="text-gray-600 text-lg leading-relaxed mb-8 max-w-md">
                Des étudiants seniors t'accompagnent dans tes révisions, projets et orientation.
            </p>
            <div class="flex items-center gap-4">
                <a href="{{ route('login') }}"
                   class="bg-orange text-white font-semibold px-7 py-3 rounded-xl hover:opacity-90 transition text-sm">
                   Se connecter
                </a>
                <a href="{{ route('register') }}"
                   class="border-2 border-oxford text-oxford font-semibold px-7 py-3 rounded-xl hover:bg-oxford hover:text-white transition text-sm">
                   S'inscrire gratuitement
                </a>
            </div>
        </div>

        {{-- Illustration droite --}}
        <div class="flex justify-center">
                <img src="{{ asset('images/graduated.png') }}" class="w-full h-full object-cover">
        </div>

    </div>
</section>

{{-- ===== SECTION À PROPOS ===== --}}
<section id="apropos" class="bg-oxford py-24">
    <div class="max-w-7xl mx-auto px-6">

        {{-- Stats --}}
        <div class="text-center mb-16">
            <h2 class="text-white text-3xl font-bold mb-3">MentorLink en chiffres</h2>
            <p class="text-vista text-sm">Une communauté académique qui grandit chaque jour</p>
        </div>

        <div class="grid grid-cols-3 gap-6 mb-24">
            @php
    $totalMentors  = 0;
    $totalSessions = 0;
    $avgRating     = null;
    $totalMentors  = \App\Models\User::where('role','mentor')->count();
    $totalSessions = \App\Models\Session::where('status','completed')->count();
    $avgRating     = \App\Models\Review::avg('rating');
            @endphp
            <div class="bg-white/5 border border-vista/20 rounded-2xl p-8 text-center hover:border-orange/30 transition">
                <div class="text-5xl font-extrabold text-orange mb-2">{{ $totalMentors }}</div>
                <div class="text-vista text-sm">Mentors actifs</div>
            </div>
            <div class="bg-white/5 border border-vista/20 rounded-2xl p-8 text-center hover:border-orange/30 transition">
                <div class="text-5xl font-extrabold text-orange mb-2">{{ $totalSessions }}</div>
                <div class="text-vista text-sm">Sessions réalisées</div>
            </div>
            <div class="bg-white/5 border border-vista/20 rounded-2xl p-8 text-center hover:border-orange/30 transition">
                <div class="text-5xl font-extrabold text-orange mb-2">
                    {{ $avgRating ? number_format($avgRating, 1) : '—' }}
                </div>
                <div class="text-vista text-sm">Note moyenne / 5</div>
            </div>
        </div>

        {{-- Top mentors --}}
        <div class="text-center mb-12">
            <h2 class="text-white text-2xl font-bold mb-2">Mentors les mieux notés</h2>
            <p class="text-vista text-sm">Découvre les mentors que la communauté plébiscite</p>
        </div>

        <div class="grid grid-cols-3 gap-6 mb-24">
            @forelse($topMentors as $mentor)
            <div class="bg-white/5 border border-vista/20 rounded-2xl p-6 hover:border-orange/40 transition">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-12 h-12 rounded-full bg-orange flex items-center justify-center font-bold text-oxford text-sm flex-shrink-0">
                        {{ strtoupper(substr($mentor->name, 0, 2)) }}
                    </div>
                    <div>
                        <div class="text-white font-semibold text-sm">{{ $mentor->name }}</div>
                        <div class="text-orange text-xs">
                            @if($mentor->mentorProfile?->average_rating)
                                ★ {{ number_format($mentor->mentorProfile->average_rating, 1) }} / 5
                            @else
                                <span class="text-vista">Nouveau mentor</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="flex flex-wrap gap-2 mb-4">
                    @foreach(array_slice($mentor->mentorProfile?->domains ?? [], 0, 2) as $domain)
                        <span class="bg-vista/20 text-vista text-xs px-2 py-1 rounded-full">
                            {{ $domain }}
                        </span>
                    @endforeach
                </div>
                @if($mentor->bio)
                    <p class="text-vista/70 text-xs leading-relaxed line-clamp-2 mb-4">
                        {{ $mentor->bio }}
                    </p>
                @endif
                <div class="pt-3 border-t border-vista/10 flex items-center justify-between">
                    <span class="text-vista text-xs">
                        {{ $mentor->mentorProfile?->hourly_rate
                            ? number_format($mentor->mentorProfile->hourly_rate) . ' FCFA/h'
                            : 'Gratuit' }}
                    </span>
                    <a href="{{ route('login') }}"
                       class="bg-orange text-oxford text-xs font-semibold px-3 py-1 rounded-lg hover:opacity-90 transition">
                       Voir profil
                    </a>
                </div>
            </div>
            @empty
            {{-- Cartes fictives si base vide --}}
            @foreach([
                ['AD','Alice Dupont','Mathématiques','4.9','Gratuit'],
                ['BM','Bob Martin','Programmation','4.5','500 FCFA/h'],
                ['SK','Sara Koné','Physique','4.3','Gratuit'],
            ] as $m)
            <div class="bg-white/5 border border-vista/20 rounded-2xl p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-12 h-12 rounded-full bg-orange flex items-center justify-center font-bold text-oxford text-sm">
                        {{ $m[0] }}
                    </div>
                    <div>
                        <div class="text-white font-semibold text-sm">{{ $m[1] }}</div>
                        <div class="text-orange text-xs">★ {{ $m[3] }} / 5</div>
                    </div>
                </div>
                <span class="bg-vista/20 text-vista text-xs px-2 py-1 rounded-full">{{ $m[2] }}</span>
                <div class="mt-4 pt-3 border-t border-vista/10 flex items-center justify-between">
                    <span class="text-vista text-xs">{{ $m[4] }}</span>
                    <a href="{{ route('login') }}" class="bg-orange text-oxford text-xs font-semibold px-3 py-1 rounded-lg">
                        Voir profil
                    </a>
                </div>
            </div>
            @endforeach
            @endforelse
        </div>

        {{-- Comment ça marche --}}
        <div class="text-center mb-12">
            <h2 class="text-white text-2xl font-bold mb-2">Comment ça marche ?</h2>
            <p class="text-vista text-sm">En 3 étapes simples</p>
        </div>

        <div class="grid grid-cols-3 gap-6">
            @foreach([
                ['01','Inscris-toi','Inscris-toi en tant que mentor ou mentoré.'],
                ['02','Trouve ton mentor','Filtre par domaine et consulte les profils.'],
                ['03','Réserve une session','Choisis un créneau et confirme.'],
            ] as $step)
            <div class="text-center px-6">
                <div class="w-16 h-16 bg-orange/10 border-2 border-orange rounded-2xl flex items-center justify-center mx-auto mb-5">
                    <span class="text-orange font-extrabold text-xl">{{ $step[0] }}</span>
                </div>
                <h3 class="text-white font-semibold mb-2">{{ $step[1] }}</h3>
                <p class="text-vista text-sm leading-relaxed">{{ $step[2] }}</p>
            </div>
            @endforeach
        </div>

    </div>
</section>

{{-- ===== SECTION CONTACT ===== --}}
<section id="contact" class="bg-silver py-24">
    <div class="max-w-2xl mx-auto px-6">
        <div class="text-center mb-10">
            <h2 class="text-3xl font-bold text-oxford mb-3">Contactez-nous ici</h2>
            <p class="text-gray-500 text-sm leading-relaxed">
                Pour tout apport, questions ou suggestion n'hésitez pas<br>à nous envoyer un message ici.
            </p>
        </div>
        <form action="#" method="POST"
              class="bg-white rounded-2xl border border-vista/20 p-8 flex flex-col gap-5 shadow-sm">
            @csrf
            <div class="grid grid-cols-1 gap-5">
                <div>
                    <label class="text-2xl font-semibold text-oxford mb-1 block">Nom</label>
                    <input type="text" name="nom" placeholder="Votre nom"
                           class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm text-oxford focus:outline-none focus:border-oxford transition">
                </div>
                <div>
                    <label class="text-2xl font-semibold text-oxford mb-1 block">Email</label>
                    <input type="email" name="email" placeholder="Votre email"
                           class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm text-oxford focus:outline-none focus:border-oxford transition">
                </div>
            </div>
            <div>
                <label class="text-2xl font-semibold text-oxford mb-1 block">Message</label>
                <textarea name="message" rows="5" placeholder="Votre message..."
                          class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm text-oxford focus:outline-none focus:border-oxford transition resize-none"></textarea>
            </div>
            <button type="submit"
                    class="bg-orange text-white font-semibold py-3 rounded-xl hover:opacity-90 transition text-sm w-full">
                Envoyer le message
            </button>
        </form>
    </div>
</section>

{{-- ===== FOOTER ===== --}}
<footer class="bg-oxford py-8">
    <div class="max-w-7xl mx-auto px-6 flex flex-col items-center gap-6">
        <span class="text-white font-bold text-lg">
            Mentor<span class="text-orange">Link</span>
        </span>
        <div class="flex flex-col items-center gap-2">
            <span class="text-vista text-sm flex items-center gap-2">
                <i class="fa-solid fa-envelope"></i>
                benedictehounkanli@gmail.com
            </span>
            <span class="text-vista text-sm flex items-center gap-2">
                <i class="fa-solid fa-phone"></i>
                +228 96 46 96 93
            </span>
        </div>
        <div class="border-t border-vista/20 pt-4 w-full text-center">
            <span class="text-vista/50 text-xs">© 2026 MentorLink | All right deserved</span>
        </div>
    </div>
</footer>

</body>
</html>