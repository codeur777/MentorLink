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
        
        // Redirection automatique pour les admins
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        
        $stats = [];
        
        if ($user->role === 'mentor') {
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