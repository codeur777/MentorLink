<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Admin - MentorLink</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
    <h1>Dashboard Administrateur</h1>
    
    <nav>
        <a href="{{ route('dashboard') }}">← Dashboard principal</a> |
        <a href="{{ route('admin.stats') }}">Statistiques</a> |
        <a href="{{ route('admin.pending-mentors') }}">Mentors en attente</a>
    </nav>
    
    @if(session('success'))
        <div style="color: green; margin: 10px 0;">{{ session('success') }}</div>
    @endif
    
    <h2>Statistiques globales</h2>
    <div style="display: flex; gap: 20px; margin: 20px 0;">
        <div style="border: 1px solid #ccc; padding: 15px; min-width: 150px;">
            <h3>{{ $stats['total_users'] }}</h3>
            <p>Utilisateurs total</p>
        </div>
        
        <div style="border: 1px solid #ccc; padding: 15px; min-width: 150px;">
            <h3>{{ $stats['total_mentors'] }}</h3>
            <p>Mentors</p>
        </div>
        
        <div style="border: 1px solid #ccc; padding: 15px; min-width: 150px;">
            <h3>{{ $stats['total_mentees'] }}</h3>
            <p>Mentorés</p>
        </div>
        
        <div style="border: 1px solid #ccc; padding: 15px; min-width: 150px; background-color: #fff3cd;">
            <h3>{{ $stats['pending_mentors'] }}</h3>
            <p>Mentors en attente</p>
        </div>
        
        <div style="border: 1px solid #ccc; padding: 15px; min-width: 150px; background-color: #d4edda;">
            <h3>{{ $stats['validated_mentors'] }}</h3>
            <p>Mentors validés</p>
        </div>
    </div>
    
    <div style="margin-top: 30px;">
        <h3>Actions rapides</h3>
        <ul>
            <li><a href="{{ route('admin.pending-mentors') }}">Valider les profils mentors ({{ $stats['pending_mentors'] }} en attente)</a></li>
            <li><a href="{{ route('mentors.index') }}">Voir tous les mentors validés</a></li>
            <li><a href="{{ route('admin.stats') }}">Voir les statistiques détaillées</a></li>
        </ul>
    </div>
</body>
</html>