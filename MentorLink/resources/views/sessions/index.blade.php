@extends('layouts.app')
@section('title', 'Mes sessions')

@section('content')

<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-2xl font-extrabold text-oxford">Mes sessions</h1>
        <p class="text-gray-500 text-sm mt-1">Historique et gestion de tes sessions</p>
    </div>
    @if(auth()->user()->isMentee())
        <a href="{{ route('mentors.index') }}"
           class="bg-orange text-white text-sm font-semibold px-5 py-2.5 rounded-xl hover:opacity-90 transition">
            <i class="fa-solid fa-plus mr-2"></i>Réserver une session
        </a>
    @endif
</div>

@php
    $statusConfig = [
        'pending'   => ['label' => 'En attente',  'class' => 'bg-orange/10 text-orange'],
        'confirmed' => ['label' => 'Confirmée',   'class' => 'bg-vista/20 text-vista'],
        'completed' => ['label' => 'Terminée',    'class' => 'bg-green-50 text-green-600'],
        'cancelled' => ['label' => 'Annulée',     'class' => 'bg-red-50 text-red-500'],
    ];
@endphp

@if($sessions->count() > 0)
    <div class="flex flex-col gap-4 mb-6">
        @foreach($sessions as $session)
        @php $cfg = $statusConfig[$session->status] ?? ['label' => $session->status, 'class' => 'bg-gray-100 text-gray-500']; @endphp
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <div class="flex items-start justify-between gap-4">

                {{-- Infos session --}}
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-oxford rounded-xl flex flex-col items-center justify-center flex-shrink-0">
                        <span class="text-white text-xs font-bold">{{ $session->date->format('d') }}</span>
                        <span class="text-vista text-xs">{{ $session->date->format('M') }}</span>
                    </div>
                    <div>
                        <p class="text-oxford font-semibold">
                            @if($user->isMentee())
                                Avec {{ $session->mentor->name }}
                            @else
                                Avec {{ $session->mentee->name }}
                            @endif
                        </p>
                        <p class="text-gray-400 text-sm">{{ $session->start_time }} – {{ $session->end_time }}</p>
                        @if($session->note)
                            <p class="text-gray-500 text-xs mt-1 italic">{{ $session->note }}</p>
                        @endif
                    </div>
                </div>

                {{-- Statut + actions --}}
                <div class="flex flex-col items-end gap-3">
                    <span class="text-xs font-semibold px-3 py-1 rounded-full {{ $cfg['class'] }}">
                        {{ $cfg['label'] }}
                    </span>

                    <div class="flex items-center gap-2">
                        {{-- Actions mentor --}}
                        @if($user->isMentor())
                            @if($session->isPending())
                                <form method="POST" action="{{ route('sessions.confirm', $session) }}">
                                    @csrf @method('PATCH')
                                    <button class="bg-oxford text-white text-xs font-semibold px-3 py-1.5 rounded-xl hover:opacity-80 transition">
                                        Confirmer
                                    </button>
                                </form>
                            @endif
                            @if($session->isConfirmed())
                                <form method="POST" action="{{ route('sessions.complete', $session) }}">
                                    @csrf @method('PATCH')
                                    <button class="bg-green-500 text-white text-xs font-semibold px-3 py-1.5 rounded-xl hover:opacity-80 transition">
                                        Terminer
                                    </button>
                                </form>
                            @endif
                        @endif

                        {{-- Rejoindre la réunion (session confirmée uniquement) --}}
                        @if($session->isConfirmed() && $session->meeting_room_id)
                            <a href="{{ route('sessions.meeting', $session) }}"
                               class="bg-green-500 text-white text-xs font-semibold px-3 py-1.5 rounded-xl hover:bg-green-600 transition flex items-center gap-1.5">
                                <i class="fa-solid fa-video"></i>
                                Rejoindre
                            </a>
                        @endif

                        {{-- Annuler --}}
                        @if(in_array($session->status, ['pending', 'confirmed']))
                            <form method="POST" action="{{ route('sessions.cancel', $session) }}">
                                @csrf @method('PATCH')
                                <button onclick="return confirm('Annuler cette session ?')"
                                        class="bg-red-50 text-red-500 text-xs font-semibold px-3 py-1.5 rounded-xl hover:bg-red-100 transition">
                                    Annuler
                                </button>
                            </form>
                        @endif

                        {{-- Avis --}}
                        @if($user->isMentee() && $session->isCompleted() && !$session->review)
                            <a href="{{ route('reviews.create', $session) }}"
                               class="bg-orange text-white text-xs font-semibold px-3 py-1.5 rounded-xl hover:opacity-90 transition">
                                Laisser un avis
                            </a>
                        @endif

                        {{-- Signaler --}}
                        @if($session->isCompleted())
                            @php $alreadyReported = \App\Models\Report::where('reporter_id', $user->id)->where('session_id', $session->id)->exists(); @endphp
                            @if(!$alreadyReported)
                                <a href="{{ route('reports.create', $session) }}"
                                   class="border border-gray-200 text-gray-400 text-xs font-semibold px-3 py-1.5 rounded-xl hover:border-red-300 hover:text-red-400 transition">
                                    <i class="fa-solid fa-flag"></i>
                                </a>
                            @endif
                        @endif
                    </div>

                    {{-- Avis existant --}}
                    @if($session->review)
                        <div class="text-right">
                            <span class="text-orange text-xs">
                                @for($i = 1; $i <= 5; $i++){{ $i <= $session->review->rating ? '★' : '☆' }}@endfor
                            </span>
                            @if($session->review->comment)
                                <p class="text-gray-400 text-xs italic mt-0.5">{{ Str::limit($session->review->comment, 60) }}</p>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{ $sessions->links() }}

@else
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-16 text-center">
        <div class="w-16 h-16 bg-vista/20 rounded-2xl flex items-center justify-center mx-auto mb-4">
            <i class="fa-solid fa-calendar-xmark text-vista text-2xl"></i>
        </div>
        <p class="text-oxford font-bold text-lg mb-1">Aucune session</p>
        <p class="text-gray-400 text-sm mb-6">Tu n'as pas encore de sessions.</p>
        @if($user->isMentee())
            <a href="{{ route('mentors.index') }}"
               class="bg-orange text-white text-sm font-semibold px-6 py-2.5 rounded-xl hover:opacity-90 transition">
                Trouver un mentor
            </a>
        @endif
    </div>
@endif

@endsection
