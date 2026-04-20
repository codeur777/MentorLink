<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\MentorSession;
use App\Models\Review;
use App\Models\MentorProfile;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ReviewTestSeeder extends Seeder
{
    public function run(): void
    {
        // Créer un mentor de test
        $mentor = User::firstOrCreate(
            ['email' => 'mentor.test@example.com'],
            [
                'name' => 'Jean Mentor',
                'password' => bcrypt('password'),
                'role' => 'mentor',
                'email_verified_at' => now(),
            ]
        );

        // Créer son profil mentor
        MentorProfile::firstOrCreate(
            ['user_id' => $mentor->id],
            [
                'domains' => ['web', 'backend'],
                'hourly_rate' => 50,
                'is_validated' => true,
            ]
        );

        // Créer un mentee de test
        $mentee = User::firstOrCreate(
            ['email' => 'mentee.test@example.com'],
            [
                'name' => 'Marie Étudiante',
                'password' => bcrypt('password'),
                'role' => 'mentee',
                'email_verified_at' => now(),
            ]
        );

        // Créer quelques sessions terminées
        for ($i = 1; $i <= 3; $i++) {
            $session = MentorSession::create([
                'mentor_id' => $mentor->id,
                'mentee_id' => $mentee->id,
                'scheduled_at' => Carbon::now()->subDays($i * 2),
                'duration_min' => 60,
                'status' => 'terminee',
                'completed_at' => Carbon::now()->subDays($i * 2)->addMinutes(60),
                'is_reviewed' => true,
                'meeting_link' => 'https://meet.google.com/test-' . $i,
            ]);

            // Créer un avis pour chaque session
            Review::create([
                'session_id' => $session->id,
                'reviewer_id' => $mentee->id,
                'mentor_id' => $mentor->id,
                'rating' => 4 + ($i % 2), // Notes entre 4 et 5
                'comment' => "Excellente session ! Le mentor était très pédagogue et m'a beaucoup aidé. Session $i très enrichissante.",
                'is_late_cancellation_penalty' => false,
            ]);
        }

        // Recalculer la note moyenne du mentor
        $reviews = Review::where('mentor_id', $mentor->id)
            ->where('is_late_cancellation_penalty', false)
            ->get();

        $mentor->update([
            'total_reviews' => $reviews->count(),
            'average_rating' => round($reviews->avg('rating'), 2)
        ]);

        $this->command->info('Données de test créées avec succès !');
        $this->command->info("Mentor: {$mentor->email} (Note: {$mentor->average_rating}/5)");
        $this->command->info("Mentee: {$mentee->email}");
    }
}