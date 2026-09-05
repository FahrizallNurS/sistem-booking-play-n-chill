<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MsPaket;
use App\Models\MsRuangan;
use App\Models\MsSubKategoriPaket;
use App\Models\PenetapanHarga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            'penetapanHarga' => function ($q) {
                $q->currentPrices()->with('ruangan');
            },
        ])->get();

        // FIX: badge "punya transaksi" di halaman index harus mengecek SEMUA
        // penetapan harga milik paket (current + historical), bukan cuma yang
        // current. Kalau tidak, admin bisa lihat tombol "Hapus Permanen" untuk
        // paket yang sebenarnya punya transaksi di harga lama — baru ditolak
        // setelah submit, karena destroy() sudah benar mengecek semuanya.
        $paketIdsWithTransaksi = PenetapanHarga::whereIn('id_paket', $pakets->pluck('id_paket'))
            ->whereHas('transaksis')
            ->distinct()
            ->pluck('id_paket')
            ->flip();

        $pakets->each(function ($paket) use ($paketIdsWithTransaksi) {
            $paket->punya_transaksi = $paketIdsWithTransaksi->has($paket->id_paket);
        });

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

        // FIX: seluruh proses (buat/pakai sub-kategori -> buat paket -> insert
        // banyak baris penetapan harga) dibungkus transaction. Kalau ada error
        // di tengah loop matrix, semuanya rollback, tidak ada state "setengah jalan".
        return DB::transaction(function () use ($request) {
            $idSubKategori = $this->resolveSubKategori($request);

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
        });
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
            'nama_paket' => [
                'required',
                'string',
                'max:40',
                \Illuminate\Validation\Rule::unique('ms_paket', 'nama_paket')
                    ->where('is_active', 1)
                    ->ignore($id, 'id_paket'),
            ],
            'id_sub_kategori_paket'  => 'required_without:sub_kategori_baru|nullable|exists:ms_sub_kategori_paket,id_sub_kategori_paket',
            'sub_kategori_baru'      => 'required_without:id_sub_kategori_paket|nullable|string|max:50',
            'deskripsi_paket' => 'nullable|string',
            'maksimal_orang'  => 'required|integer|min:1',
            'is_active'       => 'required|in:0,1',
        ]);

        $validator->validate();

        $idSubKategori = $this->resolveSubKategori($request);

        $paket = MsPaket::findOrFail($id);
        $paket->update([
            'nama_paket'      => $request->nama_paket,
            'ms_sub_kategori_paket_id_sub_kategori_paket' => $idSubKategori,
            'deskripsi_paket' => $request->deskripsi_paket,
            'maksimal_orang'  => $request->maksimal_orang,
            'is_active'       => $request->is_active,
        ]);

        return redirect()->route('admin.paket.edit', $id)
            ->with('success', 'Info paket berhasil diupdate!');
    }

    public function storePenetapanHarga(Request $request, $id)
    {
        $paket = MsPaket::findOrFail($id);

        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
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

        DB::transaction(function () use ($request, $paket) {
            $this->savePenetapanHargaMatrix($request, $paket->id_paket);
        });

        return redirect()->route('admin.paket.edit', $paket->id_paket)
            ->with('success', 'Penetapan harga berhasil disimpan!');
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

    // FIX 1: UPDATE HARGA INLINE (SILENT INSERT JIKA ADA TRANSAKSI)
    public function updatePenetapanInline(Request $request, $id)
    {
        $penetapan = PenetapanHarga::findOrFail($id);

        // Hanya validasi harga, karena SKU read-only
        $validated = $request->validate([
            'harga' => 'required|string',
        ]);

        $harga = (int) str_replace('.', '', $validated['harga']);

        if ($penetapan->transaksis()->exists()) {
            // SILENT INSERT: Bikin baris baru (sebagai currentPrices), baris lama jadi history
            PenetapanHarga::create([
                'id_paket'   => $penetapan->id_paket,
                'id_ruangan' => $penetapan->id_ruangan,
                'tipe_hari'  => $penetapan->tipe_hari,
                'durasi_jam' => $penetapan->durasi_jam,
                'sku'        => $penetapan->sku, // SKU tetap menggunakan yang lama
                'harga'      => $harga,
            ]);
        } else {
            // NORMAL UPDATE: Karena belum pernah ada transaksi
            $penetapan->update([
                'harga' => $harga,
            ]);
        }

        return back()->with('success', 'Harga berhasil diupdate!');
    }

    // FIX 2: METHOD BARU UNTUK MODAL TAMBAH
    public function storePenetapanModal(Request $request, $id)
    {
        $paket = MsPaket::findOrFail($id);

        $validated = $request->validate([
            'id_ruangan' => 'required|exists:ms_ruangan,id_ruangan',
            'tipe_hari'  => 'required|in:harian,akhir_pekan,liburan',
            'durasi_jam' => 'required|integer|min:1',
            'harga'      => 'required|string',
            'sku'        => 'required|string|max:10',
        ]);

        $harga = (int) str_replace('.', '', $validated['harga']);
        $sku = trim($validated['sku']);

        // CEK 1: Mencegah Kombinasi Dobel (di harga yang aktif)
        $kombinasiAda = PenetapanHarga::currentPrices()
            ->where('id_paket', $paket->id_paket)
            ->where('id_ruangan', $validated['id_ruangan'])
            ->where('tipe_hari', $validated['tipe_hari'])
            ->where('durasi_jam', $validated['durasi_jam'])
            ->exists();

        if ($kombinasiAda) {
            return back()
                ->withErrors(['kombinasi' => 'Kombinasi Ruangan, Hari, dan Durasi ini sudah ada. Silakan gunakan tombol Edit pada tabel.'])
                ->withInput();
        }

        // CEK 2: Mencegah SKU kembar (hanya ngecek di harga yang sedang aktif)
        $skuBentrok = PenetapanHarga::currentPrices()
            ->where('sku', $sku)
            ->exists();

        if ($skuBentrok) {
            return back()
                ->withErrors(['sku' => "SKU \"{$sku}\" sudah dipakai di sistem. Gunakan SKU lain."])
                ->withInput();
        }

        // AMAN, Lakukan Insert
        PenetapanHarga::create([
            'id_paket'   => $paket->id_paket,
            'id_ruangan' => $validated['id_ruangan'],
            'tipe_hari'  => $validated['tipe_hari'],
            'durasi_jam' => $validated['durasi_jam'],
            'harga'      => $harga,
            'sku'        => $sku,
        ]);

        return back()->with('success', 'Penetapan harga baru berhasil ditambahkan!');
    }

    private function resolveSubKategori(Request $request): int
    {
        if ($request->filled('sub_kategori_baru')) {
            $nama = trim($request->sub_kategori_baru);

            $existing = MsSubKategoriPaket::whereRaw('LOWER(nama_sub_kategori) = ?', [mb_strtolower($nama)])
                ->first();

            if ($existing) {
                if (!$existing->is_active) {
                    $existing->update(['is_active' => 1]);
                }
                return $existing->id_sub_kategori_paket;
            }

            $subKategori = MsSubKategoriPaket::create([
                'nama_sub_kategori' => $nama,
                'is_active'         => 1,
            ]);

            return $subKategori->id_sub_kategori_paket;
        }

        return (int) $request->id_sub_kategori_paket;
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