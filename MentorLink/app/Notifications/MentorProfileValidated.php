<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class MentorProfileValidated extends Notification
{
    use Queueable;

    protected $mentor;

    /**
     * Create a new notification instance.
     */
    public function __construct(User $mentor)
    {
        $this->mentor = $mentor;
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
            ->subject('Profil mentor validé - MentorLink')
            ->greeting('Félicitations !')
            ->line('Votre profil mentor a été validé par notre équipe.')
            ->line('Vous pouvez maintenant recevoir des demandes de sessions de la part des mentees.')
            ->action('Accéder à mon dashboard', route('dashboard'))
            ->line('Merci de faire partie de la communauté MentorLink !');
    }

    /**
     * Get the broadcast representation of the notification.
     */
    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'title' => 'Profil validé !',
            'message' => 'Votre profil mentor a été validé. Vous pouvez maintenant recevoir des demandes de sessions.',
            'type' => 'success',
            'mentor_name' => $this->mentor->name,
            'created_at' => now()->toISOString(),
        ]);
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Profil mentor validé',
            'message' => 'Votre profil mentor a été validé par notre équipe. Vous pouvez maintenant recevoir des demandes de sessions.',
            'type' => 'success',
            'mentor_name' => $this->mentor->name,
            'created_at' => now()->toISOString(),
        ];
    }
}