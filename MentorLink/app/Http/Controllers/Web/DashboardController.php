<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\MentorProfile;

class DashboardController extends Controller
{
    /**
     * Dashboard principal selon le rôle
     */
    public function index()
    {
        $user = auth()->user();
        
        $stats = [];
        
        if ($user->isAdmin()) {
            $stats = [
                'total_users' => User::count(),
                'total_mentors' => User::where('role', 'mentor')->count(),
                'total_mentees' => User::where('role', 'mentee')->count(),
                'pending_mentors' => MentorProfile::where('is_validated', false)->count(),
                'validated_mentors' => MentorProfile::where('is_validated', true)->count(),
            ];
        } elseif ($user->isMentor()) {
            $stats = [
                'profile_status' => $user->mentorProfile?->is_validated ? 'Validé' : 'En attente',
                'total_availabilities' => $user->availabilities()->count(),
            ];
        }

        return view('dashboard', compact('user', 'stats'));
    }
}