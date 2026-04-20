<!DOCTYPE html>
<html>
<head>
    <title>Mot de passe oublié - MentorLink</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
    <h1>Mot de passe oublié</h1>

    @if (session('status'))
        <div style="color: green;">{{ session('status') }}</div>
    @endif

    @if ($errors->any())
        <div style="color: red;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <p>Entrez votre adresse e-mail et nous vous enverrons un lien pour réinitialiser votre mot de passe.</p>

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div>
            <label for="email">Adresse e-mail</label><br>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
        </div>

        <div>
            <button type="submit">Envoyer le lien</button>
        </div>
    </form>

    <p><a href="{{ route('login') }}">Retour à la connexion</a></p>
</body>
</html>
