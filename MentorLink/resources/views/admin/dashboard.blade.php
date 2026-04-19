@extends('layouts.app')

@section('title', 'Administration - MentorLink')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">Administration</h1>
        <p class="text-muted">Tableau de bord administrateur</p>
    </div>
    <div class="badge bg-danger fs-6">Administrateur</div>
</div>

<!-- Statistiques principales -->
<div class="row mb-4">
    <div class="col-md-6 col-lg-3 mb-3">
        <div class="card stat-card">
            <div class="card-body text-center">
                <i class="fas fa-users fa-3x mb-3"></i>
                <h2 class="mb-0">{{ $stats['total_users'] }}</h2>
                <p class="mb-0">Utilisateurs totaux</p>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3 mb-3">
        <div class="card stat-card-success">
            <div class="card-body text-center">
                <i class="fas fa-chalkboard-teacher fa-3x mb-3"></i>
                <h2 class="mb-0">{{ $stats['total_mentors'] }}</h2>
                <p class="mb-0">Mentors</p>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3 mb-3">
        <div class="card stat-card-info">
            <div class="card-body text-center">
                <i class="fas fa-user-graduate fa-3x mb-3"></i>
                <h2 class="mb-0">{{ $stats['total_mentees'] }}</h2>
                <p class="mb-0">Mentees</p>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3 mb-3">
        <div class="card stat-card-warning">
            <div class="card-body text-center">
                <i class="fas fa-clock fa-3x mb-3"></i>
                <h2 class="mb-0">{{ $stats['pending_mentors'] }}</h2>
                <p class="mb-0">En attente de validation</p>
            </div>
        </div>
    </div>
</div>

<!-- Actions rapides -->
<div class="row mb-4">
    <div class="col-md-6 col-lg-3 mb-3">
        <div class="card h-100">
            <div class="card-body text-center">
                <i class="fas fa-user-clock fa-2x text-warning mb-3"></i>
                <h5>Mentors en attente</h5>
                <p class="text-muted">Valider les nouveaux profils mentors</p>
                <a href="{{ route('admin.pending-mentors') }}" class="btn btn-warning">
                    Gérer ({{ $stats['pending_mentors'] }})
                </a>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3 mb-3">
        <div class="card h-100">
            <div class="card-body text-center">
                <i class="fas fa-users fa-2x text-primary mb-3"></i>
                <h5>Utilisateurs</h5>
                <p class="text-muted">Gérer tous les utilisateurs</p>
                <a href="{{ route('admin.users') }}" class="btn btn-primary">
                    Voir tous ({{ $stats['total_users'] }})
                </a>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3 mb-3">
        <div class="card h-100">
            <div class="card-body text-center">
                <i class="fas fa-chart-pie fa-2x text-info mb-3"></i>
                <h5>Statistiques</h5>
                <p class="text-muted">Analyses détaillées</p>
                <a href="{{ route('admin.stats') }}" class="btn btn-info">
                    Voir les stats
                </a>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3 mb-3">
        <div class="card h-100">
            <div class="card-body text-center">
                <i class="fas fa-chalkboard-teacher fa-2x text-success mb-3"></i>
                <h5>Mentors validés</h5>
                <p class="text-muted">Voir les mentors actifs</p>
                <a href="{{ route('mentors.index') }}" class="btn btn-success">
                    Voir ({{ $stats['validated_mentors'] }})
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Graphiques et informations -->
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Répartition des utilisateurs</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-4">
                        <div class="border-end">
                            <h3 class="text-primary">{{ $stats['total_mentors'] }}</h3>
                            <small class="text-muted">Mentors</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="border-end">
                            <h3 class="text-info">{{ $stats['total_mentees'] }}</h3>
                            <small class="text-muted">Mentees</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <h3 class="text-secondary">{{ $stats['total_users'] - $stats['total_mentors'] - $stats['total_mentees'] }}</h3>
                        <small class="text-muted">Autres</small>
                    </div>
                </div>
                
                <!-- Barre de progression -->
                <div class="mt-4">
                    <div class="progress" style="height: 20px;">
                        @php
                            $mentorPercent = $stats['total_users'] > 0 ? ($stats['total_mentors'] / $stats['total_users']) * 100 : 0;
                            $menteePercent = $stats['total_users'] > 0 ? ($stats['total_mentees'] / $stats['total_users']) * 100 : 0;
                            $otherPercent = 100 - $mentorPercent - $menteePercent;
                        @endphp
                        <div class="progress-bar bg-primary" style="width: {{ $mentorPercent }}%">
                            {{ round($mentorPercent, 1) }}%
                        </div>
                        <div class="progress-bar bg-info" style="width: {{ $menteePercent }}%">
                            {{ round($menteePercent, 1) }}%
                        </div>
                        <div class="progress-bar bg-secondary" style="width: {{ $otherPercent }}%">
                            {{ round($otherPercent, 1) }}%
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Actions rapides</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    @if($stats['pending_mentors'] > 0)
                        <a href="{{ route('admin.pending-mentors') }}" class="btn btn-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            {{ $stats['pending_mentors'] }} mentor(s) à valider
                        </a>
                    @else
                        <div class="alert alert-success mb-2">
                            <i class="fas fa-check-circle me-2"></i>
                            Aucun mentor en attente
                        </div>
                    @endif
                    
                    <a href="{{ route('admin.users') }}" class="btn btn-outline-primary">
                        <i class="fas fa-users me-2"></i>Gérer les utilisateurs
                    </a>
                    
                    <a href="{{ route('admin.stats') }}" class="btn btn-outline-info">
                        <i class="fas fa-chart-bar me-2"></i>Statistiques détaillées
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection