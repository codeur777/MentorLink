<?php

namespace App\Notifications;

use App\Models\MentorSession;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class SessionCompleted extends Notification
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
            ->subject('Session terminée - MentorLink')
            ->greeting('Session terminée !')
            ->line('Votre session de mentorat avec ' . $this->session->mentor->name . ' est maintenant terminée.')
            ->line('**Date :** ' . $this->session->scheduled_at->format('d/m/Y à H:i'))
            ->line('**Durée :** ' . $this->session->duration_min . ' minutes')
            ->line('Nous espérons que cette session vous a été utile !')
            ->action('Laisser un avis', route('reviews.create', $this->session))
            ->line('Votre avis nous aide à améliorer la qualité du mentorat sur notre plateforme.');
    }

    /**
     * Get the broadcast representation of the notification.
     */
    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'title' => 'Session terminée',
            'message' => 'Votre session avec ' . $this->session->mentor->name . ' est terminée. Vous pouvez maintenant laisser un avis.',
            'type' => 'success',
            'session_id' => $this->session->id,
            'mentor_name' => $this->session->mentor->name,
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
            'title' => 'Session terminée',
            'message' => 'Votre session avec ' . $this->session->mentor->name . ' est terminée. Vous pouvez maintenant laisser un avis.',
            'type' => 'success',
            'session_id' => $this->session->id,
            'mentor_name' => $this->session->mentor->name,
            'scheduled_at' => $this->session->scheduled_at->format('d/m/Y à H:i'),
            'duration' => $this->session->duration_min,
            'created_at' => now()->toISOString(),
        ];
    }
}