<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\MentorProfile;
use App\Models\Session;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $user  = auth()->user();
        $stats = [];

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->isMentor()) {
            $stats = [
                'profile_status'       => $user->mentorProfile?->is_validated ? 'Valide' : 'En attente',
                'total_availabilities' => $user->availabilities()->count(),
                'pending_sessions'     => Session::where('mentor_id', $user->id)->where('status', 'pending')->count(),
                'confirmed_sessions'   => Session::where('mentor_id', $user->id)->where('status', 'confirmed')->count(),
                'completed_sessions'   => Session::where('mentor_id', $user->id)->where('status', 'completed')->count(),
            ];
        } elseif ($user->isMentee()) {
            $stats = [
                'pending_sessions'   => Session::where('mentee_id', $user->id)->where('status', 'pending')->count(),
                'confirmed_sessions' => Session::where('mentee_id', $user->id)->where('status', 'confirmed')->count(),
                'completed_sessions' => Session::where('mentee_id', $user->id)->where('status', 'completed')->count(),
            ];
        }

        return view('dashboard', compact('user', 'stats'));
    }
}
