@extends('layouts.app')
@section('title', 'Ajouter une disponibilité')

@section('content')

<div class="mb-6">
    <a href="{{ route('mentor.profile') }}" class="text-vista text-sm hover:text-oxford transition">
        <i class="fa-solid fa-arrow-left mr-1"></i> Mon profil
    </a>
</div>

@if($errors->any())
    <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6">
        <ul class="text-red-600 text-sm space-y-1">
            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
@endif

<div class="max-w-lg">
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-4">
        <p class="text-xs text-gray-400 uppercase tracking-widest mb-5">Nouveau créneau</p>

        <form method="POST" action="{{ route('availabilities.store') }}" class="flex flex-col gap-5">
            @csrf

            <div>
                <label class="text-xs font-semibold text-oxford mb-1 block">Jour de la semaine</label>
                <select name="day_of_week" required
                        class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm text-oxford focus:outline-none focus:border-orange transition bg-white">
                    <option value="">Choisir un jour</option>
                    <option value="1" {{ old('day_of_week') == '1' ? 'selected' : '' }}>Lundi</option>
                    <option value="2" {{ old('day_of_week') == '2' ? 'selected' : '' }}>Mardi</option>
                    <option value="3" {{ old('day_of_week') == '3' ? 'selected' : '' }}>Mercredi</option>
                    <option value="4" {{ old('day_of_week') == '4' ? 'selected' : '' }}>Jeudi</option>
                    <option value="5" {{ old('day_of_week') == '5' ? 'selected' : '' }}>Vendredi</option>
                    <option value="6" {{ old('day_of_week') == '6' ? 'selected' : '' }}>Samedi</option>
                    <option value="0" {{ old('day_of_week') == '0' ? 'selected' : '' }}>Dimanche</option>
                </select>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-xs font-semibold text-oxford mb-1 block">Heure de début</label>
                    <input type="time" name="start_time" value="{{ old('start_time') }}" required
                           class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-orange transition">
                </div>
                <div>
                    <label class="text-xs font-semibold text-oxford mb-1 block">Heure de fin</label>
                    <input type="time" name="end_time" value="{{ old('end_time') }}" required
                           class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-orange transition">
                </div>
            </div>

            <button type="submit"
                    class="bg-orange text-white font-semibold py-3 rounded-xl hover:opacity-90 transition text-sm">
                Ajouter la disponibilité
            </button>
        </form>
    </div>

    <div class="bg-oxford/5 border border-oxford/10 rounded-2xl p-4 flex gap-3">
        <i class="fa-solid fa-circle-info text-vista mt-0.5"></i>
        <p class="text-oxford/70 text-sm leading-relaxed">
            Les disponibilités sont récurrentes chaque semaine. Par exemple, "Lundi 09:00–12:00" te rend disponible tous les lundis matin.
        </p>
    </div>
</div>

@endsection
