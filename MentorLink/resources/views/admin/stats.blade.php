@extends('layouts.admin')

@section('title', 'Statistiques')
@section('subtitle', 'Métriques complètes de la plateforme')

@section('content')

<div class="grid grid-cols-2 gap-6">

    {{-- Utilisateurs --}}
    <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
        <div class="flex items-center gap-3 mb-5">
            <div class="w-10 h-10 bg-orange/10 rounded-xl flex items-center justify-center">
                <i class="fa-solid fa-users text-orange"></i>
            </div>
            <h2 class="text-oxford font-bold">Utilisateurs</h2>
        </div>
        <div class="space-y-3">
            @foreach([
                ['Total utilisateurs',    $stats['total_users'],         'text-oxford'],
                ['Mentors',               $stats['total_mentors'],        'text-vista'],
                ['Mentorés',              $stats['total_mentees'],        'text-vista'],
                ['Mentors validés',       $stats['validated_mentors'],    'text-green-500'],
                ['En attente validation', $stats['pending_validations'],  'text-orange'],
                ['Suspendus',             $stats['suspended_users'],      'text-red-500'],
            ] as [$label, $value, $color])
            <div class="flex items-center justify-between py-2 border-b border-gray-50 last:border-0">
                <span class="text-gray-500 text-sm">{{ $label }}</span>
                <span class="font-bold text-sm {{ $color }}">{{ $value }}</span>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Sessions --}}
    <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
        <div class="flex items-center gap-3 mb-5">
            <div class="w-10 h-10 bg-vista/20 rounded-xl flex items-center justify-center">
                <i class="fa-solid fa-calendar-check text-vista"></i>
            </div>
            <h2 class="text-oxford font-bold">Sessions</h2>
        </div>
        <div class="space-y-3">
            @foreach([
                ['Total sessions',    $stats['total_sessions'],     'text-oxford'],
                ['En attente',        $stats['pending_sessions'],   'text-orange'],
                ['Confirmées',        $stats['confirmed_sessions'], 'text-vista'],
                ['Terminées',         $stats['completed_sessions'], 'text-green-500'],
                ['Annulées',          $stats['cancelled_sessions'], 'text-red-500'],
            ] as [$label, $value, $color])
            <div class="flex items-center justify-between py-2 border-b border-gray-50 last:border-0">
                <span class="text-gray-500 text-sm">{{ $label }}</span>
                <span class="font-bold text-sm {{ $color }}">{{ $value }}</span>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Signalements --}}
    <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
        <div class="flex items-center gap-3 mb-5">
            <div class="w-10 h-10 bg-red-50 rounded-xl flex items-center justify-center">
                <i class="fa-solid fa-flag text-red-500"></i>
            </div>
            <h2 class="text-oxford font-bold">Signalements</h2>
        </div>
        <div class="space-y-3">
            @foreach([
                ['Ouverts',   $stats['open_reports'],     'text-red-500'],
                ['Résolus',   $stats['resolved_reports'], 'text-green-500'],
            ] as [$label, $value, $color])
            <div class="flex items-center justify-between py-2 border-b border-gray-50 last:border-0">
                <span class="text-gray-500 text-sm">{{ $label }}</span>
                <span class="font-bold text-sm {{ $color }}">{{ $value }}</span>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Résumé visuel --}}
    <div class="bg-oxford rounded-2xl p-6 shadow-sm flex flex-col justify-between">
        <div>
            <p class="text-vista text-xs uppercase tracking-widest mb-4">Résumé</p>
            <div class="space-y-4">
                @php
                    $total = max($stats['total_sessions'], 1);
                    $pct   = round($stats['completed_sessions'] / $total * 100);
                @endphp
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="text-vista">Sessions terminées</span>
                        <span class="text-white font-semibold">{{ $pct }}%</span>
                    </div>
                    <div class="w-full bg-white/10 rounded-full h-2">
                        <div class="bg-orange h-2 rounded-full" style="width: {{ $pct }}%"></div>
                    </div>
                </div>
                @php
                    $totalMentors = max($stats['total_mentors'], 1);
                    $pctV = round($stats['validated_mentors'] / $totalMentors * 100);
                @endphp
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="text-vista">Mentors validés</span>
                        <span class="text-white font-semibold">{{ $pctV }}%</span>
                    </div>
                    <div class="w-full bg-white/10 rounded-full h-2">
                        <div class="bg-vista h-2 rounded-full" style="width: {{ $pctV }}%"></div>
                    </div>
                </div>
            </div>
        </div>
        <a href="{{ route('admin.dashboard') }}"
           class="mt-6 inline-flex items-center gap-2 bg-orange text-oxford text-xs font-semibold px-4 py-2 rounded-xl hover:opacity-90 transition w-fit">
            <i class="fa-solid fa-arrow-left"></i> Dashboard
        </a>
    </div>

</div>

@endsection
