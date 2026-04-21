<?php

namespace App\Policies;

use App\Models\Session;
use App\Models\User;

class SessionPolicy
{
    /** Mentor confirms a pending session */
    public function confirm(User $user, Session $session): bool
    {
        return $user->id === $session->mentor_id && $session->isPending();
    }

    /** Mentor or mentee can cancel (only pending or confirmed) */
    public function cancel(User $user, Session $session): bool
    {
        return ($user->id === $session->mentor_id || $user->id === $session->mentee_id)
            && in_array($session->status, ['pending', 'confirmed']);
    }

    /** Mentor marks a confirmed session as completed */
    public function complete(User $user, Session $session): bool
    {
        return $user->id === $session->mentor_id && $session->isConfirmed();
    }

    /** Mentor or mentee can join the meeting room (session must be confirmed and have a room) */
    public function joinMeeting(User $user, Session $session): bool
    {
        return in_array($user->id, [$session->mentor_id, $session->mentee_id])
            && $session->isConfirmed()
            && $session->meeting_room_id !== null;
    }
}
