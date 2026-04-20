@extends('layouts.app')

@section('title', 'Laisser un avis')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">
                        <i class="fas fa-star me-2"></i>Évaluer votre session
                    </h4>
                </div>
                <div class="card-body">
                    <!-- Informations de la session -->
                    <div class="alert alert-info mb-4">
                        <div class="row">
                            <div class="col-md-6">
                                <strong><i class="fas fa-chalkboard-teacher me-2"></i>Mentor :</strong> {{ $session->mentor->name }}<br>
                                <strong><i class="fas fa-calendar me-2"></i>Date :</strong> {{ $session->scheduled_at->format('d/m/Y à H:i') }}<br>
                            </div>
                            <div class="col-md-6">
                                <strong><i class="fas fa-clock me-2"></i>Durée :</strong> {{ $session->duration_min }} minutes<br>
                                <strong><i class="fas fa-check-circle me-2"></i>Statut :</strong> Session terminée
                            </div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('reviews.store', $session) }}">
                        @csrf

                        <!-- Notation par étoiles -->
                        <div class="mb-4">
                            <label class="form-label">
                                <strong>Votre note</strong>
                                <span class="text-danger">*</span>
                            </label>
                            <div class="rating-input">
                                <div class="star-rating" id="starRating">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star star" data-rating="{{ $i }}"></i>
                                    @endfor
                                </div>
                                <input type="hidden" name="rating" id="ratingValue" value="{{ old('rating') }}">
                                <div class="rating-text mt-2">
                                    <span id="ratingText" class="text-muted">Cliquez sur les étoiles pour noter</span>
                                </div>
                            </div>
                            @error('rating')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Commentaire -->
                        <div class="mb-4">
                            <label for="comment" class="form-label">
                                <strong>Votre commentaire</strong>
                                <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control @error('comment') is-invalid @enderror" 
                                      id="comment" 
                                      name="comment" 
                                      rows="4" 
                                      placeholder="Partagez votre expérience avec ce mentor. Qu'avez-vous appris ? Comment s'est déroulée la session ?"
                                      required>{{ old('comment') }}</textarea>
                            @error('comment')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                Minimum 10 caractères. Votre avis aidera d'autres étudiants à choisir ce mentor.
                            </div>
                        </div>

                        <!-- Conseils pour un bon avis -->
                        <div class="alert alert-light mb-4">
                            <h6><i class="fas fa-lightbulb me-2"></i>Conseils pour un avis utile :</h6>
                            <ul class="mb-0 small">
                                <li>Décrivez ce que vous avez appris pendant la session</li>
                                <li>Mentionnez la qualité de l'explication du mentor</li>
                                <li>Indiquez si le mentor était bien préparé</li>
                                <li>Restez constructif et respectueux</li>
                            </ul>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('sessions.show', $session) }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Retour
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane me-2"></i>Publier mon avis
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.star-rating {
    font-size: 2rem;
    color: #ddd;
    cursor: pointer;
    user-select: none;
}

.star-rating .star {
    transition: color 0.2s ease;
    margin-right: 0.25rem;
}

.star-rating .star:hover,
.star-rating .star.active {
    color: #ffc107;
}

.star-rating .star.hover {
    color: #ffeb3b;
}

.rating-input {
    padding: 1rem;
    border: 2px solid #e9ecef;
    border-radius: 0.5rem;
    text-align: center;
    background: #f8f9fa;
}

.rating-input.has-rating {
    border-color: #ffc107;
    background: #fff8e1;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const stars = document.querySelectorAll('.star');
    const ratingValue = document.getElementById('ratingValue');
    const ratingText = document.getElementById('ratingText');
    const ratingInput = document.querySelector('.rating-input');
    
    const ratingTexts = {
        1: '⭐ Décevant - La session n\'a pas répondu à mes attentes',
        2: '⭐⭐ Moyen - Quelques points utiles mais peut mieux faire',
        3: '⭐⭐⭐ Bien - Session satisfaisante avec de bons conseils',
        4: '⭐⭐⭐⭐ Très bien - Excellente session, mentor très compétent',
        5: '⭐⭐⭐⭐⭐ Exceptionnel - Session parfaite, mentor extraordinaire !'
    };
    
    // Restaurer la note si elle existe (old input)
    const oldRating = ratingValue.value;
    if (oldRating) {
        updateRating(parseInt(oldRating));
    }
    
    stars.forEach((star, index) => {
        star.addEventListener('mouseover', function() {
            highlightStars(index + 1, 'hover');
        });
        
        star.addEventListener('mouseout', function() {
            const currentRating = parseInt(ratingValue.value) || 0;
            highlightStars(currentRating, 'active');
        });
        
        star.addEventListener('click', function() {
            const rating = index + 1;
            updateRating(rating);
        });
    });
    
    function highlightStars(count, className) {
        stars.forEach((star, index) => {
            star.classList.remove('active', 'hover');
            if (index < count) {
                star.classList.add(className);
            }
        });
    }
    
    function updateRating(rating) {
        ratingValue.value = rating;
        ratingText.textContent = ratingTexts[rating];
        ratingInput.classList.add('has-rating');
        highlightStars(rating, 'active');
    }
});
</script>
@endsection