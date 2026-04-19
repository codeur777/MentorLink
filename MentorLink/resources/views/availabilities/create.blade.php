@extends('layouts.app')

@section('title', 'Ajouter des disponibilités - MentorLink')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">Gérer mes disponibilités</h1>
        <p class="text-muted">Définissez vos créneaux de mentorat</p>
    </div>
    <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Retour au dashboard
    </a>
</div>

<div class="row">
    <!-- Formulaire d'ajout -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Ajouter une disponibilité</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('availabilities.store') }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label">Jour de la semaine</label>
                        <select name="day_of_week" class="form-select" required>
                            <option value="">Sélectionner un jour</option>
                            <option value="0">Dimanche</option>
                            <option value="1">Lundi</option>
                            <option value="2">Mardi</option>
                            <option value="3">Mercredi</option>
                            <option value="4">Jeudi</option>
                            <option value="5">Vendredi</option>
                            <option value="6">Samedi</option>
                        </select>
                        @error('day_of_week')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Heure de début</label>
                        <input type="time" name="start_time" class="form-control" required>
                        @error('start_time')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Heure de fin</label>
                        <input type="time" name="end_time" class="form-control" required>
                        @error('end_time')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Ajouter cette disponibilité
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Liste des disponibilités existantes -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Mes disponibilités actuelles</h5>
            </div>
            <div class="card-body">
                @if($user->availabilities->count() > 0)
                    @php
                        $days = ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'];
                    @endphp
                    
                    @foreach($user->availabilities->sortBy('day_of_week') as $availability)
                        <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                            <div>
                                <strong>{{ $days[$availability->day_of_week] }}</strong><br>
                                <small class="text-muted">
                                    {{ \Carbon\Carbon::parse($availability->start_time)->format('H:i') }} - 
                                    {{ \Carbon\Carbon::parse($availability->end_time)->format('H:i') }}
                                </small>
                            </div>
                            <form method="POST" action="{{ route('availabilities.destroy', $availability) }}" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm" 
                                        onclick="return confirm('Supprimer cette disponibilité ?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    @endforeach
                @else
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-calendar-times fa-3x mb-3"></i>
                        <p>Aucune disponibilité définie</p>
                        <small>Ajoutez vos créneaux pour que les mentees puissent vous contacter</small>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Informations -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card bg-light">
            <div class="card-body">
                <h6 class="card-title">
                    <i class="fas fa-info-circle text-info me-2"></i>Informations importantes
                </h6>
                <ul class="mb-0 small">
                    <li>Les disponibilités sont récurrentes chaque semaine</li>
                    <li>Les mentees pourront voir vos créneaux disponibles</li>
                    <li>Vous pouvez modifier ou supprimer vos disponibilités à tout moment</li>
                    <li>Assurez-vous de maintenir vos disponibilités à jour</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection