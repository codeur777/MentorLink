<!DOCTYPE html>
<html>
<head>
    <title>MentorLink - Accueil</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
    <h1>Bienvenue sur MentorLink</h1>
    <p>Plateforme de mentorat académique entre étudiants</p>
    
    <div>
        <a href="{{ route('login') }}">Se connecter</a> |
        <a href="{{ route('register') }}">S'inscrire</a>
    </div>
</body>
</html>
@extends('layouts.app')

@section('content')
    <section class="hero">
        <div class="hero-copy" data-reveal>
            <span class="eyebrow">Mentorat entre etudiants</span>
            <h1>Des seniors qui aident des juniors a reviser, s orienter et avancer sur leurs projets.</h1>
            <p class="hero-text">
                MentorLink presente une experience claire pour mettre en relation des etudiants seniors
                et des juniors autour de revisions, d orientation academique et de projets.
                Le site met aussi en avant la reservation de creneaux, la gestion des sessions
                et les evaluations apres chaque accompagnement.
            </p>

            <div class="hero-actions">
                <a href="#experience" class="button button-primary">Decouvrir le parcours</a>
                <a href="{{ route('mentors.index') }}" class="button button-ghost">Trouver un mentor</a>
            </div>

            <div class="metric-row">
                @foreach ($heroMetrics as $metric)
                    <article class="metric-card">
                        <strong>{{ $metric['value'] }}</strong>
                        <span>{{ $metric['label'] }}</span>
                    </article>
                @endforeach
            </div>
        </div>

        <div class="hero-panel" data-reveal>
            <div class="panel-topline">
                <span class="status-pill">
                    <span class="status-dot" data-health-dot></span>
                    <span data-health-label>Plateforme prete a afficher vos donnees</span>
                </span>
                <span class="panel-tag">Parcours etudiant</span>
            </div>

            <div class="dashboard-card spotlight-card">
                @if ($featuredMentorship)
                    <p class="card-label">Accompagnement en cours</p>
                    <h2>{{ $featuredMentorship['title'] }}</h2>
                    <p class="muted">
                        {{ $featuredMentorship['summary'] ?: 'Resume non renseigne pour cette relation.' }}
                    </p>

                    <div class="session-grid">
                        <div class="session-card">
                            <span class="session-title">Mentor</span>
                            <strong>{{ $featuredMentorship['mentor']['name'] ?? 'Non renseigne' }}</strong>
                            <small>Etudiant senior disponible</small>
                        </div>
                        <div class="session-card">
                            <span class="session-title">Mentore</span>
                            <strong>{{ $featuredMentorship['mentee']['name'] ?? 'Non renseigne' }}</strong>
                            <small>{{ $featuredMentorship['cadence'] ?: 'Rythme a definir' }}</small>
                        </div>
                    </div>
                @else
                    <p class="card-label">Premieres mises en relation</p>
                    <h2>Vos prochains accompagnements apparaitront ici automatiquement.</h2>
                    <p class="muted">
                        Quand vous aurez ajoute vos mentors, vos mentorés et vos relations,
                        cette carte mettra en avant le dernier accompagnement actif.
                    </p>

                    <div class="session-grid">
                        <div class="session-card">
                            <span class="session-title">Reservation</span>
                            <strong>Creneaux a venir</strong>
                            <small>Planifiez les disponibilites</small>
                        </div>
                        <div class="session-card">
                            <span class="session-title">Evaluation</span>
                            <strong>Retour apres session</strong>
                            <small>Gardez une trace utile du suivi</small>
                        </div>
                    </div>
                @endif
            </div>

            <div class="dashboard-grid">
                <article class="dashboard-card">
                    <p class="card-label">Mentors</p>
                    <strong class="dashboard-value" data-metric-value="mentors">{{ $dbState['mentors_count'] }}</strong>
                    <span>Etudiants seniors disponibles pour accompagner</span>
                </article>

                <article class="dashboard-card">
                    <p class="card-label">Binomes actifs</p>
                    <strong class="dashboard-value" data-metric-value="active-mentorships">{{ $dbState['active_mentorships_count'] }}</strong>
                    <span>Mentor et mentore actuellement en suivi</span>
                </article>

                <article class="dashboard-card">
                    <p class="card-label">Creneaux reserves</p>
                    <strong class="dashboard-value" data-metric-value="upcoming-sessions">{{ $dbState['upcoming_sessions_count'] }}</strong>
                    <span>Sessions planifiees a venir</span>
                </article>
            </div>
        </div>
    </section>

    <section class="stack-strip" data-reveal>
        <p>Socle actuel du projet</p>
        <div class="stack-list" data-stack-items>
            @foreach ($platform['stack'] as $stackItem)
                <span>{{ $stackItem }}</span>
            @endforeach
        </div>
    </section>

    <section id="experience" class="content-section">
        <div class="section-heading" data-reveal>
            <span class="eyebrow">Experience etudiant</span>
            <h2>Une plateforme pensee pour la mise en relation, la reservation et le suivi des sessions.</h2>
            <p>
                L interface doit d abord parler au bon usage: trouver un etudiant senior de confiance,
                reserver un creneau utile, suivre les sessions et recueillir un avis apres chaque rencontre.
            </p>
        </div>

        <div class="feature-grid">
            <article class="feature-card" data-reveal>
                <span class="feature-index">01</span>
                <h3>Mise en relation mentor / mentore</h3>
                <p>Les juniors peuvent reperer rapidement des seniors selon la matiere, le type d aide et la disponibilite.</p>
            </article>
            <article class="feature-card" data-reveal>
                <span class="feature-index">02</span>
                <h3>Reservation de creneaux</h3>
                <p>Le parcours visuel prepare une experience simple pour choisir un moment, confirmer une session et eviter les frictions.</p>
            </article>
            <article class="feature-card" data-reveal>
                <span class="feature-index">03</span>
                <h3>Gestion des sessions</h3>
                <p>Chaque accompagnement peut ensuite etre suivi avec son sujet, son format, son rythme et les prochaines etapes.</p>
            </article>
            <article class="feature-card" data-reveal>
                <span class="feature-index">04</span>
                <h3>Evaluations apres session</h3>
                <p>Le produit prepare deja l endroit ou les retours pourront etre centralises pour mesurer la qualite de l aide fournie.</p>
            </article>
        </div>
    </section>

    <section id="journey" class="content-section alternate-section">
        <div class="journey-layout">
            <div class="journey-column" data-reveal>
                <span class="eyebrow">Parcours mentorat</span>
                <h2>Le design raconte maintenant le vrai scenario attendu pour les etudiants seniors et juniors.</h2>

                <div class="timeline">
                    <article class="timeline-item">
                        <span class="timeline-step">01</span>
                        <div>
                            <h3>Le junior exprime son besoin</h3>
                            <p>Revision avant examen, besoin d orientation ou accompagnement sur un projet de semestre.</p>
                        </div>
                    </article>
                    <article class="timeline-item">
                        <span class="timeline-step">02</span>
                        <div>
                            <h3>Le senior propose un creneau</h3>
                            <p>Le binome se forme autour d une disponibilite, d un sujet et d un format de session clair.</p>
                        </div>
                    </article>
                    <article class="timeline-item">
                        <span class="timeline-step">03</span>
                        <div>
                            <h3>La session est suivie et evaluee</h3>
                            <p>Apres la rencontre, le site garde la trace de la session et recueille un retour utile pour la suite.</p>
                        </div>
                    </article>
                </div>
            </div>

            <aside class="journey-sidebar" data-reveal>
                <div class="dashboard-card">
                    <p class="card-label">Usage principal</p>
                    <h3>Une aide etudiante simple a comprendre</h3>
                    <ul class="detail-list">
                        <li>Un senior aide un junior sur une matiere, un choix d orientation ou un projet</li>
                        <li>Le creneau se reserve sans noyer l utilisateur dans des ecrans techniques</li>
                        <li>La session et l evaluation restent visibles dans un suivi simple</li>
                    </ul>
                </div>

                <div class="dashboard-card note-card">
                    <p class="card-label">Intention de design</p>
                    <p>
                        Le ton visuel reste chaleureux et serieux pour inspirer confiance a des etudiants,
                        sans donner l impression d un outil administratif froid.
                    </p>
                </div>
            </aside>
        </div>
    </section>

    <section id="api" class="content-section">
        <div class="api-layout">
            <div class="api-copy" data-reveal>
                <span class="eyebrow">Ce que la plateforme couvre</span>
                <h2>Le site montre clairement les fonctions attendues par votre contexte de mentorat etudiant.</h2>
                <p>
                    Au lieu de parler surtout technique, cette section met l accent sur les besoins reels:
                    trouver un mentor senior, reserver une session, suivre les echanges et recueillir une evaluation.
                </p>

                <div class="capability-list">
                    <article class="capability-item">
                        <span>Mise en relation</span>
                        <strong>Mentors seniors visibles pour les juniors</strong>
                    </article>
                    <article class="capability-item">
                        <span>Reservations</span>
                        <strong>Creneaux et sessions organises simplement</strong>
                    </article>
                    <article class="capability-item">
                        <span>Evaluations</span>
                        <strong>Retour apres chaque accompagnement</strong>
                    </article>
                </div>
            </div>

            <div class="api-card dashboard-card" data-reveal>
                <div class="schedule-list">
                    <div class="schedule-item">
                        <strong>Revisions</strong>
                        <span>Aide sur les cours, exercices et preparation aux examens</span>
                        <small>Avant partiels, rattrapage, methodologie</small>
                    </div>
                    <div class="schedule-item">
                        <strong>Orientation</strong>
                        <span>Echanges sur les choix de parcours et les experiences etudiantes</span>
                        <small>Conseils, retour d experience, organisation</small>
                    </div>
                    <div class="schedule-item">
                        <strong>Projets</strong>
                        <span>Accompagnement ponctuel sur les rendus, maquettes ou travaux d equipe</span>
                        <small>Projet de groupe, memoire, demo, soutenance</small>
                    </div>
                </div>

                <div class="sync-block">
                    <strong data-sync-label>Derniere synchronisation: en attente</strong>
                    <p>
                        Les indicateurs visibles sur la page d accueil restent relies a vos donnees,
                        mais leur presentation parle maintenant d usage etudiant plutot que de structure technique.
                    </p>
                </div>

                <div class="dashboard-grid compact-grid">
                    <article class="dashboard-card inset-card">
                        <p class="card-label">Etat des donnees</p>
                        <strong data-platform-status>{{ $dbState['connected'] ? 'Actif' : 'En attente' }}</strong>
                    </article>

                    <article class="dashboard-card inset-card">
                        <p class="card-label">Source</p>
                        <strong data-stack-count>{{ $dbState['database_name'] ?: 'Donnees locales' }}</strong>
                    </article>
                </div>
            </div>
        </div>
    </section>
@endsection
