<?php

use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\MentorController;
use App\Http\Controllers\Web\AdminController;
use App\Http\Controllers\Web\AvailabilityController;
use App\Http\Controllers\NewsletterController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return view('welcome');
});

// Routes authentifiées (session Laravel Breeze)
Route::middleware(['auth', 'verified'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Mentors
    Route::get('/mentors', [MentorController::class, 'index'])->name('mentors.index');
    Route::get('/mentors/{id}', [MentorController::class, 'show'])->name('mentors.show');
    
    // Profil mentor (pour les mentors connectés)
    Route::get('/mentor/profile', [MentorController::class, 'profile'])->name('mentor.profile');
    Route::post('/mentor/profile', [MentorController::class, 'updateProfile'])->name('mentor.profile.update');
    
    // Disponibilités
    Route::get('/mentors/{mentorId}/availabilities', [AvailabilityController::class, 'index'])->name('availabilities.index');
    Route::get('/availabilities/create', [AvailabilityController::class, 'create'])->name('availabilities.create');
    Route::post('/availabilities', [AvailabilityController::class, 'store'])->name('availabilities.store');
    Route::delete('/availabilities/{availability}', [AvailabilityController::class, 'destroy'])->name('availabilities.destroy');
    
    // Newsletter
    Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');
    Route::get('/newsletter/unsubscribe', [NewsletterController::class, 'unsubscribe'])->name('newsletter.unsubscribe');
    
    // Admin routes
    Route::prefix('admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/stats', [AdminController::class, 'stats'])->name('admin.stats');
        Route::get('/newsletters', [AdminController::class, 'newsletters'])->name('admin.newsletters');
        Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
        Route::get('/pending-mentors', [AdminController::class, 'pendingMentors'])->name('admin.pending-mentors');
        Route::put('/mentors/{id}/validate', [AdminController::class, 'validateProfile'])->name('admin.mentors.validate');
        Route::delete('/mentors/{id}/reject', [AdminController::class, 'rejectProfile'])->name('admin.mentors.reject');
    });
});

require __DIR__.'/auth.php';
