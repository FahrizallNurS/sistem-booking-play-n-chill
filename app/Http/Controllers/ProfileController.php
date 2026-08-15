<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Models\TrPos;

class ProfileController extends Controller
{
    public function show()
    {
        /** @var User $user */
        $user = Auth::user();

        \App\Models\TrTransaksi::where('status_sewa', 'ditahan')
        ->where('created_at', '<', now()->subMinutes(30))
        ->update([
            'status_sewa'        => 'dibatalkan',
            'catatan_pembayaran' => 'Waktu pembayaran habis!',
        ]);

        \App\Models\TrTransaksi::where('status_sewa', 'dikonfirmasi')
            ->where('waktu_selesai', '<', now())
            ->update([
                'status_sewa' => 'selesai',
            ]);

        TrPos::where('status_pesanan', 'Menunggu')
            ->where('created_at', '<', now()->subMinutes(15))
            ->update([
                'status_pesanan'    => 'Dibatalkan',
                'status_pembayaran' => 'kadaluarsa',
            ]);

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

        $riwayatFb = TrPos::where('id_pengguna', $user->id_pengguna)
            ->whereNull('id_transaksi')
            ->orderBy('created_at', 'desc')
            ->get();

        $totalJam = \App\Models\TrTransaksi::join('penetapan_harga', 'tr_transaksi.id_penetapan_harga', '=', 'penetapan_harga.id_penetapan_harga')
            ->where('tr_transaksi.id_pengguna', $user->id_pengguna)
            ->whereIn('tr_transaksi.status_sewa', ['dikonfirmasi', 'selesai'])
            ->sum('penetapan_harga.durasi_jam');

        $sisaDetik = 0;
        if ($bookingAktif && $bookingAktif->status_sewa === 'ditahan') {
            $expiredAt = \Carbon\Carbon::parse($bookingAktif->created_at)->addMinutes(30);
            $sisaDetik = max(0, now()->diffInSeconds($expiredAt, false));
        }

        return view('pelanggan.profile', compact('user', 'bookingAktif', 'riwayat', 'riwayatFb', 'totalJam', 'sisaDetik'));
    }

    public function update(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $validated = $request->validate([

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

        $user->nama_pengguna = $validated['nama_pengguna'];
        $user->email         = $validated['email'];
        $user->no_hp         = $validated['no_hp'] ?? null;
        $user->alamat        = $validated['alamat'] ?? null;
        $user->save();

        return redirect()->route('profile')->with('success', 'Profil berhasil diperbarui!');
    }
}