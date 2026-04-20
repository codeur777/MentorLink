@extends('layouts.admin')

@section('title', 'Validation mentors')
@section('subtitle', 'Profils en attente d\'approbation')

@section('content')

@if($profiles->count() > 0)
    <div class="grid grid-cols-1 gap-4">
        @foreach($profiles as $profile)
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 flex items-center justify-between gap-6">

            {{-- Avatar + infos --}}
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-orange flex items-center justify-center font-bold text-oxford text-sm flex-shrink-0">
                    {{ strtoupper(substr($profile->user->name, 0, 2)) }}
                </div>
                <div>
                    <p class="text-oxford font-semibold">{{ $profile->user->name }}</p>
                    <p class="text-gray-400 text-sm">{{ $profile->user->email }}</p>
                </div>
            </div>

            {{-- Domaines --}}
            <div class="flex flex-wrap gap-2 flex-1">
                @foreach($profile->domains ?? [] as $domain)
                    <span class="bg-vista/20 text-vista text-xs px-3 py-1 rounded-full">{{ $domain }}</span>
                @endforeach
            </div>

            {{-- Tarif --}}
            <div class="text-center flex-shrink-0">
                <p class="text-oxford font-bold">{{ $profile->hourly_rate ? number_format($profile->hourly_rate) . ' FCFA/h' : 'Gratuit' }}</p>
                <p class="text-gray-400 text-xs">{{ $profile->created_at->format('d/m/Y') }}</p>
            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-3 flex-shrink-0">
                <a href="{{ route('mentors.show', $profile->user_id) }}"
                   class="border border-oxford text-oxford text-xs font-semibold px-4 py-2 rounded-xl hover:bg-oxford hover:text-white transition">
                    Voir profil
                </a>
                <form method="POST" action="{{ route('admin.mentors.validate', $profile->user_id) }}">
                    @csrf @method('PUT')
                    <button type="submit"
                            onclick="return confirm('Valider ce profil mentor ?')"
                            class="bg-orange text-white text-xs font-semibold px-4 py-2 rounded-xl hover:opacity-90 transition">
                        <i class="fa-solid fa-check mr-1"></i> Valider
                    </button>
                </form>
            </div>

        </div>
        @endforeach
    </div>

    <div class="mt-6">{{ $profiles->links() }}</div>

@else
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-16 text-center">
        <div class="w-16 h-16 bg-green-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
            <i class="fa-solid fa-circle-check text-green-500 text-2xl"></i>
        </div>
        <p class="text-oxford font-bold text-lg mb-1">Tout est à jour</p>
        <p class="text-gray-400 text-sm">Aucun mentor en attente de validation.</p>
        <a href="{{ route('admin.dashboard') }}"
           class="mt-6 inline-block bg-orange text-white text-sm font-semibold px-6 py-2.5 rounded-xl hover:opacity-90 transition">
            Retour au dashboard
        </a>
    </div>
@endif

@endsection
