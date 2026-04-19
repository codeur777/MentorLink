<?php

namespace Database\Seeders;

use App\Models\Availability;
use App\Models\MentorProfile;
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
        ]);

        // Mentors
        $mentor1 = User::create([
            'name'              => 'Jean Dupont',
            'email'             => 'jean@mentorlink.com',
            'password'          => Hash::make('password'),
            'role'              => 'mentor',
            'email_verified_at' => now(),
        ]);

        $mentor2 = User::create([
            'name'              => 'Marie Martin',
            'email'             => 'marie@mentorlink.com',
            'password'          => Hash::make('password'),
            'role'              => 'mentor',
            'email_verified_at' => now(),
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

        // Availabilities for mentor1
        Availability::create([
            'mentor_id'   => $mentor1->id,
            'day_of_week' => 1, // Monday
            'start_time'  => '09:00',
            'end_time'    => '12:00',
        ]);

        Availability::create([
            'mentor_id'   => $mentor1->id,
            'day_of_week' => 3, // Wednesday
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
        ]);

        // A completed session with a review
        $session = Session::create([
            'mentor_id'  => $mentor1->id,
            'mentee_id'  => $mentee->id,
            'date'       => now()->subDays(7)->toDateString(),
            'start_time' => '09:00',
            'end_time'   => '10:00',
            'status'     => 'completed',
            'note'       => 'Introduction au développement web',
        ]);

        Review::create([
            'session_id' => $session->id,
            'mentee_id'  => $mentee->id,
            'mentor_id'  => $mentor1->id,
            'rating'     => 5,
            'comment'    => 'Excellent mentor, très pédagogue !',
        ]);

        // A pending session
        Session::create([
            'mentor_id'  => $mentor1->id,
            'mentee_id'  => $mentee->id,
            'date'       => now()->addDays(3)->toDateString(),
            'start_time' => '09:00',
            'end_time'   => '10:00',
            'status'     => 'pending',
            'note'       => 'Suite du cours JavaScript',
        ]);
    }
}
