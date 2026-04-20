@extends('layouts.app')

@section('title', 'Liste des mentors - MentorLink')

@push('styles')
<style>
.mentor-card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    border: none;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.mentor-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.star-rating-display {
    font-size: 1.1rem;
}

.domain-badges {
    max-height: 60px;
    overflow: hidden;
    position: relative;
}

.domain-badges::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 20px;
    background: linear-gradient(transparent, white);
    pointer-events: none;
}
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">Liste des mentors</h1>
        <p class="text-muted">Découvrez nos mentors validés</p>
    </div>
    <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Retour au dashboard
    </a>
</div>

<!-- Filtre par domaine -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-6">
                <label class="form-label">Filtrer par domaine d'expertise</label>
                <select name="domain" class="form-select">
                    <option value="">Tous les domaines</option>
                    <option value="web" {{ request('domain') === 'web' ? 'selected' : '' }}>Développement Web</option>
                    <option value="mobile" {{ request('domain') === 'mobile' ? 'selected' : '' }}>Développement Mobile</option>
                    <option value="data" {{ request('domain') === 'data' ? 'selected' : '' }}>Data Science</option>
                    <option value="devops" {{ request('domain') === 'devops' ? 'selected' : '' }}>DevOps</option>
                    <option value="design" {{ request('domain') === 'design' ? 'selected' : '' }}>Design UI/UX</option>
                    <option value="backend" {{ request('domain') === 'backend' ? 'selected' : '' }}>Backend</option>
                    <option value="frontend" {{ request('domain') === 'frontend' ? 'selected' : '' }}>Frontend</option>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-filter me-2"></i>Filtrer
                </button>
            </div>
            <div class="col-md-3">
                <a href="{{ route('mentors.index') }}" class="btn btn-outline-secondary w-100">
                    <i class="fas fa-times me-2"></i>Réinitialiser
                </a>
            </div>
        </form>
    </div>
</div>

@if($mentors->count() > 0)
    <div class="row">
        @foreach($mentors as $mentor)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100 mentor-card">
                    <div class="card-header bg-success text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0">
                                <i class="fas fa-chalkboard-teacher me-2"></i>{{ $mentor->name }}
                            </h6>
                            <span class="badge bg-light text-dark">
                                <i class="fas fa-check me-1"></i>Validé
                            </span>
                        </div>
                    </div>
                    <div class="card-body d-flex flex-column">
                        <!-- Notation par étoiles -->
                        <div class="mb-3">
                            <div class="d-flex align-items-center justify-content-center">
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
                                <span class="fw-bold">{{ number_format($mentor->average_rating, 1) }}</span>
                                <small class="text-muted ms-1">
                                    ({{ $mentor->total_reviews }} avis)
                                </small>
                                @if($mentor->penalties && $mentor->penalties->count() > 0)
                                    <small class="text-warning ms-2" title="Ce mentor a {{ $mentor->penalties->count() }} pénalité(s)">
                                        <i class="fas fa-exclamation-triangle"></i>
                                    </small>
                                @endif
                            </div>
                            @if($mentor->total_reviews == 0)
                                <div class="text-center">
                                    <small class="text-muted">Nouveau mentor</small>
                                </div>
                            @endif
                        </div>
                        
                        @if($mentor->mentorProfile && $mentor->mentorProfile->domains)
                            <div class="mb-4 flex-grow-1">
                                <div class="text-center">
                                    <strong class="text-muted small">Domaines d'expertise</strong>
                                </div>
                                <div class="domain-badges mt-2 text-center">
                                    @foreach($mentor->mentorProfile->domains as $domain)
                                        <span class="badge bg-primary me-1 mb-1">{{ ucfirst($domain) }}</span>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                        
                        <!-- Aperçu rapide -->
                        <div class="text-center mt-auto">
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Cliquez pour voir les détails complets
                            </small>
                        </div>
                    </div>
                    <div class="card-footer bg-light">
                        <div class="d-grid">
                            <a href="{{ route('mentors.show', $mentor->id) }}" class="btn btn-primary">
                                <i class="fas fa-eye me-2"></i>Voir le profil détaillé
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    
    <!-- Pagination -->
    <div class="d-flex justify-content-center">
        {{ $mentors->appends(request()->query())->links() }}
    </div>
@else
    <div class="card">
        <div class="card-body text-center py-5">
            <i class="fas fa-search fa-4x text-muted mb-3"></i>
            <h4>Aucun mentor trouvé</h4>
            @if(request('domain'))
                <p class="text-muted">Aucun mentor trouvé pour le domaine "{{ request('domain') }}".</p>
                <a href="{{ route('mentors.index') }}" class="btn btn-primary">
                    <i class="fas fa-list me-2"></i>Voir tous les mentors
                </a>
            @else
                <p class="text-muted">Aucun mentor validé n'est disponible pour le moment.</p>
                <a href="{{ route('dashboard') }}" class="btn btn-primary">
                    <i class="fas fa-arrow-left me-2"></i>Retour au dashboard
                </a>
            @endif
        </div>
    </div>
@endif

<!-- Statistiques -->
<div class="row mt-4">
    <div class="col-md-4">
        <div class="card bg-primary text-white">
            <div class="card-body text-center">
                <h4>{{ $mentors->total() }}</h4>
                <small>Mentors {{ request('domain') ? 'dans ce domaine' : 'au total' }}</small>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-success text-white">
            <div class="card-body text-center">
                <h4>{{ $mentors->count() }}</h4>
                <small>Affichés sur cette page</small>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-info text-white">
            <div class="card-body text-center">
                <h4>100%</h4>
                <small>Profils validés</small>
            </div>
        </div>
    </div>
</div>
@endsection