<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSessionRequest;
use App\Models\Session;
use App\Models\User;
use Illuminate\Http\Request;

class SessionController extends Controller
{
    /**
     * List sessions for the authenticated user (mentor or mentee).
     */
    public function index()
    {
        $user = auth()->user();

        if ($user->isMentor()) {
            $sessions = Session::with(['mentee', 'review'])
                ->where('mentor_id', $user->id)
                ->orderByDesc('date')
                ->orderByDesc('start_time')
                ->paginate(15);
        } else {
            $sessions = Session::with(['mentor.mentorProfile', 'review'])
                ->where('mentee_id', $user->id)
                ->orderByDesc('date')
                ->orderByDesc('start_time')
                ->paginate(15);
        }

        return view('sessions.index', compact('sessions', 'user'));
    }

    /**
     * Show the booking form for a specific mentor.
     */
    public function create(Request $request)
    {
        $user = auth()->user();

        if (! $user->isMentee()) {
            return redirect()->route('dashboard')->with('error', 'Seuls les mentorés peuvent réserver une session.');
        }

        $mentor = User::where('role', 'mentor')
            ->with(['mentorProfile', 'availabilities'])
            ->findOrFail($request->query('mentor_id'));

        if (! $mentor->mentorProfile?->is_validated) {
            return redirect()->route('mentors.index')->with('error', 'Ce mentor n\'est pas encore validé.');
        }

        return view('sessions.create', compact('mentor'));
    }

    /**
     * Store a new session booking.
     */
    public function store(StoreSessionRequest $request)
    {
        $mentor = User::findOrFail($request->mentor_id);

        // Conflict check: mentor cannot have two confirmed/pending sessions that overlap
        $conflict = Session::where('mentor_id', $request->mentor_id)
            ->where('date', $request->date)
            ->whereIn('status', ['pending', 'confirmed'])
            ->where(function ($q) use ($request) {
                $q->where(function ($q2) use ($request) {
                    $q2->where('start_time', '<', $request->end_time)
                       ->where('end_time', '>', $request->start_time);
                });
            })
            ->exists();

        if ($conflict) {
            return back()
                ->withInput()
                ->withErrors(['date' => 'Ce créneau est déjà réservé pour ce mentor.']);
        }

        Session::create([
            'mentor_id'  => $request->mentor_id,
            'mentee_id'  => auth()->id(),
            'date'       => $request->date,
            'start_time' => $request->start_time,
            'end_time'   => $request->end_time,
            'note'       => $request->note,
            'status'     => 'pending',
        ]);

        return redirect()->route('sessions.index')
            ->with('success', 'Session réservée avec succès. En attente de confirmation du mentor.');
    }

    /**
     * Mentor confirms a pending session.
     */
    public function confirm(Session $session)
    {
        $this->authorize('confirm', $session);

        $session->update(['status' => 'confirmed']);

        return back()->with('success', 'Session confirmée.');
    }

    /**
     * Cancel a session (mentor or mentee).
     */
    public function cancel(Session $session)
    {
        $this->authorize('cancel', $session);

        $session->update(['status' => 'cancelled']);

        return back()->with('success', 'Session annulée.');
    }

    /**
     * Mentor marks a confirmed session as completed.
     */
    public function complete(Session $session)
    {
        $this->authorize('complete', $session);

        $session->update(['status' => 'completed']);

        return back()->with('success', 'Session marquée comme terminée.');
    }
}
