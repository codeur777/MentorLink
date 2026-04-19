@extends('layouts.app')

@section('title', 'Dashboard - MentorLink')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">Dashboard</h1>
        <p class="text-muted">Bienvenue, {{ $user->name }} !</p>
    </div>
    <div class="badge bg-primary fs-6">{{ ucfirst($user->role) }}</div>
</div>

<!-- Statistiques -->
@if(count($stats) > 0)
<div class="row mb-4">
    @if($user->role === 'admin')
        <div class="col-md-6 col-lg-3 mb-3">
            <div class="card stat-card">
                <div class="card-body text-center">
                    <i class="fas fa-users fa-2x mb-2"></i>
                    <h3 class="mb-0">{{ $stats['total_users'] }}</h3>
                    <small>Utilisateurs totaux</small>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3 mb-3">
            <div class="card stat-card-success">
                <div class="card-body text-center">
                    <i class="fas fa-chalkboard-teacher fa-2x mb-2"></i>
                    <h3 class="mb-0">{{ $stats['total_mentors'] }}</h3>
                    <small>Mentors</small>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3 mb-3">
            <div class="card stat-card-info">
                <div class="card-body text-center">
                    <i class="fas fa-user-graduate fa-2x mb-2"></i>
                    <h3 class="mb-0">{{ $stats['total_mentees'] }}</h3>
                    <small>Mentees</small>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3 mb-3">
            <div class="card stat-card-warning">
                <div class="card-body text-center">
                    <i class="fas fa-clock fa-2x mb-2"></i>
                    <h3 class="mb-0">{{ $stats['pending_mentors'] }}</h3>
                    <small>En attente</small>
                </div>
            </div>
        </div>
    @elseif($user->role === 'mentor')
        <div class="col-md-6 mb-3">
            <div class="card stat-card">
                <div class="card-body text-center">
                    <i class="fas fa-user-check fa-2x mb-2"></i>
                    <h3 class="mb-0">{{ $stats['profile_status'] }}</h3>
                    <small>Statut du profil</small>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="card stat-card-success">
                <div class="card-body text-center">
                    <i class="fas fa-calendar-alt fa-2x mb-2"></i>
                    <h3 class="mb-0">{{ $stats['total_availabilities'] }}</h3>
                    <small>Disponibilités</small>
                </div>
            </div>
        </div>
    @elseif($user->role === 'mentee')
        <div class="col-md-6 mb-3">
            <div class="card stat-card">
                <div class="card-body text-center">
                    <i class="fas fa-chalkboard-teacher fa-2x mb-2"></i>
                    <h3 class="mb-0">{{ $stats['available_mentors'] }}</h3>
                    <small>Mentors disponibles</small>
                </div>
            </div>
        </div>
    @endif
</div>
@endif

<!-- Actions rapides -->
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Actions rapides</h5>
            </div>
            <div class="card-body">
                @if($user->role === 'admin')
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <a href="{{ route('admin.pending-mentors') }}" class="btn btn-outline-primary w-100">
                                <i class="fas fa-user-clock me-2"></i>Valider les mentors
                            </a>
                        </div>
                        <div class="col-md-6 mb-3">
                            <a href="{{ route('admin.stats') }}" class="btn btn-outline-info w-100">
                                <i class="fas fa-chart-pie me-2"></i>Voir les statistiques
                            </a>
                        </div>
                        <div class="col-md-6 mb-3">
                            <a href="{{ route('admin.users') }}" class="btn btn-outline-secondary w-100">
                                <i class="fas fa-users me-2"></i>Gérer les utilisateurs
                            </a>
                        </div>
                        <div class="col-md-6 mb-3">
                            <a href="{{ route('mentors.index') }}" class="btn btn-outline-success w-100">
                                <i class="fas fa-chalkboard-teacher me-2"></i>Voir les mentors
                            </a>
                        </div>
                    </div>
                @elseif($user->role === 'mentor')
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <a href="{{ route('mentor.profile') }}" class="btn btn-outline-primary w-100">
                                <i class="fas fa-user-edit me-2"></i>Gérer mon profil
                            </a>
                        </div>
                        <div class="col-md-6 mb-3">
                            <a href="{{ route('availabilities.create') }}" class="btn btn-outline-success w-100">
                                <i class="fas fa-calendar-plus me-2"></i>Ajouter disponibilités
                            </a>
                        </div>
                    </div>
                @else
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <a href="{{ route('mentors.index') }}" class="btn btn-outline-primary w-100">
                                <i class="fas fa-search me-2"></i>Trouver un mentor
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Informations</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>Nom :</strong> {{ $user->name }}
                </div>
                <div class="mb-3">
                    <strong>Email :</strong> {{ $user->email }}
                </div>
                <div class="mb-3">
                    <strong>Rôle :</strong> 
                    <span class="badge bg-secondary">{{ ucfirst($user->role) }}</span>
                </div>
                <div class="mb-3">
                    <strong>Membre depuis :</strong> {{ $user->created_at->format('d/m/Y') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection