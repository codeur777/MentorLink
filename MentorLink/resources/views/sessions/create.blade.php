@extends('layouts.app')

@section('title', 'Réserver une session')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Réserver une session avec {{ $mentor->name }}</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('sessions.store') }}">
                        @csrf
                        <input type="hidden" name="mentor_id" value="{{ $mentor->id }}">

                        <!-- Date et heure -->
                        <div class="mb-3">
                            <label for="scheduled_at" class="form-label">Date et heure</label>
                            <input type="datetime-local" 
                                   class="form-control @error('scheduled_at') is-invalid @enderror" 
                                   id="scheduled_at" 
                                   name="scheduled_at" 
                                   value="{{ old('scheduled_at') }}" 
                                   min="{{ now()->addHours(2)->format('Y-m-d\TH:i') }}"
                                   required>
                            @error('scheduled_at')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                Vous devez réserver au moins 2 heures à l'avance.
                            </div>
                        </div>

                        <!-- Durée -->
                        <div class="mb-3">
                            <label for="duration_min" class="form-label">Durée</label>
                            <select class="form-select @error('duration_min') is-invalid @enderror" 
                                    id="duration_min" 
                                    name="duration_min" 
                                    required>
                                <option value="">Choisir une durée</option>
                                <option value="30" {{ old('duration_min') == '30' ? 'selected' : '' }}>30 minutes</option>
                                <option value="60" {{ old('duration_min') == '60' ? 'selected' : '' }}>1 heure</option>
                                <option value="90" {{ old('duration_min') == '90' ? 'selected' : '' }}>1h30</option>
                                <option value="120" {{ old('duration_min') == '120' ? 'selected' : '' }}>2 heures</option>
                            </select>
                            @error('duration_min')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Notes -->
                        <div class="mb-3">
                            <label for="session_notes" class="form-label">Notes (optionnel)</label>
                            <textarea class="form-control @error('session_notes') is-invalid @enderror" 
                                      id="session_notes" 
                                      name="session_notes" 
                                      rows="3" 
                                      placeholder="Décrivez brièvement ce que vous aimeriez aborder pendant la session...">{{ old('session_notes') }}</textarea>
                            @error('session_notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Disponibilités du mentor -->
                        @if($availabilities->count() > 0)
                            <div class="mb-3">
                                <h6>Disponibilités du mentor :</h6>
                                <div class="row">
                                    @foreach($availabilities->groupBy('day_of_week') as $day => $dayAvailabilities)
                                        <div class="col-md-6 mb-2">
                                            <strong>{{ ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'][$day] }} :</strong><br>
                                            @foreach($dayAvailabilities as $availability)
                                                <small class="text-muted">
                                                    {{ Carbon\Carbon::parse($availability->start_time)->format('H:i') }} - 
                                                    {{ Carbon\Carbon::parse($availability->end_time)->format('H:i') }}
                                                </small><br>
                                            @endforeach
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('mentors.show', $mentor) }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Retour
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-calendar-plus me-2"></i>Réserver la session
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const bookedSessions = @json($bookedSessions);
    const scheduledInput = document.getElementById('scheduled_at');
    
    scheduledInput.addEventListener('change', function() {
        const selectedDateTime = this.value;
        if (bookedSessions.includes(selectedDateTime)) {
            alert('Cette heure est déjà réservée. Veuillez choisir une autre heure.');
            this.value = '';
        }
    });
});
</script>
@endpush
@endsection