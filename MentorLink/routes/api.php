<?php

use App\Models\Mentorship;
use App\Models\MentorshipSession;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/platform-overview', function () {
    $databaseName = config('database.connections.mysql.database');
    $connected = false;
    $metrics = [
        'public_pages' => 4,
        'api_endpoints' => 2,
        'user_fields' => 4,
        'mentors' => 0,
        'active_mentorships' => 0,
        'upcoming_sessions' => 0,
    ];

    try {
        $connected = Schema::hasTable('users')
            && Schema::hasTable('mentor_profiles')
            && Schema::hasTable('mentorships')
            && Schema::hasTable('mentorship_goals')
            && Schema::hasTable('mentorship_sessions');

        if ($connected) {
            $databaseName = DB::connection()->getDatabaseName();
            $metrics['mentors'] = User::query()->where('role', 'mentor')->count();
            $metrics['active_mentorships'] = Mentorship::query()->where('status', 'active')->count();
            $metrics['upcoming_sessions'] = MentorshipSession::query()->where('starts_at', '>=', now())->count();
        }
    } catch (\Throwable) {
        $connected = false;
    }

    return response()->json([
        'application' => 'MentorLink',
        'generated_at' => now()->toIso8601String(),
        'status' => $connected ? 'ready' : 'waiting',
        'database' => $databaseName,
        'stack' => [
            'Laravel 12',
            'Blade',
            'Vite',
            'MySQL',
            'Sanctum',
            'Eloquent',
        ],
        'metrics' => $metrics,
        'capabilities' => [
            'Landing page Blade exposee sur /',
            'Pages produit branchees sur des tables MySQL',
            'Schema users, mentor_profiles, mentorships, mentorship_goals, mentorship_sessions',
            'Endpoint public pour lire le statut de la plateforme',
            'Endpoint /api/user protege par auth:sanctum',
        ],
        'secure_endpoint' => url('/api/user'),
    ]);
})->name('api.platform-overview');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
