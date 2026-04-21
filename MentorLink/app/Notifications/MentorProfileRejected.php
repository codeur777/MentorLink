<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class MentorProfileRejected extends Notification
{
    use Queueable;

    protected $mentor;
    protected $reason;

    /**
     * Create a new notification instance.
     */
    public function __construct(User $mentor, $reason = null)
    {
        $this->mentor = $mentor;
        $this->reason = $reason;
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
        $mail = (new MailMessage)
            ->subject('Profil mentor non validé - MentorLink')
            ->greeting('Concernant votre demande de profil mentor')
            ->line('Nous avons examiné votre demande de profil mentor, mais nous ne pouvons pas la valider pour le moment.');
        
        if ($this->reason) {
            $mail->line('**Raison :** ' . $this->reason);
        }
        
        return $mail
            ->line('Vous pouvez soumettre une nouvelle demande en mettant à jour vos informations.')
            ->action('Créer un nouveau profil', route('mentors.create'))
            ->line('N\'hésitez pas à nous contacter si vous avez des questions.');
    }

    /**
     * Get the broadcast representation of the notification.
     */
    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'title' => 'Profil mentor non validé',
            'message' => 'Votre demande de profil mentor n\'a pas pu être validée. Vous pouvez soumettre une nouvelle demande.',
            'type' => 'warning',
            'mentor_name' => $this->mentor->name,
            'reason' => $this->reason,
            'created_at' => now()->toISOString(),
        ]);
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Profil mentor non validé',
            'message' => 'Votre demande de profil mentor n\'a pas pu être validée. Vous pouvez soumettre une nouvelle demande.',
            'type' => 'warning',
            'mentor_name' => $this->mentor->name,
            'reason' => $this->reason,
            'created_at' => now()->toISOString(),
        ];
    }
}