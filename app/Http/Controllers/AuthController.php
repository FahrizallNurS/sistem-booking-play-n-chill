<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            if (!Auth::user()->hasVerifiedEmail()) {
                return redirect()->route('verification.notice');
            }
            return $this->redirectByRole(Auth::user());
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
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

            if (!Auth::user()->hasVerifiedEmail()) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Email belum diverifikasi. Cek inbox kamu.',
                ])->onlyInput('email');
            }

            return $this->redirectByRole(Auth::user());
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors([
                'email' => 'Login dengan Google gagal, coba lagi.',
            ]);
        }

        $user = User::firstOrCreate(
            ['email' => $googleUser->getEmail()],
            [
                'nama_pengguna'     => $googleUser->getName(),
                'password'          => bcrypt(\Illuminate\Support\Str::random(24)),
                'role'              => 'pelanggan',
                'google_id'         => $googleUser->getId(),
                'email_verified_at' => now(),
            ]
        );

        if (!$user->google_id) {
            $user->update([
                'google_id'         => $googleUser->getId(),
                'email_verified_at' => now(),
            ]);
        }

        Auth::login($user);
        return $this->redirectByRole($user);
    }

    private function redirectByRole(User $user)
    {
        return match ($user->role) {
            'superadmin' => redirect()->route('superadmin.dashboard'),
            'admin'      => redirect()->route('admin.dashboard'),
            default      => redirect('/'),
        };
    }
}