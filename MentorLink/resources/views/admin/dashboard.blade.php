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
        <a href="{{ route('admin.pending-mentors') }}">Mentors en attente</a> |
        <a href="{{ route('admin.reports') }}">Signalements</a>
    </nav>

    @if(session('success'))
        <div style="color:green; margin:10px 0;">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div style="color:red; margin:10px 0;">{{ session('error') }}</div>
    @endif

    <h2>Statistiques globales</h2>
    <ul>
        <li>Utilisateurs : {{ $stats['total_users'] }}</li>
        <li>Mentors : {{ $stats['total_mentors'] }}</li>
        <li>Mentores : {{ $stats['total_mentees'] }}</li>
        <li>Mentors en attente de validation : {{ $stats['pending_mentors'] }}</li>
        <li>Mentors valides : {{ $stats['validated_mentors'] }}</li>
        <li>Sessions totales : {{ $stats['total_sessions'] }}</li>
        <li>
            Signalements ouverts :
            <strong style="color:{{ $stats['open_reports'] > 0 ? 'orange' : 'green' }};">
                {{ $stats['open_reports'] }}
            </strong>
        </li>
    </ul>

    <h3>Actions rapides</h3>
    <ul>
        <li><a href="{{ route('admin.pending-mentors') }}">Valider les profils mentors ({{ $stats['pending_mentors'] }} en attente)</a></li>
        <li><a href="{{ route('admin.reports') }}">Gerer les signalements ({{ $stats['open_reports'] }} ouverts)</a></li>
        <li><a href="{{ route('admin.stats') }}">Statistiques detaillees</a></li>
    </ul>
</body>
</html>
