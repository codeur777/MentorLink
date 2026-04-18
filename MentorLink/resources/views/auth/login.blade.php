<!DOCTYPE html>
<html>
<head>
    <title>Connexion - MentorLink</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
    <h1>Connexion</h1>

    @if ($errors->any())
        <div style="color: red;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div>
            <label for="email">Adresse e-mail</label><br>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
        </div>

        <div>
            <label for="password">Mot de passe</label><br>
            <input id="password" type="password" name="password" required>
        </div>

        <div>
            <label>
                <input type="checkbox" name="remember"> Se souvenir de moi
            </label>
        </div>

        <div>
            <button type="submit">Se connecter</button>
        </div>
    </form>

    <p>Pas encore de compte ? <a href="{{ route('register') }}">S'inscrire</a></p>
    <p><a href="{{ route('password.request') }}">Mot de passe oublié ?</a></p>
</body>
</html>
