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
        
        if ($user->role === 'admin') {
            $stats = [
                'total_users' => User::count(),
                'total_mentors' => User::where('role', 'mentor')->count(),
                'total_mentees' => User::where('role', 'mentee')->count(),
                'pending_mentors' => MentorProfile::where('is_validated', false)->count(),
                'validated_mentors' => MentorProfile::where('is_validated', true)->count(),
            ];
        } elseif ($user->role === 'mentor') {
            $stats = [
                'profile_status' => $user->mentorProfile?->is_validated ? 'Validé' : 'En attente',
                'total_availabilities' => $user->availabilities()->count(),
            ];
        } elseif ($user->role === 'mentee') {
            $stats = [
                'available_mentors' => User::where('role', 'mentor')
                    ->whereHas('mentorProfile', function($q) {
                        $q->where('is_validated', true);
                    })->count(),
            ];
        }

        return view('dashboard', compact('user', 'stats'));
    }
}