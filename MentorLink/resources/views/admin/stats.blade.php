@extends('layouts.app')

@section('title', 'Statistiques - Administration')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">Statistiques détaillées</h1>
        <p class="text-muted">Analyses et métriques de la plateforme</p>
    </div>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Retour
    </a>
</div>

<!-- Statistiques générales -->
<div class="row mb-4">
    <div class="col-md-6 col-lg-3 mb-3">
        <div class="card stat-card text-center">
            <div class="card-body">
                <i class="fas fa-users fa-2x mb-2"></i>
                <h3>{{ $stats['total_users'] }}</h3>
                <small>Utilisateurs totaux</small>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3 mb-3">
        <div class="card stat-card-success text-center">
            <div class="card-body">
                <i class="fas fa-chalkboard-teacher fa-2x mb-2"></i>
                <h3>{{ $stats['total_mentors'] }}</h3>
                <small>Mentors</small>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3 mb-3">
        <div class="card stat-card-info text-center">
            <div class="card-body">
                <i class="fas fa-user-graduate fa-2x mb-2"></i>
                <h3>{{ $stats['total_mentees'] }}</h3>
                <small>Mentees</small>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3 mb-3">
        <div class="card stat-card-warning text-center">
            <div class="card-body">
                <i class="fas fa-user-shield fa-2x mb-2"></i>
                <h3>{{ $stats['total_admins'] }}</h3>
                <small>Administrateurs</small>
            </div>
        </div>
    </div>
</div>

<!-- Statistiques des mentors -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Statut des mentors</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6">
                        <h3 class="text-success">{{ $stats['validated_mentors'] }}</h3>
                        <small class="text-muted">Validés</small>
                    </div>
                    <div class="col-6">
                        <h3 class="text-warning">{{ $stats['pending_validations'] }}</h3>
                        <small class="text-muted">En attente</small>
                    </div>
                </div>
                
                @if($stats['total_mentors'] > 0)
                    <div class="progress mt-3" style="height: 20px;">
                        @php
                            $validatedPercent = ($stats['validated_mentors'] / $stats['total_mentors']) * 100;
                            $pendingPercent = ($stats['pending_validations'] / $stats['total_mentors']) * 100;
                        @endphp
                        <div class="progress-bar bg-success" style="width: {{ $validatedPercent }}%">
                            {{ round($validatedPercent, 1) }}%
                        </div>
                        <div class="progress-bar bg-warning" style="width: {{ $pendingPercent }}%">
                            {{ round($pendingPercent, 1) }}%
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Activité récente</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span>Nouvelles inscriptions (7 jours)</span>
                    <span class="badge bg-primary fs-6">{{ $stats['recent_registrations'] }}</span>
                </div>
                
                @if($stats['pending_validations'] > 0)
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        {{ $stats['pending_validations'] }} mentor(s) en attente de validation
                    </div>
                @else
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle me-2"></i>
                        Aucun mentor en attente
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Statistiques par domaine -->
@if(!empty($domainStats))
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Répartition par domaines d'expertise</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($domainStats as $domain => $count)
                        <div class="col-md-6 col-lg-3 mb-3">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <h4 class="text-primary">{{ $count }}</h4>
                                    <small class="text-muted">{{ ucfirst($domain) }}</small>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                @if(count($domainStats) === 0)
                    <div class="text-center text-muted">
                        <i class="fas fa-info-circle me-2"></i>
                        Aucun domaine d'expertise enregistré
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endif

<!-- Actions rapides -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Actions rapides</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-2">
                        <a href="{{ route('admin.pending-mentors') }}" class="btn btn-warning w-100">
                            <i class="fas fa-user-clock me-2"></i>Valider mentors
                        </a>
                    </div>
                    <div class="col-md-3 mb-2">
                        <a href="{{ route('admin.users') }}" class="btn btn-primary w-100">
                            <i class="fas fa-users me-2"></i>Gérer utilisateurs
                        </a>
                    </div>
                    <div class="col-md-3 mb-2">
                        <a href="{{ route('mentors.index') }}" class="btn btn-success w-100">
                            <i class="fas fa-chalkboard-teacher me-2"></i>Voir mentors
                        </a>
                    </div>
                    <div class="col-md-3 mb-2">
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary w-100">
                            <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection