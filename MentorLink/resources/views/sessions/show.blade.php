@extends('layouts.app')

@section('title', 'Détails de la session')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Détails de la session</h4>
                    <span class="badge badge-lg
                        @if($session->status === 'confirmee') bg-success
                        @elseif($session->status === 'en_attente') bg-warning
                        @elseif($session->status === 'annulee') bg-danger
                        @elseif($session->status === 'terminee') bg-info
                        @else bg-secondary
                        @endif">
                        {{ ucfirst(str_replace('_', ' ', $session->status)) }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Informations générales</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <td><i class="fas fa-calendar me-2"></i>Date :</td>
                                    <td><strong>{{ $session->scheduled_at->format('d/m/Y') }}</strong></td>
                                </tr>
                                <tr>
                                    <td><i class="fas fa-clock me-2"></i>Heure :</td>
                                    <td><strong>{{ $session->scheduled_at->format('H:i') }}</strong></td>
                                </tr>
                                <tr>
                                    <td><i class="fas fa-hourglass-half me-2"></i>Durée :</td>
                                    <td><strong>{{ $session->duration_min }} minutes</strong></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6>Participants</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <td><i class="fas fa-chalkboard-teacher me-2"></i>Mentor :</td>
                                    <td><strong>{{ $session->mentor->name }}</strong></td>
                                </tr>
                                <tr>
                                    <td><i class="fas fa-user-graduate me-2"></i>Mentee :</td>
                                    <td><strong>{{ $session->mentee->name }}</strong></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($session->session_notes)
                        <div class="mt-3">
                            <h6>Notes de la session</h6>
                            <div class="alert alert-light">
                                <i class="fas fa-sticky-note me-2"></i>
                                {{ $session->session_notes }}
                            </div>
                        </div>
                    @endif

                    @if($session->meeting_link && $session->status === 'confirmee')
                        <div class="mt-3">
                            <h6>Lien de la réunion</h6>
                            @php
                                $now = now();
                                $sessionStart = $session->scheduled_at;
                                $sessionEnd = $session->scheduled_at->copy()->addMinutes($session->duration_min);
                                $canJoin = $now->greaterThanOrEqualTo($sessionStart->copy()->subMinutes(10)) && $now->lessThanOrEqualTo($sessionEnd);
                                $isActive = $now->greaterThanOrEqualTo($sessionStart) && $now->lessThanOrEqualTo($sessionEnd);
                            @endphp
                            
                            <div class="alert {{ $isActive ? 'alert-success' : ($canJoin ? 'alert-warning' : 'alert-info') }}">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <i class="fas fa-video me-2"></i>
                                        @if($isActive)
                                            <strong>Session en cours !</strong>
                                        @elseif($canJoin)
                                            <strong>Vous pouvez rejoindre la session</strong>
                                        @else
                                            <strong>Lien de réunion généré</strong>
                                        @endif
                                    </div>
                                    <div>
                                        @if($canJoin)
                                            <a href="{{ $session->meeting_link }}" target="_blank" class="btn {{ $isActive ? 'btn-success' : 'btn-warning' }} btn-lg">
                                                <i class="fas fa-video me-2"></i>
                                                {{ $isActive ? 'Rejoindre maintenant' : 'Rejoindre la session' }}
                                            </a>
                                        @else
                                            <button class="btn btn-outline-secondary" onclick="copyMeetLink('{{ $session->meeting_link }}')">
                                                <i class="fas fa-copy me-2"></i>Copier le lien
                                            </button>
                                        @endif
                                    </div>
                                </div>
                                
                                @if(!$canJoin)
                                    <div class="mt-2">
                                        <small class="text-muted">
                                            <i class="fas fa-info-circle me-1"></i>
                                            Vous pourrez rejoindre la session 10 minutes avant le début.
                                        </small>
                                    </div>
                                @endif
                                
                                <div class="mt-2">
                                    <small class="text-muted d-block">
                                        <strong>Lien :</strong> 
                                        <code id="meetLink">{{ $session->meeting_link }}</code>
                                    </small>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Actions -->
                    <div class="mt-4">
                        <div class="d-flex gap-2 flex-wrap">
                            <a href="{{ route('sessions.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Retour aux sessions
                            </a>

                            @if($session->status === 'en_attente' && auth()->user()->role === 'mentor')
                                <form method="POST" action="{{ route('sessions.confirm', $session) }}" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-check me-2"></i>Confirmer la session
                                    </button>
                                </form>
                            @endif

                            @if($session->status === 'confirmee' && auth()->user()->role === 'mentor' && $session->scheduled_at->isPast())
                                <form method="POST" action="{{ route('sessions.complete', $session) }}" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-info">
                                        <i class="fas fa-flag-checkered me-2"></i>Marquer comme terminée
                                    </button>
                                </form>
                            @endif

                            @if(in_array($session->status, ['en_attente', 'confirmee']) && $session->scheduled_at->isFuture())
                                @php
                                    $now = now();
                                    $minutesUntilSession = $now->diffInMinutes($session->scheduled_at, false);
                                    $isLateCancellation = $minutesUntilSession <= 15;
                                @endphp
                                
                                <form method="POST" action="{{ route('sessions.cancel', $session) }}" class="d-inline" id="cancelForm">
                                    @csrf
                                    @method('PATCH')
                                    <button type="button" class="btn btn-outline-danger" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#cancelModal"
                                            data-session-id="{{ $session->id }}"
                                            data-is-late="{{ $isLateCancellation && auth()->user()->role === 'mentor' ? 'true' : 'false' }}">
                                        <i class="fas fa-times me-2"></i>Annuler la session
                                        @if($isLateCancellation && auth()->user()->role === 'mentor')
                                            <i class="fas fa-exclamation-triangle ms-1 text-warning"></i>
                                        @endif
                                    </button>
                                </form>
                            @endif

                            @if($session->status === 'terminee' && auth()->user()->role === 'mentee' && !$session->is_reviewed)
                                <a href="{{ route('reviews.create', $session) }}" class="btn btn-warning">
                                    <i class="fas fa-star me-2"></i>Laisser un avis
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- Countdown pour les sessions confirmées -->
                    @if($session->status === 'confirmee' && $session->scheduled_at->isFuture())
                        <div class="mt-4">
                            <div class="alert alert-success border-0 shadow-sm">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <h6 class="mb-2">
                                            <i class="fas fa-clock me-2"></i>Temps restant avant la session
                                        </h6>
                                        <div id="countdown" class="h3 mb-0 fw-bold text-success"></div>
                                        <small class="text-muted mt-1 d-block">
                                            Session prévue le {{ $session->scheduled_at->format('d/m/Y à H:i') }}
                                        </small>
                                    </div>
                                    <div class="text-end">
                                        <i class="fas fa-hourglass-half fa-2x text-success opacity-50"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmation d'annulation -->
<div class="modal fade" id="cancelModal" tabindex="-1" aria-labelledby="cancelModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cancelModalLabel">
                    <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                    Confirmer l'annulation
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="normalCancelMessage">
                    <p class="mb-0">Êtes-vous sûr de vouloir annuler cette session ?</p>
                </div>
                <div id="lateCancelMessage" style="display: none;">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>ATTENTION :</strong> Annuler moins de 15 minutes avant la session entraînera une pénalité de 0.5 étoile.
                    </div>
                    <p class="mb-0">Êtes-vous sûr de vouloir continuer ?</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Annuler
                </button>
                <button type="button" class="btn btn-danger" id="confirmCancelBtn">
                    <i class="fas fa-check me-2"></i>Confirmer l'annulation
                </button>
            </div>
        </div>
    </div>
</div>

@if($session->status === 'confirmee' && $session->scheduled_at->isFuture())
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const sessionDate = new Date('{{ $session->scheduled_at->toISOString() }}');
    const sessionDuration = {{ $session->duration_min }};
    const countdownElement = document.getElementById('countdown');
    
    if (!countdownElement) {
        console.error('Élément countdown non trouvé');
        return;
    }
    
    function updateCountdown() {
        const now = new Date();
        const timeLeft = sessionDate - now;
        const sessionEnd = new Date(sessionDate.getTime() + (sessionDuration * 60000));
        const timeUntilEnd = sessionEnd - now;
        
        if (timeLeft > 0) {
            // Avant le début de la session
            const days = Math.floor(timeLeft / (1000 * 60 * 60 * 24));
            const hours = Math.floor((timeLeft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);
            
            let countdownText = '';
            if (days > 0) {
                countdownText += `${days}j `;
            }
            if (hours > 0 || days > 0) {
                countdownText += `${hours.toString().padStart(2, '0')}h `;
            }
            countdownText += `${minutes.toString().padStart(2, '0')}m `;
            countdownText += `${seconds.toString().padStart(2, '0')}s`;
            
            countdownElement.textContent = countdownText;
            
            // Changer la couleur selon la proximité
            const alertBox = countdownElement.closest('.alert');
            if (timeLeft <= 300000) { // 5 minutes
                countdownElement.className = 'h3 mb-0 fw-bold text-danger';
                alertBox.className = 'alert alert-danger border-0 shadow-sm';
            } else if (timeLeft <= 600000) { // 10 minutes
                countdownElement.className = 'h3 mb-0 fw-bold text-warning';
                alertBox.className = 'alert alert-warning border-0 shadow-sm';
            } else if (timeLeft <= 3600000) { // 1 heure
                countdownElement.className = 'h3 mb-0 fw-bold text-info';
                alertBox.className = 'alert alert-info border-0 shadow-sm';
            } else {
                countdownElement.className = 'h3 mb-0 fw-bold text-success';
                alertBox.className = 'alert alert-success border-0 shadow-sm';
            }
            
        } else if (timeUntilEnd > 0) {
            // Pendant la session
            const minutesLeft = Math.floor(timeUntilEnd / (1000 * 60));
            const secondsLeft = Math.floor((timeUntilEnd % (1000 * 60)) / 1000);
            
            countdownElement.textContent = `Session en cours - Fin dans ${minutesLeft}m ${secondsLeft.toString().padStart(2, '0')}s`;
            countdownElement.className = 'h3 mb-0 fw-bold text-primary';
            
            const alertBox = countdownElement.closest('.alert');
            alertBox.className = 'alert alert-primary border-0 shadow-sm';
            
        } else {
            // Session terminée
            countdownElement.textContent = 'Session terminée';
            countdownElement.className = 'h3 mb-0 fw-bold text-muted';
            
            const alertBox = countdownElement.closest('.alert');
            alertBox.className = 'alert alert-secondary border-0 shadow-sm';
        }
    }
    
    // Mise à jour immédiate
    updateCountdown();
    
    // Mise à jour toutes les secondes
    const intervalId = setInterval(updateCountdown, 1000);
    
    // Nettoyer l'intervalle quand on quitte la page
    window.addEventListener('beforeunload', function() {
        clearInterval(intervalId);
    });
    
    // Debug: afficher l'heure de la session
    console.log('Session prévue à:', sessionDate);
    console.log('Heure actuelle:', new Date());
});

// Fonction pour copier le lien de réunion
function copyMeetLink(link) {
    navigator.clipboard.writeText(link).then(function() {
        // Afficher une notification de succès
        const toast = document.createElement('div');
        toast.className = 'toast align-items-center text-white bg-success border-0 position-fixed';
        toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999;';
        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">
                    <i class="fas fa-check me-2"></i>Lien copié dans le presse-papiers !
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        `;
        
        document.body.appendChild(toast);
        const bsToast = new bootstrap.Toast(toast);
        bsToast.show();
        
        // Supprimer le toast après fermeture
        toast.addEventListener('hidden.bs.toast', function() {
            document.body.removeChild(toast);
        });
    }).catch(function(err) {
        alert('Erreur lors de la copie : ' + err);
    });
}

// Gestion du modal d'annulation
document.addEventListener('DOMContentLoaded', function() {
    const cancelModal = document.getElementById('cancelModal');
    const confirmCancelBtn = document.getElementById('confirmCancelBtn');
    const normalMessage = document.getElementById('normalCancelMessage');
    const lateMessage = document.getElementById('lateCancelMessage');
    
    if (cancelModal) {
        cancelModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const isLate = button.getAttribute('data-is-late') === 'true';
            
            if (isLate) {
                normalMessage.style.display = 'none';
                lateMessage.style.display = 'block';
            } else {
                normalMessage.style.display = 'block';
                lateMessage.style.display = 'none';
            }
        });
        
        confirmCancelBtn.addEventListener('click', function() {
            document.getElementById('cancelForm').submit();
        });
    }
});
</script>
@endpush
@endif
@endsection