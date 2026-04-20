<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReviewRequest;
use App\Models\Review;
use App\Models\Session;

class ReviewController extends Controller
{
    /**
     * Show the review form for a completed session.
     */
    public function create(Session $session)
    {
        $this->authorize('create', [Review::class, $session]);

        return view('reviews.create', compact('session'));
    }

    /**
     * Store a review for a completed session.
     */
    public function store(StoreReviewRequest $request, Session $session)
    {
        $this->authorize('create', [Review::class, $session]);

        Review::create([
            'session_id' => $session->id,
            'mentee_id'  => auth()->id(),
            'mentor_id'  => $session->mentor_id,
            'rating'     => $request->rating,
            'comment'    => $request->comment,
        ]);

        return redirect()->route('sessions.index')
            ->with('success', 'Avis soumis avec succès. Merci !');
    }
}
