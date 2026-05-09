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
                'required', 'string', 'max:50',
                'unique:users,nama_pengguna',
                'not_regex:' . $suspiciousPattern
            ],
            'email'    => 'required|string|email:rfc,dns|max:30|unique:users,email',
            'no_hp'    => 'required|string|max:15',
            'password' => 'required|string|min:8'
        ], [
            'nama_pengguna.not_regex' => 'Input tidak valid.',
            'nama_pengguna.unique'    => 'Username sudah digunakan.',
            'email.unique'            => 'Email sudah terdaftar.',
            'password.min'            => 'Password minimal 8 karakter.',
        ]);

        $user = User::create([
            'nama_pengguna' => $request->nama_pengguna,
            'email'         => $request->email,
            'no_hp'         => $request->no_hp,
            'password'      => Hash::make($request->password),
            'role'          => 'pelanggan',
            'alamat'        => null,
        ]);

        // Login sementara hanya untuk kirim notifikasi
        Auth::login($user);
        $user->sendEmailVerificationNotification();

        // Langsung logout — user harus aktivasi dulu
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'Pendaftaran berhasil! Cek email kamu untuk aktivasi akun sebelum login.');
    }
}