<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureUserHasRole
{
    public function handle(Request $request, Closure $next, string $role)
    {
        if (!Auth::check()) {
            return 
            redirect('/login');
        }
        if ($role == 'All') {
            return $next($request);
        }

        if ($role == 'Managers' && (Auth::user()->role->role_name == 'Team Leader' || Auth::user()->role->role_name == 'Project Manager')) {
            return $next($request);
        }
        
        if (Auth::user()->role->role_name == $role) {
            return $next($request);
        }

        abort(403);
    }

}