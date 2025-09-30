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
     * @param  string  $role  The required role (e.g. "admin" or "user")
     */
  public function handle(Request $request, Closure $next, string $roles): Response
{
    if (!Auth::check()) {
        return redirect('/'); // or a guest page
    }

    $userRole = Auth::user()->role;

    // Multiple roles allowed: role:admin|staff|user
    $allowedRoles = explode('|', $roles);

    if (!in_array($userRole, $allowedRoles)) {
        abort(403, 'Unauthorized.');
    }

    return $next($request);
}

}
