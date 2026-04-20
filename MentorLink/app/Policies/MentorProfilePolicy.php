<?php

namespace App\Policies;

use App\Models\User;
use App\Models\MentorProfile;

class MentorProfilePolicy
{
    public function validate(User $user, MentorProfile $profile): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, MentorProfile $profile): bool
    {
        return $user->id === $profile->user_id || $user->isAdmin();
    }
}
