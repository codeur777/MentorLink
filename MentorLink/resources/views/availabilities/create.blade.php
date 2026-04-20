<!DOCTYPE html>
<html>
<head>
    <title>Ajouter une disponibilité - MentorLink</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
    <h1>Ajouter une disponibilité</h1>
    
    <nav>
        <a href="{{ route('mentor.profile') }}">← Retour au profil</a>
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
    
    <form method="POST" action="{{ route('availabilities.store') }}">
        @csrf
        
        <div style="margin: 15px 0;">
            <label><strong>Jour de la semaine:</strong></label><br>
            <select name="day_of_week" required>
                <option value="">Choisir un jour</option>
                <option value="1" {{ old('day_of_week') == '1' ? 'selected' : '' }}>Lundi</option>
                <option value="2" {{ old('day_of_week') == '2' ? 'selected' : '' }}>Mardi</option>
                <option value="3" {{ old('day_of_week') == '3' ? 'selected' : '' }}>Mercredi</option>
                <option value="4" {{ old('day_of_week') == '4' ? 'selected' : '' }}>Jeudi</option>
                <option value="5" {{ old('day_of_week') == '5' ? 'selected' : '' }}>Vendredi</option>
                <option value="6" {{ old('day_of_week') == '6' ? 'selected' : '' }}>Samedi</option>
                <option value="0" {{ old('day_of_week') == '0' ? 'selected' : '' }}>Dimanche</option>
            </select>
        </div>
        
        <div style="margin: 15px 0;">
            <label><strong>Heure de début:</strong></label><br>
            <input type="time" name="start_time" value="{{ old('start_time') }}" required>
        </div>
        
        <div style="margin: 15px 0;">
            <label><strong>Heure de fin:</strong></label><br>
            <input type="time" name="end_time" value="{{ old('end_time') }}" required>
        </div>
        
        <button type="submit">Ajouter la disponibilité</button>
    </form>
    
    <div style="margin-top: 30px; padding: 15px; border: 1px solid #ddd; background-color: #f8f9fa;">
        <h3>ℹ️ Information</h3>
        <p>Les disponibilités sont récurrentes chaque semaine. Par exemple, si vous ajoutez "Lundi 09:00-12:00", vous serez disponible tous les lundis de 9h à 12h.</p>
    </div>
</body>
</html>