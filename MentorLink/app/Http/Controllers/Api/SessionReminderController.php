<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MentorSession;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SessionReminderController extends Controller
{
    /**
     * Récupérer les rappels de session pour l'utilisateur connecté
     */
    public function index(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([]);
        }

        $user = Auth::user();
        $now = Carbon::now();
        $reminders = [];

        // Sessions où l'utilisateur est impliqué
        $sessions = MentorSession::where('status', 'confirmee')
            ->where(function ($query) use ($user) {
                $query->where('mentor_id', $user->id)
                      ->orWhere('mentee_id', $user->id);
            })
            ->where('scheduled_at', '>', $now)
            ->where('scheduled_at', '<=', $now->copy()->addHours(2))
            ->get();

        foreach ($sessions as $session) {
            $timeUntilSession = $now->diffInMinutes($session->scheduled_at, false);
            
            // Rappel 1 heure avant (55-65 minutes)
            if ($timeUntilSession >= 55 && $timeUntilSession <= 65 && !$session->notification_1h_sent) {
                $reminders[] = [
                    'session_id' => $session->id,
                    'message' => 'Votre session avec ' . 
                        ($user->id === $session->mentor_id ? $session->mentee->name : $session->mentor->name) . 
                        ' commence dans 1 heure',
                    'type' => '1h',
                    'scheduled_at' => $session->scheduled_at->toISOString(),
                    'meeting_link' => $session->meeting_link
                ];
                
                // Marquer comme envoyé
                $session->update(['notification_1h_sent' => true]);
            }
            
            // Rappel 5 minutes avant (3-7 minutes)
            if ($timeUntilSession >= 3 && $timeUntilSession <= 7 && !$session->notification_5m_sent) {
                $reminders[] = [
                    'session_id' => $session->id,
                    'message' => 'Votre session avec ' . 
                        ($user->id === $session->mentor_id ? $session->mentee->name : $session->mentor->name) . 
                        ' commence dans 5 minutes !',
                    'type' => '5m',
                    'scheduled_at' => $session->scheduled_at->toISOString(),
                    'meeting_link' => $session->meeting_link
                ];
                
                // Marquer comme envoyé
                $session->update(['notification_5m_sent' => true]);
            }
        }

        return response()->json($reminders);
    }
}