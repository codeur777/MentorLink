<?php

namespace App\Notifications;

use App\Models\MentorSession;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class SessionConfirmed extends Notification implements ShouldQueue
{
    use Queueable;

    protected $session;

    public function __construct(MentorSession $session)
    {
        $this->session = $session;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['database', 'broadcast', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Session de mentorat confirmée !')
            ->greeting('Excellente nouvelle !')
            ->line('Votre session de mentorat a été confirmée par le mentor.')
            ->line('Détails de la session :')
            ->line('• Date : ' . $this->session->scheduled_at->format('d/m/Y à H:i'))
            ->line('• Durée : ' . $this->session->duration_min . ' minutes')
            ->line('• Mentor : ' . $this->session->mentor->name)
            ->line('Un lien de réunion Google Meet a été généré pour cette session.')
            ->action('Voir la session', route('sessions.show', $this->session))
            ->line('Vous pourrez rejoindre la réunion 10 minutes avant le début de la session.')
            ->line('Merci d\'utiliser MentorLink !');
    }

    /**
     * Get the broadcastable representation of the notification.
     */
    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'title' => 'Session confirmée !',
            'message' => 'Votre session avec ' . $this->session->mentor->name . ' a été confirmée',
            'session_id' => $this->session->id,
            'scheduled_at' => $this->session->scheduled_at->toISOString(),
            'meeting_link' => $this->session->meeting_link,
            'type' => 'session_confirmed'
        ]);
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Session confirmée !',
            'message' => 'Votre session avec ' . $this->session->mentor->name . ' a été confirmée',
            'session_id' => $this->session->id,
            'scheduled_at' => $this->session->scheduled_at->toISOString(),
            'meeting_link' => $this->session->meeting_link,
            'type' => 'session_confirmed'
        ];
    }
}