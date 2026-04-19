@extends('layouts.app')

@section('title', 'Gestion des utilisateurs - Administration')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">Gestion des utilisateurs</h1>
        <p class="text-muted">Liste et gestion de tous les utilisateurs</p>
    </div>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Retour
    </a>
</div>

<!-- Filtres -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Filtrer par rôle</label>
                <select name="role" class="form-select">
                    <option value="">Tous les rôles</option>
                    <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Administrateur</option>
                    <option value="mentor" {{ request('role') === 'mentor' ? 'selected' : '' }}>Mentor</option>
                    <option value="mentee" {{ request('role') === 'mentee' ? 'selected' : '' }}>Mentee</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Rechercher</label>
                <input type="text" name="search" class="form-control" 
                       placeholder="Nom ou email..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label">&nbsp;</label>
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search me-1"></i>Filtrer
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Liste des utilisateurs -->
@if($users->count() > 0)
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">
                Utilisateurs ({{ $users->total() }} résultat(s))
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Utilisateur</th>
                            <th>Rôle</th>
                            <th>Statut</th>
                            <th>Inscription</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td>
                                    <div>
                                        <strong>{{ $user->name }}</strong><br>
                                        <small class="text-muted">{{ $user->email }}</small>
                                    </div>
                                </td>
                                <td>
                                    @if($user->role === 'admin')
                                        <span class="badge bg-danger">Administrateur</span>
                                    @elseif($user->role === 'mentor')
                                        <span class="badge bg-success">Mentor</span>
                                    @elseif($user->role === 'mentee')
                                        <span class="badge bg-info">Mentee</span>
                                    @else
                                        <span class="badge bg-secondary">{{ ucfirst($user->role) }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($user->email_verified_at)
                                        <span class="badge bg-success">
                                            <i class="fas fa-check me-1"></i>Vérifié
                                        </span>
                                    @else
                                        <span class="badge bg-warning">
                                            <i class="fas fa-exclamation-triangle me-1"></i>Non vérifié
                                        </span>
                                    @endif
                                    
                                    @if($user->role === 'mentor' && $user->mentorProfile)
                                        <br>
                                        @if($user->mentorProfile->is_validated)
                                            <span class="badge bg-success mt-1">Profil validé</span>
                                        @else
                                            <span class="badge bg-warning mt-1">Profil en attente</span>
                                        @endif
                                    @endif
                                </td>
                                <td>
                                    <small class="text-muted">
                                        {{ $user->created_at->format('d/m/Y') }}<br>
                                        {{ $user->created_at->diffForHumans() }}
                                    </small>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        @if($user->role === 'mentor')
                                            <a href="{{ route('mentors.show', $user->id) }}" 
                                               class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        @endif
                                        
                                        @if($user->role === 'mentor' && $user->mentorProfile && !$user->mentorProfile->is_validated)
                                            <a href="{{ route('admin.pending-mentors') }}" 
                                               class="btn btn-outline-warning btn-sm">
                                                <i class="fas fa-clock"></i>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        {{ $users->appends(request()->query())->links() }}
    </div>
@else
    <div class="card">
        <div class="card-body text-center py-5">
            <i class="fas fa-users fa-4x text-muted mb-3"></i>
            <h4>Aucun utilisateur trouvé</h4>
            <p class="text-muted">Aucun utilisateur ne correspond aux critères de recherche.</p>
            <a href="{{ route('admin.users') }}" class="btn btn-primary">
                <i class="fas fa-refresh me-2"></i>Voir tous les utilisateurs
            </a>
        </div>
    </div>
@endif

<!-- Statistiques rapides -->
<div class="row mt-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body text-center">
                <h4>{{ $users->total() }}</h4>
                <small>Résultats affichés</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body text-center">
                <h4>{{ $users->where('role', 'mentor')->count() }}</h4>
                <small>Mentors dans cette page</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body text-center">
                <h4>{{ $users->where('role', 'mentee')->count() }}</h4>
                <small>Mentees dans cette page</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body text-center">
                <h4>{{ $users->where('email_verified_at', null)->count() }}</h4>
                <small>Non vérifiés dans cette page</small>
            </div>
        </div>
    </div>
</div>
@endsection