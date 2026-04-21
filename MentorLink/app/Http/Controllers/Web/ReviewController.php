<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\MentorSession;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Afficher le formulaire de review
     */
    public function create(MentorSession $session)
    {
        // Vérifier que l'utilisateur peut laisser un avis
        if ($session->mentee_id !== Auth::id()) {
            abort(403, 'Seul le mentee peut laisser un avis.');
        }

        // Vérifier que la session est terminée
        if ($session->status !== 'terminee' || !$session->completed_at) {
            return back()->withErrors(['session' => 'Vous ne pouvez laisser un avis que pour une session terminée.']);
        }

        // Vérifier qu'il n'y a pas déjà un avis
        if ($session->is_reviewed) {
            return back()->withErrors(['session' => 'Vous avez déjà laissé un avis pour cette session.']);
        }

        return view('reviews.create', compact('session'));
    }

    /**
     * Enregistrer un avis
     */
    public function store(Request $request, MentorSession $session)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10|max:500',
        ]);

        // Vérifications de sécurité
        if ($session->mentee_id !== Auth::id() || $session->is_reviewed) {
            abort(403);
        }

        // Créer l'avis
        $review = Review::create([
            'session_id' => $session->id,
            'reviewer_id' => Auth::id(),
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        // Marquer la session comme évaluée
        $session->update(['is_reviewed' => true]);

        // Mettre à jour la note moyenne du mentor
        $this->updateMentorRating($session->mentor);

        // Notifier le mentor qu'il a reçu un avis
        $session->mentor->notify(new \App\Notifications\ReviewReceived($review, $session));

        return redirect()->route('sessions.show', $session)
            ->with('success', 'Merci pour votre avis ! Il aidera d\'autres étudiants à choisir ce mentor.');
    }

    /**
     * Mettre à jour la note moyenne d'un mentor
     */
    private function updateMentorRating(User $mentor)
    {
        $reviews = Review::whereHas('session', function ($query) use ($mentor) {
            $query->where('mentor_id', $mentor->id);
        })->get();

        // Récupérer les pénalités
        $penalties = \App\Models\MentorPenalty::where('mentor_id', $mentor->id)->get();
        $totalPenaltyAmount = $penalties->sum('penalty_amount');

        $totalReviews = $reviews->count();
        $averageRating = $totalReviews > 0 ? $reviews->avg('rating') : 0;

        // Appliquer les pénalités
        $finalRating = max(0, $averageRating - $totalPenaltyAmount);

        $mentor->update([
            'total_reviews' => $totalReviews,
            'average_rating' => round($finalRating, 2)
        ]);
    }

    /**
     * Appliquer une pénalité pour annulation tardive
     */
    public function applyLateCancellationPenalty(User $mentor)
    {
        // Créer une pénalité dans la table dédiée
        \App\Models\MentorPenalty::create([
            'mentor_id' => $mentor->id,
            'penalty_amount' => 0.5,
            'reason' => 'late_cancellation',
            'description' => 'Pénalité automatique pour annulation tardive (moins de 15 minutes avant la session)'
        ]);

        // Recalculer la note avec pénalité
        $this->updateMentorRatingWithPenalty($mentor);
    }

    /**
     * Mettre à jour la note avec pénalités
     */
    private function updateMentorRatingWithPenalty(User $mentor)
    {
        // Récupérer tous les avis normaux du mentor
        $regularReviews = Review::whereHas('session', function ($query) use ($mentor) {
            $query->where('mentor_id', $mentor->id);
        })->get();

        // Récupérer toutes les pénalités du mentor
        $penalties = \App\Models\MentorPenalty::where('mentor_id', $mentor->id)->get();
        $totalPenaltyAmount = $penalties->sum('penalty_amount');

        $totalReviews = $regularReviews->count();
        $averageRating = $totalReviews > 0 ? $regularReviews->avg('rating') : 0;

        // Appliquer les pénalités
        $finalRating = max(0, $averageRating - $totalPenaltyAmount);

        $mentor->update([
            'total_reviews' => $totalReviews,
            'average_rating' => round($finalRating, 2)
        ]);
    }
}