<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function show()
    {
        /** @var User $user */
        $user = Auth::user();

        // FIX: id_pengguna bukan id
        $bookingAktif = \App\Models\TrTransaksi::with(['penetapanHarga.ruangan', 'penetapanHarga.paket'])
            ->where('id_pengguna', $user->id_pengguna)
            ->whereIn('status_sewa', ['ditahan', 'dikonfirmasi'])
            ->where('waktu_selesai', '>=', now())
            ->latest('waktu_mulai')
            ->first();

        $riwayat = \App\Models\TrTransaksi::with(['penetapanHarga.ruangan', 'penetapanHarga.paket'])
            ->where('id_pengguna', $user->id_pengguna)
            ->where(function($q) {
                $q->whereIn('status_sewa', ['selesai', 'dibatalkan'])
                  ->orWhere('waktu_selesai', '<', now());
            })
            ->latest('waktu_mulai')
            ->get();

        $totalJam = \App\Models\TrTransaksi::join('penetapan_harga', 'tr_transaksi.id_penetapan_harga', '=', 'penetapan_harga.id_penetapan_harga')
            ->where('tr_transaksi.id_pengguna', $user->id_pengguna)
            ->whereIn('tr_transaksi.status_sewa', ['dikonfirmasi', 'selesai'])
            ->sum('penetapan_harga.durasi_jam');

        return view('pelanggan.profile', compact('user', 'bookingAktif', 'riwayat', 'totalJam'));
    }

    public function update(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $validated = $request->validate([
            // FIX: name → nama_pengguna
            'nama_pengguna'    => ['required', 'string', 'max:45'],
            'email'            => ['required', 'email', 'max:30', Rule::unique('users', 'email')->ignore($user->id_pengguna, 'id_pengguna')],
            'no_hp'            => ['nullable', 'string', 'max:15'],
            'alamat'           => ['nullable', 'string', 'max:50'],
            'current_password' => ['nullable', 'required_with:new_password'],
            'new_password'     => ['nullable', 'min:8', 'confirmed'],
        ], [
            'nama_pengguna.required'         => 'Nama tidak boleh kosong.',
            'email.required'                 => 'Email tidak boleh kosong.',
            'email.email'                    => 'Format email tidak valid.',
            'email.unique'                   => 'Email sudah digunakan akun lain.',
            'current_password.required_with' => 'Password lama wajib diisi jika ingin ganti password.',
            'new_password.min'               => 'Password baru minimal 8 karakter.',
            'new_password.confirmed'         => 'Konfirmasi password baru tidak cocok.',
        ]);

        if ($request->filled('new_password')) {
            if (empty($user->password)) {
                $user->password = Hash::make($request->new_password);
            } else {
                if (!Hash::check($request->current_password, $user->password)) {
                    return back()->withErrors([
                        'current_password' => 'Password lama tidak sesuai.'
                    ])->withInput();
                }
                $user->password = Hash::make($request->new_password);
            }
        }

        // FIX: name → nama_pengguna
        $user->nama_pengguna = $validated['nama_pengguna'];
        $user->email         = $validated['email'];
        $user->no_hp         = $validated['no_hp'] ?? null;
        $user->alamat        = $validated['alamat'] ?? null;
        $user->save();

        return redirect()->route('profile')->with('success', 'Profil berhasil diperbarui!');
    }
}