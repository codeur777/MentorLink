<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMentorProfileRequest;
use App\Models\MentorProfile;
use App\Models\User;
use Illuminate\Http\Request;

class MentorController extends Controller
{
    /**
     * Liste des mentors validés
     */
    public function index(Request $request)
    {
        $query = User::where('role', 'mentor')
            ->whereHas('mentorProfile', function($q) {
                $q->where('is_validated', true);
            })
            ->with('mentorProfile');

        // Filtre par domaine si spécifié
        if ($request->filled('domain')) {
            $query->whereHas('mentorProfile', function($q) use ($request) {
                $q->whereJsonContains('domains', $request->domain);
            });
        }

        $mentors = $query->paginate(12);

        return view('mentors.index', compact('mentors'));
    }

    /**
     * Détail d'un mentor
     */
    public function show($id)
    {
        $mentor = User::where('role', 'mentor')
            ->where('id', $id)
            ->with(['mentorProfile', 'availabilities'])
            ->firstOrFail();

        // Reviews sorted newest first, via completed sessions
        $reviews = \App\Models\Review::with('mentee')
            ->where('mentor_id', $id)
            ->latest()
            ->get();

        return view('mentors.show', compact('mentor', 'reviews'));
    }

    /**
     * Créer/mettre à jour le profil mentor (pour les mentors connectés)
     */
    public function profile()
    {
        $user = auth()->user();
        
        if ($user->role !== 'mentor') {
            return redirect()->route('dashboard')->with('error', 'Accès réservé aux mentors');
        }

        return view('mentors.profile', compact('user'));
    }

    /**
     * Sauvegarder le profil mentor
     */
    public function updateProfile(StoreMentorProfileRequest $request)
    {
        $user = auth()->user();
        
        $user->mentorProfile()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'domains' => $request->domains,
                'hourly_rate' => $request->hourly_rate,
                'is_validated' => false, // Nécessite validation admin
            ]
        );

        return back()->with('success', 'Profil mis à jour avec succès. En attente de validation.');
    }
}