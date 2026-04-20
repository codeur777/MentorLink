<?php

namespace Database\Seeders;

use App\Models\MentorSession;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class SessionSeeder extends Seeder
{
    public function run(): void
    {
        $mentor = User::where('email', 'mentor@test.com')->first();
        $mentee = User::where('email', 'mentee@test.com')->first();
        
        if ($mentor && $mentee) {
            // Session dans 1 heure pour tester les notifications
            MentorSession::create([
                'mentor_id' => $mentor->id,
                'mentee_id' => $mentee->id,
                'scheduled_at' => Carbon::now()->addHour(),
                'duration_min' => 60,
                'status' => 'confirmee',
                'session_notes' => 'Session de test pour les notifications',
                'meeting_link' => 'https://meet.google.com/test-session-1h',
                'notification_1h_sent' => false,
                'notification_5m_sent' => false,
            ]);
            
            // Session dans 5 minutes pour tester les notifications
            MentorSession::create([
                'mentor_id' => $mentor->id,
                'mentee_id' => $mentee->id,
                'scheduled_at' => Carbon::now()->addMinutes(5),
                'duration_min' => 30,
                'status' => 'confirmee',
                'session_notes' => 'Session de test pour les notifications 5min',
                'meeting_link' => 'https://meet.google.com/test-session-5m',
                'notification_1h_sent' => true,
                'notification_5m_sent' => false,
            ]);
            
            // Session en attente
            MentorSession::create([
                'mentor_id' => $mentor->id,
                'mentee_id' => $mentee->id,
                'scheduled_at' => Carbon::now()->addDays(2),
                'duration_min' => 90,
                'status' => 'en_attente',
                'session_notes' => 'Session de révision pour les examens',
            ]);
            
            $this->command->info('Sessions de test créées avec succès !');
        } else {
            $this->command->error('Utilisateurs mentor ou mentee non trouvés. Exécutez d\'abord AdminSeeder.');
        }
    }
}