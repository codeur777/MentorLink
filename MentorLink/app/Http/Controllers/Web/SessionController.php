<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSessionRequest;
use App\Models\Session;
use App\Models\User;
use App\Services\AvailabilityService;
use App\Services\MeetingService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SessionController extends Controller
{
    public function __construct(
        private AvailabilityService $availabilityService,
        private MeetingService $meetingService,
    ) {}

    /**
     * List sessions for the authenticated user.
     */
    public function index()
    {
        $user = auth()->user();

        if ($user->isMentor()) {
            $sessions = Session::with(['mentee', 'review'])
                ->where('mentor_id', $user->id)
                ->orderByDesc('date')->orderByDesc('start_time')
                ->paginate(15);
        } else {
            $sessions = Session::with(['mentor.mentorProfile', 'review'])
                ->where('mentee_id', $user->id)
                ->orderByDesc('date')->orderByDesc('start_time')
                ->paginate(15);
        }

        return view('sessions.index', compact('sessions', 'user'));
    }

    /**
     * Show the booking form — pass availability slots with booked status.
     */
    public function create(Request $request)
    {
        $user = auth()->user();

        if (! $user->isMentee()) {
            return redirect()->route('dashboard')->with('error', 'Seuls les mentores peuvent reserver une session.');
        }

        $mentor = User::where('role', 'mentor')
            ->with(['mentorProfile', 'availabilities'])
            ->findOrFail($request->query('mentor_id'));

        if (! $mentor->mentorProfile?->is_validated) {
            return redirect()->route('mentors.index')->with('error', 'Ce mentor n\'est pas encore valide.');
        }

        // Show slots for the requested week (default: current week)
        $weekStart = $request->filled('week')
            ? Carbon::parse($request->week)
            : Carbon::now();

        $slots = $this->availabilityService->getSlotsForWeek($mentor->id, $weekStart);

        // Week navigation
        $prevWeek = $weekStart->copy()->startOfWeek(Carbon::MONDAY)->subWeek()->toDateString();
        $nextWeek = $weekStart->copy()->startOfWeek(Carbon::MONDAY)->addWeek()->toDateString();

        return view('sessions.create', compact('mentor', 'slots', 'weekStart', 'prevWeek', 'nextWeek'));
    }

    /**
     * Store a new session — validate against availability before saving.
     */
    public function store(StoreSessionRequest $request)
    {
        // Server-side availability + conflict check
        if (! $this->availabilityService->isSlotValid(
            $request->mentor_id,
            $request->date,
            $request->start_time,
            $request->end_time
        )) {
            return back()->withInput()->withErrors([
                'start_time' => 'Ce creneau ne correspond pas aux disponibilites du mentor ou est deja reserve.',
            ]);
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
            ->with('success', 'Session reservee avec succes. En attente de confirmation du mentor.');
    }

    /**
     * Mentor confirms a pending session — generates the meeting room at this point.
     */
    public function confirm(Session $session)
    {
        $this->authorize('confirm', $session);

        // Load relationships needed to build the room slug
        $session->load(['mentor', 'mentee']);

        $session->update([
            'status'          => 'confirmed',
            'meeting_room_id' => $this->meetingService->generateRoomId($session),
        ]);

        return back()->with('success', 'Session confirmée. Le lien de visioconférence est maintenant disponible.');
    }

    /**
     * Cancel a session (mentor or mentee).
     */
    public function cancel(Session $session)
    {
        $this->authorize('cancel', $session);
        $session->update(['status' => 'cancelled']);
        return back()->with('success', 'Session annulee.');
    }

    /**
     * Mentor marks a confirmed session as completed.
     */
    public function complete(Session $session)
    {
        $this->authorize('complete', $session);
        $session->update(['status' => 'completed']);
        return back()->with('success', 'Session marquee comme terminee.');
    }

    /**
     * Show the in-app Jitsi meeting room for a confirmed session.
     * Access is restricted to the mentor and mentee of the session.
     */
    public function meeting(Session $session)
    {
        $this->authorize('joinMeeting', $session);

        $session->load(['mentor', 'mentee']);

        $roomUrl   = $this->meetingService->getRoomUrl($session);
        $serverUrl = $this->meetingService->getServerUrl();
        $user      = auth()->user();

        return view('sessions.meeting', compact('session', 'roomUrl', 'serverUrl', 'user'));
    }
}
