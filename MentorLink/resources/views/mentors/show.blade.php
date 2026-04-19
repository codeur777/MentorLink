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

    <div style="border: 1px solid #ccc; padding: 20px; margin: 20px 0;">
        <h2>Informations générales</h2>
        <p><strong>Nom :</strong> {{ $mentor->name }}</p>
        <p><strong>Email :</strong> {{ $mentor->email }}</p>
        @if($mentor->bio)
            <p><strong>Bio :</strong> {{ $mentor->bio }}</p>
        @endif
    </div>

    @if($mentor->mentorProfile)
        <div style="border: 1px solid #ccc; padding: 20px; margin: 20px 0;">
            <h2>Profil mentor</h2>
            <p><strong>Domaines :</strong> {{ implode(', ', $mentor->mentorProfile->domains ?? []) }}</p>
            <p><strong>Tarif horaire :</strong> {{ $mentor->mentorProfile->hourly_rate }}€/h</p>
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

    @if($mentor->availabilities->count() > 0)
        @php $days = ['Dimanche','Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi']; @endphp
        <div style="border: 1px solid #ccc; padding: 20px; margin: 20px 0;">
            <h2>Disponibilités</h2>
            @foreach($mentor->availabilities as $availability)
                <p>
                    <strong>{{ $days[$availability->day_of_week] }} :</strong>
                    {{ $availability->start_time }} – {{ $availability->end_time }}
                </p>
            @endforeach
        </div>
    @else
        <p>Aucune disponibilité renseignée.</p>
    @endif

    @if(auth()->user()->isMentee() && $mentor->mentorProfile?->is_validated)
        <div style="margin: 20px 0;">
            <a href="{{ route('sessions.create', ['mentor_id' => $mentor->id]) }}"
               style="padding: 10px 20px; background: #007bff; color: white; text-decoration: none;">
                Réserver une session
            </a>
        </div>
    @endif
</body>
</html>
