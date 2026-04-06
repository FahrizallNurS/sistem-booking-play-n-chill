<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;

class AuthController extends Controller
{
    // =====================
    // Tampilkan halaman login
    // =====================
    public function showLogin()
    {
        // Kalau sudah login, langsung redirect sesuai role
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user());
        }

        return view('auth.login');
    }

    // =====================
    // Proses login
    // =====================
    public function login(Request $request)
    {
        // Validasi input
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ], [
            'email.required'    => 'Email wajib diisi.',
            'email.email'       => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return $this->redirectByRole(Auth::user());
        }

        // Login gagal
        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    // =====================
    // Logout
    // =====================
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/'); // ← ganti dari route('login') ke /
    }

    // =====================
    // Redirect ke Google
    // =====================
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    // =====================
    // Callback dari Google
    // =====================
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors([
                'email' => 'Login dengan Google gagal, coba lagi.',
            ]);
        }

        // Cari user berdasarkan email, kalau belum ada buat baru
        $user = User::firstOrCreate(
            ['email' => $googleUser->getEmail()],
            [
                'name'              => $googleUser->getName(),
                'password'          => bcrypt(\Illuminate\Support\Str::random(24)),
                'role'              => 'pelanggan',
                'google_id'         => $googleUser->getId(), // ← ini hanya diisi saat CREATE
                'email_verified_at' => now(),
            ]
        );

        // Update google_id kalau user sudah ada tapi belum punya google_id
        if (!$user->google_id) {
            $user->update(['google_id' => $googleUser->getId()]);
        }

        Auth::login($user);

        return $this->redirectByRole($user);
    }

    // =====================
    // Helper: redirect berdasarkan role
    // =====================
    private function redirectByRole(User $user)
    {
        return match ($user->role) {
            'superadmin' => redirect()->route('superadmin.dashboard'),
            'admin'      => redirect()->route('admin.dashboard'),
            default      => redirect('/'), // ← ganti dari route('pelanggan.home') ke /
        };
    }
}