@extends('layouts.app')

@section('title', 'Notifications')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">
            <i class="fas fa-bell me-2"></i>Notifications
        </h1>
        <p class="text-muted">Gérez vos notifications</p>
    </div>
    <div>
        @if(auth()->user()->unreadNotifications->count() > 0)
            <form method="POST" action="{{ route('notifications.mark-all-read') }}" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-outline-primary">
                    <i class="fas fa-check-double me-2"></i>Tout marquer comme lu
                </button>
            </form>
        @endif
        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary ms-2">
            <i class="fas fa-arrow-left me-2"></i>Retour
        </a>
    </div>
</div>

@if($notifications->count() > 0)
    <div class="card">
        <div class="card-body p-0">
            @foreach($notifications as $notification)
                <div class="notification-item border-bottom p-3 {{ $notification->read_at ? 'bg-light' : 'bg-white' }}">
                    <div class="d-flex align-items-start">
                        <div class="notification-icon me-3">
                            @php
                                $type = $notification->data['type'] ?? 'default';
                                $iconClass = match($type) {
                                    'session_confirmed' => 'fas fa-check-circle text-success',
                                    'session_cancelled' => 'fas fa-times-circle text-danger',
                                    'session_reminder' => 'fas fa-clock text-warning',
                                    'mentor_validation' => 'fas fa-user-check text-info',
                                    default => 'fas fa-bell text-primary'
                                };
                            @endphp
                            <i class="{{ $iconClass }} fa-lg"></i>
                        </div>
                        
                        <div class="notification-content flex-grow-1">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="mb-1 {{ !$notification->read_at ? 'fw-bold' : '' }}">
                                        {{ $notification->data['title'] ?? 'Notification' }}
                                    </h6>
                                    <p class="mb-2 text-muted">
                                        {{ $notification->data['message'] ?? 'Aucun message' }}
                                    </p>
                                    <small class="text-muted">
                                        <i class="fas fa-clock me-1"></i>
                                        {{ $notification->created_at->diffForHumans() }}
                                    </small>
                                </div>
                                
                                <div class="notification-actions">
                                    @if(!$notification->read_at)
                                        <span class="badge bg-primary">Nouveau</span>
                                    @endif
                                    
                                    @if(!$notification->read_at)
                                        <button class="btn btn-sm btn-outline-secondary ms-2" 
                                                onclick="markAsRead('{{ $notification->id }}')">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Actions spécifiques selon le type -->
                            @if(isset($notification->data['session_id']))
                                <div class="mt-2">
                                    <a href="{{ route('sessions.show', $notification->data['session_id']) }}" 
                                       class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye me-1"></i>Voir la session
                                    </a>
                                </div>
                            @endif
                            
                            @if($type === 'mentor_validation' && auth()->user()->role === 'admin')
                                <div class="mt-2">
                                    <a href="{{ route('admin.pending-mentors') }}" 
                                       class="btn btn-sm btn-info">
                                        <i class="fas fa-user-check me-1"></i>Gérer les validations
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    
    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        {{ $notifications->links() }}
    </div>
@else
    <div class="card">
        <div class="card-body text-center py-5">
            <i class="fas fa-bell-slash fa-4x text-muted mb-3"></i>
            <h4>Aucune notification</h4>
            <p class="text-muted">Vous n'avez aucune notification pour le moment.</p>
            <a href="{{ route('dashboard') }}" class="btn btn-primary">
                <i class="fas fa-arrow-left me-2"></i>Retour au dashboard
            </a>
        </div>
    </div>
@endif

<script>
function markAsRead(notificationId) {
    fetch(`/notifications/${notificationId}/read`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
    });
}
</script>

<style>
.notification-item:hover {
    background-color: #f8f9fa !important;
}

.notification-icon {
    width: 40px;
    text-align: center;
}

.notification-actions {
    min-width: 100px;
    text-align: right;
}
</style>
@endsection