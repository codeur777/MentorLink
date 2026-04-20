@extends('layouts.app')

@section('title', 'Profil de ' . $mentor->name . ' - MentorLink')

@push('styles')
<style>
.mentor-profile-card {
    border: none;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    border-radius: 15px;
    overflow: hidden;
}

.mentor-header {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
    padding: 2rem;
    text-align: center;
}

.info-card {
    border: none;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    border-radius: 10px;
    transition: transform 0.2s ease;
}

.info-card:hover {
    transform: translateY(-2px);
}

.info-item {
    padding: 1rem 0;
    border-bottom: 1px solid #f8f9fa;
}

.info-item:last-child {
    border-bottom: none;
}

.info-label {
    font-weight: 600;
    color: #495057;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 0.5rem;
}

.info-value {
    font-size: 1.1rem;
    color: #212529;
}

.star-rating-large {
    font-size: 1.5rem;
}

.availability-item {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 0.75rem;
    margin-bottom: 0.5rem;
    border-left: 4px solid #28a745;
}

.domain-badge-large {
    font-size: 0.9rem;
    padding: 0.5rem 1rem;
    margin: 0.25rem;
    border-radius: 20px;
}

.action-card {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    color: white;
    border-radius: 10px;
}

.star-rating-small {
    font-size: 0.9rem;
}

.review-item:last-child {
    border-bottom: none !important;
    margin-bottom: 0 !important;
    padding-bottom: 0 !important;
}

.btn-reserve-session {
    background-color: #1976d2 !important;
    border-color: #1976d2 !important;
    color: white !important;
}

.btn-reserve-session:hover {
    background-color: #1565c0 !important;
    border-color: #1565c0 !important;
    color: white !important;
}

.btn-other-mentors {
    background-color: #e3f2fd !important;
    border-color: #bbdefb !important;
    color: #1976d2 !important;
}

.btn-other-mentors:hover {
    background-color: #bbdefb !important;
    border-color: #90caf9 !important;
    color: #1565c0 !important;
}
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- En-tête avec bouton retour -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Profil du mentor</h1>
            <p class="text-muted">Découvrez les détails complets</p>
        </div>
        <a href="{{ route('mentors.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Retour à la liste
        </a>
    </div>

    <div class="row">
        <!-- Profil principal -->
        <div class="col-lg-8 mb-4">
            <div class="card mentor-profile-card">
                <!-- En-tête du profil -->
                <div class="mentor-header">
                    <h2 class="mb-2">{{ $mentor->name }}</h2>
                    @if($mentor->mentorProfile && $mentor->mentorProfile->is_validated)
                        <span class="badge bg-light text-success fs-6">
                            <i class="fas fa-check me-1"></i>Profil validé
                        </span>
                    @else
                        <span class="badge bg-warning fs-6">
                            <i class="fas fa-clock me-1"></i>En attente de validation
                        </span>
                    @endif
                </div>

                <!-- Corps du profil -->
                <div class="card-body p-4">
                    <div class="row">
                        <!-- Colonne gauche -->
                        <div class="col-md-6">
                            <div class="info-item">
                                <div class="info-label">Contact</div>
                                <div class="info-value">
                                    <i class="fas fa-envelope text-primary me-2"></i>
                                    {{ $mentor->email }}
                                </div>
                            </div>

                            @if($mentor->mentorProfile)
                                <div class="info-item">
                                    <div class="info-label">Tarif horaire</div>
                                    <div class="info-value">
                                        <span class="h4 text-success">
                                            <i class="fas fa-euro-sign me-1"></i>
                                            {{ number_format($mentor->mentorProfile->hourly_rate, 2) }}€/h
                                        </span>
                                    </div>
                                </div>
                            @endif

                            <div class="info-item">
                                <div class="info-label">Membre depuis</div>
                                <div class="info-value">
                                    <i class="fas fa-calendar text-info me-2"></i>
                                    {{ $mentor->created_at->format('d/m/Y') }}
                                </div>
                            </div>
                        </div>

                        <!-- Colonne droite -->
                        <div class="col-md-6">
                            @if($mentor->mentorProfile && $mentor->mentorProfile->domains)
                                <div class="info-item">
                                    <div class="info-label">Domaines d'expertise</div>
                                    <div class="info-value">
                                        @foreach($mentor->mentorProfile->domains as $domain)
                                            <span class="badge bg-primary domain-badge-large">{{ ucfirst($domain) }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Évaluation -->
                            <div class="info-item">
                                <div class="info-label">Évaluation</div>
                                <div class="info-value">
                                    <div class="d-flex align-items-center">
                                        <div class="star-rating-large me-3">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= floor($mentor->average_rating))
                                                    <i class="fas fa-star text-warning"></i>
                                                @elseif($i - 0.5 <= $mentor->average_rating)
                                                    <i class="fas fa-star-half-alt text-warning"></i>
                                                @else
                                                    <i class="far fa-star text-muted"></i>
                                                @endif
                                            @endfor
                                        </div>
                                        <div>
                                            <span class="h5 mb-0">{{ number_format($mentor->average_rating, 1) }}/5</span>
                                            <div class="small text-muted">
                                                {{ $mentor->total_reviews }} {{ $mentor->total_reviews > 1 ? 'avis' : 'avis' }}
                                            </div>
                                        </div>
                                    </div>
                                    @if($mentor->total_reviews == 0)
                                        <small class="text-muted">Nouveau mentor - Pas encore d'avis</small>
                                    @endif
                                </div>
                            </div>

                            @if($mentor->bio)
                                <div class="info-item">
                                    <div class="info-label">À propos</div>
                                    <div class="info-value">
                                        <p class="mb-0">{{ $mentor->bio }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar droite -->
        <div class="col-lg-4">
            <!-- Disponibilités -->
            <div class="card info-card mb-4">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-calendar-alt text-primary me-2"></i>Disponibilités
                    </h5>
                </div>
                <div class="card-body">
                    @if($mentor->availabilities->count() > 0)
                        @php
                            $days = ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'];
                        @endphp
                        
                        @foreach($mentor->availabilities->sortBy('day_of_week') as $availability)
                            <div class="availability-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $days[$availability->day_of_week] }}</strong>
                                        <div class="small text-muted">
                                            {{ \Carbon\Carbon::parse($availability->start_time)->format('H:i') }} - 
                                            {{ \Carbon\Carbon::parse($availability->end_time)->format('H:i') }}
                                        </div>
                                    </div>
                                    <span class="badge bg-success">
                                        <i class="fas fa-check me-1"></i>Disponible
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-calendar-times fa-3x mb-3"></i>
                            <p class="mb-0">Aucune disponibilité définie</p>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Actions -->
            <div class="card action-card">
                <div class="card-body text-center">
                    <h5 class="card-title text-white">
                        <i class="fas fa-rocket me-2"></i>Actions
                    </h5>
                    
                    @if($mentor->mentorProfile && $mentor->mentorProfile->is_validated)
                        @if($mentor->availabilities->count() > 0)
                            @auth
                                @if(auth()->user()->role === 'mentee')
                                    <a href="{{ route('sessions.create', $mentor->id) }}" class="btn btn-reserve-session btn-lg w-100 mb-3">
                                        <i class="fas fa-calendar-plus me-2"></i>Réserver une session
                                    </a>
                                @else
                                    <div class="alert alert-light">
                                        <i class="fas fa-info-circle me-2"></i>
                                        Seuls les mentees peuvent réserver des sessions.
                                    </div>
                                @endif
                            @else
                                <a href="{{ route('login') }}?redirect=sessions.create&mentor_id={{ $mentor->id }}" class="btn btn-reserve-session btn-lg w-100 mb-3">
                                    <i class="fas fa-calendar-plus me-2"></i>Réserver une session
                                </a>
                                <small class="text-light">Connectez-vous pour réserver</small>
                            @endauth
                        @else
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                Ce mentor n'a pas encore défini ses disponibilités.
                            </div>
                        @endif
                        
                        <a href="{{ route('mentors.index') }}" class="btn btn-other-mentors w-100">
                            <i class="fas fa-search me-2"></i>Voir d'autres mentors
                        </a>
                    @else
                        <div class="alert alert-warning">
                            <i class="fas fa-info-circle me-2"></i>
                            Ce profil mentor est en attente de validation.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Section des avis récents -->
    @if($mentor->total_reviews > 0)
    <div class="row mt-4">
        <div class="col-12">
            <div class="card info-card">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-star text-warning me-2"></i>Avis récents
                    </h5>
                </div>
                <div class="card-body">
                    @if($recentReviews->count() > 0)
                        @foreach($recentReviews as $review)
                            <div class="review-item border-bottom pb-3 mb-3">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <div class="d-flex align-items-center mb-2">
                                            <div class="star-rating-small me-2">
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= $review->rating)
                                                        <i class="fas fa-star text-warning"></i>
                                                    @else
                                                        <i class="far fa-star text-muted"></i>
                                                    @endif
                                                @endfor
                                            </div>
                                            <strong>{{ $review->reviewer->name }}</strong>
                                            <small class="text-muted ms-2">
                                                {{ $review->created_at->format('d/m/Y') }}
                                            </small>
                                        </div>
                                        <p class="mb-0 text-muted">{{ $review->comment }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        
                        @if($mentor->total_reviews > 5)
                            <div class="text-center">
                                <small class="text-muted">
                                    Et {{ $mentor->total_reviews - 5 }} autre(s) avis...
                                </small>
                            </div>
                        @endif
                    @else
                        <div class="text-center text-muted py-3">
                            <i class="fas fa-star fa-2x mb-2"></i>
                            <p class="mb-0">Aucun avis pour le moment</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection