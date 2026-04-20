<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     * Aborts with 403 if the authenticated user is not an admin.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Acces reserve aux administrateurs');
        }

        return $next($request);
    }
}
