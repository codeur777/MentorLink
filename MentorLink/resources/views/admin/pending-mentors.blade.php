<!DOCTYPE html>
<html>
<head>
    <title>Mentors en attente - Admin MentorLink</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
    <h1>Mentors en attente de validation</h1>
    
    <nav>
        <a href="{{ route('admin.dashboard') }}">← Dashboard admin</a>
    </nav>
    
    @if(session('success'))
        <div style="color: green; margin: 10px 0;">{{ session('success') }}</div>
    @endif
    
    @if($profiles->count() > 0)
        @foreach($profiles as $profile)
            <div style="border: 1px solid #ccc; margin: 15px 0; padding: 20px; background-color: #fff3cd;">
                <h3>{{ $profile->user->name }}</h3>
                <p><strong>Email:</strong> {{ $profile->user->email }}</p>
                <p><strong>Domaines:</strong> {{ implode(', ', $profile->domains ?? []) }}</p>
                <p><strong>Tarif horaire:</strong> {{ $profile->hourly_rate }}€/h</p>
                <p><strong>Créé le:</strong> {{ $profile->created_at->format('d/m/Y H:i') }}</p>
                
                <div style="margin-top: 15px;">
                    <form method="POST" action="{{ route('admin.mentors.validate', $profile->user_id) }}" style="display: inline;">
                        @csrf
                        @method('PUT')
                        <button type="submit" 
                                style="background-color: #28a745; color: white; padding: 8px 15px; border: none; cursor: pointer;"
                                onclick="return confirm('Valider ce profil mentor ?')">
                            ✓ Valider
                        </button>
                    </form>
                    
                    <a href="{{ route('mentors.show', $profile->user_id) }}" 
                       style="margin-left: 10px; padding: 8px 15px; background-color: #007bff; color: white; text-decoration: none;">
                        Voir le profil détaillé
                    </a>
                </div>
            </div>
        @endforeach
        
        <!-- Pagination -->
        {{ $profiles->links() }}
    @else
        <div style="text-align: center; margin: 50px 0;">
            <h3>🎉 Aucun mentor en attente !</h3>
            <p>Tous les profils mentors ont été traités.</p>
            <a href="{{ route('admin.dashboard') }}">Retour au dashboard</a>
        </div>
    @endif
</body>
</html>