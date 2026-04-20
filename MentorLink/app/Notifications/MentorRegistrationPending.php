<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class MentorRegistrationPending extends Notification implements ShouldQueue
{
    use Queueable;

    protected $mentor;

    public function __construct(User $mentor)
    {
        $this->mentor = $mentor;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    /**
     * Get the broadcastable representation of the notification.
     */
    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'title' => 'Nouveau mentor à valider',
            'message' => $this->mentor->name . ' a soumis son profil de mentor pour validation',
            'mentor_id' => $this->mentor->id,
            'type' => 'mentor_validation'
        ]);
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Nouveau mentor à valider',
            'message' => $this->mentor->name . ' a soumis son profil de mentor pour validation',
            'mentor_id' => $this->mentor->id,
            'type' => 'mentor_validation'
        ];
    }
}