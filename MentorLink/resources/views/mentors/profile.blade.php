@extends('layouts.app')

@section('title', 'Mon profil mentor - MentorLink')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">Mon profil mentor</h1>
        <p class="text-muted">Gérez vos informations de mentorat</p>
    </div>
    <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Retour au dashboard
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Informations du profil</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('mentor.profile.update') }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label">Domaines d'expertise</label>
                        <div class="row">
                            @php
                                $domains = ['web', 'mobile', 'data', 'devops', 'design', 'backend', 'frontend'];
                                $userDomains = $user->mentorProfile->domains ?? [];
                            @endphp
                            
                            @foreach($domains as $domain)
                                <div class="col-md-6 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" 
                                               name="domains[]" value="{{ $domain }}" 
                                               id="domain_{{ $domain }}"
                                               {{ in_array($domain, $userDomains) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="domain_{{ $domain }}">
                                            {{ ucfirst($domain) }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @error('domains')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Tarif horaire (€)</label>
                        <input type="number" name="hourly_rate" class="form-control @error('hourly_rate') is-invalid @enderror" 
                               value="{{ old('hourly_rate', $user->mentorProfile->hourly_rate ?? '') }}" 
                               min="1" max="200" required>
                        @error('hourly_rate')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Votre tarif horaire en euros</div>
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Sauvegarder le profil
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <!-- Statut du profil -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Statut du profil</h5>
            </div>
            <div class="card-body">
                @if($user->mentorProfile)
                    @if($user->mentorProfile->is_validated)
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle me-2"></i>
                            <strong>Profil validé</strong><br>
                            Votre profil est visible par les mentees.
                        </div>
                    @else
                        <div class="alert alert-warning">
                            <i class="fas fa-clock me-2"></i>
                            <strong>En attente de validation</strong><br>
                            Votre profil sera examiné par un administrateur.
                        </div>
                    @endif
                    
                    <div class="mb-3">
                        <strong>Domaines actuels :</strong><br>
                        @if($user->mentorProfile->domains)
                            @foreach($user->mentorProfile->domains as $domain)
                                <span class="badge bg-primary me-1 mb-1">{{ ucfirst($domain) }}</span>
                            @endforeach
                        @else
                            <span class="text-muted">Aucun domaine défini</span>
                        @endif
                    </div>
                    
                    <div class="mb-3">
                        <strong>Tarif :</strong> {{ $user->mentorProfile->hourly_rate }}€/h
                    </div>
                @else
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Profil non créé</strong><br>
                        Remplissez le formulaire pour créer votre profil mentor.
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Actions rapides -->
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="card-title mb-0">Actions rapides</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('availabilities.create') }}" class="btn btn-outline-primary">
                        <i class="fas fa-calendar-plus me-2"></i>Gérer mes disponibilités
                    </a>
                    
                    @if($user->mentorProfile && $user->mentorProfile->is_validated)
                        <a href="{{ route('mentors.show', $user->id) }}" class="btn btn-outline-success">
                            <i class="fas fa-eye me-2"></i>Voir mon profil public
                        </a>
                    @endif
                    
                    <a href="{{ route('mentors.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-users me-2"></i>Voir les autres mentors
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection