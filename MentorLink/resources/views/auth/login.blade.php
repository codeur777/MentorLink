@extends('layouts.guest')

@section('title', 'Connexion - MentorLink')

@section('content')
<div class="card">
    <div class="card-header">
        <h4 class="text-center mb-0">Connexion</h4>
    </div>
    <div class="card-body">
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
                       type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" />
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Password -->
            <div class="mb-3">
                <label for="password" class="form-label">Mot de passe</label>
                <input id="password" class="form-control @error('password') is-invalid @enderror" 
                       type="password" name="password" required autocomplete="current-password" />
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Remember Me -->
            <div class="mb-3 form-check">
                <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
                <label for="remember_me" class="form-check-label">Se souvenir de moi</label>
            </div>

            <div class="d-flex justify-content-between align-items-center">
                @if (Route::has('password.request'))
                    <a class="text-decoration-none" href="{{ route('password.request') }}">
                        Mot de passe oublié ?
                    </a>
                @endif
                <button type="submit" class="btn btn-primary">
                    Se connecter
                </button>
            </div>
        </form>

        <div class="text-center mt-3">
            <p>Pas encore de compte ? <a href="{{ route('register') }}">S'inscrire</a></p>
        </div>
    </div>
</div>
@endsection