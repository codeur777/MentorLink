@extends('layouts.admin')

@section('title', 'Dashboard')
@section('subtitle', 'Vue d\'ensemble de la plateforme')

@section('content')

{{-- Cartes stats principales --}}
<div class="grid grid-cols-4 gap-6 mb-8">

    <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <div class="w-11 h-11 bg-orange/10 rounded-xl flex items-center justify-center">
                <i class="fa-solid fa-users text-orange"></i>
            </div>
            <span class="text-xs text-gray-400">Total</span>
        </div>
        <p class="text-3xl font-extrabold text-oxford">{{ $stats['total_users'] }}</p>
        <p class="text-gray-500 text-sm mt-1">Utilisateurs</p>
    </div>

    <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <div class="w-11 h-11 bg-vista/20 rounded-xl flex items-center justify-center">
                <i class="fa-solid fa-chalkboard-user text-vista"></i>
            </div>
            <span class="text-xs text-gray-400">Validés</span>
        </div>
        <p class="text-3xl font-extrabold text-oxford">{{ $stats['validated_mentors'] }}</p>
        <p class="text-gray-500 text-sm mt-1">Mentors actifs</p>
    </div>

    <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <div class="w-11 h-11 bg-green-50 rounded-xl flex items-center justify-center">
                <i class="fa-solid fa-graduation-cap text-green-500"></i>
            </div>
            <span class="text-xs text-gray-400">Inscrits</span>
        </div>
        <p class="text-3xl font-extrabold text-oxford">{{ $stats['total_mentees'] }}</p>
        <p class="text-gray-500 text-sm mt-1">Mentorés</p>
    </div>

    <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <div class="w-11 h-11 bg-purple-50 rounded-xl flex items-center justify-center">
                <i class="fa-solid fa-calendar-check text-purple-500"></i>
            </div>
            <span class="text-xs text-gray-400">Total</span>
        </div>
        <p class="text-3xl font-extrabold text-oxford">{{ $stats['total_sessions'] }}</p>
        <p class="text-gray-500 text-sm mt-1">Sessions</p>
    </div>

</div>

{{-- Alertes + Actions rapides --}}
<div class="grid grid-cols-3 gap-6 mb-8">

    {{-- Alerte mentors en attente --}}
    <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
        <p class="text-xs text-gray-400 uppercase tracking-widest mb-3">À traiter</p>
        <div class="flex items-center justify-between">
            <div>
                <p class="text-2xl font-extrabold {{ $stats['pending_mentors'] > 0 ? 'text-orange' : 'text-green-500' }}">
                    {{ $stats['pending_mentors'] }}
                </p>
                <p class="text-gray-500 text-sm">Mentors en attente</p>
            </div>
            <div class="w-11 h-11 {{ $stats['pending_mentors'] > 0 ? 'bg-orange/10' : 'bg-green-50' }} rounded-xl flex items-center justify-center">
                <i class="fa-solid fa-user-clock {{ $stats['pending_mentors'] > 0 ? 'text-orange' : 'text-green-500' }}"></i>
            </div>
        </div>
        <a href="{{ route('admin.pending-mentors') }}"
           class="mt-4 block text-center bg-orange text-white text-xs font-semibold py-2 rounded-xl hover:opacity-90 transition">
            Voir les demandes
        </a>
    </div>

    {{-- Alerte signalements --}}
    <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
        <p class="text-xs text-gray-400 uppercase tracking-widest mb-3">Signalements</p>
        <div class="flex items-center justify-between">
            <div>
                <p class="text-2xl font-extrabold {{ $stats['open_reports'] > 0 ? 'text-red-500' : 'text-green-500' }}">
                    {{ $stats['open_reports'] }}
                </p>
                <p class="text-gray-500 text-sm">Ouverts</p>
            </div>
            <div class="w-11 h-11 {{ $stats['open_reports'] > 0 ? 'bg-red-50' : 'bg-green-50' }} rounded-xl flex items-center justify-center">
                <i class="fa-solid fa-flag {{ $stats['open_reports'] > 0 ? 'text-red-500' : 'text-green-500' }}"></i>
            </div>
        </div>
        <a href="{{ route('admin.reports') }}"
           class="mt-4 block text-center bg-oxford text-white text-xs font-semibold py-2 rounded-xl hover:opacity-90 transition">
            Gérer les signalements
        </a>
    </div>

    {{-- Lien stats --}}
    <div class="bg-oxford rounded-2xl p-6 shadow-sm flex flex-col justify-between">
        <div>
            <p class="text-vista text-xs uppercase tracking-widest mb-3">Analyse</p>
            <p class="text-white font-bold text-lg leading-snug">Voir les statistiques détaillées</p>
            <p class="text-vista/60 text-sm mt-2">Sessions, notes, activité globale</p>
        </div>
        <a href="{{ route('admin.stats') }}"
           class="mt-6 inline-flex items-center gap-2 bg-orange text-oxford text-xs font-semibold px-4 py-2 rounded-xl hover:opacity-90 transition w-fit">
            <i class="fa-solid fa-chart-bar"></i> Statistiques
        </a>
    </div>

</div>

@endsection
