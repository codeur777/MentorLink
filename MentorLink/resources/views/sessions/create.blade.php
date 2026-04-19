<!DOCTYPE html>
<html>
<head>
    <title>Réserver une session - MentorLink</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
    <h1>Réserver une session avec {{ $mentor->name }}</h1>

    <nav>
        <a href="{{ route('mentors.show', $mentor->id) }}">← Retour au profil</a>
    </nav>

    @if($errors->any())
        <div style="color: red; margin: 10px 0;">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if($mentor->mentorProfile)
        <div style="margin: 10px 0; padding: 10px; border: 1px solid #ddd; background: #f9f9f9;">
            <strong>Domaines :</strong> {{ implode(', ', $mentor->mentorProfile->domains ?? []) }}
            &nbsp;|&nbsp;
            <strong>Tarif :</strong> {{ $mentor->mentorProfile->hourly_rate }}€/h
        </div>
    @endif

    @if($mentor->availabilities->count() > 0)
        @php $days = ['Dimanche','Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi']; @endphp
        <div style="margin: 10px 0; padding: 10px; border: 1px solid #ddd;">
            <strong>Disponibilités hebdomadaires :</strong>
            <ul>
                @foreach($mentor->availabilities as $slot)
                    <li>{{ $days[$slot->day_of_week] }} : {{ $slot->start_time }} – {{ $slot->end_time }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('sessions.store') }}">
        @csrf
        <input type="hidden" name="mentor_id" value="{{ $mentor->id }}">

        <div style="margin: 15px 0;">
            <label><strong>Date :</strong></label><br>
            <input type="date" name="date" value="{{ old('date') }}"
                   min="{{ date('Y-m-d') }}" required>
        </div>

        <div style="margin: 15px 0;">
            <label><strong>Heure de début :</strong></label><br>
            <input type="time" name="start_time" value="{{ old('start_time') }}" required>
        </div>

        <div style="margin: 15px 0;">
            <label><strong>Heure de fin :</strong></label><br>
            <input type="time" name="end_time" value="{{ old('end_time') }}" required>
        </div>

        <div style="margin: 15px 0;">
            <label><strong>Note (optionnel) :</strong></label><br>
            <textarea name="note" rows="3" maxlength="500"
                      style="width: 400px;">{{ old('note') }}</textarea>
        </div>

        <button type="submit">Envoyer la demande de réservation</button>
    </form>
</body>
</html>
