<?php

namespace App\Services;

use App\Models\Availability;
use App\Models\MentorSession;
use Carbon\Carbon;

class AvailabilityService
{
    public function getAvailableSlots(int $mentorId, Carbon $weekStart): array
    {
        $availabilities = Availability::where('mentor_id', $mentorId)->get();

        $bookedSessions = MentorSession::where('mentor_id', $mentorId)
            ->where('status', 'confirmee')
            ->whereBetween('scheduled_at', [$weekStart, $weekStart->copy()->endOfWeek()])
            ->get();

        $slots = [];

        foreach ($availabilities as $availability) {
            $date = $weekStart->copy()->startOfWeek()->addDays($availability->day_of_week);
            $slotStart = Carbon::parse($date->toDateString() . ' ' . $availability->start_time);

            $isBooked = $bookedSessions->contains(function ($session) use ($slotStart) {
                return Carbon::parse($session->scheduled_at)->isSameHour($slotStart);
            });

            if (!$isBooked) {
                $slots[] = [
                    'date'       => $date->toDateString(),
                    'start_time' => $availability->start_time,
                    'end_time'   => $availability->end_time,
                    'day_label'  => $date->locale('fr')->isoFormat('dddd D MMMM'),
                ];
            }
        }

        return $slots;
    }
}
