<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAvailabilityRequest;
use App\Http\Requests\UpdateAvailabilityRequest;
use App\Models\Availability;
use App\Services\AvailabilityService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AvailabilityController extends Controller
{
    public function __construct(private readonly AvailabilityService $availabilityService) {}

    /**
     * @OA\Get(path="/api/mentors/{mentorId}/availabilities", summary="Créneaux libres d'un mentor", tags={"Disponibilités"}, security={{"bearerAuth":{}}},
     *   @OA\Parameter(name="mentorId", in="path", required=true, @OA\Schema(type="integer")),
     *   @OA\Parameter(name="week_start", in="query", required=false, @OA\Schema(type="string", example="2025-06-02")),
     *   @OA\Response(response=200, description="Créneaux disponibles")
     * )
     */
    public function index(int $mentorId, Request $request): JsonResponse
    {
        $weekStart = $request->has('week_start')
            ? Carbon::parse($request->week_start)
            : Carbon::now()->startOfWeek();

        $slots = $this->availabilityService->getAvailableSlots($mentorId, $weekStart);

        return response()->json(['slots' => $slots]);
    }

    /**
     * @OA\Post(path="/api/availabilities", summary="Ajouter un créneau", tags={"Disponibilités"}, security={{"bearerAuth":{}}},
     *   @OA\RequestBody(required=true, @OA\JsonContent(required={"day_of_week","start_time","end_time"},
     *     @OA\Property(property="day_of_week", type="integer", example=1),
     *     @OA\Property(property="start_time", type="string", example="09:00"),
     *     @OA\Property(property="end_time", type="string", example="11:00")
     *   )),
     *   @OA\Response(response=201, description="Créneau créé"),
     *   @OA\Response(response=403, description="Réservé aux mentors")
     * )
     */
    public function store(StoreAvailabilityRequest $request): JsonResponse
    {
        $availability = Availability::create([
            'mentor_id'   => $request->user()->id,
            'day_of_week' => $request->day_of_week,
            'start_time'  => $request->start_time,
            'end_time'    => $request->end_time,
        ]);

        return response()->json($availability, 201);
    }

    /**
     * @OA\Delete(path="/api/availabilities/{id}", summary="Supprimer un créneau", tags={"Disponibilités"}, security={{"bearerAuth":{}}},
     *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *   @OA\Response(response=204, description="Supprimé")
     * )
     */
    public function destroy(Availability $availability): JsonResponse
    {
        if ($availability->mentor_id !== request()->user()->id) {
            return response()->json(['message' => 'Non autorisé.'], 403);
        }

        $availability->delete();

        return response()->json(null, 204);
    }

    /**
     * @OA\Put(path="/api/availabilities/{id}", summary="Modifier un créneau", tags={"Disponibilités"}, security={{"bearerAuth":{}}},
     *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *   @OA\RequestBody(required=false, @OA\JsonContent(
     *     @OA\Property(property="day_of_week", type="integer", example=2),
     *     @OA\Property(property="start_time", type="string", example="10:00"),
     *     @OA\Property(property="end_time", type="string", example="12:00")
     *   )),
     *   @OA\Response(response=200, description="Créneau mis à jour"),
     *   @OA\Response(response=403, description="Non autorisé")
     * )
     */
    public function update(UpdateAvailabilityRequest $request, Availability $availability): JsonResponse
    {
        if ($availability->mentor_id !== $request->user()->id) {
            return response()->json(['message' => 'Non autorisé.'], 403);
        }

        $availability->update($request->only(['day_of_week', 'start_time', 'end_time']));

        return response()->json($availability->fresh());
    }
}
