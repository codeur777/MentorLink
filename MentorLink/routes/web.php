<?php

use App\Http\Controllers\Web\AdminController;
use App\Http\Controllers\Web\AvailabilityController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\MentorController;
use App\Http\Controllers\Web\ReviewController;
use App\Http\Controllers\Web\SessionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return view('welcome');
});

Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ------------------------------------------------------------------ Mentors (public within auth)
    Route::get('/mentors',      [MentorController::class, 'index'])->name('mentors.index');
    Route::get('/mentors/{id}', [MentorController::class, 'show'])->name('mentors.show');

    // ------------------------------------------------------------------ Mentor profile (mentor only)
    Route::get('/mentor/profile',  [MentorController::class, 'profile'])->name('mentor.profile');
    Route::post('/mentor/profile', [MentorController::class, 'updateProfile'])->name('mentor.profile.update');

    // ------------------------------------------------------------------ Availabilities
    Route::get('/mentors/{mentorId}/availabilities', [AvailabilityController::class, 'index'])->name('availabilities.index');
    Route::get('/availabilities/create',             [AvailabilityController::class, 'create'])->name('availabilities.create');
    Route::post('/availabilities',                   [AvailabilityController::class, 'store'])->name('availabilities.store');
    Route::delete('/availabilities/{availability}',  [AvailabilityController::class, 'destroy'])->name('availabilities.destroy');

    // ------------------------------------------------------------------ Sessions
    Route::get('/sessions',                       [SessionController::class, 'index'])->name('sessions.index');
    Route::get('/sessions/book',                  [SessionController::class, 'create'])->name('sessions.create');
    Route::post('/sessions',                      [SessionController::class, 'store'])->name('sessions.store');
    Route::patch('/sessions/{session}/confirm',   [SessionController::class, 'confirm'])->name('sessions.confirm');
    Route::patch('/sessions/{session}/cancel',    [SessionController::class, 'cancel'])->name('sessions.cancel');
    Route::patch('/sessions/{session}/complete',  [SessionController::class, 'complete'])->name('sessions.complete');

    // ------------------------------------------------------------------ Reviews
    Route::get('/sessions/{session}/review',  [ReviewController::class, 'create'])->name('reviews.create');
    Route::post('/sessions/{session}/review', [ReviewController::class, 'store'])->name('reviews.store');

    // ------------------------------------------------------------------ Admin
    Route::middleware('admin')->prefix('admin')->group(function () {
        Route::get('/dashboard',              [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/stats',                  [AdminController::class, 'stats'])->name('admin.stats');
        Route::get('/pending-mentors',        [AdminController::class, 'pendingMentors'])->name('admin.pending-mentors');
        Route::put('/mentors/{id}/validate',  [AdminController::class, 'validateProfile'])->name('admin.mentors.validate');
    });
});

require __DIR__ . '/auth.php';
