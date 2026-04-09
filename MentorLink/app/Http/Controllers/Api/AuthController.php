<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/**
 * @OA\Info(title="MentorLink API", version="1.0.0", description="API RESTful MentorLink — Auth via Bearer token (Sanctum)")
 * @OA\SecurityScheme(securityScheme="bearerAuth", type="http", scheme="bearer", bearerFormat="Token")
 * @OA\Server(url=L5_SWAGGER_CONST_HOST, description="Serveur local")
 */
class AuthController extends Controller
{
    /**
     * @OA\Post(path="/api/register", summary="Inscription", tags={"Auth"},
     *   @OA\RequestBody(required=true, @OA\JsonContent(required={"name","email","password","password_confirmation","role"},
     *     @OA\Property(property="name", type="string"),
     *     @OA\Property(property="email", type="string"),
     *     @OA\Property(property="password", type="string"),
     *     @OA\Property(property="password_confirmation", type="string"),
     *     @OA\Property(property="role", type="string", enum={"mentor","mentee"})
     *   )),
     *   @OA\Response(response=201, description="Utilisateur créé"),
     *   @OA\Response(response=422, description="Erreur de validation")
     * )
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $user  = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
        ]);
        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json(['user' => $user, 'token' => $token], 201);
    }

    /**
     * @OA\Post(path="/api/login", summary="Connexion", tags={"Auth"},
     *   @OA\RequestBody(required=true, @OA\JsonContent(required={"email","password"},
     *     @OA\Property(property="email", type="string"),
     *     @OA\Property(property="password", type="string")
     *   )),
     *   @OA\Response(response=200, description="Token retourné"),
     *   @OA\Response(response=401, description="Identifiants invalides")
     * )
     */
    public function login(Request $request): JsonResponse
    {
        $request->validate(['email' => 'required|email', 'password' => 'required|string']);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Identifiants invalides.'], 401);
        }

        $token = $request->user()->createToken('api-token')->plainTextToken;

        return response()->json(['user' => $request->user(), 'token' => $token]);
    }

    /**
     * @OA\Post(path="/api/logout", summary="Déconnexion", tags={"Auth"}, security={{"bearerAuth":{}}},
     *   @OA\Response(response=200, description="Déconnecté")
     * )
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Déconnecté.']);
    }

    /**
     * @OA\Get(path="/api/me", summary="Utilisateur connecté", tags={"Auth"}, security={{"bearerAuth":{}}},
     *   @OA\Response(response=200, description="Profil utilisateur")
     * )
     */
    public function me(Request $request): JsonResponse
    {
        return response()->json($request->user());
    }
}
