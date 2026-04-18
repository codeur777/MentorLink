<!DOCTYPE html>
<html>
<head>
    <title>Inscription - MentorLink</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
    <h1>Inscription</h1>

    @if ($errors->any())
        <div style="color: red;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div>
            <label for="name">Nom complet</label><br>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus>
        </div>

        <div>
            <label for="email">Adresse e-mail</label><br>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required>
        </div>

        <div>
            <label for="password">Mot de passe</label><br>
            <input id="password" type="password" name="password" required>
        </div>

        <div>
            <label for="password_confirmation">Confirmer le mot de passe</label><br>
            <input id="password_confirmation" type="password" name="password_confirmation" required>
        </div>

        <div>
            <label for="role">Rôle</label><br>
            <select id="role" name="role" required>
                <option value="">-- Choisir --</option>
                <option value="mentor" {{ old('role') === 'mentor' ? 'selected' : '' }}>Mentor</option>
                <option value="mentee" {{ old('role') === 'mentee' ? 'selected' : '' }}>Mentoré</option>
            </select>
        </div>

        <div>
            <button type="submit">S'inscrire</button>
        </div>
    </form>

    <p>Déjà un compte ? <a href="{{ route('login') }}">Se connecter</a></p>
</body>
</html>
