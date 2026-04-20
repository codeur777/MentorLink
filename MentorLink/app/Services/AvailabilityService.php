<?php

namespace App\Services;

use App\Models\Availability;
use App\Models\Session;
use Carbon\Carbon;

class AvailabilityService
{
    /**
     * Return weekly slots for a mentor, each marked as available or booked.
     * Booked slots do NOT reveal who made the booking.
     *
     * @return array<int, array{date: string, day_of_week: int, start_time: string, end_time: string, booked: bool}>
     */
    public function getSlotsForWeek(int $mentorId, Carbon $weekStart): array
    {
        $monday = $weekStart->copy()->startOfWeek(Carbon::MONDAY);

        $availabilities = Availability::where('mentor_id', $mentorId)->get();

        // Fetch all pending/confirmed sessions for this mentor in the target week
        $weekEnd = $monday->copy()->endOfWeek(Carbon::SUNDAY);
        $bookedSessions = Session::where('mentor_id', $mentorId)
            ->whereIn('status', ['pending', 'confirmed'])
            ->whereBetween('date', [$monday->toDateString(), $weekEnd->toDateString()])
            ->get(['date', 'start_time', 'end_time']);

        $slots = [];

        foreach ($availabilities as $availability) {
            // day_of_week: 1=Monday … 7=Sunday (Carbon ISO)
            // Our DB stores 0=Sunday,1=Monday…6=Saturday (PHP date('w'))
            // Map to Carbon: addDays from Monday
            $dayOffset = $availability->day_of_week === 0 ? 6 : $availability->day_of_week - 1;
            $date = $monday->copy()->addDays($dayOffset);

            $dateStr = $date->toDateString();

            // Check if any booked session overlaps this availability slot on this date
            $booked = $bookedSessions->contains(function ($session) use ($dateStr, $availability) {
                if ($session->date->toDateString() !== $dateStr) {
                    return false;
                }
                // Overlap: session starts before slot ends AND session ends after slot starts
                return $session->start_time < $availability->end_time
                    && $session->end_time > $availability->start_time;
            });

            $slots[] = [
                'availability_id' => $availability->id,
                'date'            => $dateStr,
                'day_of_week'     => $availability->day_of_week,
                'start_time'      => $availability->start_time,
                'end_time'        => $availability->end_time,
                'booked'          => $booked,
            ];
        }

        // Sort by date then start_time
        usort($slots, fn($a, $b) => $a['date'] <=> $b['date'] ?: $a['start_time'] <=> $b['start_time']);

        return $slots;
    }

    /**
     * Check whether a requested date+time falls within the mentor's availability
     * and does not conflict with an existing booking.
     */
    public function isSlotValid(int $mentorId, string $date, string $startTime, string $endTime): bool
    {
        $dayOfWeek = (int) Carbon::parse($date)->format('w'); // 0=Sun … 6=Sat

        // 1. Must match an availability window
        $covered = Availability::where('mentor_id', $mentorId)
            ->where('day_of_week', $dayOfWeek)
            ->where('start_time', '<=', $startTime)
            ->where('end_time', '>=', $endTime)
            ->exists();

        if (! $covered) {
            return false;
        }

        // 2. Must not overlap an existing pending/confirmed session
        $conflict = Session::where('mentor_id', $mentorId)
            ->where('date', $date)
            ->whereIn('status', ['pending', 'confirmed'])
            ->where('start_time', '<', $endTime)
            ->where('end_time', '>', $startTime)
            ->exists();

        return ! $conflict;
    }
}
