<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAvailabilityRequest;
use App\Models\Availability;
use App\Models\User;
use Illuminate\Http\Request;

class AvailabilityController extends Controller
{
    /**
     * Afficher les disponibilités d'un mentor
     */
    public function index($mentorId)
    {
        $mentor = User::where('role', 'mentor')
            ->where('id', $mentorId)
            ->with(['mentorProfile', 'availabilities'])
            ->firstOrFail();

        return view('availabilities.index', compact('mentor'));
    }

    /**
     * Formulaire de création de disponibilité
     */
    public function create()
    {
        $user = auth()->user();
        
        if ($user->role !== 'mentor') {
            return redirect()->route('dashboard')->with('error', 'Accès réservé aux mentors');
        }

        return view('availabilities.create', compact('user'));
    }

    /**
     * Sauvegarder une nouvelle disponibilité
     */
    public function store(StoreAvailabilityRequest $request)
    {
        $user = auth()->user();
        
        if ($user->role !== 'mentor') {
            return redirect()->route('dashboard')->with('error', 'Accès réservé aux mentors');
        }

        Availability::create([
            'mentor_id' => $user->id,
            'day_of_week' => $request->day_of_week,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
        ]);

        return back()->with('success', 'Disponibilité ajoutée avec succès.');
    }

    /**
     * Supprimer une disponibilité
     */
    public function destroy(Availability $availability)
    {
        // Vérifier que l'utilisateur est le propriétaire
        if ($availability->mentor_id !== auth()->id()) {
            abort(403, 'Non autorisé');
        }

        $availability->delete();

        return back()->with('success', 'Disponibilité supprimée avec succès.');
    }
}