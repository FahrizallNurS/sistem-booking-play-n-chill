<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class KelolaUserController extends Controller
{
    public function index(Request $request)
    {
         $query = User::query();

        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where('nama_pengguna', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");

            });
        }

         if ($request->filled('role')) {

                $query->where('role', $request->role);
        }

            $users = $query->latest('created_at')->get();
            return view('superadmin.kelola-user.index', compact('users'));

    }

    public function create()
    {
        return view('superadmin.kelola-user.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_pengguna' => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email',
            'password'      => 'required|min:8|confirmed',
            'role'          => 'required|in:admin,pelanggan,superadmin',
            'no_hp'         => 'nullable|string|max:15',
            'alamat'        => 'nullable|string',
        ]);

        User::create([
            'nama_pengguna'      => $request->nama_pengguna,
            'email'              => $request->email,
            'password'           => Hash::make($request->password),
            'role'               => $request->role,
            'no_hp'              => $request->no_hp,
            'alamat'             => $request->alamat,
            'status'             => 1,
            'email_verified_at'  => now(),
        ]);

        return redirect()->route('superadmin.users.index')
            ->with('success', 'User berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('superadmin.kelola-user.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'nama_pengguna' => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email,' . $user->id_pengguna . ',id_pengguna',
            'role'          => 'required|in:admin,pelanggan,superadmin',
            'no_hp'         => 'nullable|string|max:15',
            'alamat'        => 'nullable|string',
        ]);

        $user->update([
            'nama_pengguna' => $request->nama_pengguna,
            'email'         => $request->email,
            'role'          => $request->role,
            'no_hp'         => $request->no_hp,
            'alamat'        => $request->alamat,
        ]);

        return redirect()->route('superadmin.users.index')
            ->with('success', 'User berhasil diupdate!');
    }

   public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->id_pengguna === auth()->id()) {
            return back()->with('error', 'Tidak bisa menghapus akun sendiri!');
        }

        $punya_transaksi = $user->transaksis()->exists();

        if ($punya_transaksi) {
            return back()->with('error', 'User memiliki data transaksi dan tidak dapat dihapus.');
        }

        $user->delete();

        return redirect()->route('superadmin.users.index')
            ->with('success', 'User berhasil dihapus permanen!');
    }

    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);

        if ($user->id_pengguna === auth()->id()) {
            return back()->with('error', 'Tidak bisa menonaktifkan akun sendiri!');
        }

        $user->status = $user->status == 1 ? 0 : 1;
        $user->save();

        $keterangan = $user->status == 1 ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->route('superadmin.users.index')
            ->with('success', "User berhasil {$keterangan}!");
    }

    public function gantiPassword(Request $request, $id)
    {
        $request->validate([
            'password' => 'required|min:8|confirmed',
        ]);

        User::findOrFail($id)->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Password berhasil diubah!');
    }
}