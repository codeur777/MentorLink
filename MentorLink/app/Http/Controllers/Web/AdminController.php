<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\MentorProfile;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Dashboard admin
     */
    public function dashboard()
    {
        $this->authorize('admin');

        $stats = [
            'total_users' => User::count(),
            'total_mentors' => User::where('role', 'mentor')->count(),
            'total_mentees' => User::where('role', 'mentee')->count(),
            'pending_mentors' => MentorProfile::where('is_validated', false)->count(),
            'validated_mentors' => MentorProfile::where('is_validated', true)->count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }

    /**
     * Liste des mentors en attente de validation
     */
    public function pendingMentors()
    {
        $this->authorize('admin');

        $profiles = MentorProfile::with('user')
            ->where('is_validated', false)
            ->latest()
            ->paginate(15);

        return view('admin.pending-mentors', compact('profiles'));
    }

    /**
     * Valider un profil mentor
     */
    public function validateProfile($id)
    {
        $this->authorize('admin');

        $profile = MentorProfile::where('user_id', $id)->firstOrFail();
        $profile->update(['is_validated' => true]);

        return back()->with('success', 'Profil mentor validé avec succès.');
    }

    /**
     * Statistiques globales
     */
    public function stats()
    {
        $this->authorize('admin');

        $stats = [
            'total_users' => User::count(),
            'total_mentors' => User::where('role', 'mentor')->count(),
            'total_mentees' => User::where('role', 'mentee')->count(),
            'pending_validations' => MentorProfile::where('is_validated', false)->count(),
            'validated_mentors' => MentorProfile::where('is_validated', true)->count(),
        ];

        return view('admin.stats', compact('stats'));
    }
}