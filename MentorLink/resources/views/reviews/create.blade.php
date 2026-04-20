<!DOCTYPE html>
<html>
<head>
    <title>Laisser un avis - MentorLink</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
    <h1>Laisser un avis</h1>

    <nav>
        <a href="{{ route('sessions.index') }}">← Mes sessions</a>
    </nav>

    <div style="margin: 10px 0; padding: 10px; border: 1px solid #ddd; background: #f9f9f9;">
        <strong>Session du</strong> {{ $session->date->format('d/m/Y') }}
        avec <strong>{{ $session->mentor->name }}</strong>
        ({{ $session->start_time }} – {{ $session->end_time }})
    </div>

    @if($errors->any())
        <div style="color: red; margin: 10px 0;">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('reviews.store', $session) }}">
        @csrf

        <div style="margin: 15px 0;">
            <label><strong>Note (1 à 5) :</strong></label><br>
            @for($i = 1; $i <= 5; $i++)
                <label style="margin-right: 10px;">
                    <input type="radio" name="rating" value="{{ $i }}"
                           {{ old('rating') == $i ? 'checked' : '' }} required>
                    {{ $i }} ★
                </label>
            @endfor
        </div>

        <div style="margin: 15px 0;">
            <label><strong>Commentaire (optionnel) :</strong></label><br>
            <textarea name="comment" rows="4" maxlength="1000"
                      style="width: 400px;">{{ old('comment') }}</textarea>
        </div>

        <button type="submit">Soumettre l'avis</button>
    </form>
</body>
</html>
