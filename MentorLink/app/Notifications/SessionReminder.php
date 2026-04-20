<?php

namespace App\Notifications;

use App\Models\MentorSession;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class SessionReminder extends Notification implements ShouldQueue
{
    use Queueable;

    protected $session;
    protected $timeBeforeSession;

    public function __construct(MentorSession $session, string $timeBeforeSession)
    {
        $this->session = $session;
        $this->timeBeforeSession = $timeBeforeSession;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $timeText = $this->timeBeforeSession === '1h' ? 'dans 1 heure' : 'dans 5 minutes';
        
        return (new MailMessage)
            ->subject('Rappel de session de mentorat')
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line('Votre session de mentorat commence ' . $timeText . '.')
            ->line('Détails de la session :')
            ->line('• Date : ' . $this->session->scheduled_at->format('d/m/Y à H:i'))
            ->line('• Durée : ' . $this->session->duration_min . ' minutes')
            ->line('• Avec : ' . ($notifiable->id === $this->session->mentor_id ? $this->session->mentee->name : $this->session->mentor->name))
            ->when($this->session->meeting_link, function ($mail) {
                return $mail->action('Rejoindre la session', $this->session->meeting_link);
            })
            ->line('Merci d\'utiliser MentorLink !');
    }

    /**
     * Get the broadcastable representation of the notification.
     */
    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        $timeText = $this->timeBeforeSession === '1h' ? 'dans 1 heure' : 'dans 5 minutes';
        
        return new BroadcastMessage([
            'title' => 'Session de mentorat',
            'message' => 'Votre session commence ' . $timeText,
            'session_id' => $this->session->id,
            'scheduled_at' => $this->session->scheduled_at->toISOString(),
            'meeting_link' => $this->session->meeting_link,
            'type' => 'session_reminder',
            'time_before' => $this->timeBeforeSession
        ]);
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        $timeText = $this->timeBeforeSession === '1h' ? 'dans 1 heure' : 'dans 5 minutes';
        
        return [
            'title' => 'Session de mentorat',
            'message' => 'Votre session commence ' . $timeText,
            'session_id' => $this->session->id,
            'scheduled_at' => $this->session->scheduled_at->toISOString(),
            'meeting_link' => $this->session->meeting_link,
            'type' => 'session_reminder',
            'time_before' => $this->timeBeforeSession
        ];
    }
}