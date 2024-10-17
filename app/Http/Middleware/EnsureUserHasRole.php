<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */

    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!Auth::check()) {
            return redirect('/login');
        }
        if ($role == 'all') {
            return $next($request);
        }
        if ($role == 'managers' && (Auth::user()->role->role_name == 'Team Leader' || Auth::user()->role->role_name == 'Project Manager')) {
            return $next($request);
        }
        if (Auth::user()->role->role_name == $role) {
            return $next($request);
        }

        abort(403);
        return redirect('/login');
    }

}