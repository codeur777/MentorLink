@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')

<div class="mb-8">
    <h1 class="text-2xl font-extrabold text-oxford">Bonjour, {{ $user->name }} </h1>
    <p class="text-gray-500 text-sm mt-1 capitalize">{{ $user->role }} - Bienvenue sur MentorLink</p>
</div>

{{-- Cartes stats --}}
@if(count($stats) > 0)
<div class="grid grid-cols-2 md:grid-cols-3 gap-6 mb-8">
    @foreach($stats as $key => $value)
    <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
        <p class="text-gray-400 text-xs uppercase tracking-widest mb-2">{{ ucfirst(str_replace('_', ' ', $key)) }}</p>
        <p class="text-3xl font-extrabold text-oxford">{{ $value }}</p>
    </div>
    @endforeach
</div>
@endif

{{-- Actions rapides --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
    <p class="text-xs text-gray-400 uppercase tracking-widest mb-4">Actions rapides</p>
    <div class="flex flex-wrap gap-3">
        @if($user->isMentee())
            <a href="{{ route('mentors.index') }}"
               class="bg-orange text-white text-sm font-semibold px-5 py-2.5 rounded-xl hover:opacity-90 transition">
                <i class="fa-solid fa-magnifying-glass mr-2"></i>Trouver un mentor
            </a>
            <a href="{{ route('sessions.index') }}"
               class="border border-oxford text-oxford text-sm font-semibold px-5 py-2.5 rounded-xl hover:bg-oxford hover:text-white transition">
                <i class="fa-solid fa-calendar mr-2"></i>Mes sessions
            </a>
        @elseif($user->isMentor())
            <a href="{{ route('mentor.profile') }}"
               class="bg-orange text-white text-sm font-semibold px-5 py-2.5 rounded-xl hover:opacity-90 transition">
                <i class="fa-solid fa-user mr-2"></i>Mon profil
            </a>
            <a href="{{ route('sessions.index') }}"
               class="border border-oxford text-oxford text-sm font-semibold px-5 py-2.5 rounded-xl hover:bg-oxford hover:text-white transition">
                <i class="fa-solid fa-calendar mr-2"></i>Mes sessions
            </a>
            <a href="{{ route('availabilities.create') }}"
               class="border border-vista text-vista text-sm font-semibold px-5 py-2.5 rounded-xl hover:bg-vista hover:text-white transition">
                <i class="fa-solid fa-clock mr-2"></i>Disponibilités
            </a>
        @endif
    </div>
</div>

@endsection
