<!DOCTYPE html>
<html>
<head>
    <title>Liste des mentors - MentorLink</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
    <h1>Liste des mentors</h1>

    <nav>
        <a href="{{ route('dashboard') }}">← Dashboard</a>
    </nav>

    <form method="GET" style="margin: 15px 0;">
        <label>Filtrer par domaine :</label>
        <select name="domain" onchange="this.form.submit()">
            <option value="">Tous les domaines</option>
            @foreach(['web','mobile','data','devops','design','python','javascript','php','machine-learning'] as $d)
                <option value="{{ $d }}" {{ request('domain') === $d ? 'selected' : '' }}>{{ ucfirst($d) }}</option>
            @endforeach
        </select>
    </form>

    @if($mentors->count() > 0)
        @foreach($mentors as $mentor)
            <div style="border: 1px solid #ccc; margin: 10px 0; padding: 15px;">
                <h3>{{ $mentor->name }}</h3>

                @if($mentor->mentorProfile)
                    <p><strong>Domaines :</strong> {{ implode(', ', $mentor->mentorProfile->domains ?? []) }}</p>
                    <p><strong>Tarif :</strong> {{ $mentor->mentorProfile->hourly_rate }}€/h</p>
                    @if($mentor->mentorProfile->average_rating)
                        <p><strong>Note :</strong> {{ $mentor->mentorProfile->average_rating }}/5
                            ({{ $mentor->mentorProfile->review_count }} avis)</p>
                    @endif
                @endif

                <a href="{{ route('mentors.show', $mentor->id) }}">Voir le profil</a>

                @if(auth()->user()->isMentee())
                    &nbsp;|&nbsp;
                    <a href="{{ route('sessions.create', ['mentor_id' => $mentor->id]) }}">Réserver</a>
                @endif
            </div>
        @endforeach

        {{ $mentors->links() }}
    @else
        <p>Aucun mentor trouvé.</p>
    @endif
</body>
</html>
