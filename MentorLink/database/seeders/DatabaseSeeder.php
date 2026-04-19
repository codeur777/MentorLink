<?php

namespace Database\Seeders;

use App\Models\Availability;
use App\Models\MentorProfile;
use App\Models\Report;
use App\Models\Review;
use App\Models\Session;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'name'              => 'Admin MentorLink',
            'email'             => 'admin@mentorlink.com',
            'password'          => Hash::make('password'),
            'role'              => 'admin',
            'email_verified_at' => now(),
            'suspended'         => false,
        ]);

        // Mentors
        $mentor1 = User::create([
            'name'              => 'Jean Dupont',
            'email'             => 'jean@mentorlink.com',
            'password'          => Hash::make('password'),
            'role'              => 'mentor',
            'email_verified_at' => now(),
            'suspended'         => false,
        ]);

        $mentor2 = User::create([
            'name'              => 'Marie Martin',
            'email'             => 'marie@mentorlink.com',
            'password'          => Hash::make('password'),
            'role'              => 'mentor',
            'email_verified_at' => now(),
            'suspended'         => false,
        ]);

        // Mentor profiles
        MentorProfile::create([
            'user_id'      => $mentor1->id,
            'domains'      => ['web', 'javascript', 'php'],
            'hourly_rate'  => 50.00,
            'is_validated' => true,
        ]);

        MentorProfile::create([
            'user_id'      => $mentor2->id,
            'domains'      => ['data', 'python', 'machine-learning'],
            'hourly_rate'  => 60.00,
            'is_validated' => false,
        ]);

        // Availabilities for mentor1 (Monday 09-12, Wednesday 14-17)
        Availability::create([
            'mentor_id'   => $mentor1->id,
            'day_of_week' => 1,
            'start_time'  => '09:00',
            'end_time'    => '12:00',
        ]);

        Availability::create([
            'mentor_id'   => $mentor1->id,
            'day_of_week' => 3,
            'start_time'  => '14:00',
            'end_time'    => '17:00',
        ]);

        // Mentee
        $mentee = User::create([
            'name'              => 'Pierre Etudiant',
            'email'             => 'pierre@mentorlink.com',
            'password'          => Hash::make('password'),
            'role'              => 'mentee',
            'email_verified_at' => now(),
            'suspended'         => false,
        ]);

        // Completed session with review (last Monday)
        $lastMonday = now()->startOfWeek(\Carbon\Carbon::MONDAY)->subWeek();
        $completedSession = Session::create([
            'mentor_id'  => $mentor1->id,
            'mentee_id'  => $mentee->id,
            'date'       => $lastMonday->toDateString(),
            'start_time' => '09:00',
            'end_time'   => '10:00',
            'status'     => 'completed',
            'note'       => 'Introduction au developpement web',
        ]);

        Review::create([
            'session_id' => $completedSession->id,
            'mentee_id'  => $mentee->id,
            'mentor_id'  => $mentor1->id,
            'rating'     => 5,
            'comment'    => 'Excellent mentor, tres pedagogique !',
        ]);

        // Sample open report
        Report::create([
            'reporter_id' => $mentee->id,
            'reported_id' => $mentor1->id,
            'session_id'  => $completedSession->id,
            'reason'      => 'Le mentor ne s\'est pas presente a la session convenue.',
            'status'      => 'open',
        ]);

        // Pending session (next Monday within availability)
        $nextMonday = now()->startOfWeek(\Carbon\Carbon::MONDAY)->addWeek();
        Session::create([
            'mentor_id'  => $mentor1->id,
            'mentee_id'  => $mentee->id,
            'date'       => $nextMonday->toDateString(),
            'start_time' => '09:00',
            'end_time'   => '10:00',
            'status'     => 'pending',
            'note'       => 'Suite du cours JavaScript',
        ]);
    }
}
