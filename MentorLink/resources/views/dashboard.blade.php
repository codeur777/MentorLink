<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - MentorLink</title>
    <meta charset="utf-8">
</head>
<body>
    <h1>Dashboard MentorLink</h1>
    <p>Bienvenue {{ $user->name }} ({{ $user->role }})</p>
    
    <h2>Statistiques</h2>
    <ul>
        <li>Total mentors: {{ $stats['total_mentors'] }}</li>
        <li>Mentors validés: {{ $stats['validated_mentors'] }}</li>
        <li>Mentors en attente: {{ $stats['pending_mentors'] }}</li>
    </ul>
    
    <h2>Navigation</h2>
    <ul>
        <li><a href="{{ route('mentors.index') }}">Liste des mentors</a></li>
        @if($user->role === 'mentor')
            <li><a href="{{ route('mentor.profile') }}">Mon profil mentor</a></li>
        @endif
        @if($user->role === 'admin')
            <li><a href="{{ route('admin.dashboard') }}">Dashboard Admin</a></li>
        @endif
        <li>
            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                @csrf
                <button type="submit">Déconnexion</button>
            </form>
        </li>
    </ul>
</body>
</html>