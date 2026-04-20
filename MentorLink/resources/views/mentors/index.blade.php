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
        <a href="{{ route('dashboard') }}">← Retour au dashboard</a>
    </nav>
    
    <!-- Filtre par domaine -->
    <form method="GET">
        <label>Filtrer par domaine:</label>
        <select name="domain" onchange="this.form.submit()">
            <option value="">Tous les domaines</option>
            <option value="web" {{ request('domain') === 'web' ? 'selected' : '' }}>Développement Web</option>
            <option value="mobile" {{ request('domain') === 'mobile' ? 'selected' : '' }}>Mobile</option>
            <option value="data" {{ request('domain') === 'data' ? 'selected' : '' }}>Data Science</option>
            <option value="devops" {{ request('domain') === 'devops' ? 'selected' : '' }}>DevOps</option>
        </select>
    </form>
    
    @if($mentors->count() > 0)
        <div>
            @foreach($mentors as $mentor)
                <div style="border: 1px solid #ccc; margin: 10px; padding: 15px;">
                    <h3>{{ $mentor->name }}</h3>
                    <p>Email: {{ $mentor->email }}</p>
                    
                    @if($mentor->mentorProfile)
                        <p><strong>Domaines:</strong> {{ implode(', ', $mentor->mentorProfile->domains ?? []) }}</p>
                        <p><strong>Tarif:</strong> {{ $mentor->mentorProfile->hourly_rate }}€/h</p>
                        <p><strong>Statut:</strong> 
                            <span style="color: {{ $mentor->mentorProfile->is_validated ? 'green' : 'orange' }}">
                                {{ $mentor->mentorProfile->is_validated ? 'Validé' : 'En attente' }}
                            </span>
                        </p>
                    @endif
                    
                    <a href="{{ route('mentors.show', $mentor->id) }}">Voir le profil détaillé</a>
                </div>
            @endforeach
        </div>
        
        <!-- Pagination -->
        {{ $mentors->links() }}
    @else
        <p>Aucun mentor trouvé pour ce domaine.</p>
    @endif
</body>
</html>