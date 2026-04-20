<!DOCTYPE html>
<html>
<head>
    <title>Vérification e-mail - MentorLink</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
    <h1>Vérifiez votre adresse e-mail</h1>

    @if (session('status') == 'verification-link-sent')
        <div style="color: green;">
            Un nouveau lien de vérification a été envoyé à votre adresse e-mail.
        </div>
    @endif

    <p>
        Merci de vous être inscrit ! Avant de continuer, veuillez vérifier votre adresse e-mail
        en cliquant sur le lien que nous venons de vous envoyer.
    </p>

    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit">Renvoyer l'e-mail de vérification</button>
    </form>

    <form method="POST" action="{{ route('logout') }}" style="margin-top: 1rem;">
        @csrf
        <button type="submit">Se déconnecter</button>
    </form>
</body>
</html>
