<?php

namespace App\Policies;

use App\Models\Availability;
use App\Models\User;

class AvailabilityPolicy
{
    public function delete(User $user, Availability $availability): bool
    {
        return $user->id === $availability->mentor_id || $user->isAdmin();
    }

    public function update(User $user, Availability $availability): bool
    {
        return $user->id === $availability->mentor_id || $user->isAdmin();
    }
}
