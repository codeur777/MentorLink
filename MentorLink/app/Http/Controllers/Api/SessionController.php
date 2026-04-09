<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSessionRequest;
use App\Models\MentorSession;
use App\Services\SessionService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SessionController extends Controller
{
    public function __construct(private readonly SessionService $sessionService) {}

    /**
     * @OA\Get(path="/api/sessions", summary="Mes sessions", tags={"Sessions"}, security={{"bearerAuth":{}}},
     *   @OA\Response(response=200, description="Liste des sessions")
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        $sessions = $user->isMentor()
            ? MentorSession::with(['mentee', 'review'])->where('mentor_id', $user->id)->latest()->paginate(15)
            : MentorSession::with(['mentor.mentorProfile', 'review'])->where('mentee_id', $user->id)->latest()->paginate(15);

        return response()->json($sessions);
    }

    /**
     * @OA\Post(path="/api/sessions", summary="Réserver une session", tags={"Sessions"}, security={{"bearerAuth":{}}},
     *   @OA\RequestBody(required=true, @OA\JsonContent(required={"mentor_id","scheduled_at","duration_min"},
     *     @OA\Property(property="mentor_id", type="integer"),
     *     @OA\Property(property="scheduled_at", type="string", example="2025-06-10 14:00:00"),
     *     @OA\Property(property="duration_min", type="integer", example=60)
     *   )),
     *   @OA\Response(response=201, description="Session créée"),
     *   @OA\Response(response=409, description="Conflit de créneau"),
     *   @OA\Response(response=403, description="Réservé aux mentorés")
     * )
     */
    public function store(StoreSessionRequest $request): JsonResponse
    {
        $scheduledAt = Carbon::parse($request->scheduled_at);

        if ($this->sessionService->hasConflict($request->mentor_id, $scheduledAt, $request->duration_min)) {
            return response()->json(['message' => 'Le mentor a déjà une session confirmée sur ce créneau.'], 409);
        }

        $session = MentorSession::create([
            'mentor_id'    => $request->mentor_id,
            'mentee_id'    => $request->user()->id,
            'scheduled_at' => $scheduledAt,
            'duration_min' => $request->duration_min,
            'status'       => 'en_attente',
        ]);

        return response()->json($session->load(['mentor', 'mentee']), 201);
    }

    /**
     * @OA\Put(path="/api/sessions/{id}/confirm", summary="[Mentor] Confirmer une session", tags={"Sessions"}, security={{"bearerAuth":{}}},
     *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *   @OA\Response(response=200, description="Session confirmée")
     * )
     */
    public function confirm(MentorSession $session): JsonResponse
    {
        $this->authorize('confirm', $session);
        $session->update(['status' => 'confirmee']);

        return response()->json(['message' => 'Session confirmée.', 'session' => $session]);
    }

    /**
     * @OA\Put(path="/api/sessions/{id}/cancel", summary="Annuler une session", tags={"Sessions"}, security={{"bearerAuth":{}}},
     *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *   @OA\Response(response=200, description="Session annulée")
     * )
     */
    public function cancel(MentorSession $session): JsonResponse
    {
        $this->authorize('cancel', $session);
        $session->update(['status' => 'annulee']);

        return response()->json(['message' => 'Session annulée.', 'session' => $session]);
    }

    /**
     * @OA\Put(path="/api/sessions/{id}/complete", summary="[Mentor] Terminer une session", tags={"Sessions"}, security={{"bearerAuth":{}}},
     *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *   @OA\Response(response=200, description="Session terminée")
     * )
     */
    public function complete(MentorSession $session): JsonResponse
    {
        $this->authorize('complete', $session);
        $session->update(['status' => 'terminee']);

        return response()->json(['message' => 'Session terminée.', 'session' => $session]);
    }

    /**
     * @OA\Put(path="/api/sessions/{id}/refuse", summary="[Mentor] Refuser une demande de session", tags={"Sessions"}, security={{"bearerAuth":{}}},
     *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *   @OA\Response(response=200, description="Session refusée"),
     *   @OA\Response(response=403, description="Réservé au mentor / session déjà traitée")
     * )
     */
    public function refuse(MentorSession $session): JsonResponse
    {
        $this->authorize('refuse', $session);
        $session->update(['status' => 'annulee']);

        return response()->json(['message' => 'Demande de session refusée.', 'session' => $session]);
    }
}
