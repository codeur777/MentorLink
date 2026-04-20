@extends('layouts.guest')

@section('title', 'Inscription - MentorLink')

@section('content')
<div class="card">
    <div class="card-header">
        <h4 class="text-center mb-0">Inscription</h4>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- Name -->
            <div class="mb-3">
                <label for="name" class="form-label">Nom complet</label>
                <input id="name" class="form-control @error('name') is-invalid @enderror" 
                       type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" />
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Email Address -->
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input id="email" class="form-control @error('email') is-invalid @enderror" 
                       type="email" name="email" value="{{ old('email') }}" required autocomplete="username" />
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Role -->
            <div class="mb-3">
                <label for="role" class="form-label">Je souhaite être</label>
                <select id="role" name="role" class="form-select @error('role') is-invalid @enderror" required>
                    <option value="">Choisissez votre rôle</option>
                    <option value="mentee" {{ old('role') == 'mentee' ? 'selected' : '' }}>Mentoré (cherche un mentor)</option>
                    <option value="mentor" {{ old('role') == 'mentor' ? 'selected' : '' }}>Mentor (offre du mentorat)</option>
                </select>
                @error('role')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Password -->
            <div class="mb-3">
                <label for="password" class="form-label">Mot de passe</label>
                <input id="password" class="form-control @error('password') is-invalid @enderror" 
                       type="password" name="password" required autocomplete="new-password" />
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Confirmer le mot de passe</label>
                <input id="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror" 
                       type="password" name="password_confirmation" required autocomplete="new-password" />
                @error('password_confirmation')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex justify-content-between align-items-center">
                <a class="text-decoration-none" href="{{ route('login') }}">
                    Déjà inscrit ?
                </a>
                <button type="submit" class="btn btn-primary">
                    S'inscrire
                </button>
            </div>
        </form>
    </div>
</div>
@endsection