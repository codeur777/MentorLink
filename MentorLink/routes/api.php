<?php

use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AvailabilityController;
use App\Http\Controllers\Api\MentorController;
use Illuminate\Support\Facades\Route;

// -----------------------------------------------------------------------
// Routes d'authentification — gérées par Laravel Breeze (stack API)
// Les contrôleurs sont dans App\Http\Controllers\Auth\
// -----------------------------------------------------------------------
require __DIR__ . '/auth.php';

// -----------------------------------------------------------------------
// Routes publiques
// -----------------------------------------------------------------------
Route::get('/mentors',                        [MentorController::class, 'index']);
Route::get('/mentors/{id}',                   [MentorController::class, 'show']);

// -----------------------------------------------------------------------
// Routes authentifiées (Sanctum)
// -----------------------------------------------------------------------
Route::middleware('auth:sanctum')->group(function () {

    // Profil utilisateur connecté
    Route::get('/me',       [AuthController::class, 'me']);
    Route::post('/profile', [AuthController::class, 'updateProfile']);

    // Profil mentor
    Route::post('/mentor/profile', [MentorController::class, 'upsertProfile']);

    // Disponibilités
    Route::get('/mentors/{mentorId}/availabilities', [AvailabilityController::class, 'index']);
    Route::post('/availabilities',                   [AvailabilityController::class, 'store']);
    Route::put('/availabilities/{availability}',     [AvailabilityController::class, 'update']);
    Route::delete('/availabilities/{availability}',  [AvailabilityController::class, 'destroy']);

    // Admin
    Route::middleware('can:admin')->prefix('admin')->group(function () {
        Route::get('/stats',                 [AdminController::class, 'stats']);
        Route::get('/pending-mentors',       [AdminController::class, 'pendingMentors']);
        Route::put('/mentors/{id}/validate', [MentorController::class, 'validateProfile']);
    });
});
