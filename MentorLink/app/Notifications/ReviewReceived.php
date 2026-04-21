<?php

namespace App\Notifications;

use App\Models\Review;
use App\Models\MentorSession;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class ReviewReceived extends Notification
{
    use Queueable;

    protected $review;
    protected $session;

    /**
     * Create a new notification instance.
     */
    public function __construct(Review $review, MentorSession $session)
    {
        $this->review = $review;
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
        $stars = str_repeat('⭐', $this->review->rating);
        
        return (new MailMessage)
            ->subject('Nouvel avis reçu - MentorLink')
            ->greeting('Nouvel avis reçu !')
            ->line('Vous avez reçu un nouvel avis de la part de ' . $this->session->mentee->name . '.')
            ->line('**Note :** ' . $stars . ' (' . $this->review->rating . '/5)')
            ->line('**Commentaire :** "' . $this->review->comment . '"')
            ->line('**Session du :** ' . $this->session->scheduled_at->format('d/m/Y à H:i'))
            ->action('Voir mes avis', route('dashboard'))
            ->line('Continuez votre excellent travail de mentorat !');
    }

    /**
     * Get the broadcast representation of the notification.
     */
    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'title' => 'Nouvel avis reçu',
            'message' => $this->session->mentee->name . ' vous a laissé un avis de ' . $this->review->rating . '/5 étoiles.',
            'type' => 'success',
            'review_id' => $this->review->id,
            'session_id' => $this->session->id,
            'mentee_name' => $this->session->mentee->name,
            'rating' => $this->review->rating,
            'comment' => $this->review->comment,
            'created_at' => now()->toISOString(),
        ]);
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Nouvel avis reçu',
            'message' => $this->session->mentee->name . ' vous a laissé un avis de ' . $this->review->rating . '/5 étoiles.',
            'type' => 'success',
            'review_id' => $this->review->id,
            'session_id' => $this->session->id,
            'mentee_name' => $this->session->mentee->name,
            'rating' => $this->review->rating,
            'comment' => $this->review->comment,
            'created_at' => now()->toISOString(),
        ];
    }
}