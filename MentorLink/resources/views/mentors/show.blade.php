@extends('layouts.app')

@section('title', 'Profil de ' . $mentor->name . ' - MentorLink')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">Profil du mentor</h1>
        <p class="text-muted">{{ $mentor->name }}</p>
    </div>
    <a href="{{ route('mentors.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Retour à la liste
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-chalkboard-teacher me-2"></i>{{ $mentor->name }}
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <strong>Email :</strong><br>
                            <span class="text-muted">{{ $mentor->email }}</span>
                        </div>
                        
                        @if($mentor->mentorProfile)
                            <div class="mb-3">
                                <strong>Tarif horaire :</strong><br>
                                <span class="h4 text-success">{{ $mentor->mentorProfile->hourly_rate }}€/h</span>
                            </div>
                            
                            <div class="mb-3">
                                <strong>Statut :</strong><br>
                                @if($mentor->mentorProfile->is_validated)
                                    <span class="badge bg-success fs-6">
                                        <i class="fas fa-check me-1"></i>Profil validé
                                    </span>
                                @else
                                    <span class="badge bg-warning fs-6">
                                        <i class="fas fa-clock me-1"></i>En attente de validation
                                    </span>
                                @endif
                            </div>
                        @endif
                    </div>
                    
                    <div class="col-md-6">
                        @if($mentor->mentorProfile && $mentor->mentorProfile->domains)
                            <div class="mb-3">
                                <strong>Domaines d'expertise :</strong><br>
                                @foreach($mentor->mentorProfile->domains as $domain)
                                    <span class="badge bg-primary me-1 mb-1">{{ ucfirst($domain) }}</span>
                                @endforeach
                            </div>
                        @endif
                        
                        <div class="mb-3">
                            <strong>Membre depuis :</strong><br>
                            <span class="text-muted">{{ $mentor->created_at->format('d/m/Y') }}</span>
                        </div>
                        
                        @if($mentor->bio)
                            <div class="mb-3">
                                <strong>Bio :</strong><br>
                                <p class="text-muted">{{ $mentor->bio }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <!-- Disponibilités -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-calendar-alt me-2"></i>Disponibilités
                </h5>
            </div>
            <div class="card-body">
                @if($mentor->availabilities->count() > 0)
                    @php
                        $days = ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'];
                    @endphp
                    
                    @foreach($mentor->availabilities->sortBy('day_of_week') as $availability)
                        <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                            <div>
                                <strong>{{ $days[$availability->day_of_week] }}</strong><br>
                                <small class="text-muted">
                                    {{ \Carbon\Carbon::parse($availability->start_time)->format('H:i') }} - 
                                    {{ \Carbon\Carbon::parse($availability->end_time)->format('H:i') }}
                                </small>
                            </div>
                            <span class="badge bg-success">Disponible</span>
                        </div>
                    @endforeach
                @else
                    <div class="text-center text-muted py-3">
                        <i class="fas fa-calendar-times fa-2x mb-2"></i>
                        <p class="mb-0">Aucune disponibilité définie</p>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Actions -->
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="card-title mb-0">Actions</h5>
            </div>
            <div class="card-body">
                @if($mentor->mentorProfile && $mentor->mentorProfile->is_validated)
                    <div class="d-grid gap-2">
                        @if($mentor->availabilities->count() > 0)
                            <button class="btn btn-primary" disabled>
                                <i class="fas fa-calendar-plus me-2"></i>Réserver une session
                            </button>
                            <small class="text-muted">Fonctionnalité à venir</small>
                        @else
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                Ce mentor n'a pas encore défini ses disponibilités.
                            </div>
                        @endif
                        
                        <a href="{{ route('mentors.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-search me-2"></i>Voir d'autres mentors
                        </a>
                    </div>
                @else
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Ce profil mentor est en attente de validation.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection