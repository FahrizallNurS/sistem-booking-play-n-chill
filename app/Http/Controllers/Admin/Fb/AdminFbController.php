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
            'status_pesanan'    => $request->status_pesanan,
            'status_pembayaran' => $request->status_pembayaran,
            'id_admin'          => auth()->user()->id_pengguna,
        ]);

        return redirect()->back()->with('success', 'Status transaksi berhasil diperbarui!');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_pelanggan'     => 'required|string|max:50',
            'no_telp'            => 'nullable|string|max:15',
            'catatan'            => 'nullable|string|max:50',
            'metode_pembayaran'  => 'required|in:TUNAI,QRIS',
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
                'id_admin'          => auth()->user()->id_pengguna,
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
        $produks = \App\Models\MsProduk::with('subKategori')
                    ->where('is_active', 1)
                    ->get();

        $kategoriFnb = $produks->pluck('subKategori.sub_kategori_produk')
                       ->filter()
                       ->unique()
                       ->values();

        return view('admin.fb.transaksi.tambah-pesanan', compact('produks', 'kategoriFnb'));
    }
    
}