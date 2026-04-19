<?php

namespace App\Policies;

use App\Models\Session;
use App\Models\User;

class ReportPolicy
{
    /**
     * A user can file a report only if they shared a completed session
     * with the person they want to report, and haven't already reported
     * that same session.
     */
    public function create(User $reporter, Session $session): bool
    {
        // Reporter must be one of the two participants
        $isParticipant = $reporter->id === $session->mentor_id
            || $reporter->id === $session->mentee_id;

        if (! $isParticipant || ! $session->isCompleted()) {
            return false;
        }

        // No duplicate report for the same session
        return ! \App\Models\Report::where('reporter_id', $reporter->id)
            ->where('session_id', $session->id)
            ->exists();
    }
}
