<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('pelanggan.register');
    }

    public function register(Request $request)
    {
        $suspiciousPattern = '/[<>{}\[\];]/';

        $request->validate([
            'nama_pengguna' => [
                'required', 'string', 'max:45',
                'unique:users,name', // FIX: kolom nama_pengguna
                'not_regex:' . $suspiciousPattern
            ],
            'email' => 'required|string|email:rfc,dns|max:30|unique:users',
            'no_hp'    => 'required|string|max:15',
            'password' => 'required|string|min:8'
        ], [
            'nama_pengguna.not_regex' => 'Input tidak valid.',
            'nama_pengguna.unique'    => 'Username sudah digunakan.',
            'email.unique'            => 'Email sudah terdaftar.',
            'password.min'            => 'Password minimal 8 karakter.',
        ]);

        $user = User::create([
            'name'              => $request->nama_pengguna, // FIX: nama_pengguna
            'email'             => $request->email,
            'no_hp'             => $request->no_hp,
            'password'          => Hash::make($request->password), // FIX: Hash::make
            'role'              => 'pelanggan',
            'alamat'            => null,
        ]);

       // Login otomatis setelah daftar
        Auth::login($user);

        // Kirim email verifikasi di background
        $user->sendEmailVerificationNotification();

        return redirect()->route('pelanggan.home')
            ->with('success', 'Pendaftaran berhasil! Selamat datang di Play N Chill 🎮');
            }
}