@extends('layouts.app')
@section('title', 'Signaler un utilisateur')

@section('content')

<div class="mb-6">
    <a href="{{ route('sessions.index') }}" class="text-vista text-sm hover:text-oxford transition">
        <i class="fa-solid fa-arrow-left mr-1"></i> Mes sessions
    </a>
</div>

@if($errors->any())
    <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6">
        <ul class="text-red-600 text-sm space-y-1">
            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
        </ul>
    </div>
@endif

<div class="max-w-lg">

    {{-- Résumé session --}}
    <div class="bg-oxford rounded-2xl p-5 mb-4 flex items-center gap-4">
        <div class="w-12 h-12 bg-red-500 rounded-xl flex items-center justify-center flex-shrink-0">
            <i class="fa-solid fa-flag text-white"></i>
        </div>
        <div>
            <p class="text-white font-semibold">Signalement — {{ $reported->name }}</p>
            <p class="text-vista text-sm">Session du {{ $session->date->format('d/m/Y') }}, {{ $session->start_time }} – {{ $session->end_time }}</p>
        </div>
    </div>

    {{-- Formulaire --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <p class="text-xs text-gray-400 uppercase tracking-widest mb-5">Motif du signalement</p>

        <form method="POST" action="{{ route('reports.store', $session) }}" class="flex flex-col gap-5">
            @csrf

            <div>
                <label class="text-xs font-semibold text-oxford mb-1 block">Description</label>
                <textarea name="reason" rows="5" maxlength="1000" required
                          placeholder="Décris le problème rencontré (minimum 10 caractères)..."
                          class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-orange transition resize-none">{{ old('reason') }}</textarea>
            </div>

            <div class="bg-orange/5 border border-orange/20 rounded-xl p-4 flex gap-3">
                <i class="fa-solid fa-triangle-exclamation text-orange mt-0.5 text-sm"></i>
                <p class="text-oxford/70 text-xs leading-relaxed">
                    Les signalements abusifs peuvent entraîner des sanctions. Assure-toi que ton signalement est justifié.
                </p>
            </div>

            <button type="submit"
                    class="bg-red-500 text-white font-semibold py-3 rounded-xl hover:opacity-90 transition text-sm">
                <i class="fa-solid fa-flag mr-2"></i>Envoyer le signalement
            </button>
        </form>
    </div>
</div>

@endsection
