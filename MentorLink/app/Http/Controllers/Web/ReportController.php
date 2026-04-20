<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\Session;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Show the report form for a completed session.
     */
    public function create(Session $session)
    {
        $this->authorize('create', [Report::class, $session]);

        $reporter = auth()->user();

        // The person being reported is the other participant
        $reported = $reporter->id === $session->mentor_id
            ? $session->mentee
            : $session->mentor;

        return view('reports.create', compact('session', 'reported'));
    }

    /**
     * Store the report.
     */
    public function store(Request $request, Session $session)
    {
        $this->authorize('create', [Report::class, $session]);

        $request->validate([
            'reason' => 'required|string|min:10|max:1000',
        ]);

        $reporter = auth()->user();

        $reported = $reporter->id === $session->mentor_id
            ? $session->mentee
            : $session->mentor;

        Report::create([
            'reporter_id' => $reporter->id,
            'reported_id' => $reported->id,
            'session_id'  => $session->id,
            'reason'      => $request->reason,
            'status'      => 'open',
        ]);

        return redirect()->route('sessions.index')
            ->with('success', 'Signalement soumis. L\'administrateur en sera informe.');
    }
}
