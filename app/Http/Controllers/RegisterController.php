<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    // Tampilkan halaman register
    public function showRegistrationForm()
    {
        // Kalau sudah login, redirect sesuai role
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user());
        }

        return view('pelanggan.register');
    }

    // Proses simpan data
    public function register(Request $request)
    {
        // Kalau sudah login, redirect sesuai role
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user());
        }

        // Validasi input
        $request->validate([
            'nama'     => 'required|string|max:45',
            'email'    => 'required|email|max:100|unique:users,email',
            'no_hp'    => 'required|string|max:15',
            'password' => 'required|string|min:8',
        ], [
            'nama.required'     => 'Nama wajib diisi.',
            'nama.max'          => 'Nama maksimal 45 karakter.',
            'email.required'    => 'Email wajib diisi.',
            'email.email'       => 'Format email tidak valid.',
            'email.unique'      => 'Email sudah terdaftar.',
            'no_hp.required'    => 'No HP wajib diisi.',
            'no_hp.max'         => 'No HP maksimal 15 karakter.',
            'password.required' => 'Password wajib diisi.',
            'password.min'      => 'Password minimal 8 karakter.',
        ]);

        // Simpan ke database — role selalu 'pelanggan', tidak bisa dimanipulasi dari form
        User::create([
            'name'      => $request->nama,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'no_hp'     => $request->no_hp,
            'role'      => 'pelanggan',
            'google_id' => null,
        ]);

        return redirect()->route('login')->with('success', 'Berhasil daftar! Silakan login.');
    }

    // Helper redirect berdasarkan role
    private function redirectByRole(User $user)
    {
        return match ($user->role) {
            'superadmin' => redirect()->route('superadmin.dashboard'),
            'admin'      => redirect()->route('admin.dashboard'),
            default      => redirect()->route('pelanggan.home'),
        };
    }
}