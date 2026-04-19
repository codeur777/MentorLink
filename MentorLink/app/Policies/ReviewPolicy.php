<?php

namespace App\Policies;

use App\Models\Review;
use App\Models\Session;
use App\Models\User;

class ReviewPolicy
{
    /** Only the mentee of a completed session can create a review, and only once */
    public function create(User $user, Session $session): bool
    {
        return $user->id === $session->mentee_id
            && $session->isCompleted()
            && $session->review === null;
    }
}
