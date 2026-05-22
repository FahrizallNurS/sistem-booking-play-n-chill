<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MsPaket;
use App\Models\MsRuangan;
use App\Models\PenetapanHarga;
use Illuminate\Http\Request;

class PaketController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status', 'all');
        
        $query = MsPaket::latest();
        
        if ($status === 'active') {
            $query->where('is_active', 1);
        } elseif ($status === 'inactive') {
            $query->where('is_active', 0);
        }
        
        $pakets = $query->with([
            'penetapanHarga' => function($q) {
                $q->currentPrices()->with('ruangan');
            },
            'penetapanHarga.transaksis'
        ])->get();
        
        return view('admin.paket.index', compact('pakets', 'status'));
    }

    public function create()
    {
        $ruangans = MsRuangan::where('is_active', 1)->get();
        return view('admin.paket.create', compact('ruangans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_paket' => [
            'required',
            'string',
            'max:40',
            \Illuminate\Validation\Rule::unique('ms_paket', 'nama_paket')
                ->where('is_active', 1)
            ],
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
        $paket = MsPaket::with([
            'penetapanHarga' => function($q) {
                $q->currentPrices()->with('ruangan');
            }
        ])->findOrFail($id);
        
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
            'harga.*'         => 'nullable|string',
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
                        $harga = (int) str_replace('.', '', $request->harga[$index]);

                        $existing = PenetapanHarga::findExisting(
                            $ruanganId, 
                            $paket->id_paket, 
                            $request->tipe_hari, 
                            $durasi
                        );
                        if (!$existing || $existing->harga != $harga) {
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
        }

        return redirect()->route('admin.paket.index')
            ->with('success', 'Paket berhasil diupdate!');
    }

    public function toggleAktif($id)
    {
        $paket = MsPaket::findOrFail($id);
        
        $newStatus = $paket->is_active == 1 ? 0 : 1;
        $paket->update(['is_active' => $newStatus]);
        
        $message = $newStatus == 1 
            ? 'Paket berhasil diaktifkan!' 
            : 'Paket berhasil dinonaktifkan!';

        return redirect()->route('admin.paket.index')
            ->with('success', $message);
    }

    public function activate($id)
    {
        $paket = MsPaket::findOrFail($id);
        
        $paket->update(['is_active' => 1]);

        return redirect()->route('admin.paket.index')
            ->with('success', 'Paket berhasil diaktifkan kembali!');
    }

    public function getRuanganByKategori($kategori)
    {
        $ruangans = MsRuangan::where('kategori', $kategori)
            ->where('is_active', 1)
            ->get(['id_ruangan', 'nama_ruangan']);
        return response()->json($ruangans);
    }

    public function destroy($id){

        $paket = MsPaket::findOrFail($id);
        $punya_transaksi = $paket->penetapanHarga()
        ->whereHas('transaksis')
        ->exists();

         if ($punya_transaksi) {
            return back()->with('error', 'Paket tidak dapat dihapus karena memiliki data transaksi.');
        }

        $paket->penetapanHarga()->delete();
        $paket->delete();
            return redirect()->route('admin.paket.index')
                ->with('success', 'Paket berhasil dihapus!');

    }
    
    public function destroyPenetapan($id)
    {
        $penetapan = PenetapanHarga::findOrFail($id);
        
        // Cek apakah ada transaksi
        if ($penetapan->transaksis()->exists()) {
            return back()->with('error', 'Penetapan harga tidak dapat dihapus karena sudah ada transaksi terkait.');
        }
        
        $penetapan->delete();
        
        return back()->with('success', 'Penetapan harga berhasil dihapus!');
    }

}