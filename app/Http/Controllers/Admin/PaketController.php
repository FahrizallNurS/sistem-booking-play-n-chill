<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MsPaket;
use App\Models\MsRuangan;
use App\Models\PenetapanHarga;
use Illuminate\Http\Request;

class PaketController extends Controller
{
    public function index()
    {
        $pakets = MsPaket::latest()->get();
        return view('admin.paket.index', compact('pakets'));
    }

    public function create()
    {
        $ruangans = MsRuangan::where('is_active', 1)->get();
        return view('admin.paket.create', compact('ruangans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_paket'       => 'required|string|max:40|unique:ms_paket,nama_paket',
            'deskripsi_paket'  => 'nullable|string',
            'maksimal_orang'   => 'required|integer|min:1',
            'is_active'        => 'required|in:0,1',
            'ruangan_ids'      => 'nullable|array',
            'ruangan_ids.*'    => 'exists:ms_ruangan,id_ruangan',
            'tipe_hari'        => 'nullable|in:harian,akhir_pekan,liburan',
            'durasi_jam'       => 'nullable|array',
            'durasi_jam.*'     => 'nullable|integer|min:1',
            'harga'            => 'nullable|array',
            'harga.*'          => 'nullable|string', // ← ubah ke string biar titik tidak dipotong
        ]);

        $paket = MsPaket::create([
            'nama_paket'      => $request->nama_paket,
            'deskripsi_paket' => $request->deskripsi_paket,
            'maksimal_orang'  => $request->maksimal_orang,
            'is_active'       => $request->is_active,
        ]);

        if ($request->ruangan_ids && $request->durasi_jam && $request->tipe_hari) {
            foreach ($request->ruangan_ids as $ruanganId) {
                foreach ($request->durasi_jam as $index => $durasi) {
                    if ($durasi && !empty($request->harga[$index])) {
                        // ← strip titik ribuan sebelum simpan
                        $harga = (int) str_replace('.', '', $request->harga[$index]);

                        PenetapanHarga::create([
                            'id_ruangan' => $ruanganId,
                            'id_paket'   => $paket->id_paket,
                            'tipe_hari'  => $request->tipe_hari,
                            'durasi_jam' => $durasi,
                            'harga'      => $harga,
                        ]);
                    }
                }
            }
        }

        return redirect()->route('admin.paket.index')
            ->with('success', 'Paket berhasil dibuat!');
    }

    public function show($id)
    {
        $paket = MsPaket::with('penetapanHarga.ruangan')->findOrFail($id);
        return view('admin.paket.show', compact('paket'));
    }

    public function edit($id)
    {
        $paket    = MsPaket::with('penetapanHarga.ruangan')->findOrFail($id);
        $ruangans = MsRuangan::where('is_active', 1)->get();
        return view('admin.paket.edit', compact('paket', 'ruangans'));
    }

        public function update(Request $request, $id)
    {
        $request->validate([
            'nama_paket'      => 'required|string|max:40',
            'deskripsi_paket' => 'nullable|string',
            'maksimal_orang'  => 'required|integer|min:1',
            'is_active'       => 'required|in:0,1',
            'ruangan_ids'     => 'nullable|array',
            'ruangan_ids.*'   => 'exists:ms_ruangan,id_ruangan',
            'tipe_hari'       => 'nullable|in:harian,akhir_pekan,liburan',
            'durasi_jam'      => 'nullable|array',
            'durasi_jam.*'    => 'integer|min:1',
            'harga'           => 'nullable|array',
            'harga.*'         => 'nullable|string', // ← string biar titik tidak terpotong
        ]);

        $paket = MsPaket::findOrFail($id);
        $paket->update([
            'nama_paket'      => $request->nama_paket,
            'deskripsi_paket' => $request->deskripsi_paket,
            'maksimal_orang'  => $request->maksimal_orang,
            'is_active'       => $request->is_active,
        ]);

        if ($request->ruangan_ids && $request->durasi_jam && $request->tipe_hari) {
            foreach ($request->ruangan_ids as $ruanganId) {
                foreach ($request->durasi_jam as $index => $durasi) {
                    if ($durasi && isset($request->harga[$index])) {
                        // ← strip titik ribuan di dalam loop
                        $harga = (int) str_replace('.', '', $request->harga[$index]);

                        PenetapanHarga::create([
                            'id_ruangan' => $ruanganId,
                            'id_paket'   => $paket->id_paket,
                            'tipe_hari'  => $request->tipe_hari,
                            'durasi_jam' => $durasi,
                            'harga'      => $harga,
                        ]);
                    }
                }
            }
        }

        return redirect()->route('admin.paket.index')
            ->with('success', 'Paket berhasil diupdate!');
    }
    public function destroy($id)
    {
        $paket = MsPaket::findOrFail($id);

        // Hapus transaksi yang terkait dengan penetapan harga paket ini
        $penetapanIds = $paket->penetapanHarga()->pluck('id_penetapan_harga');
        
        \App\Models\TrTransaksi::whereIn('id_penetapan_harga', $penetapanIds)->delete();

        // Baru hapus penetapan harga dan paket
        $paket->penetapanHarga()->delete();
        $paket->delete();

        return redirect()->route('admin.paket.index')
            ->with('success', 'Paket berhasil dihapus!');
    }

    // AJAX: ambil ruangan by kategori
    public function getRuanganByKategori($kategori)
    {
        $ruangans = MsRuangan::where('kategori', $kategori)
            ->where('is_active', 1)
            ->get(['id_ruangan', 'nama_ruangan']);
        return response()->json($ruangans);
    }
}