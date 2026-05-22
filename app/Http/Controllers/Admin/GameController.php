<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MsPermainan;
use App\Models\MsRuangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GameController extends Controller
{
    public function index()
    {
        $permainans = MsPermainan::with('ruangans')->get();
        return view('admin.game.index', compact('permainans'));
    }

    public function create()
    {
        $ruangans = MsRuangan::where('is_active', 1)->get();
        return view('admin.game.create', compact('ruangans'));
    }

    public function store(Request $request)
    {
        $request->validate([
        'nama_permainan' => 'required|string|max:30|unique:ms_permainan,nama_permainan',
        'gambar'         => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        'devices' => 'required|array|min:1',
        'devices.*' => 'in:PS3,PS4,PS5',
        ]);

        $gambarPath = null;
        if ($request->hasFile('gambar')) {
            $gambarPath = $request->file('gambar')->store('permainan', 'public');
        }

        $permainan = MsPermainan::create([
            'nama_permainan' => $request->nama_permainan,
            'gambar'         => $gambarPath,
        ]);

        $ruanganIds = MsRuangan::whereIn('perangkat', $request->devices)
            ->pluck('id_ruangan')
            ->unique();

            $permainan->ruangans()->sync($ruanganIds);

                return redirect()->route('admin.game.index')
                    ->with('success', 'Game berhasil ditambahkan!');
    }
    

    public function edit($id)
    {
       $permainan = MsPermainan::with('ruangans')->findOrFail($id);
        $ruangans  = MsRuangan::where('is_active', 1)->get();
        $currentDevices = $permainan->ruangans->pluck('perangkat')->unique()->values();

        return view('admin.game.edit', compact('permainan', 'ruangans', 'currentDevice'));
    }

    public function update(Request $request, $id)
    {
        $permainan = MsPermainan::findOrFail($id);

        $request->validate([
            'nama_permainan' => 'required|string|max:30|unique:ms_permainan,nama_permainan,' . $id . ',id_permainan',
            'gambar'         => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'devices' => 'required|array|min:1',
            'devices.*' => 'in:PS3,PS4,PS5,Nintendo Switch',
        ]);

        if ($request->hasFile('gambar')) {
            // Hapus gambar lama
            if ($permainan->gambar) {
                Storage::disk('public')->delete($permainan->gambar);
            }
            $permainan->gambar = $request->file('gambar')->store('permainan', 'public');
        }

        $permainan->nama_permainan = $request->nama_permainan;
        $permainan->save();

        $ruanganIds = MsRuangan::whereIn('perangkat', $request->devices)
            ->pluck('id_ruangan')
            ->unique();
              $permainan->ruangans()->sync($ruanganIds);

        return redirect()->route('admin.game.index')
            ->with('success', 'Game berhasil diupdate!');
    }

    public function destroy($id)
    {
        $permainan = MsPermainan::findOrFail($id);

        if ($permainan->gambar) {
            Storage::disk('public')->delete($permainan->gambar);
        }

        $permainan->ruangans()->detach();
        $permainan->delete();

        return redirect()->route('admin.game.index')
            ->with('success', 'Game berhasil dihapus!');
    }
}