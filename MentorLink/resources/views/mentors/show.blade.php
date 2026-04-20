@extends('layouts.app')

@section('title', 'Profil de ' . $mentor->name . ' - MentorLink')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">Profil du mentor</h1>
        <p class="text-muted">{{ $mentor->name }}</p>
    </div>
    <a href="{{ route('mentors.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Retour à la liste
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-chalkboard-teacher me-2"></i>{{ $mentor->name }}
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <strong>Email :</strong><br>
                            <span class="text-muted">{{ $mentor->email }}</span>
                        </div>
                        
                        @if($mentor->mentorProfile)
                            <div class="mb-3">
                                <strong>Tarif horaire :</strong><br>
                                <span class="h4 text-success">{{ $mentor->mentorProfile->hourly_rate }}€/h</span>
                            </div>
                            
                            <div class="mb-3">
                                <strong>Statut :</strong><br>
                                @if($mentor->mentorProfile->is_validated)
                                    <span class="badge bg-success fs-6">
                                        <i class="fas fa-check me-1"></i>Profil validé
                                    </span>
                                @else
                                    <span class="badge bg-warning fs-6">
                                        <i class="fas fa-clock me-1"></i>En attente de validation
                                    </span>
                                @endif
                            </div>
                        @endif
                    </div>
                    
                    <div class="col-md-6">
                        @if($mentor->mentorProfile && $mentor->mentorProfile->domains)
                            <div class="mb-3">
                                <strong>Domaines d'expertise :</strong><br>
                                @foreach($mentor->mentorProfile->domains as $domain)
                                    <span class="badge bg-primary me-1 mb-1">{{ ucfirst($domain) }}</span>
                                @endforeach
                            </div>
                        @endif
                        
                        <div class="mb-3">
                            <strong>Membre depuis :</strong><br>
                            <span class="text-muted">{{ $mentor->created_at->format('d/m/Y') }}</span>
                        </div>
                        
                        <!-- Notation par étoiles -->
                        <div class="mb-3">
                            <strong>Évaluation :</strong><br>
                            <div class="d-flex align-items-center">
                                <div class="star-rating-display me-2">
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
                                <span class="fw-bold">{{ number_format($mentor->average_rating, 1) }}/5</span>
                                <small class="text-muted ms-1">
                                    ({{ $mentor->total_reviews }} {{ $mentor->total_reviews > 1 ? 'avis' : 'avis' }})
                                </small>
                            </div>
                            @if($mentor->total_reviews == 0)
                                <small class="text-muted">Nouveau mentor - Pas encore d'avis</small>
                            @endif
                        </div>
                        
                        @if($mentor->bio)
                            <div class="mb-3">
                                <strong>Bio :</strong><br>
                                <p class="text-muted">{{ $mentor->bio }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <!-- Disponibilités -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-calendar-alt me-2"></i>Disponibilités
                </h5>
            </div>
            <div class="card-body">
                @if($mentor->availabilities->count() > 0)
                    @php
                        $days = ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'];
                    @endphp
                    
                    @foreach($mentor->availabilities->sortBy('day_of_week') as $availability)
                        <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                            <div>
                                <strong>{{ $days[$availability->day_of_week] }}</strong><br>
                                <small class="text-muted">
                                    {{ \Carbon\Carbon::parse($availability->start_time)->format('H:i') }} - 
                                    {{ \Carbon\Carbon::parse($availability->end_time)->format('H:i') }}
                                </small>
                            </div>
                            <span class="badge bg-success">Disponible</span>
                        </div>
                    @endforeach
                @else
                    <div class="text-center text-muted py-3">
                        <i class="fas fa-calendar-times fa-2x mb-2"></i>
                        <p class="mb-0">Aucune disponibilité définie</p>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Actions -->
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="card-title mb-0">Actions</h5>
            </div>
            <div class="card-body">
                @if($mentor->mentorProfile && $mentor->mentorProfile->is_validated)
                    <div class="d-grid gap-2">
                        @if($mentor->availabilities->count() > 0)
                            @auth
                                @if(auth()->user()->role === 'mentee')
                                    <a href="{{ route('sessions.create', $mentor->id) }}" class="btn btn-primary">
                                        <i class="fas fa-calendar-plus me-2"></i>Réserver une session
                                    </a>
                                @else
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle me-2"></i>
                                        Seuls les mentees peuvent réserver des sessions.
                                    </div>
                                @endif
                            @else
                                <a href="{{ route('login') }}?redirect=sessions.create&mentor_id={{ $mentor->id }}" class="btn btn-primary">
                                    <i class="fas fa-calendar-plus me-2"></i>Réserver une session
                                </a>
                                <small class="text-muted">Connectez-vous pour réserver</small>
                            @endauth
                        @else
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                Ce mentor n'a pas encore défini ses disponibilités.
                            </div>
                        @endif
                        
                        <a href="{{ route('mentors.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-search me-2"></i>Voir d'autres mentors
                        </a>
                    </div>
                @else
                    <div class="alert alert-info">
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
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-star me-2"></i>Avis récents
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

<style>
.star-rating-display {
    font-size: 1.2rem;
}

.star-rating-small {
    font-size: 0.9rem;
}

.review-item:last-child {
    border-bottom: none !important;
    margin-bottom: 0 !important;
    padding-bottom: 0 !important;
}
</style>
@endsection