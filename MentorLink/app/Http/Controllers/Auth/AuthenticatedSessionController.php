<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    /**
     * @OA\Post(path="/api/login", summary="Connexion (Breeze)", tags={"Auth"},
     *   @OA\RequestBody(required=true, @OA\JsonContent(
     *     required={"email","password"},
     *     @OA\Property(property="email", type="string"),
     *     @OA\Property(property="password", type="string")
     *   )),
     *   @OA\Response(response=200, description="Token Sanctum retourné"),
     *   @OA\Response(response=422, description="Identifiants invalides")
     * )
     */
    public function store(LoginRequest $request): JsonResponse
    {
        $request->authenticate();

        $token = $request->user()->createToken('api-token')->plainTextToken;

        return response()->json(['user' => $request->user(), 'token' => $token]);
    }

    /**
     * @OA\Post(path="/api/logout", summary="Déconnexion (Breeze)", tags={"Auth"}, security={{"bearerAuth":{}}},
     *   @OA\Response(response=200, description="Déconnecté")
     * )
     */
    public function destroy(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Déconnecté.']);
    }
}
