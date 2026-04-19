<!DOCTYPE html>
<html>
<head>
    <title>Reserver une session - MentorLink</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
    <h1>Reserver une session avec {{ $mentor->name }}</h1>

    <nav>
        <a href="{{ route('mentors.show', $mentor->id) }}">← Retour au profil</a>
    </nav>

    @if($errors->any())
        <div style="color: red; margin: 10px 0;">
            <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    @if($mentor->mentorProfile)
        <p>
            <strong>Domaines :</strong> {{ implode(', ', $mentor->mentorProfile->domains ?? []) }}
            &nbsp;|&nbsp;
            <strong>Tarif :</strong> {{ $mentor->mentorProfile->hourly_rate }}€/h
        </p>
    @endif

    {{-- Week navigation --}}
    @php
        $weekLabel = \Carbon\Carbon::parse($weekStart)->startOfWeek(\Carbon\Carbon::MONDAY)->format('d/m/Y')
            . ' – '
            . \Carbon\Carbon::parse($weekStart)->endOfWeek(\Carbon\Carbon::SUNDAY)->format('d/m/Y');
        $days = ['Dimanche','Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi'];
    @endphp

    <div style="margin: 15px 0;">
        <a href="{{ route('sessions.create', ['mentor_id' => $mentor->id, 'week' => $prevWeek]) }}">← Semaine precedente</a>
        &nbsp;&nbsp;
        <strong>Semaine du {{ $weekLabel }}</strong>
        &nbsp;&nbsp;
        <a href="{{ route('sessions.create', ['mentor_id' => $mentor->id, 'week' => $nextWeek]) }}">Semaine suivante →</a>
    </div>

    {{-- Available slots --}}
    @if(count($slots) > 0)
        <h3>Creneaux disponibles</h3>
        <table border="1" cellpadding="8" cellspacing="0">
            <thead>
                <tr>
                    <th>Jour</th>
                    <th>Date</th>
                    <th>Debut</th>
                    <th>Fin</th>
                    <th>Statut</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($slots as $slot)
                    <tr style="{{ $slot['booked'] ? 'background:#f8d7da;' : 'background:#d4edda;' }}">
                        <td>{{ $days[$slot['day_of_week']] }}</td>
                        <td>{{ \Carbon\Carbon::parse($slot['date'])->format('d/m/Y') }}</td>
                        <td>{{ $slot['start_time'] }}</td>
                        <td>{{ $slot['end_time'] }}</td>
                        <td>
                            @if($slot['booked'])
                                <em style="color:red;">Indisponible</em>
                            @else
                                <span style="color:green;">Disponible</span>
                            @endif
                        </td>
                        <td>
                            @if(! $slot['booked'] && \Carbon\Carbon::parse($slot['date'])->isFuture())
                                <button type="button"
                                    onclick="fillForm('{{ $slot['date'] }}','{{ $slot['start_time'] }}','{{ $slot['end_time'] }}')"
                                    style="cursor:pointer;">
                                    Selectionner
                                </button>
                            @else
                                —
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p><em>Aucune disponibilite definie pour cette semaine.</em></p>
    @endif

    {{-- Booking form --}}
    <h3>Confirmer la reservation</h3>
    <form method="POST" action="{{ route('sessions.store') }}" id="bookingForm">
        @csrf
        <input type="hidden" name="mentor_id" value="{{ $mentor->id }}">

        <div style="margin: 10px 0;">
            <label><strong>Date :</strong></label><br>
            <input type="date" name="date" id="field_date"
                   value="{{ old('date') }}" min="{{ date('Y-m-d') }}" required>
        </div>

        <div style="margin: 10px 0;">
            <label><strong>Heure de debut :</strong></label><br>
            <input type="time" name="start_time" id="field_start"
                   value="{{ old('start_time') }}" required>
        </div>

        <div style="margin: 10px 0;">
            <label><strong>Heure de fin :</strong></label><br>
            <input type="time" name="end_time" id="field_end"
                   value="{{ old('end_time') }}" required>
        </div>

        <div style="margin: 10px 0;">
            <label><strong>Note (optionnel) :</strong></label><br>
            <textarea name="note" rows="3" maxlength="500" style="width:400px;">{{ old('note') }}</textarea>
        </div>

        <button type="submit">Envoyer la demande</button>
    </form>

    <script>
        function fillForm(date, start, end) {
            document.getElementById('field_date').value  = date;
            document.getElementById('field_start').value = start;
            document.getElementById('field_end').value   = end;
            document.getElementById('bookingForm').scrollIntoView({behavior:'smooth'});
        }
    </script>
</body>
</html>
