@extends('layouts.app')
@section('title', 'Mon profil mentor')

@section('content')

<div class="mb-6">
    <a href="{{ route('dashboard') }}" class="text-vista text-sm hover:text-oxford transition">
        <i class="fa-solid fa-arrow-left mr-1"></i> Dashboard
    </a>
</div>

@if($errors->any())
    <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6">
        <ul class="text-red-600 text-sm space-y-1">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="grid grid-cols-3 gap-6">

    {{-- Formulaire profil --}}
    <div class="col-span-2">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <p class="text-xs text-gray-400 uppercase tracking-widest mb-6">Informations du profil</p>

            <form method="POST" action="{{ route('mentor.profile.update') }}" class="flex flex-col gap-5">
                @csrf

                {{-- Domaines --}}
                <div>
                    <label class="text-xs font-semibold text-oxford mb-3 block">Domaines d'expertise</label>
                    <div class="grid grid-cols-2 gap-2">
                        @foreach(['web' => 'Développement Web', 'mobile' => 'Développement Mobile', 'data' => 'Data Science', 'devops' => 'DevOps', 'design' => 'Design', 'python' => 'Python', 'javascript' => 'JavaScript', 'php' => 'PHP', 'machine-learning' => 'Machine Learning'] as $val => $label)
                            <label class="flex items-center gap-2 cursor-pointer p-2 rounded-xl hover:bg-silver transition">
                                <input type="checkbox" name="domains[]" value="{{ $val }}"
                                       class="w-4 h-4 text-orange border-gray-300 rounded"
                                       {{ in_array($val, old('domains', $user->mentorProfile->domains ?? [])) ? 'checked' : '' }}>
                                <span class="text-sm text-oxford">{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                {{-- Tarif --}}
                <div>
                    <label class="text-xs font-semibold text-oxford mb-1 block">Tarif horaire (FCFA)</label>
                    <input type="number" name="hourly_rate" step="0.01" min="0"
                           value="{{ old('hourly_rate', $user->mentorProfile->hourly_rate ?? '') }}"
                           placeholder="0 = Gratuit"
                           class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm text-oxford focus:outline-none focus:border-orange transition">
                </div>

                <button type="submit"
                        class="bg-orange text-white font-semibold py-3 rounded-xl hover:opacity-90 transition text-sm">
                    Mettre à jour le profil
                </button>
            </form>
        </div>
    </div>

    {{-- Colonne droite --}}
    <div class="flex flex-col gap-6">

        {{-- Statut validation --}}
        @if($user->mentorProfile)
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <p class="text-xs text-gray-400 uppercase tracking-widest mb-4">Statut</p>
            @if($user->mentorProfile->is_validated)
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-green-50 rounded-xl flex items-center justify-center">
                        <i class="fa-solid fa-circle-check text-green-500 text-sm"></i>
                    </div>
                    <span class="text-green-600 font-semibold text-sm">Profil validé</span>
                </div>
            @else
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-orange/10 rounded-xl flex items-center justify-center">
                        <i class="fa-solid fa-clock text-orange text-sm"></i>
                    </div>
                    <span class="text-orange font-semibold text-sm">En attente de validation</span>
                </div>
            @endif
        </div>
        @endif

        {{-- Disponibilités --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <p class="text-xs text-gray-400 uppercase tracking-widest">Disponibilités</p>
                <a href="{{ route('availabilities.create') }}"
                   class="bg-orange text-white text-xs font-semibold px-3 py-1.5 rounded-xl hover:opacity-90 transition">
                    <i class="fa-solid fa-plus mr-1"></i>Ajouter
                </a>
            </div>

            @php $days = ['Dimanche','Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi']; @endphp

            @if($user->availabilities->count() > 0)
                <div class="flex flex-col gap-2">
                    @foreach($user->availabilities as $availability)
                        <div class="flex items-center justify-between py-2 border-b border-gray-50 last:border-0">
                            <div>
                                <p class="text-oxford text-sm font-medium">{{ $days[$availability->day_of_week] }}</p>
                                <p class="text-gray-400 text-xs">{{ $availability->start_time }} – {{ $availability->end_time }}</p>
                            </div>
                            <form method="POST" action="{{ route('availabilities.destroy', $availability) }}">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        onclick="return confirm('Supprimer cette disponibilité ?')"
                                        class="text-red-400 hover:text-red-600 text-xs transition">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-400 text-sm">Aucune disponibilité.</p>
            @endif
        </div>

    </div>
</div>

@endsection
