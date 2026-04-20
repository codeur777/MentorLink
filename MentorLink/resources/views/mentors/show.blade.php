@extends('layouts.app')
@section('title', $mentor->name)

@section('content')

@php $days = ['Dimanche','Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi']; @endphp

<div class="mb-6">
    <a href="{{ route('mentors.index') }}" class="text-vista text-sm hover:text-oxford transition">
        <i class="fa-solid fa-arrow-left mr-1"></i> Liste des mentors
    </a>
</div>

<div class="grid grid-cols-3 gap-6">

    {{-- Colonne gauche --}}
    <div class="col-span-1 flex flex-col gap-6">

        {{-- Carte identité --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 text-center">
            <div class="w-20 h-20 rounded-full bg-orange flex items-center justify-center font-bold text-oxford text-2xl mx-auto mb-4">
                {{ strtoupper(substr($mentor->name, 0, 2)) }}
            </div>
            <p class="text-oxford font-bold text-lg">{{ $mentor->name }}</p>
            <p class="text-gray-400 text-sm">{{ $mentor->email }}</p>
            @if($mentor->mentorProfile?->average_rating)
                <p class="text-orange text-sm mt-2">★ {{ number_format($mentor->mentorProfile->average_rating, 1) }} / 5
                    <span class="text-gray-400 text-xs">({{ $mentor->mentorProfile->review_count }} avis)</span>
                </p>
            @endif
            @if($mentor->bio)
                <p class="text-gray-500 text-sm mt-3 leading-relaxed">{{ $mentor->bio }}</p>
            @endif
        </div>

        {{-- Profil mentor --}}
        @if($mentor->mentorProfile)
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <p class="text-xs text-gray-400 uppercase tracking-widest mb-4">Profil mentor</p>
            <div class="flex flex-wrap gap-2 mb-4">
                @foreach($mentor->mentorProfile->domains ?? [] as $domain)
                    <span class="bg-vista/20 text-vista text-xs px-2 py-1 rounded-full">{{ $domain }}</span>
                @endforeach
            </div>
            <p class="text-oxford font-bold text-lg">
                {{ $mentor->mentorProfile->hourly_rate ? number_format($mentor->mentorProfile->hourly_rate) . ' FCFA/h' : 'Gratuit' }}
            </p>
        </div>
        @endif

        {{-- Bouton réserver --}}
        @if(auth()->user()->isMentee() && $mentor->mentorProfile?->is_validated)
            <a href="{{ route('sessions.create', ['mentor_id' => $mentor->id]) }}"
               class="bg-orange text-white font-semibold py-3 rounded-xl hover:opacity-90 transition text-sm text-center">
                <i class="fa-solid fa-calendar-plus mr-2"></i>Réserver une session
            </a>
        @endif
    </div>

    {{-- Colonne droite --}}
    <div class="col-span-2 flex flex-col gap-6">

        {{-- Disponibilités --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <p class="text-xs text-gray-400 uppercase tracking-widest mb-4">Disponibilités hebdomadaires</p>
            @if($mentor->availabilities->count() > 0)
                <div class="flex flex-col gap-2">
                    @foreach($mentor->availabilities->sortBy('day_of_week') as $a)
                        <div class="flex items-center justify-between py-2 border-b border-gray-50 last:border-0">
                            <span class="text-oxford text-sm font-medium">{{ $days[$a->day_of_week] }}</span>
                            <span class="bg-vista/20 text-vista text-xs px-3 py-1 rounded-full">
                                {{ $a->start_time }} – {{ $a->end_time }}
                            </span>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-400 text-sm">Aucune disponibilité renseignée.</p>
            @endif
        </div>

        {{-- Avis --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <p class="text-xs text-gray-400 uppercase tracking-widest mb-4">Avis ({{ $reviews->count() }})</p>
            @forelse($reviews as $review)
                <div class="py-4 border-b border-gray-50 last:border-0">
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-orange text-sm">
                            @for($i = 1; $i <= 5; $i++)
                                {{ $i <= $review->rating ? '★' : '☆' }}
                            @endfor
                        </span>
                        <span class="text-gray-400 text-xs">{{ $review->created_at->format('d/m/Y') }}</span>
                    </div>
                    @if($review->comment)
                        <p class="text-gray-600 text-sm">{{ $review->comment }}</p>
                    @endif
                </div>
            @empty
                <p class="text-gray-400 text-sm">Aucun avis pour ce mentor.</p>
            @endforelse
        </div>

    </div>
</div>

@endsection
