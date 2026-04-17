<!DOCTYPE html>
<html>
<head>
    <title>Liste des mentors - MentorLink</title>
    <meta charset="utf-8">
</head>
<body>
    <h1>Liste des mentors</h1>
    
    <div>
        <a href="{{ route('dashboard') }}">← Retour au dashboard</a>
    </div>
    
    @if($mentors->count() > 0)
        <div>
            @foreach($mentors as $mentor)
                <div style="border: 1px solid #ccc; margin: 10px; padding: 10px;">
                    <h3>{{ $mentor->name }}</h3>
                    <p>Email: {{ $mentor->email }}</p>
                    @if($mentor->mentorProfile)
                        <p>Domaines: {{ implode(', ', $mentor->mentorProfile->domains ?? []) }}</p>
                        <p>Tarif: {{ $mentor->mentorProfile->hourly_rate }}€/h</p>
                        <p>Statut: {{ $mentor->mentorProfile->is_validated ? 'Validé' : 'En attente' }}</p>
                    @endif
                    <a href="{{ route('mentors.show', $mentor->id) }}">Voir le profil</a>
                </div>
            @endforeach
        </div>
        
        {{ $mentors->links() }}
    @else
        <p>Aucun mentor trouvé.</p>
    @endif
</body>
</html>