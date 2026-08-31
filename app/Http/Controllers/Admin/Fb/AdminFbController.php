<?php

namespace App\Http\Controllers\Admin\Fb;

use App\Http\Controllers\Controller;
use App\Models\TrPos;
use App\Models\User;
use App\Models\MsPengaturan;
use App\Models\TrPosDetail;
use App\Models\MsProduk;
use App\Services\FbService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use PDF;

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
            'uang_diterima'      => 'required_if:metode_pembayaran,TUNAI|nullable|integer|min:0',
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
                'uang_diterima'     => $validated['uang_diterima'] ?? null,
                'items'             => $validated['items'],
                'id_admin'          => auth()->user()->id_pengguna,
                'dicetak_oleh'      => auth()->user()->nama_pengguna,
            ]);

            // 🔹 JURUS ELOQUENT AMAN: Simpan nama ke kolom baru & buang ID Octopus 🔹
            $pos->nama_pelanggan = $validated['nama_pelanggan'];
            $pos->id_pengguna = null; 
            $pos->save();

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
                'pdf_url'  => url('/admin/fb/transaksi/cetak-struk/' . $pos->id_pos),
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

    public function cetakStruk($id)
    {
        $pos = TrPos::findOrFail($id);
        $pengaturan = MsPengaturan::current();

        $rincian = TrPosDetail::where('id_pos', $id)->get();
        
        $items = [];
        $subTotal = 0;
        
        foreach ($rincian as $detail) {
            $namaProduk = MsProduk::where('id_produk', $detail->id_produk)->value('nama_produk') ?? 'Produk F&B';

            $items[] = [
                'qty' => $detail->jumlah,
                'nama' => $namaProduk,
                'subtotal' => $detail->subtotal,
                'sub' => ''
            ];
            $subTotal += $detail->subtotal;
        }

       // Tentukan nama customer: Prioritaskan input manual kasir terlebih dahulu
        if (!empty($pos->nama_pelanggan)) {
            $customer = $pos->nama_pelanggan;
        } elseif ($pos->id_pengguna) {
            $customer = User::where('id_pengguna', $pos->id_pengguna)->value('nama_pengguna');
        } else {
            $customer = 'Pelanggan Umum';
        }

        $waktu = \Carbon\Carbon::parse($pos->created_at)->format('d/m/Y H:i');

            $data = [
            'pengaturan' => $pengaturan,
            'kodeSewa'  => $pos->kode_pos,
            'nomorNota' => $pos->nomor_nota ?: ('FNBPNC-' . str_pad($pos->id_pos, 3, '0', STR_PAD_LEFT)), // fallback data lama
            'waktu' => $waktu,
            'kasir' => $pos->dicetak_oleh ?? auth()->user()->nama_pengguna,
            'customer' => $customer,
            'items' => $items,
            'subTotal' => $subTotal,
            'totalTagihan' => $pos->total_pos,
            'jumlahDp' => 0, 
            'metodePembayaran' => $pos->metode_pembayaran ?? 'TUNAI',
            'totalBayar' => $pos->total_pos,
            'uangDiterima' => $pos->uang_diterima,
            'kembalian' => $pos->kembalian,
            'catatan' => $pos->catatan,
            'waktuPembayaran' => $waktu,
            'dicetakOleh' => auth()->user()->nama_pengguna,
        ];

        $pdf = PDF::loadView('admin.bookings.struk-pdf', $data);
        
        // Atur ukuran kertas struk kasir 58mm (46mm margin custom)
        $pdf->setPaper([0, 0, 130, 566], 'portrait'); 
        
        return $pdf->stream('Struk-FNB-' . $pos->id_pos . '.pdf');
    }
    
}