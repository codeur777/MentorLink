<!DOCTYPE html>
<html>
<head>
    <title>Mes sessions - MentorLink</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
    <h1>Mes sessions</h1>

    <nav>
        <a href="{{ route('dashboard') }}">← Dashboard</a>
        @if(auth()->user()->isMentee())
            | <a href="{{ route('mentors.index') }}">Réserver une session</a>
        @endif
    </nav>

    @if(session('success'))
        <div style="color: green; margin: 10px 0;">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div style="color: red; margin: 10px 0;">{{ session('error') }}</div>
    @endif

    @if($sessions->count() > 0)
        @foreach($sessions as $session)
            @php
                $statusColors = [
                    'pending'   => '#856404',
                    'confirmed' => '#155724',
                    'completed' => '#0c5460',
                    'cancelled' => '#721c24',
                ];
                $statusLabels = [
                    'pending'   => 'En attente',
                    'confirmed' => 'Confirmée',
                    'completed' => 'Terminée',
                    'cancelled' => 'Annulée',
                ];
            @endphp
            <div style="border: 1px solid #ccc; margin: 15px 0; padding: 15px;">
                <p>
                    <strong>Date :</strong> {{ $session->date->format('d/m/Y') }}
                    &nbsp;|&nbsp;
                    <strong>Horaire :</strong> {{ $session->start_time }} – {{ $session->end_time }}
                </p>

                @if($user->isMentee())
                    <p><strong>Mentor :</strong> {{ $session->mentor->name }}</p>
                @else
                    <p><strong>Mentoré :</strong> {{ $session->mentee->name }}</p>
                @endif

                @if($session->note)
                    <p><strong>Note :</strong> {{ $session->note }}</p>
                @endif

                <p>
                    <strong>Statut :</strong>
                    <span style="color: {{ $statusColors[$session->status] ?? '#000' }}; font-weight: bold;">
                        {{ $statusLabels[$session->status] ?? $session->status }}
                    </span>
                </p>

                {{-- Mentor actions --}}
                @if($user->isMentor())
                    @if($session->isPending())
                        <form method="POST" action="{{ route('sessions.confirm', $session) }}" style="display:inline;">
                            @csrf @method('PATCH')
                            <button type="submit">Confirmer</button>
                        </form>
                    @endif
                    @if($session->isConfirmed())
                        <form method="POST" action="{{ route('sessions.complete', $session) }}" style="display:inline;">
                            @csrf @method('PATCH')
                            <button type="submit">Marquer terminée</button>
                        </form>
                    @endif
                @endif

                {{-- Cancel (mentor or mentee) --}}
                @if(in_array($session->status, ['pending', 'confirmed']))
                    <form method="POST" action="{{ route('sessions.cancel', $session) }}" style="display:inline;">
                        @csrf @method('PATCH')
                        <button type="submit" onclick="return confirm('Annuler cette session ?')"
                                style="color: red;">Annuler</button>
                    </form>
                @endif

                {{-- Leave review (mentee, completed, no review yet) --}}
                @if($user->isMentee() && $session->isCompleted() && ! $session->review)
                    <a href="{{ route('reviews.create', $session) }}" style="margin-left: 10px;">
                        Laisser un avis
                    </a>
                @endif

                {{-- Show existing review --}}
                @if($session->review)
                    <div style="margin-top: 10px; padding: 8px; background: #f0f0f0;">
                        <strong>Avis :</strong>
                        {{ str_repeat('★', $session->review->rating) }}{{ str_repeat('☆', 5 - $session->review->rating) }}
                        @if($session->review->comment)
                            — {{ $session->review->comment }}
                        @endif
                    </div>
                @endif
            </div>
        @endforeach

        {{ $sessions->links() }}
    @else
        <p>Aucune session pour le moment.</p>
        @if($user->isMentee())
            <a href="{{ route('mentors.index') }}">Trouver un mentor</a>
        @endif
    @endif
</body>
</html>
