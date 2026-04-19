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
        // Vérifier que l'utilisateur est admin
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Accès réservé aux administrateurs');
        }

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
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Accès réservé aux administrateurs');
        }

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
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Accès réservé aux administrateurs');
        }

        $profile = MentorProfile::where('user_id', $id)->firstOrFail();
        $profile->update(['is_validated' => true]);

        return back()->with('success', 'Profil mentor validé avec succès.');
    }

    /**
     * Rejeter un profil mentor
     */
    public function rejectProfile($id)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Accès réservé aux administrateurs');
        }

        $profile = MentorProfile::where('user_id', $id)->firstOrFail();
        $profile->delete();

        return back()->with('success', 'Profil mentor rejeté et supprimé.');
    }

    /**
     * Statistiques globales détaillées
     */
    public function stats()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Accès réservé aux administrateurs');
        }

        $stats = [
            'total_users' => User::count(),
            'total_mentors' => User::where('role', 'mentor')->count(),
            'total_mentees' => User::where('role', 'mentee')->count(),
            'total_admins' => User::where('role', 'admin')->count(),
            'pending_validations' => MentorProfile::where('is_validated', false)->count(),
            'validated_mentors' => MentorProfile::where('is_validated', true)->count(),
            'recent_registrations' => User::where('created_at', '>=', now()->subDays(7))->count(),
        ];

        // Statistiques par domaine
        $domainStats = MentorProfile::where('is_validated', true)
            ->get()
            ->flatMap(function ($profile) {
                return $profile->domains ?? [];
            })
            ->countBy()
            ->toArray();

        return view('admin.stats', compact('stats', 'domainStats'));
    }

    /**
     * Liste des abonnés newsletter
     */
    public function newsletters(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Accès réservé aux administrateurs');
        }

        $query = \App\Models\Newsletter::query();

        // Filtre par statut
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        // Recherche par email
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('email', 'like', '%' . $request->search . '%')
                  ->orWhere('name', 'like', '%' . $request->search . '%');
            });
        }

        $newsletters = $query->latest('subscribed_at')->paginate(20);

        return view('admin.newsletters', compact('newsletters'));
    }
    public function users(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Accès réservé aux administrateurs');
        }

        $query = User::query();

        // Filtre par rôle
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Recherche par nom/email
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $users = $query->with('mentorProfile')->latest()->paginate(20);

        return view('admin.users', compact('users'));
    }
}