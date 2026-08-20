<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MsPaket;
use App\Models\MsRuangan;
use App\Models\MsSubKategoriPaket;
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
            'subKategori',
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
        $subKategoris = MsSubKategoriPaket::where('is_active', 1)->orderBy('nama_sub_kategori')->get();
        return view('admin.paket.create', compact('ruangans', 'subKategoris'));
    }

    public function store(Request $request)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'nama_paket' => [
                'required',
                'string',
                'max:40',
                \Illuminate\Validation\Rule::unique('ms_paket', 'nama_paket')
                    ->where('is_active', 1)
            ],
            'id_sub_kategori_paket'  => 'required_without:sub_kategori_baru|nullable|exists:ms_sub_kategori_paket,id_sub_kategori_paket',
            'sub_kategori_baru'      => 'required_without:id_sub_kategori_paket|nullable|string|max:50',
            'deskripsi_paket'  => 'nullable|string',
            'maksimal_orang'   => 'required|integer|min:1',
            'is_active'        => 'required|in:0,1',
            'ruangan_ids'      => 'nullable|array',
            'ruangan_ids.*'    => 'exists:ms_ruangan,id_ruangan',
            'tipe_hari'        => 'nullable|in:harian,akhir_pekan,liburan',
            'durasi_jam'       => 'nullable|array',
            'durasi_jam.*'     => 'nullable|integer|min:1',
            'harga'            => 'nullable|array',
            'sku'              => 'nullable|array',
        ]);

        $validator->after(fn($v) => $this->validateMatrixCells($request, $v));
        $validator->validate();

        // Sub kategori baru? buat dulu, kalau enggak pakai yang sudah dipilih
        if ($request->filled('sub_kategori_baru')) {
            $subKategori = MsSubKategoriPaket::create([
                'nama_sub_kategori' => $request->sub_kategori_baru,
                'is_active'         => 1,
            ]);
            $idSubKategori = $subKategori->id_sub_kategori_paket;
        } else {
            $idSubKategori = $request->id_sub_kategori_paket;
        }

        $paket = MsPaket::create([
            'nama_paket'      => $request->nama_paket,
            'ms_sub_kategori_paket_id_sub_kategori_paket' => $idSubKategori,
            'deskripsi_paket' => $request->deskripsi_paket,
            'maksimal_orang'  => $request->maksimal_orang,
            'is_active'       => $request->is_active,
        ]);

        $this->savePenetapanHargaMatrix($request, $paket->id_paket);

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
        $subKategoris = MsSubKategoriPaket::where('is_active', 1)->orderBy('nama_sub_kategori')->get();
        
        return view('admin.paket.edit', compact('paket', 'ruangans', 'subKategoris'));
    }

    public function update(Request $request, $id)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'nama_paket'      => 'required|string|max:40',
            'id_sub_kategori_paket'  => 'required_without:sub_kategori_baru|nullable|exists:ms_sub_kategori_paket,id_sub_kategori_paket',
            'sub_kategori_baru'      => 'required_without:id_sub_kategori_paket|nullable|string|max:50',
            'deskripsi_paket' => 'nullable|string',
            'maksimal_orang'  => 'required|integer|min:1',
            'is_active'       => 'required|in:0,1',
            'ruangan_ids'     => 'nullable|array',
            'ruangan_ids.*'   => 'exists:ms_ruangan,id_ruangan',
            'tipe_hari'       => 'nullable|in:harian,akhir_pekan,liburan',
            'durasi_jam'      => 'nullable|array',
            'durasi_jam.*'    => 'integer|min:1',
            'harga'           => 'nullable|array',
            'sku'             => 'nullable|array',
        ]);

        $validator->after(fn($v) => $this->validateMatrixCells($request, $v));
        $validator->validate();

        if ($request->filled('sub_kategori_baru')) {
            $subKategori = MsSubKategoriPaket::create([
                'nama_sub_kategori' => $request->sub_kategori_baru,
                'is_active'         => 1,
            ]);
            $idSubKategori = $subKategori->id_sub_kategori_paket;
        } else {
            $idSubKategori = $request->id_sub_kategori_paket;
        }

        $paket = MsPaket::findOrFail($id);
        $paket->update([
            'nama_paket'      => $request->nama_paket,
            'ms_sub_kategori_paket_id_sub_kategori_paket' => $idSubKategori,
            'deskripsi_paket' => $request->deskripsi_paket,
            'maksimal_orang'  => $request->maksimal_orang,
            'is_active'       => $request->is_active,
        ]);

        $this->savePenetapanHargaMatrix($request, $paket->id_paket);

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
        if ($penetapan->transaksis()->exists()) {
            return back()->with('error', 'Penetapan harga tidak dapat dihapus karena sudah ada transaksi terkait.');
        }
        
        $penetapan->delete();
        
        return back()->with('success', 'Penetapan harga berhasil dihapus!');
    }

    private function validateMatrixCells(Request $request, $validator): void
{
    if (!$request->ruangan_ids || !$request->durasi_jam || !$request->tipe_hari) {
        return;
    }

    $durasiList = array_unique(array_filter($request->durasi_jam));
    $skusInRequest = [];

    foreach ($request->ruangan_ids as $ruanganId) {
        foreach ($durasiList as $durasi) {
            $hargaRaw = $request->input("harga.$ruanganId.$durasi");
            if ($hargaRaw === null || $hargaRaw === '') {
                continue; // sel memang dikosongkan, dilewati
            }

            $field = "sku.$ruanganId.$durasi";
            $sku = trim((string) $request->input($field));

            if ($sku === '') {
                $validator->errors()->add($field, "SKU wajib diisi (ada harga untuk durasi {$durasi} jam).");
                continue;
            }

            if (mb_strlen($sku) > 10) {
                $validator->errors()->add($field, 'SKU maksimal 10 karakter.');
                continue;
            }

            if (isset($skusInRequest[$sku])) {
                $validator->errors()->add($field, "SKU \"{$sku}\" dipakai lebih dari sekali di form ini.");
                continue;
            }

            $skusInRequest[$sku] = true;
        }
    }

    if (!empty($skusInRequest)) {
        $clashes = PenetapanHarga::whereIn('sku', array_keys($skusInRequest))
            ->pluck('sku')
            ->unique();

        foreach ($clashes as $clashSku) {
            $validator->errors()->add('sku_conflict', "SKU \"{$clashSku}\" sudah dipakai di sistem, gunakan SKU lain.");
        }
    }
}
    private function savePenetapanHargaMatrix(Request $request, int $idPaket): void
    {
        if (!$request->ruangan_ids || !$request->durasi_jam || !$request->tipe_hari) {
            return;
        }

        $durasiList = array_unique(array_filter($request->durasi_jam));

        foreach ($request->ruangan_ids as $ruanganId) {
            foreach ($durasiList as $durasi) {
                $hargaRaw = $request->input("harga.$ruanganId.$durasi");
                if ($hargaRaw === null || $hargaRaw === '') {
                    continue;
                }

                $harga = (int) str_replace('.', '', $hargaRaw);
                $sku   = trim((string) $request->input("sku.$ruanganId.$durasi"));

                PenetapanHarga::create([
                    'id_ruangan' => $ruanganId,
                    'id_paket'   => $idPaket,
                    'tipe_hari'  => $request->tipe_hari,
                    'durasi_jam' => $durasi,
                    'harga'      => $harga,
                    'sku'        => $sku,
                ]);
            }
        }
    }

}