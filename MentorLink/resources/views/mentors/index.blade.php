@extends('layouts.app')
@section('title', 'Mentors')

@section('content')

<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-2xl font-extrabold text-oxford">Nos mentors</h1>
        <p class="text-gray-500 text-sm mt-1">Trouve le mentor qui correspond à tes besoins</p>
    </div>

    {{-- Filtre domaine --}}
    <form method="GET">
        <select name="domain" onchange="this.form.submit()"
                class="border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-oxford focus:outline-none focus:border-orange bg-white">
            <option value="">Tous les domaines</option>
            @foreach(['web','mobile','data','devops','design','python','javascript','php','machine-learning'] as $d)
                <option value="{{ $d }}" {{ request('domain') === $d ? 'selected' : '' }}>{{ ucfirst($d) }}</option>
            @endforeach
        </select>
    </form>
</div>

@if($mentors->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
        @foreach($mentors as $mentor)
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 hover:border-orange/30 transition">

            {{-- Header --}}
            <div class="flex items-center gap-3 mb-4">
                <div class="w-12 h-12 rounded-full bg-orange flex items-center justify-center font-bold text-oxford text-sm flex-shrink-0">
                    {{ strtoupper(substr($mentor->name, 0, 2)) }}
                </div>
                <div>
                    <p class="text-oxford font-semibold">{{ $mentor->name }}</p>
                    @if($mentor->mentorProfile?->average_rating)
                        <p class="text-orange text-xs">★ {{ number_format($mentor->mentorProfile->average_rating, 1) }} / 5
                            <span class="text-gray-400">({{ $mentor->mentorProfile->review_count }} avis)</span>
                        </p>
                    @else
                        <p class="text-vista text-xs">Nouveau mentor</p>
                    @endif
                </div>
            </div>

            {{-- Domaines --}}
            <div class="flex flex-wrap gap-2 mb-4">
                @foreach(array_slice($mentor->mentorProfile?->domains ?? [], 0, 3) as $domain)
                    <span class="bg-vista/20 text-vista text-xs px-2 py-1 rounded-full">{{ $domain }}</span>
                @endforeach
            </div>

            {{-- Footer --}}
            <div class="flex items-center justify-between pt-4 border-t border-gray-50">
                <span class="text-oxford text-sm font-semibold">
                    {{ $mentor->mentorProfile?->hourly_rate ? number_format($mentor->mentorProfile->hourly_rate) . ' FCFA/h' : 'Gratuit' }}
                </span>
                <div class="flex gap-2">
                    <a href="{{ route('mentors.show', $mentor->id) }}"
                       class="border border-oxford text-oxford text-xs font-semibold px-3 py-1.5 rounded-xl hover:bg-oxford hover:text-white transition">
                        Voir profil
                    </a>
                    @if(auth()->user()->isMentee())
                        <a href="{{ route('sessions.create', ['mentor_id' => $mentor->id]) }}"
                           class="bg-orange text-white text-xs font-semibold px-3 py-1.5 rounded-xl hover:opacity-90 transition">
                            Réserver
                        </a>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{ $mentors->links() }}

@else
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-16 text-center">
        <div class="w-16 h-16 bg-vista/20 rounded-2xl flex items-center justify-center mx-auto mb-4">
            <i class="fa-solid fa-user-slash text-vista text-2xl"></i>
        </div>
        <p class="text-oxford font-bold text-lg mb-1">Aucun mentor trouvé</p>
        <p class="text-gray-400 text-sm">Essaie un autre filtre de domaine.</p>
    </div>
@endif

@endsection
