<!DOCTYPE html>
<html>
<head>
    <title>Signaler un utilisateur - MentorLink</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
    <h1>Signaler un utilisateur</h1>

    <nav>
        <a href="{{ route('sessions.index') }}">← Mes sessions</a>
    </nav>

    <div style="border:1px solid #ddd; padding:12px; margin:12px 0; background:#fff3cd;">
        <p>
            <strong>Session du</strong> {{ $session->date->format('d/m/Y') }}
            ({{ $session->start_time }} – {{ $session->end_time }})
        </p>
        <p><strong>Utilisateur signale :</strong> {{ $reported->name }}</p>
    </div>

    @if($errors->any())
        <div style="color:red; margin:10px 0;">
            <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <form method="POST" action="{{ route('reports.store', $session) }}">
        @csrf

        <div style="margin:12px 0;">
            <label><strong>Motif du signalement :</strong></label><br>
            <textarea name="reason" rows="5" maxlength="1000" required
                      style="width:450px;">{{ old('reason') }}</textarea>
            <br><small>Minimum 10 caracteres.</small>
        </div>

        <button type="submit">Envoyer le signalement</button>
    </form>
</body>
</html>
