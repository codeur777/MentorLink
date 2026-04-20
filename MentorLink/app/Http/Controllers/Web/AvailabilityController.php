<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Availability;
use App\Services\AvailabilityService;
use Illuminate\Http\Request;

class AvailabilityController extends Controller
{
    public function __construct(private AvailabilityService $availabilityService) {}

    /**
     * Disponibilités d'un mentor
     */
    public function index($mentorId)
    {
        $availabilities = Availability::where('mentor_id', $mentorId)
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get();

        return view('availabilities.index', compact('availabilities', 'mentorId'));
    }

    /**
     * Formulaire de création de disponibilité (pour mentors)
     */
    public function create()
    {
        $user = auth()->user();
        
        if ($user->role !== 'mentor') {
            return redirect()->route('dashboard')->with('error', 'Accès réservé aux mentors');
        }

        return view('availabilities.create');
    }

    /**
     * Sauvegarder une disponibilité
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        
        $request->validate([
            'day_of_week' => 'required|integer|between:0,6',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        Availability::create([
            'mentor_id' => $user->id,
            'day_of_week' => $request->day_of_week,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
        ]);

        return redirect()->route('mentor.profile')->with('success', 'Disponibilité ajoutée avec succès.');
    }

    /**
     * Supprimer une disponibilité
     */
    public function destroy(Availability $availability)
    {
        $this->authorize('delete', $availability);
        
        $availability->delete();

        return back()->with('success', 'Disponibilité supprimée avec succès.');
    }
}