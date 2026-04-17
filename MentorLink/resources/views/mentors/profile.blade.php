<!DOCTYPE html>
<html>
<head>
    <title>Mon profil mentor - MentorLink</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
    <h1>Mon profil mentor</h1>
    
    <nav>
        <a href="{{ route('dashboard') }}">← Retour au dashboard</a>
    </nav>
    
    @if(session('success'))
        <div style="color: green; margin: 10px 0;">{{ session('success') }}</div>
    @endif
    
    @if($errors->any())
        <div style="color: red; margin: 10px 0;">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <form method="POST" action="{{ route('mentor.profile.update') }}">
        @csrf
        
        <div style="margin: 15px 0;">
            <label><strong>Domaines d'expertise:</strong></label><br>
            <input type="checkbox" name="domains[]" value="web" 
                {{ in_array('web', old('domains', $user->mentorProfile->domains ?? [])) ? 'checked' : '' }}>
            Développement Web<br>
            
            <input type="checkbox" name="domains[]" value="mobile" 
                {{ in_array('mobile', old('domains', $user->mentorProfile->domains ?? [])) ? 'checked' : '' }}>
            Développement Mobile<br>
            
            <input type="checkbox" name="domains[]" value="data" 
                {{ in_array('data', old('domains', $user->mentorProfile->domains ?? [])) ? 'checked' : '' }}>
            Data Science<br>
            
            <input type="checkbox" name="domains[]" value="devops" 
                {{ in_array('devops', old('domains', $user->mentorProfile->domains ?? [])) ? 'checked' : '' }}>
            DevOps<br>
            
            <input type="checkbox" name="domains[]" value="design" 
                {{ in_array('design', old('domains', $user->mentorProfile->domains ?? [])) ? 'checked' : '' }}>
            Design
        </div>
        
        <div style="margin: 15px 0;">
            <label><strong>Tarif horaire (€):</strong></label><br>
            <input type="number" name="hourly_rate" step="0.01" min="0" 
                value="{{ old('hourly_rate', $user->mentorProfile->hourly_rate ?? '') }}" required>
        </div>
        
        <button type="submit">Mettre à jour le profil</button>
    </form>
    
    @if($user->mentorProfile)
        <div style="margin-top: 30px; padding: 15px; border: 1px solid #ccc;">
            <h3>Statut actuel</h3>
            <p><strong>Validation:</strong> 
                <span style="color: {{ $user->mentorProfile->is_validated ? 'green' : 'orange' }}">
                    {{ $user->mentorProfile->is_validated ? 'Profil validé' : 'En attente de validation admin' }}
                </span>
            </p>
        </div>
    @endif
    
    <div style="margin-top: 30px;">
        <h3>Mes disponibilités</h3>
        <a href="{{ route('availabilities.create') }}">Ajouter une disponibilité</a>
        
        @if($user->availabilities->count() > 0)
            @php
                $days = ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'];
            @endphp
            
            @foreach($user->availabilities as $availability)
                <div style="margin: 10px 0; padding: 10px; border: 1px solid #ddd;">
                    <strong>{{ $days[$availability->day_of_week] }}:</strong> 
                    {{ $availability->start_time }} - {{ $availability->end_time }}
                    
                    <form method="POST" action="{{ route('availabilities.destroy', $availability) }}" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Supprimer cette disponibilité ?')" 
                                style="color: red; background: none; border: none; cursor: pointer;">
                            Supprimer
                        </button>
                    </form>
                </div>
            @endforeach
        @else
            <p>Aucune disponibilité renseignée.</p>
        @endif
    </div>
</body>
</html>