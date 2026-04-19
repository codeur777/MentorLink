<!DOCTYPE html>
<html>
<head>
    <title>Statistiques - Admin MentorLink</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
    <h1>Statistiques globales</h1>

    <nav>
        <a href="{{ route('admin.dashboard') }}">← Dashboard admin</a>
    </nav>

    <ul style="margin-top: 20px; font-size: 1.1em;">
        @foreach($stats as $key => $value)
            <li><strong>{{ ucfirst(str_replace('_', ' ', $key)) }} :</strong> {{ $value }}</li>
        @endforeach
    </ul>
</body>
</html>
