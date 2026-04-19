<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Connexion - MentorLink</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .login-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
    </style>
</head>
<body class="d-flex align-items-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="login-card p-4">
                    <div class="text-center mb-4">
                        <i class="fas fa-graduation-cap fa-3x text-primary mb-3"></i>
                        <h2 class="fw-bold">MentorLink</h2>
                        @if(request('redirect') && request('role'))
                            @if(request('redirect') === 'mentors' && request('role') === 'mentee')
                                <p class="text-muted">Connectez-vous pour rechercher des mentors</p>
                            @elseif(request('redirect') === 'mentor-profile' && request('role') === 'mentor')
                                <p class="text-muted">Connectez-vous pour gérer votre profil mentor</p>
                            @else
                                <p class="text-muted">Connexion à votre compte</p>
                            @endif
                        @else
                            <p class="text-muted">Connexion à votre compte</p>
                        @endif
                    </div>
                    
                    <!-- Session Status -->
                    @if (session('status'))
                        <div class="alert alert-success mb-4">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        
                        <!-- Préserver les paramètres de redirection -->
                        @if(request('redirect'))
                            <input type="hidden" name="redirect" value="{{ request('redirect') }}">
                        @endif
                        @if(request('role'))
                            <input type="hidden" name="role" value="{{ request('role') }}">
                        @endif

                        <!-- Email Address -->
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input id="email" class="form-control @error('email') is-invalid @enderror" 
                                   type="email" name="email" value="{{ old('email') }}" required autofocus>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="mb-3">
                            <label for="password" class="form-label">Mot de passe</label>
                            <input id="password" class="form-control @error('password') is-invalid @enderror"
                                   type="password" name="password" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Remember Me -->
                        <div class="mb-3 form-check">
                            <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
                            <label for="remember_me" class="form-check-label">Se souvenir de moi</label>
                        </div>

                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-sign-in-alt me-2"></i>Se connecter
                            </button>
                        </div>

                        <div class="text-center">
                            @if (Route::has('password.request'))
                                <a class="text-decoration-none" href="{{ route('password.request') }}">
                                    Mot de passe oublié ?
                                </a>
                            @endif
                        </div>
                    </form>
                    
                    <hr class="my-4">
                    
                    <div class="text-center">
                        <p class="mb-2">Pas encore de compte ?</p>
                        <a href="{{ route('register') }}" class="btn btn-outline-primary">
                            <i class="fas fa-user-plus me-2"></i>S'inscrire
                        </a>
                    </div>
                    
                    <!-- Comptes de test -->
                    <div class="mt-4 p-3 bg-light rounded">
                        <small class="text-muted">
                            <strong>Comptes de test :</strong><br>
                            Admin: admin@mentorlink.com / admin123<br>
                            Mentor: mentor@test.com / password<br>
                            Mentee: mentee@test.com / password
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>