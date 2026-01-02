<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  ...$roles  // Accepts one or more roles: admin, moderator, etc.
     * @return Response
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Check if user is not logged in
        if (!Auth::check()) {
            return redirect('/login'); // or abort(401)
        }

        // Get current user's role
        $userRole = Auth::user()->role;

        // If user's role is not in the allowed roles → deny access
        if (!in_array($userRole, $roles)) {
            abort(403, 'Access denied. You do not have permission to access this page.');
        }

        // User has correct role → continue
        return $next($request);
    }
}