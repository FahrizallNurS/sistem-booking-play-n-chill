<?php

namespace App\Http\Controllers\Admin\Fb;

use App\Http\Controllers\Controller;
use App\Models\TrPos;
use App\Models\User;
use App\Services\FbService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class AdminFbController extends Controller
{
    public function __construct(private FbService $fbService) {}

    public function index(Request $request)
    {

        TrPos::where('status_pesanan', 'Menunggu')
            ->where('sumber_pesanan', 'Online')
            ->where('created_at', '<', now()->subMinutes(15))
            ->update([
                'status_pesanan'    => 'Dibatalkan',
                'status_pembayaran' => 'kadaluarsa',
            ]);

        $query = TrPos::query();

        if ($request->filled('tanggal')) {
            $query->whereDate('created_at', $request->tanggal);
        }
        if ($request->filled('sumber')) {
            $query->where('sumber_pesanan', $request->sumber);
        }
        if ($request->filled('status')) {
            $query->where('status_pesanan', $request->status);
        }

        $transaksiFb = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.fb.transaksi.index', compact('transaksiFb'));
    }

    public function updateStatus(Request $request, $id)
    {
        $pos = TrPos::findOrFail($id);
        $pos->update([
            'status_pesanan' => $request->status_pesanan,
            'status_pembayaran' => $request->status_pembayaran
        ]);

        return redirect()->back()->with('success', 'Status transaksi berhasil diperbarui!');
    }

    /**
     * Simpan pesanan F&B mandiri (tanpa booking) sekaligus finalisasi:
     * potong stock + generate PDF struk, semua dalam satu kali jalan lewat
     * FbService::createPos(). Berbeda dari flow booking manual (2 tahap:
     * create lalu finalisasi terpisah), di sini cukup 1 tahap karena F&B
     * mandiri cuma punya satu entry point -- tidak ada skenario reprint /
     * re-finalize dari server yang butuh idempotency guard.
     *
     * Response berupa JSON (bukan redirect), karena front-end
     * (modal-rincian-fb.blade.php) memanggil endpoint ini lewat AJAX.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_pelanggan'     => 'required|string|max:50',
            'no_telp'            => 'nullable|string|max:15',
            'catatan'            => 'nullable|string|max:50',
            'metode_pembayaran'  => 'required|in:TUNAI,QRIS',
            // Beda dari items di booking manual (nullable): di sini WAJIB
            // ada minimal 1 item, karena seluruh transaksi ini memang
            // pesanan F&B -- kalau cart kosong, tidak ada alasan membuat
            // baris tr_pos sama sekali.
            'items'              => 'required|array|min:1',
            'items.*.id_produk'  => [
                'required',
                'integer',
                Rule::exists('ms_produk', 'id_produk')->where('is_active', 1),
            ],
            'items.*.jumlah'     => 'required|integer|min:1',
        ]);

        try {
            $pos = $this->fbService->createPos([
                'nama_pelanggan'    => $validated['nama_pelanggan'],
                'no_telp'           => $validated['no_telp'] ?? null,
                'catatan'           => $validated['catatan'] ?? null,
                'metode_pembayaran' => $validated['metode_pembayaran'],
                'items'             => $validated['items'],
                'dicetak_oleh'      => auth()->user()->nama_pengguna,
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors'  => $e->errors(),
            ], 422);
        }

        return response()->json([
            'success' => true,
            'message' => 'Pesanan berhasil dibuat.',
            'data'    => [
                'id_pos'   => $pos->id_pos,
                'kode_pos' => $pos->kode_pos,
                'pdf_url'  => asset('assets/struk/' . $pos->kode_pos . '.pdf'),
            ],
        ]);
    }

    public function create()
    {
        // 1. Ambil semua produk F&B yang statusnya aktif beserta relasi kategorinya
        $produks = \App\Models\MsProduk::with('subKategori')
                    ->where('is_active', 1)
                    ->get();

        // 2. Ekstrak daftar nama kategori yang unik untuk tombol tab filter di Modal
        $kategoriFnb = $produks->pluck('subKategori.sub_kategori_produk')
                       ->filter()
                       ->unique()
                       ->values();

        // 3. Lempar datanya ke halaman form tambah pesanan
        return view('admin.fb.transaksi.tambah-pesanan', compact('produks', 'kategoriFnb'));
    }
}