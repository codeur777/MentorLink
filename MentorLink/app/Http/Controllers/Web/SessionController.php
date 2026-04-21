<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\MentorSession;
use App\Models\User;
use App\Models\Availability;
use App\Notifications\SessionConfirmed;
use App\Notifications\SessionCancelled;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class SessionController extends Controller
{
    /**
     * Afficher les sessions de l'utilisateur
     */
    public function index()
    {
        $user = Auth::user();
        
        if ($user->role === 'mentor') {
            $sessions = MentorSession::where('mentor_id', $user->id)
                ->with(['mentee'])
                ->orderBy('scheduled_at', 'desc')
                ->paginate(10);
        } else {
            $sessions = MentorSession::where('mentee_id', $user->id)
                ->with(['mentor'])
                ->orderBy('scheduled_at', 'desc')
                ->paginate(10);
        }
        
        return view('sessions.index', compact('sessions'));
    }

    /**
     * Afficher le formulaire de réservation
     */
    public function create($mentorId)
    {
        $mentor = User::where('role', 'mentor')->findOrFail($mentorId);
        
        // Récupérer les disponibilités du mentor
        $availabilities = Availability::where('mentor_id', $mentorId)
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get();
        
        // Récupérer les sessions déjà réservées pour les 30 prochains jours
        $bookedSessions = MentorSession::where('mentor_id', $mentorId)
            ->where('scheduled_at', '>=', now())
            ->where('scheduled_at', '<=', now()->addDays(30))
            ->where('status', '!=', 'annulee')
            ->pluck('scheduled_at')
            ->map(function ($date) {
                return $date->format('Y-m-d H:i');
            })
            ->toArray();
        
        return view('sessions.create', compact('mentor', 'availabilities', 'bookedSessions'));
    }

    /**
     * Enregistrer une nouvelle réservation
     */
    public function store(Request $request)
    {
        $request->validate([
            'mentor_id' => 'required|exists:users,id',
            'scheduled_at' => 'required|date|after:now',
            'duration_min' => 'required|integer|min:30|max:180',
            'session_notes' => 'nullable|string|max:500',
        ]);

        // Vérifier que le mentor est disponible à cette heure
        $scheduledAt = Carbon::parse($request->scheduled_at);
        $dayOfWeek = $scheduledAt->dayOfWeek;
        $time = $scheduledAt->format('H:i:s');
        
        $availability = Availability::where('mentor_id', $request->mentor_id)
            ->where('day_of_week', $dayOfWeek)
            ->where('start_time', '<=', $time)
            ->where('end_time', '>', $time)
            ->first();
            
        if (!$availability) {
            return back()->withErrors(['scheduled_at' => 'Le mentor n\'est pas disponible à cette heure.']);
        }
        
        // Vérifier qu'il n'y a pas déjà une session à cette heure
        $existingSession = MentorSession::where('mentor_id', $request->mentor_id)
            ->where('scheduled_at', $scheduledAt)
            ->where('status', '!=', 'annulee')
            ->first();
            
        if ($existingSession) {
            return back()->withErrors(['scheduled_at' => 'Cette heure est déjà réservée.']);
        }

        $session = MentorSession::create([
            'mentor_id' => $request->mentor_id,
            'mentee_id' => Auth::id(),
            'scheduled_at' => $scheduledAt,
            'duration_min' => $request->duration_min,
            'status' => 'en_attente',
            'session_notes' => $request->session_notes,
        ]);

        // Notifier le mentor de la nouvelle demande
        $mentor = User::find($request->mentor_id);
        $mentor->notify(new \App\Notifications\SessionRequested($session));

        return redirect()->route('sessions.show', $session)
            ->with('success', 'Votre demande de session a été envoyée au mentor.');
    }

    /**
     * Afficher une session
     */
    public function show(MentorSession $session)
    {
        // Vérifier que l'utilisateur peut voir cette session
        if ($session->mentor_id !== Auth::id() && $session->mentee_id !== Auth::id()) {
            abort(403);
        }
        
        $session->load(['mentor', 'mentee']);
        
        return view('sessions.show', compact('session'));
    }

    /**
     * Générer un lien Google Meet unique
     */
    private function generateGoogleMeetLink()
    {
        // Générer un code de réunion Google Meet réaliste
        $characters = 'abcdefghijklmnopqrstuvwxyz';
        $meetCode = '';
        
        // Format: xxx-xxxx-xxx (comme les vrais liens Google Meet)
        for ($i = 0; $i < 3; $i++) {
            $meetCode .= $characters[rand(0, strlen($characters) - 1)];
        }
        $meetCode .= '-';
        
        for ($i = 0; $i < 4; $i++) {
            $meetCode .= $characters[rand(0, strlen($characters) - 1)];
        }
        $meetCode .= '-';
        
        for ($i = 0; $i < 3; $i++) {
            $meetCode .= $characters[rand(0, strlen($characters) - 1)];
        }
        
        return 'https://meet.google.com/' . $meetCode;
    }

    /**
     * Confirmer une session (mentor seulement)
     */
    public function confirm(MentorSession $session)
    {
        if ($session->mentor_id !== Auth::id()) {
            abort(403);
        }
        
        // Générer un lien Google Meet unique pour cette session
        $meetingLink = $this->generateGoogleMeetLink();
        
        $session->update([
            'status' => 'confirmee',
            'meeting_link' => $meetingLink
        ]);
        
        // Notifier le mentee que la session est confirmée
        $session->mentee->notify(new SessionConfirmed($session));
        
        return back()->with('success', 'Session confirmée avec succès. Le lien de réunion a été généré et le mentee a été notifié.');
    }

    /**
     * Annuler une session
     */
    public function cancel(MentorSession $session)
    {
        if ($session->mentor_id !== Auth::id() && $session->mentee_id !== Auth::id()) {
            abort(403);
        }

        // Vérifier que la session peut être annulée
        if (!in_array($session->status, ['en_attente', 'confirmee'])) {
            return back()->withErrors(['session' => 'Cette session ne peut plus être annulée.']);
        }

        $now = Carbon::now();
        $sessionStart = $session->scheduled_at;
        $minutesUntilSession = $now->diffInMinutes($sessionStart, false);
        
        // Vérifier si c'est une annulation tardive (moins de 15 minutes)
        $isLateCancellation = $minutesUntilSession <= 15 && $minutesUntilSession > 0;
        $cancelledBy = Auth::id() === $session->mentor_id ? 'mentor' : 'mentee';
        
        // Appliquer une pénalité si le mentor annule tardivement
        if ($isLateCancellation && $cancelledBy === 'mentor') {
            $reviewController = new \App\Http\Controllers\Web\ReviewController();
            $reviewController->applyLateCancellationPenalty($session->mentor);
        }

        // Annuler la session
        $session->update(['status' => 'annulee']);

        // Notifier l'autre partie
        $otherUser = Auth::id() === $session->mentor_id ? $session->mentee : $session->mentor;
        $otherUser->notify(new SessionCancelled($session, $cancelledBy, $isLateCancellation));

        $message = 'Session annulée avec succès.';
        if ($isLateCancellation && $cancelledBy === 'mentor') {
            $message .= ' Une pénalité de 0.5 étoile a été appliquée pour cette annulation tardive.';
        }

        return back()->with('success', $message);
    }

    /**
     * Marquer une session comme terminée
     */
    public function complete(MentorSession $session)
    {
        if ($session->mentor_id !== Auth::id()) {
            abort(403);
        }

        if ($session->status !== 'confirmee') {
            return back()->withErrors(['session' => 'Seules les sessions confirmées peuvent être marquées comme terminées.']);
        }

        $session->update([
            'status' => 'terminee',
            'completed_at' => Carbon::now()
        ]);

        // Notifier le mentee que la session est terminée
        $session->mentee->notify(new \App\Notifications\SessionCompleted($session));

        return back()->with('success', 'Session marquée comme terminée. Le mentee a été notifié et peut maintenant laisser un avis.');
    }
}