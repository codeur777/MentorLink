@extends('layouts.app')

@section('title', 'Liste des mentors - MentorLink')

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
                <div class="card h-100">
                    <div class="card-header bg-success text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0">
                                <i class="fas fa-chalkboard-teacher me-2"></i>{{ $mentor->name }}
                            </h6>
                            <span class="badge bg-light text-dark">Validé</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <strong>Email :</strong><br>
                            <small class="text-muted">{{ $mentor->email }}</small>
                        </div>
                        
                        @if($mentor->mentorProfile)
                            <div class="mb-3">
                                <strong>Domaines d'expertise :</strong><br>
                                @if($mentor->mentorProfile->domains)
                                    @foreach($mentor->mentorProfile->domains as $domain)
                                        <span class="badge bg-primary me-1 mb-1">{{ ucfirst($domain) }}</span>
                                    @endforeach
                                @else
                                    <small class="text-muted">Non spécifié</small>
                                @endif
                            </div>
                            
                            <div class="mb-3">
                                <strong>Tarif horaire :</strong><br>
                                <span class="h5 text-success">{{ $mentor->mentorProfile->hourly_rate }}€/h</span>
                            </div>
                            
                            <div class="mb-3">
                                <strong>Statut :</strong>
                                <span class="badge bg-success">
                                    <i class="fas fa-check me-1"></i>Profil validé
                                </span>
                            </div>
                        @endif
                        
                        <div class="mb-3">
                            <strong>Membre depuis :</strong><br>
                            <small class="text-muted">{{ $mentor->created_at->format('d/m/Y') }}</small>
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