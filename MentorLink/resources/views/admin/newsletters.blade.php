@extends('layouts.app')

@section('title', 'Newsletter - Administration')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">Gestion de la newsletter</h1>
        <p class="text-muted">Liste des abonnés à la newsletter</p>
    </div>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Retour
    </a>
</div>

<!-- Statistiques rapides -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body text-center">
                <h4>{{ $newsletters->total() }}</h4>
                <small>Total abonnés</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body text-center">
                <h4>{{ \App\Models\Newsletter::where('is_active', true)->count() }}</h4>
                <small>Actifs</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body text-center">
                <h4>{{ \App\Models\Newsletter::where('is_active', false)->count() }}</h4>
                <small>Désabonnés</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body text-center">
                <h4>{{ \App\Models\Newsletter::whereDate('subscribed_at', today())->count() }}</h4>
                <small>Aujourd'hui</small>
            </div>
        </div>
    </div>
</div>

<!-- Filtres -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Filtrer par statut</label>
                <select name="status" class="form-select">
                    <option value="">Tous les statuts</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Actifs</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Désabonnés</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Rechercher</label>
                <input type="text" name="search" class="form-control" 
                       placeholder="Email ou nom..." value="{{ request('search') }}">
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

<!-- Liste des abonnés -->
@if($newsletters->count() > 0)
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">
                Abonnés newsletter ({{ $newsletters->total() }} résultat(s))
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Email</th>
                            <th>Nom</th>
                            <th>Statut</th>
                            <th>Date d'abonnement</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($newsletters as $newsletter)
                            <tr>
                                <td>
                                    <strong>{{ $newsletter->email }}</strong>
                                </td>
                                <td>
                                    {{ $newsletter->name ?: 'Non renseigné' }}
                                </td>
                                <td>
                                    @if($newsletter->is_active)
                                        <span class="badge bg-success">
                                            <i class="fas fa-check me-1"></i>Actif
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">
                                            <i class="fas fa-times me-1"></i>Désabonné
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <small class="text-muted">
                                        {{ $newsletter->subscribed_at->format('d/m/Y H:i') }}<br>
                                        {{ $newsletter->subscribed_at->diffForHumans() }}
                                    </small>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-outline-primary btn-sm" 
                                                onclick="copyEmail('{{ $newsletter->email }}')" 
                                                title="Copier l'email">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                        
                                        @if($newsletter->is_active)
                                            <a href="mailto:{{ $newsletter->email }}" 
                                               class="btn btn-outline-success btn-sm" 
                                               title="Envoyer un email">
                                                <i class="fas fa-envelope"></i>
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
        {{ $newsletters->appends(request()->query())->links() }}
    </div>
@else
    <div class="card">
        <div class="card-body text-center py-5">
            <i class="fas fa-envelope fa-4x text-muted mb-3"></i>
            <h4>Aucun abonné trouvé</h4>
            <p class="text-muted">Aucun abonné ne correspond aux critères de recherche.</p>
            <a href="{{ route('admin.newsletters') }}" class="btn btn-primary">
                <i class="fas fa-refresh me-2"></i>Voir tous les abonnés
            </a>
        </div>
    </div>
@endif

<script>
function copyEmail(email) {
    navigator.clipboard.writeText(email).then(function() {
        // Créer une notification temporaire
        const toast = document.createElement('div');
        toast.className = 'position-fixed top-0 end-0 m-3 alert alert-success';
        toast.style.zIndex = '9999';
        toast.innerHTML = '<i class="fas fa-check me-2"></i>Email copié: ' + email;
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.remove();
        }, 3000);
    });
}
</script>
@endsection