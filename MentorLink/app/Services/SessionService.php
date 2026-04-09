<?php

namespace App\Services;

use App\Models\MentorSession;
use Carbon\Carbon;

class SessionService
{
    public function hasConflict(int $mentorId, Carbon $scheduledAt, int $durationMin): bool
    {
        $end = $scheduledAt->copy()->addMinutes($durationMin);

        return MentorSession::where('mentor_id', $mentorId)
            ->where('status', 'confirmee')
            ->where(function ($query) use ($scheduledAt, $end) {
                $query->whereBetween('scheduled_at', [$scheduledAt, $end])
                      ->orWhereRaw(
                          'DATE_ADD(scheduled_at, INTERVAL duration_min MINUTE) BETWEEN ? AND ?',
                          [$scheduledAt, $end]
                      );
            })
            ->exists();
    }
}
