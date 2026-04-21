<?php

namespace App\Notifications;

use App\Models\MentorSession;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class SessionRequested extends Notification
{
    use Queueable;

    protected $session;

    /**
     * Create a new notification instance.
     */
    public function __construct(MentorSession $session)
    {
        $this->session = $session;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'broadcast', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Nouvelle demande de session - MentorLink')
            ->greeting('Nouvelle demande de session !')
            ->line('Vous avez reçu une nouvelle demande de session de mentorat.')
            ->line('**Mentee :** ' . $this->session->mentee->name)
            ->line('**Date :** ' . $this->session->scheduled_at->format('d/m/Y à H:i'))
            ->line('**Durée :** ' . $this->session->duration_min . ' minutes')
            ->when($this->session->session_notes, function ($mail) {
                return $mail->line('**Notes :** ' . $this->session->session_notes);
            })
            ->action('Voir la demande', route('sessions.show', $this->session))
            ->line('Vous pouvez confirmer ou gérer cette demande depuis votre dashboard.');
    }

    /**
     * Get the broadcast representation of the notification.
     */
    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'title' => 'Nouvelle demande de session',
            'message' => $this->session->mentee->name . ' souhaite réserver une session le ' . $this->session->scheduled_at->format('d/m/Y à H:i'),
            'type' => 'info',
            'session_id' => $this->session->id,
            'mentee_name' => $this->session->mentee->name,
            'scheduled_at' => $this->session->scheduled_at->format('d/m/Y à H:i'),
            'duration' => $this->session->duration_min,
            'created_at' => now()->toISOString(),
        ]);
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Nouvelle demande de session',
            'message' => $this->session->mentee->name . ' souhaite réserver une session le ' . $this->session->scheduled_at->format('d/m/Y à H:i'),
            'type' => 'info',
            'session_id' => $this->session->id,
            'mentee_name' => $this->session->mentee->name,
            'scheduled_at' => $this->session->scheduled_at->format('d/m/Y à H:i'),
            'duration' => $this->session->duration_min,
            'created_at' => now()->toISOString(),
        ];
    }
}