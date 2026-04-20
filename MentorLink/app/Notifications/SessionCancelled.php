<?php

namespace App\Notifications;

use App\Models\MentorSession;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class SessionCancelled extends Notification implements ShouldQueue
{
    use Queueable;

    protected $session;
    protected $cancelledBy;
    protected $isLateCancellation;

    public function __construct(MentorSession $session, $cancelledBy, $isLateCancellation = false)
    {
        $this->session = $session;
        $this->cancelledBy = $cancelledBy;
        $this->isLateCancellation = $isLateCancellation;
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
        $mail = (new MailMessage)
            ->subject('Session de mentorat annulée')
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line('Nous vous informons que votre session de mentorat a été annulée.')
            ->line('Détails de la session :')
            ->line('• Date : ' . $this->session->scheduled_at->format('d/m/Y à H:i'))
            ->line('• Durée : ' . $this->session->duration_min . ' minutes')
            ->line('• Annulée par : ' . $this->cancelledBy);

        if ($this->isLateCancellation) {
            $mail->line('⚠️ Cette annulation a eu lieu moins de 15 minutes avant le début de la session.');
            if ($this->cancelledBy === 'mentor') {
                $mail->line('Une pénalité de 0.5 étoile a été appliquée au mentor pour cette annulation tardive.');
            }
        }

        $mail->line('Vous pouvez rechercher un autre mentor ou reprogrammer une session.')
            ->action('Voir mes sessions', route('sessions.index'))
            ->line('Nous nous excusons pour ce désagrément.');

        return $mail;
    }

    /**
     * Get the broadcastable representation of the notification.
     */
    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'title' => 'Session annulée',
            'message' => 'Votre session du ' . $this->session->scheduled_at->format('d/m/Y à H:i') . ' a été annulée',
            'session_id' => $this->session->id,
            'cancelled_by' => $this->cancelledBy,
            'is_late_cancellation' => $this->isLateCancellation,
            'type' => 'session_cancelled'
        ]);
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Session annulée',
            'message' => 'Votre session du ' . $this->session->scheduled_at->format('d/m/Y à H:i') . ' a été annulée par ' . $this->cancelledBy,
            'session_id' => $this->session->id,
            'cancelled_by' => $this->cancelledBy,
            'is_late_cancellation' => $this->isLateCancellation,
            'type' => 'session_cancelled'
        ];
    }
}