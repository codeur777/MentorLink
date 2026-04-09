<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReviewRequest;
use App\Models\MentorSession;
use App\Models\Review;
use Illuminate\Http\JsonResponse;

class ReviewController extends Controller
{
    /**
     * @OA\Post(path="/api/sessions/{sessionId}/reviews", summary="Déposer une évaluation", tags={"Évaluations"}, security={{"bearerAuth":{}}},
     *   @OA\Parameter(name="sessionId", in="path", required=true, @OA\Schema(type="integer")),
     *   @OA\RequestBody(required=true, @OA\JsonContent(required={"rating"},
     *     @OA\Property(property="rating", type="integer", minimum=1, maximum=5),
     *     @OA\Property(property="comment", type="string")
     *   )),
     *   @OA\Response(response=201, description="Évaluation enregistrée"),
     *   @OA\Response(response=403, description="Non autorisé"),
     *   @OA\Response(response=409, description="Évaluation déjà déposée")
     * )
     */
    public function store(StoreReviewRequest $request, int $sessionId): JsonResponse
    {
        $session = MentorSession::findOrFail($sessionId);
        $this->authorize('create', [Review::class, $session]);

        if ($session->review !== null) {
            return response()->json(['message' => 'Une évaluation existe déjà pour cette session.'], 409);
        }

        $review = Review::create([
            'session_id'  => $session->id,
            'reviewer_id' => $request->user()->id,
            'rating'      => $request->rating,
            'comment'     => $request->comment,
        ]);

        return response()->json($review->load('session'), 201);
    }

    /**
     * @OA\Get(path="/api/mentors/{mentorId}/reviews", summary="Évaluations d'un mentor", tags={"Évaluations"},
     *   @OA\Parameter(name="mentorId", in="path", required=true, @OA\Schema(type="integer")),
     *   @OA\Response(response=200, description="Liste des reviews")
     * )
     */
    public function indexForMentor(int $mentorId): JsonResponse
    {
        $reviews = Review::whereHas('session', fn($q) => $q->where('mentor_id', $mentorId))
            ->with(['reviewer', 'session'])
            ->latest()
            ->paginate(10);

        return response()->json($reviews);
    }
}
