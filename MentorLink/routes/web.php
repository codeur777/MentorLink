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

// Newsletter (accessible à tous)
Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');
Route::get('/newsletter/unsubscribe', [NewsletterController::class, 'unsubscribe'])->name('newsletter.unsubscribe');

// Routes authentifiées (session Laravel Breeze)
Route::middleware(['auth', 'verified'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Mentors
    Route::get('/mentors', [MentorController::class, 'index'])->name('mentors.index');
    Route::get('/mentors/{id}', [MentorController::class, 'show'])->name('mentors.show');
    
    // Sessions
    Route::get('/sessions', [App\Http\Controllers\Web\SessionController::class, 'index'])->name('sessions.index');
    Route::get('/sessions/create/{mentorId}', [App\Http\Controllers\Web\SessionController::class, 'create'])->name('sessions.create');
    Route::post('/sessions', [App\Http\Controllers\Web\SessionController::class, 'store'])->name('sessions.store');
    Route::get('/sessions/{session}', [App\Http\Controllers\Web\SessionController::class, 'show'])->name('sessions.show');
    Route::patch('/sessions/{session}/confirm', [App\Http\Controllers\Web\SessionController::class, 'confirm'])->name('sessions.confirm');
    Route::patch('/sessions/{session}/cancel', [App\Http\Controllers\Web\SessionController::class, 'cancel'])->name('sessions.cancel');
    Route::patch('/sessions/{session}/complete', [App\Http\Controllers\Web\SessionController::class, 'complete'])->name('sessions.complete');
    
    // Reviews
    Route::get('/sessions/{session}/review', [App\Http\Controllers\Web\ReviewController::class, 'create'])->name('reviews.create');
    Route::post('/sessions/{session}/review', [App\Http\Controllers\Web\ReviewController::class, 'store'])->name('reviews.store');
    
    // Notifications
    Route::get('/notifications', [App\Http\Controllers\Web\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [App\Http\Controllers\Web\NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/mark-all-read', [App\Http\Controllers\Web\NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    
    // Profil mentor (pour les mentors connectés)
    Route::get('/mentor/profile', [MentorController::class, 'profile'])->name('mentor.profile');
    Route::post('/mentor/profile', [MentorController::class, 'updateProfile'])->name('mentor.profile.update');
    
    // Disponibilités
    Route::get('/mentors/{mentorId}/availabilities', [AvailabilityController::class, 'index'])->name('availabilities.index');
    Route::get('/availabilities/create', [AvailabilityController::class, 'create'])->name('availabilities.create');
    Route::post('/availabilities', [AvailabilityController::class, 'store'])->name('availabilities.store');
    Route::delete('/availabilities/{availability}', [AvailabilityController::class, 'destroy'])->name('availabilities.destroy');
    
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
