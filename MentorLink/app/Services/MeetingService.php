<?php

namespace App\Services;

use App\Models\Session;
use Illuminate\Support\Str;

class MeetingService
{
    /**
     * Serveur Jitsi public (peut être remplacé par un serveur auto-hébergé via JITSI_SERVER dans .env)
     */
    public function getServerUrl(): string
    {
        return rtrim(config('meeting.jitsi_server', 'https://meet.jit.si'), '/');
    }

    /**
     * Génère un identifiant de salle unique et sécurisé pour une session.
     * Format : mentorlink-{slug-mentor}-{slug-mentee}-{token}
     */
    public function generateRoomId(Session $session): string
    {
        $mentorSlug = Str::slug($session->mentor->name, '-');
        $menteeSlug = Str::slug($session->mentee->name, '-');
        $token      = Str::random(12);

        return "mentorlink-{$mentorSlug}-{$menteeSlug}-{$token}";
    }

    /**
     * Retourne l'URL complète de la salle Jitsi pour une session.
     */
    public function getRoomUrl(Session $session): ?string
    {
        if (! $session->meeting_room_id) {
            return null;
        }

        return $this->getServerUrl() . '/' . $session->meeting_room_id;
    }

    /**
     * Vérifie si l'utilisateur donné est autorisé à rejoindre la réunion.
     * Seuls le mentor et le mentee de la session peuvent accéder.
     */
    public function canJoin(Session $session, int $userId): bool
    {
        return $session->isConfirmed()
            && $session->meeting_room_id !== null
            && in_array($userId, [$session->mentor_id, $session->mentee_id]);
    }
}
