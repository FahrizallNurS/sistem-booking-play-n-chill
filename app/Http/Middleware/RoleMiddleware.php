<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles)
{
    if (!Auth::check()) {
        return redirect()->route('login');
    }
    $user = Auth::user();

    if ($user->role === 'superadmin') {
        return $next($request);
    }

    if (in_array($user->role, $roles)) {
        return $next($request);
    }
    abort(403, 'Maaf, Anda tidak memiliki akses ke halaman ini.');
}
}