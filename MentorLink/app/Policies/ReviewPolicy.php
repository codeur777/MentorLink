<?php

namespace App\Policies;

use App\Models\User;
use App\Models\MentorSession;

class ReviewPolicy
{
    public function create(User $user, MentorSession $session): bool
    {
        return $user->id === $session->mentee_id
            && $session->status === 'terminee'
            && $session->review === null;
    }
}
