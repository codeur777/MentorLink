<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - MentorLink</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
    <h1>Dashboard MentorLink</h1>
    <p>Bienvenue {{ $user->name }} ({{ ucfirst($user->role) }})</p>
    
    <nav>
        <a href="{{ route('mentors.index') }}">Liste des mentors</a> |
        @if($user->role === 'mentor')
            <a href="{{ route('mentor.profile') }}">Mon profil mentor</a> |
        @endif
        @if($user->role === 'admin')
            <a href="{{ route('admin.dashboard') }}">Admin</a> |
        @endif
        <form method="POST" action="{{ route('logout') }}" style="display: inline;">
            @csrf
            <button type="submit">Déconnexion</button>
        </form>
    </nav>
    
    @if(session('success'))
        <div style="color: green;">{{ session('success') }}</div>
    @endif
    
    @if(session('error'))
        <div style="color: red;">{{ session('error') }}</div>
    @endif
    
    <h2>Statistiques</h2>
    <ul>
        @foreach($stats as $key => $value)
            <li>{{ ucfirst(str_replace('_', ' ', $key)) }}: {{ $value }}</li>
        @endforeach
    </ul>
</body>
</html>