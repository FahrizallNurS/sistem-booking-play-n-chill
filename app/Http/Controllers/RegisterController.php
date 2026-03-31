<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengguna;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    // tampilkan halaman register
    public function showRegistrationForm(){
        return view('register');
    }

    // proses simpan data
    public function register(Request $request){

        // validasi input
        $request->validate([
            'nama' => 'required|string|max:45',
            'email' => 'required|email|max:30|unique:ms_pengguna,email',
            'no_hp' => 'required|string|max:15',
            'password' => 'required|string|min:8'
        ]);

        // simpan ke database
        Pengguna::create([
            'nama_pengguna' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'no_hp' => $request->no_hp,
            'status' => 'aktif',
            'google_id' => null
        ]);

        // redirect + pesan sukses
        return redirect('/register')->with('success', 'Berhasil daftar');
    }
}