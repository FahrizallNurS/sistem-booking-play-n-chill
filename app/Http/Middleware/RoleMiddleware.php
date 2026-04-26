<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{

public function handle(Request $request, Closure $next, ...$roles)
{
    if (!Auth::check()) {
        return redirect()->route('login');
    }

    $user = Auth::user();

    // Kalau superadmin nyasar ke route admin, redirect ke superadmin dashboard
    if ($user->role === 'superadmin' && in_array('admin', $roles)) {
        return redirect()->route('superadmin.dashboard');
    }

    // Superadmin bebas akses route superadmin
    if ($user->role === 'superadmin' && in_array('superadmin', $roles)) {
        return $next($request);
    }

    // Role sesuai middleware
    if (in_array($user->role, $roles)) {
        return $next($request);
    }

    abort(403, 'Maaf, Anda tidak memiliki akses ke halaman ini.');
}
}