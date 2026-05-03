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
                'unique:users,nama_pengguna', // FIX: kolom nama_pengguna
                'not_regex:' . $suspiciousPattern
            ],
            'email'    => 'required|email|max:30|unique:users,email',
            'no_hp'    => 'required|string|max:15',
            'password' => 'required|string|min:8'
        ], [
            'nama_pengguna.not_regex' => 'Input tidak valid.',
            'nama_pengguna.unique'    => 'Username sudah digunakan.',
            'email.unique'            => 'Email sudah terdaftar.',
            'password.min'            => 'Password minimal 8 karakter.',
        ]);

        $user = User::create([
            'nama_pengguna'     => $request->nama_pengguna, // FIX: nama_pengguna
            'email'             => $request->email,
            'no_hp'             => $request->no_hp,
            'password'          => Hash::make($request->password), // FIX: Hash::make
            'role'              => 'pelanggan',
            'alamat'            => null,
            'status'            => 1,
        ]);

        // Kirim email verifikasi
        $user->sendEmailVerificationNotification();

        return redirect()->route('verification.notice')
            ->with('success', 'Pendaftaran berhasil! Cek email untuk verifikasi.');
    }
}