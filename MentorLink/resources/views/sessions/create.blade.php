<!DOCTYPE html>
<html lang="fr">

<head>
    <title>Réserver une session - MentorLink</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        oxford: '#0B0829',
                        orange: '#FF8400',
                        vista: '#8FA0D8',
                        almond: '#F9DFC6',
                        silver: '#D9D9D9',
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif']
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        html {
            scroll-behavior: smooth;
        }

        input[type="date"],
        input[type="time"] {
            color-scheme: light;
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: #0B0829;
        }

        ::-webkit-scrollbar-thumb {
            background: #FF8400;
            border-radius: 3px;
        }
    </style>
</head>

<body class="bg-oxford text-white min-h-screen">

    {{-- ===== NAVBAR ===== --}}
    <nav class="bg-oxford border-b border-vista/10 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
            <a href="/" class="flex items-center gap-2">
                <div class="w-9 h-9 bg-orange rounded-xl flex items-center justify-center">
                    <span class="text-oxford font-bold text-sm">ML</span>
                </div>
                <span class="text-white font-bold text-xl tracking-tight">
                    Mentor<span class="text-orange">Link</span>
                </span>
            </a>
            <a href="{{ route('mentors.show', $mentor->id) }}"
                class="flex items-center gap-2 text-vista text-sm hover:text-white transition">
                <i class="fa-solid fa-arrow-left text-xs"></i>
                Retour au profil
            </a>
        </div>
    </nav>

    {{-- ===== MAIN CONTENT ===== --}}
    <main class="max-w-5xl mx-auto px-6 py-12">

        {{-- Header --}}
        <div class="mb-10">
            <p class="text-orange text-sm font-semibold uppercase tracking-widest mb-2">Réservation</p>
            <h1 class="text-4xl font-extrabold text-white mb-1">
                Session avec <span class="text-orange">{{ $mentor->name }}</span>
            </h1>
            @if($mentor->mentorProfile)
            <div class="flex items-center gap-4 mt-3">
                <div class="flex flex-wrap gap-2">
                    @foreach($mentor->mentorProfile->domains ?? [] as $domain)
                    <span class="bg-vista/20 text-vista text-xs px-3 py-1 rounded-full">{{ $domain }}</span>
                    @endforeach
                </div>
                <span class="text-vista/50">•</span>
                <span class="text-vista text-sm font-medium">
                    <i class="fa-solid fa-tag mr-1 text-orange"></i>
                    {{ $mentor->mentorProfile->hourly_rate }}€/h
                </span>
            </div>
            @endif
        </div>

        {{-- Errors --}}
        @if($errors->any())
        <div class="bg-red-500/10 border border-red-500/30 rounded-2xl p-4 mb-8">
            <div class="flex items-start gap-3">
                <i class="fa-solid fa-circle-exclamation text-red-400 mt-0.5"></i>
                <ul class="text-red-300 text-sm space-y-1">
                    @foreach($errors->all() as $e)
                    <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">

            {{-- ===== LEFT — Availability Calendar ===== --}}
            <div class="lg:col-span-3">
                <div class="bg-white/5 border border-vista/20 rounded-2xl p-6">

                    {{-- Week navigation --}}
                    @php
                    $weekLabel = \Carbon\Carbon::parse($weekStart)->startOfWeek(\Carbon\Carbon::MONDAY)->format('d/m/Y')
                    . ' – '
                    . \Carbon\Carbon::parse($weekStart)->endOfWeek(\Carbon\Carbon::SUNDAY)->format('d/m/Y');
                    $days = ['Dimanche','Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi'];
                    @endphp

                    <div class="flex items-center justify-between mb-6">
                        <a href="{{ route('sessions.create', ['mentor_id' => $mentor->id, 'week' => $prevWeek]) }}"
                            class="w-9 h-9 flex items-center justify-center rounded-xl bg-white/5 border border-vista/20 text-vista hover:border-orange/40 hover:text-orange transition">
                            <i class="fa-solid fa-chevron-left text-xs"></i>
                        </a>
                        <div class="text-center">
                            <p class="text-white font-semibold text-sm">{{ $weekLabel }}</p>
                            <p class="text-vista text-xs mt-0.5">Créneaux disponibles</p>
                        </div>
                        <a href="{{ route('sessions.create', ['mentor_id' => $mentor->id, 'week' => $nextWeek]) }}"
                            class="w-9 h-9 flex items-center justify-center rounded-xl bg-white/5 border border-vista/20 text-vista hover:border-orange/40 hover:text-orange transition">
                            <i class="fa-solid fa-chevron-right text-xs"></i>
                        </a>
                    </div>

                    {{-- Legend --}}
                    <div class="flex items-center gap-4 mb-5">
                        <div class="flex items-center gap-1.5">
                            <div class="w-2.5 h-2.5 rounded-full bg-green-400"></div>
                            <span class="text-vista text-xs">Disponible</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <div class="w-2.5 h-2.5 rounded-full bg-red-400/60"></div>
                            <span class="text-vista text-xs">Indisponible</span>
                        </div>
                    </div>

                    @if(count($slots) > 0)
                    <div class="space-y-2">
                        @foreach($slots as $slot)
                        <div class="flex items-center justify-between rounded-xl px-4 py-3
                            {{ $slot['booked'] ? 'bg-red-500/5 border border-red-500/20' : 'bg-green-500/5 border border-green-500/20 hover:border-orange/40 transition' }}">
                            <div class="flex items-center gap-4">
                                <div class="text-center min-w-[60px]">
                                    <p class="text-vista text-xs">{{ $days[$slot['day_of_week']] }}</p>
                                    <p class="text-white text-sm font-semibold">
                                        {{ \Carbon\Carbon::parse($slot['date'])->format('d/m') }}
                                    </p>
                                </div>
                                <div class="w-px h-8 bg-vista/20"></div>
                                <div class="flex items-center gap-2 text-sm">
                                    <i class="fa-regular fa-clock text-vista text-xs"></i>
                                    <span class="text-white font-medium">{{ $slot['start_time'] }}</span>
                                    <span class="text-vista">→</span>
                                    <span class="text-white font-medium">{{ $slot['end_time'] }}</span>
                                </div>
                            </div>
                            <div>
                                @if($slot['booked'])
                                <span class="text-red-400/80 text-xs font-medium">
                                    <i class="fa-solid fa-lock mr-1"></i>Réservé
                                </span>
                                @elseif(\Carbon\Carbon::parse($slot['date'])->isFuture())
                                <button type="button"
                                    onclick="fillForm('{{ $slot['date'] }}','{{ $slot['start_time'] }}','{{ $slot['end_time'] }}')"
                                    class="bg-orange text-oxford text-xs font-semibold px-3 py-1.5 rounded-lg hover:opacity-90 transition cursor-pointer">
                                    Sélectionner
                                </button>
                                @else
                                <span class="text-vista/40 text-xs">Passé</span>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-12">
                        <div class="w-14 h-14 bg-vista/10 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <i class="fa-regular fa-calendar-xmark text-vista text-xl"></i>
                        </div>
                        <p class="text-vista text-sm">Aucune disponibilité définie pour cette semaine.</p>
                        <p class="text-vista/50 text-xs mt-1">Essaie une autre semaine.</p>
                    </div>
                    @endif
                </div>
            </div>

            {{-- ===== RIGHT — Booking Form ===== --}}
            <div class="lg:col-span-2">
                <div class="bg-white/5 border border-vista/20 rounded-2xl p-6 sticky top-24">
                    <h2 class="text-white font-bold text-lg mb-1">Confirmer la réservation</h2>
                    <p class="text-vista text-xs mb-6">Sélectionne un créneau ou remplis manuellement.</p>

                    <form method="POST" action="{{ route('sessions.store') }}" id="bookingForm" class="space-y-5">
                        @csrf
                        <input type="hidden" name="mentor_id" value="{{ $mentor->id }}">

                        {{-- Date --}}
                        <div>
                            <label class="text-vista text-xs font-semibold uppercase tracking-wider mb-2 block">
                                <i class="fa-regular fa-calendar mr-1"></i>Date
                            </label>
                            <input type="date" name="date" id="field_date"
                                value="{{ old('date') }}" min="{{ date('Y-m-d') }}" required
                                class="w-full bg-white/5 border border-vista/20 rounded-xl px-4 py-3 text-sm text-white
                                      focus:outline-none focus:border-orange transition placeholder-vista/40">
                        </div>

                        {{-- Heure début --}}
                        <div>
                            <label class="text-vista text-xs font-semibold uppercase tracking-wider mb-2 block">
                                <i class="fa-regular fa-clock mr-1"></i>Heure de début
                            </label>
                            <input type="time" name="start_time" id="field_start"
                                value="{{ old('start_time') }}" required
                                class="w-full bg-white/5 border border-vista/20 rounded-xl px-4 py-3 text-sm text-white
                                      focus:outline-none focus:border-orange transition">
                        </div>

                        {{-- Heure fin --}}
                        <div>
                            <label class="text-vista text-xs font-semibold uppercase tracking-wider mb-2 block">
                                <i class="fa-regular fa-clock mr-1"></i>Heure de fin
                            </label>
                            <input type="time" name="end_time" id="field_end"
                                value="{{ old('end_time') }}" required
                                class="w-full bg-white/5 border border-vista/20 rounded-xl px-4 py-3 text-sm text-white
                                      focus:outline-none focus:border-orange transition">
                        </div>

                        {{-- Note --}}
                        <div>
                            <label class="text-vista text-xs font-semibold uppercase tracking-wider mb-2 block">
                                <i class="fa-regular fa-message mr-1"></i>Note <span class="text-vista/40 normal-case">(optionnel)</span>
                            </label>
                            <textarea name="note" rows="3" maxlength="500"
                                placeholder="Un mot sur ce que tu souhaites aborder..."
                                class="w-full bg-white/5 border border-vista/20 rounded-xl px-4 py-3 text-sm text-white
                                         focus:outline-none focus:border-orange transition resize-none placeholder-vista/40">{{ old('note') }}</textarea>
                        </div>

                        <button type="submit"
                            class="w-full bg-orange text-oxford font-bold py-3 rounded-xl hover:opacity-90 transition text-sm flex items-center justify-center gap-2">
                            <i class="fa-solid fa-paper-plane"></i>
                            Envoyer la demande
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </main>

    {{-- ===== FOOTER ===== --}}
    <footer class="border-t border-vista/10 mt-16 py-8">
        <div class="max-w-7xl mx-auto px-6 text-center">
            <span class="text-white font-bold text-lg">
                Mentor<span class="text-orange">Link</span>
            </span>
            <p class="text-vista/50 text-xs mt-2">© 2026 MentorLink | All right deserved</p>
        </div>
    </footer>

    <script>
        function fillForm(date, start, end) {
            document.getElementById('field_date').value = date;
            document.getElementById('field_start').value = start;
            document.getElementById('field_end').value = end;
            document.getElementById('bookingForm').scrollIntoView({
                behavior: 'smooth'
            });
        }
    </script>

</body>

</html>