@extends('layouts.app')

@section('title', 'Mentors en attente - Administration')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">Mentors en attente de validation</h1>
        <p class="text-muted">Gérer les demandes de profils mentors</p>
    </div>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Retour au dashboard
    </a>
</div>

@if($profiles->count() > 0)
    <div class="row">
        @foreach($profiles as $profile)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100">
                    <div class="card-header bg-warning text-dark">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0">
                                <i class="fas fa-user me-2"></i>{{ $profile->user->name }}
                            </h6>
                            <span class="badge bg-dark">En attente</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <strong>Email :</strong><br>
                            <small class="text-muted">{{ $profile->user->email }}</small>
                        </div>
                        
                        <div class="mb-3">
                            <strong>Domaines d'expertise :</strong><br>
                            @if($profile->domains)
                                @foreach($profile->domains as $domain)
                                    <span class="badge bg-primary me-1 mb-1">{{ $domain }}</span>
                                @endforeach
                            @else
                                <small class="text-muted">Aucun domaine spécifié</small>
                            @endif
                        </div>
                        
                        <div class="mb-3">
                            <strong>Tarif horaire :</strong><br>
                            <span class="h5 text-success">{{ $profile->hourly_rate }}€/h</span>
                        </div>
                        
                        <div class="mb-3">
                            <strong>Demande soumise :</strong><br>
                            <small class="text-muted">{{ $profile->created_at->format('d/m/Y à H:i') }}</small>
                        </div>
                        
                        @if($profile->user->email_verified_at)
                            <div class="mb-3">
                                <span class="badge bg-success">
                                    <i class="fas fa-check me-1"></i>Email vérifié
                                </span>
                            </div>
                        @else
                            <div class="mb-3">
                                <span class="badge bg-warning">
                                    <i class="fas fa-exclamation-triangle me-1"></i>Email non vérifié
                                </span>
                            </div>
                        @endif
                    </div>
                    <div class="card-footer bg-light">
                        <div class="row">
                            <div class="col-6">
                                <form method="POST" action="{{ route('admin.mentors.validate', $profile->user_id) }}" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-success btn-sm w-100" 
                                            onclick="return confirm('Valider ce profil mentor ?')">
                                        <i class="fas fa-check me-1"></i>Valider
                                    </button>
                                </form>
                            </div>
                            <div class="col-6">
                                <form method="POST" action="{{ route('admin.mentors.reject', $profile->user_id) }}" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm w-100" 
                                            onclick="return confirm('Rejeter définitivement ce profil ?')">
                                        <i class="fas fa-times me-1"></i>Rejeter
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    
    <!-- Pagination -->
    <div class="d-flex justify-content-center">
        {{ $profiles->links() }}
    </div>
@else
    <div class="card">
        <div class="card-body text-center py-5">
            <i class="fas fa-check-circle fa-4x text-success mb-3"></i>
            <h4>Aucun mentor en attente</h4>
            <p class="text-muted">Tous les profils mentors ont été traités.</p>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-primary">
                <i class="fas fa-arrow-left me-2"></i>Retour au dashboard
            </a>
        </div>
    </div>
@endif

<!-- Actions en lot -->
@if($profiles->count() > 0)
<div class="card mt-4">
    <div class="card-header">
        <h5 class="card-title mb-0">Actions en lot</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>{{ $profiles->count() }}</strong> profil(s) en attente de validation
                </div>
            </div>
            <div class="col-md-6">
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Vérifiez chaque profil avant validation
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection