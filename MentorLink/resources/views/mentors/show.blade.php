<!DOCTYPE html>
<html>
<head>
    <title>{{ $mentor->name }} - MentorLink</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
    <h1>Profil de {{ $mentor->name }}</h1>

    <nav>
        <a href="{{ route('mentors.index') }}">← Liste des mentors</a>
    </nav>

    {{-- General info --}}
    <div style="border:1px solid #ccc; padding:15px; margin:15px 0;">
        <p><strong>Nom :</strong> {{ $mentor->name }}</p>
        <p><strong>Email :</strong> {{ $mentor->email }}</p>
        @if($mentor->bio)
            <p><strong>Bio :</strong> {{ $mentor->bio }}</p>
        @endif
    </div>

    {{-- Mentor profile --}}
    @if($mentor->mentorProfile)
        <div style="border:1px solid #ccc; padding:15px; margin:15px 0;">
            <h2>Profil mentor</h2>
            <p><strong>Domaines :</strong> {{ implode(', ', $mentor->mentorProfile->domains ?? []) }}</p>
            <p><strong>Tarif :</strong> {{ $mentor->mentorProfile->hourly_rate }}€/h</p>
            @if($mentor->mentorProfile->average_rating)
                <p>
                    <strong>Note moyenne :</strong>
                    {{ $mentor->mentorProfile->average_rating }}/5
                    ({{ $mentor->mentorProfile->review_count }} avis)
                </p>
            @else
                <p><em>Pas encore d'avis.</em></p>
            @endif
        </div>
    @endif

    {{-- Availabilities --}}
    @php $days = ['Dimanche','Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi']; @endphp
    @if($mentor->availabilities->count() > 0)
        <div style="border:1px solid #ccc; padding:15px; margin:15px 0;">
            <h2>Disponibilites hebdomadaires</h2>
            @foreach($mentor->availabilities->sortBy('day_of_week') as $a)
                <p>{{ $days[$a->day_of_week] }} : {{ $a->start_time }} – {{ $a->end_time }}</p>
            @endforeach
        </div>
    @else
        <p><em>Aucune disponibilite renseignee.</em></p>
    @endif

    {{-- Book button (mentee only) --}}
    @if(auth()->user()->isMentee() && $mentor->mentorProfile?->is_validated)
        <p>
            <a href="{{ route('sessions.create', ['mentor_id' => $mentor->id]) }}"
               style="padding:8px 16px; background:#007bff; color:white; text-decoration:none;">
                Reserver une session
            </a>
        </p>
    @endif

    {{-- Reviews --}}
    <div style="border:1px solid #ccc; padding:15px; margin:15px 0;">
        <h2>Avis ({{ $reviews->count() }})</h2>

        @forelse($reviews as $review)
            <div style="border-bottom:1px solid #eee; padding:10px 0;">
                <p>
                    <strong>Note :</strong>
                    {{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}
                    ({{ $review->rating }}/5)
                    &nbsp;|&nbsp;
                    <small>{{ $review->created_at->format('d/m/Y') }}</small>
                </p>
                @if($review->comment)
                    <p>{{ $review->comment }}</p>
                @endif
            </div>
        @empty
            <p><em>Aucun avis pour ce mentor.</em></p>
        @endforelse
    </div>
</body>
</html>
