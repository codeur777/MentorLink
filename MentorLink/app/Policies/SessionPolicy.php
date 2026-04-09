<?php

namespace App\Policies;

use App\Models\User;
use App\Models\MentorSession;

class SessionPolicy
{
    public function confirm(User $user, MentorSession $session): bool
    {
        return $user->id === $session->mentor_id;
    }

    public function cancel(User $user, MentorSession $session): bool
    {
        return in_array($user->id, [$session->mentor_id, $session->mentee_id])
            && $session->status !== 'terminee';
    }

    public function complete(User $user, MentorSession $session): bool
    {
        return $user->id === $session->mentor_id && $session->status === 'confirmee';
    }
}
