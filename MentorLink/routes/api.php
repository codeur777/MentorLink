<?php

use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AvailabilityController;
use App\Http\Controllers\Api\MentorController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\SessionController;
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
Route::get('/mentors/{mentorId}/reviews',     [ReviewController::class, 'indexForMentor']);

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

    // Sessions
    Route::get('/sessions',                    [SessionController::class, 'index']);
    Route::post('/sessions',                   [SessionController::class, 'store']);
    Route::put('/sessions/{session}/confirm',  [SessionController::class, 'confirm']);
    Route::put('/sessions/{session}/refuse',   [SessionController::class, 'refuse']);
    Route::put('/sessions/{session}/cancel',   [SessionController::class, 'cancel']);
    Route::put('/sessions/{session}/complete', [SessionController::class, 'complete']);

    // Reviews
    Route::post('/sessions/{sessionId}/reviews', [ReviewController::class, 'store']);

    // Signalements
    Route::post('/reports', [ReportController::class, 'store']);

    // Admin
    Route::middleware('can:admin')->prefix('admin')->group(function () {
        Route::get('/stats',                 [AdminController::class, 'stats']);
        Route::get('/pending-mentors',       [AdminController::class, 'pendingMentors']);
        Route::put('/mentors/{id}/validate', [MentorController::class, 'validateProfile']);
        Route::get('/reports',               [ReportController::class, 'index']);
        Route::put('/reports/{report}',      [ReportController::class, 'update']);
    });
});
