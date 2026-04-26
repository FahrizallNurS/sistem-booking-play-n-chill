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
        // Mengarah ke resources/views/pelanggan/register.blade.php
        return view('pelanggan.register');
    }

    public function register(Request $request)
    {
        $suspiciousPattern = '/[<>{}\[\];]/';
        // 1. Validasi Input & Pesan Error Custom
        $request->validate([
            'nama_pengguna' => [
            'required', 'string', 'max:45', 'unique:users,name',
            'not_regex:' . $suspiciousPattern 
            ],
            'email'         => 'required|email|max:30|unique:users,email',
            'no_hp'         => 'required|string|max:15',
            'password'      => 'required|string|min:8'
        ], [
            'nama_pengguna.not_regex' => 'Username atau password salah.',
            'nama_pengguna.unique'    => 'Username atau password salah.', 
            'email.unique'            => 'Username atau password salah.',
            'password.min'            => 'Username atau password salah.',
        ]);
    
        // 2. Simpan ke Database (Mapping kolom)
        $user = User::create([
            'name'     => $request->nama_pengguna,
            'email'    => $request->email,
            'phone'    => $request->no_hp, // Masuk ke kolom phone
            'no_hp'    => $request->no_hp, // Masuk ke kolom no_hp juga agar aman
            'password' => $request->password,
            'role'     => 'pelanggan',
            'alamat'   => null, 
        ]);
    
        // 3. Langsung Login otomatis
        \Illuminate\Support\Facades\Auth::login($user);
        
            return redirect()->route('pelanggan.home')->with('success', 'Pendaftaran berhasil!');
    }
}