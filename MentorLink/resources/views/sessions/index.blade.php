@extends('layouts.app')

@section('title', 'Mes Sessions')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Mes Sessions</h1>
                @if(auth()->user()->role === 'mentee')
                    <a href="{{ route('mentors.index') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Réserver une session
                    </a>
                @endif
            </div>

            @if($sessions->count() > 0)
                <div class="row">
                    @foreach($sessions as $session)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card h-100">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0">
                                        @if(auth()->user()->role === 'mentor')
                                            Avec {{ $session->mentee->name }}
                                        @else
                                            Avec {{ $session->mentor->name }}
                                        @endif
                                    </h6>
                                    <span class="badge 
                                        @if($session->status === 'confirmee') bg-success
                                        @elseif($session->status === 'en_attente') bg-warning
                                        @elseif($session->status === 'annulee') bg-danger
                                        @else bg-secondary
                                        @endif">
                                        {{ ucfirst(str_replace('_', ' ', $session->status)) }}
                                    </span>
                                </div>
                                <div class="card-body">
                                    <p class="card-text">
                                        <i class="fas fa-calendar me-2"></i>
                                        {{ $session->scheduled_at->format('d/m/Y à H:i') }}
                                    </p>
                                    <p class="card-text">
                                        <i class="fas fa-clock me-2"></i>
                                        {{ $session->duration_min }} minutes
                                    </p>
                                    @if($session->session_notes)
                                        <p class="card-text">
                                            <i class="fas fa-sticky-note me-2"></i>
                                            {{ Str::limit($session->session_notes, 50) }}
                                        </p>
                                    @endif
                                    
                                    @if($session->meeting_link && $session->status === 'confirmee')
                                        @php
                                            $now = now();
                                            $canJoin = $now->greaterThanOrEqualTo($session->scheduled_at->copy()->subMinutes(10)) && 
                                                      $now->lessThanOrEqualTo($session->scheduled_at->copy()->addMinutes($session->duration_min));
                                            $isActive = $now->greaterThanOrEqualTo($session->scheduled_at) && 
                                                       $now->lessThanOrEqualTo($session->scheduled_at->copy()->addMinutes($session->duration_min));
                                        @endphp
                                        
                                        <div class="mt-2">
                                            @if($isActive)
                                                <span class="badge bg-success">
                                                    <i class="fas fa-video me-1"></i>Session en cours
                                                </span>
                                            @elseif($canJoin)
                                                <span class="badge bg-warning">
                                                    <i class="fas fa-clock me-1"></i>Peut rejoindre
                                                </span>
                                            @else
                                                <span class="badge bg-info">
                                                    <i class="fas fa-link me-1"></i>Lien généré
                                                </span>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                                <div class="card-footer">
                                    <div class="d-flex gap-2 flex-wrap">
                                        <a href="{{ route('sessions.show', $session) }}" class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-eye me-1"></i>Voir
                                        </a>
                                        
                                        @if($session->meeting_link && $session->status === 'confirmee')
                                            @php
                                                $now = now();
                                                $canJoin = $now->greaterThanOrEqualTo($session->scheduled_at->copy()->subMinutes(10)) && 
                                                          $now->lessThanOrEqualTo($session->scheduled_at->copy()->addMinutes($session->duration_min));
                                                $isActive = $now->greaterThanOrEqualTo($session->scheduled_at) && 
                                                           $now->lessThanOrEqualTo($session->scheduled_at->copy()->addMinutes($session->duration_min));
                                            @endphp
                                            
                                            @if($canJoin)
                                                <a href="{{ $session->meeting_link }}" target="_blank" class="btn {{ $isActive ? 'btn-success' : 'btn-warning' }} btn-sm">
                                                    <i class="fas fa-video me-1"></i>
                                                    {{ $isActive ? 'Rejoindre' : 'Prêt' }}
                                                </a>
                                            @endif
                                        @endif
                                        
                                        @if($session->status === 'en_attente' && auth()->user()->role === 'mentor')
                                            <form method="POST" action="{{ route('sessions.confirm', $session) }}" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-success btn-sm">
                                                    <i class="fas fa-check me-1"></i>Confirmer
                                                </button>
                                            </form>
                                        @endif
                                        
                                        @if(in_array($session->status, ['en_attente', 'confirmee']))
                                            <form method="POST" action="{{ route('sessions.cancel', $session) }}" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-outline-danger btn-sm" 
                                                        onclick="return confirm('Êtes-vous sûr de vouloir annuler cette session ?')">
                                                    <i class="fas fa-times me-1"></i>Annuler
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{ $sessions->links() }}
            @else
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                        <h5>Aucune session</h5>
                        <p class="text-muted">
                            @if(auth()->user()->role === 'mentee')
                                Vous n'avez pas encore réservé de session.
                            @else
                                Vous n'avez pas encore de session programmée.
                            @endif
                        </p>
                        @if(auth()->user()->role === 'mentee')
                            <a href="{{ route('mentors.index') }}" class="btn btn-primary">
                                <i class="fas fa-search me-2"></i>Trouver un mentor
                            </a>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection