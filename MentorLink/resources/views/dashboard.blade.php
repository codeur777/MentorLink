@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')

<div class="mb-8">
    <h1 class="text-2xl font-extrabold text-oxford">Bonjour, {{ $user->name }} 👋</h1>
    <p class="text-gray-500 text-sm mt-1 capitalize">{{ $user->role }} — Bienvenue sur MentorLink</p>
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


    <section class="page-hero">
        <div class="section-heading" data-reveal>
            <span class="eyebrow">Suivi des accompagnements</span>
            <h1 class="page-title">Un espace pour suivre les creneaux, les sessions et les retours apres rendez-vous.</h1>
            <p class="hero-text">
                Ce tableau de bord parle maintenant le langage attendu par votre contexte:
                reservations, sessions prevues, progression du mentorat et evaluations.
            </p>
        </div>

        <div class="hero-panel" data-reveal>
            <div class="dashboard-grid">
                @foreach ($overviewMetrics as $metric)
                    <article class="dashboard-card">
                        <p class="card-label">{{ $metric['label'] }}</p>
                        <strong class="dashboard-value">{{ $metric['value'] }}</strong>
                        <span>{{ $metric['hint'] }}</span>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="content-section dashboard-board">
        <div class="board-main">
            <article class="dashboard-card board-card board-card-large" data-reveal>
                <p class="card-label">Suivi des besoins</p>
                @if ($goals->isEmpty())
                    <div class="empty-state-card">
                        <h3>Aucun besoin de mentorat en cours.</h3>
                        <p>Les besoins declares par les juniors apparaitront ici pour suivre leur progression.</p>
                    </div>
                @else
                    <div class="goal-list">
                        @foreach ($goals as $goal)
                            <div class="goal-item">
                                <div>
                                    <h3>{{ $goal->title }}</h3>
                                    <p>{{ $goal->description ?: 'Description non renseignee.' }}</p>
                                    <small class="goal-context">
                                        {{ $goal->mentorship?->mentor?->name }} avec {{ $goal->mentorship?->mentee?->name }}
                                    </small>
                                </div>
                                <span class="progress-pill">{{ $goal->progress }}%</span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </article>

            <article class="dashboard-card board-card" data-reveal>
                <p class="card-label">Creneaux et sessions</p>
                @if ($sessions->isEmpty())
                    <div class="empty-state-card">
                        <h3>Aucun creneau reserve pour le moment.</h3>
                        <p>Les prochaines sessions entre seniors et juniors seront visibles ici.</p>
                    </div>
                @else
                    <div class="schedule-list">
                        @foreach ($sessions as $session)
                            <div class="schedule-item">
                                <strong>{{ optional($session->starts_at)->format('d/m/Y H:i') }}</strong>
                                <span>{{ $session->topic }}</span>
                                <small>{{ $session->format ?: 'Format non renseigne' }}</small>
                            </div>
                        @endforeach
                    </div>
                @endif
            </article>
        </div>

        <aside class="board-side">
            <article class="dashboard-card board-card" data-reveal>
                <p class="card-label">Etat des accompagnements</p>
                @if ($checkpoints->isEmpty())
                    <div class="empty-state-card">
                        <h3>Aucun accompagnement actif.</h3>
                        <p>Quand des binomes senior / junior seront en cours, cette colonne resumera leur activite.</p>
                    </div>
                @else
                    <ul class="detail-list">
                        @foreach ($checkpoints as $checkpoint)
                            <li>
                                <strong>{{ $checkpoint->title }}</strong><br>
                                besoins ouverts: {{ $checkpoint->open_goals_count }},
                                sessions planifiees: {{ $checkpoint->planned_sessions_count }}
                            </li>
                        @endforeach
                    </ul>
                @endif
            </article>

            <article class="dashboard-card note-card" data-reveal>
                <p class="card-label">Retour apres session</p>
                <p>
                    Cette zone peut ensuite servir a afficher les evaluations laissees apres chaque session
                    pour mesurer la qualite de l accompagnement et ajuster le suivi.
                </p>
            </article>
        </aside>
    </section>

@endsection
