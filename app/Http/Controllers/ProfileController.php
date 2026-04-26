<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Tampilkan halaman profil user yang sedang login.
     */
    public function show()
    {
        /** @var User $user */
        $user = Auth::user();

        // Booking aktif — status ditahan/dikonfirmasi DAN waktu selesai belum lewat
        $bookingAktif = \App\Models\TrTransaksi::with(['penetapanHarga.ruangan', 'penetapanHarga.paket'])
            ->where('id_pengguna', $user->id)
            ->whereIn('status_sewa', ['ditahan', 'dikonfirmasi'])
            ->where('waktu_selesai', '>=', now())
            ->latest('waktu_mulai')
            ->first();

        // Riwayat — selesai/dibatalkan ATAU waktu sudah lewat
        $riwayat = \App\Models\TrTransaksi::with(['penetapanHarga.ruangan', 'penetapanHarga.paket'])
            ->where('id_pengguna', $user->id)
            ->where(function($q) {
                $q->whereIn('status_sewa', ['selesai', 'dibatalkan'])
                ->orWhere('waktu_selesai', '<', now());
            })
            ->latest('waktu_mulai')
            ->get();

        // Total jam main
        $totalJam = \App\Models\TrTransaksi::join('penetapan_harga', 'tr_transaksi.id_penetapan_harga', '=', 'penetapan_harga.id_penetapan_harga')
            ->where('tr_transaksi.id_pengguna', $user->id)
            ->whereIn('tr_transaksi.status_sewa', ['dikonfirmasi', 'selesai'])
            ->sum('penetapan_harga.durasi_jam');

        return view('pelanggan.profile', compact('user', 'bookingAktif', 'riwayat', 'totalJam'));
    }

    public function update(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $validated = $request->validate([
            'name'         => ['required', 'string', 'max:100'],
            'email'        => ['required', 'email', 'max:100', Rule::unique('users', 'email')->ignore($user->id)],
            'no_hp' => ['nullable', 'string', 'max:20'],
            'alamat' => ['nullable', 'string', 'max:255'],
            'current_password' => ['nullable', 'required_with:new_password'],
            'new_password' => ['nullable', 'min:8', 'confirmed'],
        ], [
            'name.required'            => 'Nama tidak boleh kosong.',
            'email.required'           => 'Email tidak boleh kosong.',
            'email.email'              => 'Format email tidak valid.',
            'email.unique'             => 'Email sudah digunakan akun lain.',
            'current_password.required_with' => 'Password lama wajib diisi jika ingin ganti password.',
            'new_password.min'         => 'Password baru minimal 8 karakter.',
            'new_password.confirmed'   => 'Konfirmasi password baru tidak cocok.',
        ]);

        // Cek password lama jika ingin ganti password
        if ($request->filled('new_password')) {

        if (empty($user->password)) {
            // Login Google, langsung set password baru tanpa cek password lama
            $user->password = Hash::make($request->new_password);

        } else {
            // Sudah punya password, wajib cek password lama
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors([
                    'current_password' => 'Password lama tidak sesuai.'
                ])->withInput();
            }
        $user->password = Hash::make($request->new_password);
    }
}
        $user->name  = $validated['name'];
        $user->email = $validated['email'];
        $user->no_hp = $validated['no_hp'] ?? null;
        $user->alamat = $validated['alamat'] ?? null;
        $user->save();

        return redirect()->route('profile')->with('success', 'Profil berhasil diperbarui!');
    }
}