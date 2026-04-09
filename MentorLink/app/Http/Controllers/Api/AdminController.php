<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MentorProfile;
use App\Models\MentorSession;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class AdminController extends Controller
{
    /**
     * @OA\Get(path="/api/admin/stats", summary="[Admin] Statistiques globales", tags={"Admin"}, security={{"bearerAuth":{}}},
     *   @OA\Response(response=200, description="Statistiques"),
     *   @OA\Response(response=403, description="Réservé à l'admin")
     * )
     */
    public function stats(): JsonResponse
    {
        $topMentors = MentorProfile::with('user')
            ->where('is_validated', true)
            ->get()
            ->map(fn($p) => [
                'mentor'         => $p->user->name,
                'domains'        => $p->domains,
                'average_rating' => $p->average_rating,
            ])
            ->sortByDesc('average_rating')
            ->take(10)
            ->values();

        return response()->json([
            'total_users'         => User::count(),
            'total_mentors'       => User::where('role', 'mentor')->count(),
            'total_mentees'       => User::where('role', 'mentee')->count(),
            'pending_validations' => MentorProfile::where('is_validated', false)->count(),
            'total_sessions'      => MentorSession::count(),
            'sessions_by_status'  => MentorSession::selectRaw('status, count(*) as total')
                                        ->groupBy('status')->pluck('total', 'status'),
            'top_mentors'         => $topMentors,
        ]);
    }

    /**
     * @OA\Get(path="/api/admin/pending-mentors", summary="[Admin] Profils en attente de validation", tags={"Admin"}, security={{"bearerAuth":{}}},
     *   @OA\Response(response=200, description="Profils en attente")
     * )
     */
    public function pendingMentors(): JsonResponse
    {
        $profiles = MentorProfile::with('user')
            ->where('is_validated', false)
            ->latest()
            ->paginate(15);

        return response()->json($profiles);
    }
}
