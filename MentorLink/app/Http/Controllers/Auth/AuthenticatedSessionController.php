<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // Gérer les redirections spéciales avec rôle
        $redirect = $request->get('redirect');
        $role = $request->get('role');
        
        if ($redirect && $role) {
            // Vérifier si l'utilisateur a le bon rôle
            $user = auth()->user();
            
            if ($redirect === 'mentors' && $role === 'mentee') {
                return redirect()->route('mentors.index');
            }
            
            if ($redirect === 'mentor-profile' && $role === 'mentor') {
                if ($user->role === 'mentor') {
                    return redirect()->route('mentor.profile');
                } else {
                    return redirect()->route('dashboard')->with('error', 'Vous devez être mentor pour accéder à cette page.');
                }
            }
        }

        return redirect()->intended(route('dashboard'));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
