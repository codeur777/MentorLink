<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - MentorLink</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
    <h1>Dashboard MentorLink</h1>
    <p>Bienvenue <strong>{{ $user->name }}</strong> ({{ ucfirst($user->role) }})</p>

    <nav>
        <a href="{{ route('mentors.index') }}">Mentors</a>
        @if($user->isMentor())
            | <a href="{{ route('mentor.profile') }}">Mon profil</a>
            | <a href="{{ route('availabilities.create') }}">Mes disponibilités</a>
        @endif
        | <a href="{{ route('sessions.index') }}">Mes sessions</a>
        @if($user->isAdmin())
            | <a href="{{ route('admin.dashboard') }}">Admin</a>
        @endif
        &nbsp;
        <form method="POST" action="{{ route('logout') }}" style="display: inline;">
            @csrf
            <button type="submit">Déconnexion</button>
        </form>
    </nav>

    @if(session('success'))
        <div style="color: green; margin: 10px 0;">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div style="color: red; margin: 10px 0;">{{ session('error') }}</div>
    @endif

    <h2>Statistiques</h2>
    @if(count($stats) > 0)
        <ul>
            @foreach($stats as $key => $value)
                <li>{{ ucfirst(str_replace('_', ' ', $key)) }} : {{ $value }}</li>
            @endforeach
        </ul>
    @else
        <p>Aucune statistique disponible.</p>
    @endif
</body>
</html>
