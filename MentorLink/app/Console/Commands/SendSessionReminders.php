<?php

namespace App\Console\Commands;

use App\Models\MentorSession;
use App\Notifications\SessionReminder;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendSessionReminders extends Command
{
    protected $signature = 'sessions:send-reminders';
    protected $description = 'Envoie des rappels pour les sessions à venir';

    public function handle()
    {
        $now = Carbon::now();
        
        // Rappels 1 heure avant
        $sessionsIn1Hour = MentorSession::where('status', 'confirmee')
            ->where('notification_1h_sent', false)
            ->whereBetween('scheduled_at', [
                $now->copy()->addMinutes(55), // 55-65 minutes avant
                $now->copy()->addMinutes(65)
            ])
            ->with(['mentor', 'mentee'])
            ->get();

        foreach ($sessionsIn1Hour as $session) {
            // Notifier le mentor
            $session->mentor->notify(new SessionReminder($session, '1h'));
            
            // Notifier le mentee
            $session->mentee->notify(new SessionReminder($session, '1h'));
            
            // Marquer comme envoyé
            $session->update(['notification_1h_sent' => true]);
            
            $this->info("Rappel 1h envoyé pour la session {$session->id}");
        }

        // Rappels 5 minutes avant
        $sessionsIn5Minutes = MentorSession::where('status', 'confirmee')
            ->where('notification_5m_sent', false)
            ->whereBetween('scheduled_at', [
                $now->copy()->addMinutes(3), // 3-7 minutes avant
                $now->copy()->addMinutes(7)
            ])
            ->with(['mentor', 'mentee'])
            ->get();

        foreach ($sessionsIn5Minutes as $session) {
            // Notifier le mentor
            $session->mentor->notify(new SessionReminder($session, '5m'));
            
            // Notifier le mentee
            $session->mentee->notify(new SessionReminder($session, '5m'));
            
            // Marquer comme envoyé
            $session->update(['notification_5m_sent' => true]);
            
            $this->info("Rappel 5m envoyé pour la session {$session->id}");
        }

        $totalSent = $sessionsIn1Hour->count() + $sessionsIn5Minutes->count();
        $this->info("Total des rappels envoyés : {$totalSent}");
        
        return 0;
    }
}