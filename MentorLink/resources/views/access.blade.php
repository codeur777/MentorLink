@extends('layouts.app')

@section('content')
    <section class="page-hero page-hero-tight">
        <div class="section-heading" data-reveal>
            <span class="eyebrow">Demarrer sur la plateforme</span>
            <h1 class="page-title">Une entree simple pour les seniors qui accompagnent et les juniors qui cherchent de l aide.</h1>
            <p class="hero-text">
                Cette page doit inspirer confiance des la premiere visite:
                on comprend vite comment rejoindre la plateforme et demander un accompagnement.
            </p>
        </div>

        <aside class="page-side-card dashboard-card" data-reveal>
            <p class="card-label">Vue d ensemble</p>
            <h3>Un point d entree pense pour la communaute etudiante</h3>
            <p>
                Les seniors peuvent partager leur experience, les juniors peuvent demander de l aide
                sur un cours, une orientation ou un projet.
            </p>
            <div class="inline-stats">
                <span class="filter-chip">Mentors: {{ $dbState['mentors_count'] }}</span>
                <span class="filter-chip">Mentores: {{ $dbState['mentees_count'] }}</span>
            </div>
        </aside>
    </section>

    <section class="content-section access-layout">
        <article class="dashboard-card form-card" data-reveal>
            <p class="card-label">Connexion</p>
            <h3>Retrouver ses sessions et ses reservations</h3>

            <form class="prototype-form">
                <label class="field-group">
                    <span>Email</span>
                    <input type="email" placeholder="mentorlink@exemple.com" />
                </label>

                <label class="field-group">
                    <span>Mot de passe</span>
                    <input type="password" placeholder="........" />
                </label>

                <button type="button" class="button button-primary">Continuer</button>
            </form>
        </article>

        <article class="dashboard-card form-card" data-reveal>
            <p class="card-label">Inscription</p>
            <h3>Rejoindre la plateforme avec le bon role</h3>

            <form class="prototype-form">
                <label class="field-group">
                    <span>Nom complet</span>
                    <input type="text" placeholder="Votre nom" />
                </label>

                <label class="field-group">
                    <span>Email</span>
                    <input type="email" placeholder="vous@exemple.com" />
                </label>

                <label class="field-group">
                    <span>Role sur la plateforme</span>
                    <select>
                        <option>Junior en recherche d aide</option>
                        <option>Senior qui accompagne</option>
                        <option>Coordinateur</option>
                    </select>
                </label>

                <button type="button" class="button button-secondary">Rejoindre MentorLink</button>
            </form>
        </article>
    </section>
@endsection
