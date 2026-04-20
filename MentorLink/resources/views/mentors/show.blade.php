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
        <a href="{{ route('mentors.index') }}">← Retour à la liste</a>
    </nav>
    
    <div style="border: 1px solid #ccc; padding: 20px; margin: 20px 0;">
        <h2>Informations générales</h2>
        <p><strong>Nom:</strong> {{ $mentor->name }}</p>
        <p><strong>Email:</strong> {{ $mentor->email }}</p>
        @if($mentor->bio)
            <p><strong>Bio:</strong> {{ $mentor->bio }}</p>
        @endif
    </div>
    
    @if($mentor->mentorProfile)
        <div style="border: 1px solid #ccc; padding: 20px; margin: 20px 0;">
            <h2>Profil mentor</h2>
            <p><strong>Domaines d'expertise:</strong> {{ implode(', ', $mentor->mentorProfile->domains ?? []) }}</p>
            <p><strong>Tarif horaire:</strong> {{ $mentor->mentorProfile->hourly_rate }}€/h</p>
            <p><strong>Statut:</strong> 
                <span style="color: {{ $mentor->mentorProfile->is_validated ? 'green' : 'orange' }}">
                    {{ $mentor->mentorProfile->is_validated ? 'Validé' : 'En attente de validation' }}
                </span>
            </p>
        </div>
    @endif
    
    @if($mentor->availabilities->count() > 0)
        <div style="border: 1px solid #ccc; padding: 20px; margin: 20px 0;">
            <h2>Disponibilités</h2>
            @php
                $days = ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'];
            @endphp
            
            @foreach($mentor->availabilities as $availability)
                <p>
                    <strong>{{ $days[$availability->day_of_week] }}:</strong> 
                    {{ $availability->start_time }} - {{ $availability->end_time }}
                </p>
            @endforeach
            
            <a href="{{ route('availabilities.index', $mentor->id) }}">Voir toutes les disponibilités</a>
        </div>
    @else
        <p>Aucune disponibilité renseignée.</p>
    @endif
</body>
</html>