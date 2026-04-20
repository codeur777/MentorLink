<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - MentorLink</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
    <h1>Dashboard MentorLink</h1>
    <p>Bienvenue <strong>{{ $user->name }}</strong> ({{ ucfirst($user->role) }})</p>

    <nav>
        <a href="{{ route('mentors.index') }}">Mentors</a>
        @if($user->isMentor())
            | <a href="{{ route('mentor.profile') }}">Mon profil</a>
            | <a href="{{ route('availabilities.create') }}">Mes disponibilités</a>
        @endif
        | <a href="{{ route('sessions.index') }}">Mes sessions</a>
        @if($user->isAdmin())
            | <a href="{{ route('admin.dashboard') }}">Admin</a>
        @endif
        &nbsp;
        <form method="POST" action="{{ route('logout') }}" style="display: inline;">
            @csrf
            <button type="submit">Déconnexion</button>
        </form>
    </nav>

    @if(session('success'))
        <div style="color: green; margin: 10px 0;">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div style="color: red; margin: 10px 0;">{{ session('error') }}</div>
    @endif

    <h2>Statistiques</h2>
    @if(count($stats) > 0)
        <ul>
            @foreach($stats as $key => $value)
                <li>{{ ucfirst(str_replace('_', ' ', $key)) }} : {{ $value }}</li>
            @endforeach
        </ul>
    @else
        <p>Aucune statistique disponible.</p>
    @endif
</body>
</html>
@extends('layouts.app')

@section('content')
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
