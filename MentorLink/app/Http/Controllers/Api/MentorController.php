<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMentorProfileRequest;
use App\Models\MentorProfile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MentorController extends Controller
{
    /**
     * @OA\Get(path="/api/mentors", summary="Liste des mentors validés", tags={"Mentors"},
     *   @OA\Parameter(name="domain", in="query", required=false, @OA\Schema(type="string")),
     *   @OA\Response(response=200, description="Liste paginée")
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $query = MentorProfile::with('user')->where('is_validated', true);

        if ($request->has('domain')) {
            $query->whereJsonContains('domains', $request->domain);
        }

        $mentors = $query->latest()->paginate(15);
        $mentors->getCollection()->transform(function ($profile) {
            $profile->average_rating = $profile->average_rating;
            return $profile;
        });

        return response()->json($mentors);
    }

    /**
     * @OA\Get(path="/api/mentors/{id}", summary="Détail d'un mentor", tags={"Mentors"},
     *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *   @OA\Response(response=200, description="Profil mentor"),
     *   @OA\Response(response=404, description="Introuvable")
     * )
     */
    public function show(int $id): JsonResponse
    {
        $profile = MentorProfile::with('user')
            ->where('user_id', $id)
            ->where('is_validated', true)
            ->firstOrFail();

        $profile->average_rating = $profile->average_rating;

        return response()->json($profile);
    }

    /**
     * @OA\Post(path="/api/mentor/profile", summary="Créer/mettre à jour son profil mentor", tags={"Mentors"}, security={{"bearerAuth":{}}},
     *   @OA\RequestBody(required=true, @OA\JsonContent(required={"domains"},
     *     @OA\Property(property="domains", type="array", @OA\Items(type="string")),
     *     @OA\Property(property="hourly_rate", type="number"),
     *     @OA\Property(property="bio", type="string")
     *   )),
     *   @OA\Response(response=200, description="Profil mis à jour"),
     *   @OA\Response(response=403, description="Non autorisé")
     * )
     */
    public function upsertProfile(StoreMentorProfileRequest $request): JsonResponse
    {
        $user = $request->user();

        if ($request->has('bio')) {
            $user->update(['bio' => $request->bio]);
        }

        $profile = MentorProfile::updateOrCreate(
            ['user_id' => $user->id],
            $request->only(['domains', 'hourly_rate'])
        );

        return response()->json($profile);
    }

    /**
     * @OA\Put(path="/api/admin/mentors/{id}/validate", summary="[Admin] Valider un profil mentor", tags={"Mentors","Admin"}, security={{"bearerAuth":{}}},
     *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *   @OA\Response(response=200, description="Profil validé"),
     *   @OA\Response(response=403, description="Réservé à l'admin")
     * )
     */
    public function validateProfile(int $id): JsonResponse
    {
        $profile = MentorProfile::where('user_id', $id)->firstOrFail();
        $this->authorize('validate', $profile);
        $profile->update(['is_validated' => true]);

        return response()->json(['message' => 'Profil validé.', 'profile' => $profile]);
    }
}
