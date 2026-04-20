@extends('layouts.app')

@section('content')
    <section class="page-hero page-hero-tight">
        <div class="section-heading" data-reveal>
            <span class="eyebrow">Mentors seniors</span>
            <h1 class="page-title">Des etudiants experimentes pour aider les juniors a franchir un cap.</h1>
            <p class="hero-text">
                Cette page doit surtout aider un junior a comprendre qui peut l aider,
                sur quel sujet, avec quelle disponibilite et dans quel format de session.
            </p>
        </div>

        <aside class="page-side-card dashboard-card" data-reveal>
            <p class="card-label">Pour les juniors</p>
            <h3>Choisir un mentor selon le bon besoin</h3>
            <p>
                Revision, orientation ou projet: l interface aide a lire les profils de facon simple et rapide.
            </p>
        </aside>
    </section>

    <section class="content-section section-compact">
        @if ($filters->isNotEmpty())
            <div class="chip-row" data-reveal>
                @foreach ($filters as $filter)
                    <span class="filter-chip">{{ $filter }}</span>
                @endforeach
            </div>
        @endif

        @if ($mentors->isEmpty())
            <article class="empty-state-card dashboard-card" data-reveal>
                <p class="card-label">Aucun mentor visible</p>
                <h3>Ajoutez vos premiers etudiants seniors pour commencer les mises en relation.</h3>
                <p>
                    Quand vos mentors seront enregistres, cette page affichera leurs expertises,
                    leurs disponibilites et leur format d accompagnement.
                </p>
            </article>
        @else
            <div class="mentor-grid">
                @foreach ($mentors as $mentor)
                    <article class="mentor-card" data-reveal>
                        <div class="mentor-head">
                            <div class="avatar-badge">{{ \Illuminate\Support\Str::of($mentor->user?->name)->explode(' ')->take(2)->map(fn ($part) => strtoupper(\Illuminate\Support\Str::substr($part, 0, 1)))->implode('') }}</div>
                            <div>
                                <h3>{{ $mentor->user?->name }}</h3>
                                <p class="mentor-role">{{ $mentor->headline }}</p>
                            </div>
                        </div>

                        <p class="mentor-bio">{{ $mentor->bio ?: 'Biographie non renseignee.' }}</p>

                        <div class="mentor-meta">
                            <span>{{ $mentor->focus_area ?: 'Focus non renseigne' }}</span>
                            <span>{{ $mentor->availability_note ?: 'Disponibilite non renseignee' }}</span>
                        </div>

                        <div class="tag-row">
                            @forelse (($mentor->expertise_tags ?? []) as $tag)
                                <span class="tag-pill">{{ $tag }}</span>
                            @empty
                                <span class="tag-pill">Aucun tag</span>
                            @endforelse
                        </div>

                        <div class="mentor-footer">
                            <strong>{{ $mentor->session_format ?: 'Format non renseigne' }}</strong>
                            <a href="{{ route('access.index') }}" class="button button-ghost">Reserver un creneau</a>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </section>
@endsection
