bantu fix konflik

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

    // Superadmin bebas akses semua
    if ($user->role === 'superadmin') {
        return $next($request);
    }

    // Role sesuai middleware
    if (in_array($user->role, $roles)) {
        return $next($request);
    }

    abort(403, 'Maaf, Anda tidak memiliki akses ke halaman ini.');

}