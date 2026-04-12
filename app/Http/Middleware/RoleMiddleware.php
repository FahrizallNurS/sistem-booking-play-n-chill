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

    // PAKSA LIHAT DATA
    // Hapus baris ini kalau masalah sudah ketemu!
    dd([
        'Role_User_Sekarang' => Auth::user()->role,
        'Role_Yang_Diminta'  => $roles,
        'Hasil_Cek'          => in_array(Auth::user()->role, $roles)
    ]);

    if (!in_array(Auth::user()->role, $roles)) {
        abort(403, 'Akses ditolak.');
    }

    return $next($request);
}
}