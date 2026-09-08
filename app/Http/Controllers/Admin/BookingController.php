<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TrTransaksi;
use App\Models\MsRuangan;
use App\Models\MsPaket;
use App\Models\MsProduk;
use App\Models\PenetapanHarga;
use App\Services\BookingService;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class BookingController extends Controller
{
    public function __construct(private BookingService $bookingService) {}

    public function index(Request $request)
    {
        // [BARU] Eksekusi update otomatis sebelum data ditarik
        $this->cancelExpiredBookings();
        $this->completeExpiredBookings();

        $query = TrTransaksi::with(['penetapanHarga.ruangan', 'penetapanHarga.paket', 'pengguna'])
            ->whereNotIn('status_sewa', ['dibatalkan', 'selesai'])
            ->latest('created_at');

        // Filter sumber booking (Online / Kasir). Kosong = tampilkan semua.
        if ($request->filled('sumber_booking')) {
            $query->where('sumber_booking', $request->sumber_booking);
        }

        if ($request->filled('status_sewa')) {
            $query->where('status_sewa', $request->status_sewa);
        }

        if ($request->filled('status_pembayaran')) {
            $query->where('status_pembayaran', $request->status_pembayaran);
        }

        if ($request->filled('tanggal')) {
            $query->whereDate('waktu_mulai', $request->tanggal);
        }

        if ($request->filled('ruangan')) {
            $query->whereHas('penetapanHarga', function ($q) use ($request) {
                $q->where('id_ruangan', $request->ruangan);
            });
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kode_sewa', 'like', "%{$search}%")
                  ->orWhereHas('pengguna', fn ($u) => $u->where('nama_pengguna', 'like', "%{$search}%"));
            });
        }

        $bookings = $query->paginate(15)->withQueryString();
        $ruangans = MsRuangan::where('is_active', 1)->get();

        [$produks, $kategoriFnb] = $this->getProdukFnbData();

        return view('admin.bookings.index', compact('bookings', 'ruangans', 'produks', 'kategoriFnb'));
    }

    // ============================================================
    // CREATE (Form Tambah Booking Manual - data ruangan/paket asli)
    // ============================================================
    public function create()
    {
        $ruangans = MsRuangan::where('is_active', 1)->orderBy('nama_ruangan')->get();
        $pakets   = MsPaket::where('is_active', 1)->orderBy('nama_paket')->get();

        // Produk F&B untuk modal keranjang — sama seperti di index(), supaya
        // admin bisa langsung tambah pesanan F&B saat bikin booking baru.
        [$produks, $kategoriFnb] = $this->getProdukFnbData();

        return view('admin.bookings.create', compact('ruangans', 'pakets', 'produks', 'kategoriFnb'));
    }

    // ============================================================
    // AJAX: ambil kombinasi durasi + tipe_hari + harga
    // ============================================================
    public function getPenetapanHarga(Request $request): JsonResponse
    {
        $request->validate([
            'ruangan' => 'required|exists:ms_ruangan,id_ruangan',
            'paket'   => 'required|exists:ms_paket,id_paket',
        ]);

        $options = PenetapanHarga::where('id_ruangan', $request->ruangan)
            ->where('id_paket', $request->paket)
            ->currentPrices()
            ->orderBy('durasi_jam')
            ->get(['id_penetapan_harga', 'durasi_jam', 'tipe_hari', 'harga', 'sku']);

        // Dibungkus {options: [...]} supaya cocok dengan JS di create.blade.php
        // yang membaca res.options
        return response()->json(['options' => $options]);
    }

    // ============================================================
    // AJAX: Cek Ketersediaan Jadwal (Pre-Check Validasi Awal)
    // ============================================================
    public function cekJadwal(Request $request): JsonResponse
    {
        $request->validate([
            'ruangan'     => 'required|exists:ms_ruangan,id_ruangan',
            'waktu_mulai' => 'required|date',
            'durasi_jam'  => 'required|numeric|min:1'
        ]);

        $ruanganId  = $request->ruangan;
        $waktuMulai = \Carbon\Carbon::parse($request->waktu_mulai);
        
        // PERBAIKAN: Kita paksa durasi_jam menjadi integer (int)
        $durasiJam = (int) $request->durasi_jam;
        $waktuSelesai = $waktuMulai->copy()->addHours($durasiJam);

        // Cek apakah ada jadwal yang tumpang tindih
        $isBentrok = TrTransaksi::whereHas('penetapanHarga', function ($q) use ($ruanganId) {
                $q->where('id_ruangan', $ruanganId);
            })
            ->whereNotIn('status_sewa', ['selesai', 'dibatalkan']) 
            ->where(function ($query) use ($waktuMulai, $waktuSelesai) {
                // Logika akurat: Booking lama mulai SEBELUM booking baru selesai 
                // DAN booking lama selesai SESUDAH booking baru mulai
                $query->where('waktu_mulai', '<', $waktuSelesai)
                      ->where('waktu_selesai', '>', $waktuMulai);
            })
            ->exists();

        if ($isBentrok) {
            return response()->json([
                'tersedia' => false,
                'pesan'    => 'Jadwal Bentrok! Ruangan ini sudah dipakai pada jam tersebut. Silakan pilih waktu lain Ganteng.'
            ]);
        }

        return response()->json([
            'tersedia' => true,
            'pesan'    => 'Ruangan tersedia.'
        ]);
    }

    public function storeManual(Request $request): JsonResponse
    {
        // 1. Definisikan aturan validasi HANYA untuk data yang dikirim dari form
            $validated = $request->validate([
            'nama_pelanggan'     => 'required|string|max:100',
            'no_telp'            => 'nullable|string|max:15',
            'email'              => 'nullable|email|max:100',
            'ruangan'            => 'required|integer|exists:ms_ruangan,id_ruangan',
            'paket'              => 'required|integer|exists:ms_paket,id_paket',
            'id_penetapan_harga' => 'required|integer|exists:penetapan_harga,id_penetapan_harga',
            'waktu_mulai'        => 'required|date_format:Y-m-d\TH:i',
            'metode_pembayaran'  => 'required|in:TUNAI,QRIS',
            'uang_diterima'      => 'required_if:metode_pembayaran,TUNAI|nullable|integer|min:0',
            'catatan'            => 'nullable|string|max:100',
            'items'              => 'nullable|array',
            'items.*.id_produk'  => [
                'required_with:items',
                'integer',
                Rule::exists('ms_produk', 'id_produk')->where('is_active', 1),
            ],
            'items.*.jumlah'     => 'required_with:items|integer|min:1',
            // Baris 'dicetak_oleh' yang bikin error 500 sudah dihapus dari sini
        ]);

        try {
                $transaksi = $this->bookingService->createManualBooking([
                'nama_pelanggan'     => $validated['nama_pelanggan'],
                'no_telp'            => $validated['no_telp'] ?? null,
                'email'              => $validated['email'] ?? null,
                'id_ruangan'         => $validated['ruangan'],
                'id_paket'           => $validated['paket'],
                'id_penetapan_harga' => $validated['id_penetapan_harga'],
                'waktu_mulai'        => $validated['waktu_mulai'],
                'metode_pembayaran'  => $validated['metode_pembayaran'],
                'uang_diterima'      => $validated['uang_diterima'] ?? null,
                'catatan'            => $validated['catatan'] ?? null,
                'items'              => $validated['items'] ?? [],
                'id_admin'           => auth()->id(),
                'dicetak_oleh'       => auth()->user()->nama_pengguna,
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors'  => $e->errors(),
            ], 422);
        }

        // 2. Kembalikan respon sukses beserta link URL PDF-nya ke frontend
        return response()->json([
            'success' => true,
            'message' => 'Pesanan berhasil dibuat.',
            'data'    => [
                'kode_sewa'    => $transaksi->kode_sewa,
                'id_transaksi' => $transaksi->id_transaksi,
                'dicetak_oleh' => auth()->user()->nama_pengguna,
                'pdf_url'      => asset('assets/struk/' . $transaksi->kode_sewa . '.pdf'),
            ],
        ]);
    }

    // ============================================================
    // SHOW
    // ============================================================
    public function show($id)
    {
        // [BARU] Pastikan juga ditaruh di fungsi show
        // Biar misal admin refresh halaman detail, statusnya ikut terupdate otomatis
        $this->cancelExpiredBookings();
        $this->completeExpiredBookings();

        $booking = TrTransaksi::with(['penetapanHarga.ruangan', 'penetapanHarga.paket', 'pengguna'])
            ->where('id_transaksi', $id)
            ->firstOrFail();

        $pos = \App\Models\TrPos::where('id_transaksi', $id)->first();
        $posDetails = [];

        if ($pos) {
            $details = \App\Models\TrPosDetail::where('id_pos', $pos->id_pos)->get();
            foreach ($details as $d) {
                $produk = DB::table('ms_produk')->where('id_produk', $d->id_produk)->first();
                $posDetails[] = [
                    'name'     => $produk ? $produk->nama_produk : 'Produk Dihapus',
                    'qty'      => $d->jumlah,
                    'subtotal' => $d->subtotal,
                ];
            }
        }

        return view('admin.bookings.show', compact('booking', 'pos', 'posDetails'));
    }

    // ============================================================
    // KONFIRMASI
    // ============================================================
    public function konfirmasi($id)
    {
        $booking = TrTransaksi::where('id_transaksi', $id)->firstOrFail();

        if ($booking->status_sewa !== 'ditahan') {
            return redirect()->route('admin.booking.index')
                ->with('error', 'Hanya booking berstatus ditahan yang bisa dikonfirmasi.');
        }

        $statusPembayaranBaru = $booking->opsi_pembayaran === 'full' ? 'lunas' : 'dp';

        $booking->update([
            'status_sewa'       => 'dikonfirmasi',
            'status_pembayaran' => $statusPembayaranBaru,
            'sisa_bayar'        => $statusPembayaranBaru === 'lunas' ? 0 : $booking->sisa_bayar,
            'id_admin'          => auth()->id(),
        ]);

        return redirect()->route('admin.booking.index')
            ->with('success', "Booking {$booking->kode_sewa} berhasil dikonfirmasi.");
    }

    public function tolak(Request $request, $id)
    {
        $request->validate([
            'alasan_tolak' => 'required|string|max:255',
        ]);

        $booking = TrTransaksi::where('id_transaksi', $id)->firstOrFail();

        if ($booking->status_sewa !== 'ditahan') {
            return redirect()->route('admin.booking.index')
                ->with('error', 'Hanya booking berstatus ditahan yang bisa ditolak.');
        }

        $booking->update([
            'status_sewa'        => 'dibatalkan',
            'catatan_pembayaran' => $request->alasan_tolak,
            'id_admin'           => auth()->id(),
        ]);

        return redirect()->route('admin.booking.index')
            ->with('success', "Booking {$booking->kode_sewa} telah ditolak.");
    }

    // ============================================================
    // DESTROY
    // ============================================================
    public function destroy($id)
    {
        $booking = TrTransaksi::where('id_transaksi', $id)->firstOrFail();

        if ($booking->status_sewa !== 'ditahan') {
            return redirect()->route('admin.booking.index')
                ->with('error', 'Hanya booking berstatus ditahan yang dapat dihapus.');
        }

        $booking->delete();

        return redirect()->route('admin.booking.index')
            ->with('success', 'Booking berhasil dihapus.');
    }

    // ============================================================
    // PEMBAYARAN
    // ============================================================
    public function pembayaran(Request $request, $id)
    {
        $validated = $request->validate([
            'status_pembayaran'  => 'required|in:belum_bayar,dp,lunas,refund',
            'catatan_pembayaran' => 'nullable|string|max:255',
        ]);

        $booking = TrTransaksi::findOrFail($id);

        $booking->update([
            'status_pembayaran'  => $validated['status_pembayaran'],
            'catatan_pembayaran' => $validated['catatan_pembayaran'] ?? $booking->catatan_pembayaran,
            'sisa_bayar'         => $validated['status_pembayaran'] === 'lunas' ? 0 : $booking->sisa_bayar,
            'id_admin'           => auth()->id(),
        ]);

        return redirect()->route('admin.booking.show', $id)
            ->with('success', 'Pembayaran berhasil diupdate.');
    }

    // ============================================================
    // SELESAI
    // ============================================================
   public function selesai($id)
    {
        $booking = TrTransaksi::findOrFail($id);

        if ($booking->status_sewa !== 'dikonfirmasi') {
            return redirect()->route('admin.booking.show', $id)
                ->with('error', 'Hanya booking berstatus dikonfirmasi yang bisa ditandai selesai.');
        }

        $booking->update([
            'status_sewa'       => 'selesai',
            'status_pembayaran' => 'lunas', // <-- Wajib ditambahkan
            'sisa_bayar'        => 0,       // <-- Wajib ditambahkan
            'id_admin'          => auth()->id(),
        ]);

        return redirect()->route('admin.booking.show', $id)
            ->with('success', 'Booking ditandai selesai dan otomatis lunas.');
    }
    // ============================================================
    // BATALKAN
    // ============================================================
    public function batalkan(Request $request, $id)
    {
        $request->validate([
            'catatan_pembatalan' => 'required|string|max:255',
        ], [
            'catatan_pembatalan.required' => 'Alasan pembatalan wajib diisi.',
        ]);

        $booking = TrTransaksi::findOrFail($id);

        if ($booking->status_sewa !== 'dikonfirmasi') {
            return back()->withErrors(['error' => 'Booking ini tidak bisa di-refund.']);
        }

        $booking->update([
            'status_sewa'        => 'dibatalkan',
            'status_pembayaran'  => 'refund',
            'sisa_bayar'         => 0,
            'catatan_pembayaran' => $request->catatan_pembatalan,
            'id_admin'           => auth()->id(),
        ]);

        return back()->with('success', "Booking {$booking->kode_sewa} berhasil di-refund.");
    }

    // ============================================================
    // UBAH JADWAL
    // ============================================================
    public function ubahJadwal(Request $request, $id)
    {
        $request->validate([
            'waktu_mulai' => 'required|date_format:Y-m-d\TH:i',
        ], [
            'waktu_mulai.required' => 'Waktu mulai baru wajib diisi.',
        ]);

        $booking = TrTransaksi::where('id_transaksi', $id)->firstOrFail();

        try {
            $this->bookingService->ubahJadwal($booking, $request->waktu_mulai, auth()->id());
        } catch (ValidationException $e) {
            return back()->with('error', collect($e->errors())->flatten()->first());
        }

        return redirect()->route('admin.booking.show', $id)
            ->with('success', 'Jadwal booking berhasil diubah.');
    }

    // CETAK STRUK (finalisasi booking online: konfirmasi + lunas + PDF)
    public function cetakStruk(Request $request, $id): JsonResponse
    {
        $validated = $request->validate([
            'metode_pembayaran' => 'required|in:TUNAI,QRIS',
            'uang_diterima'      => 'required_if:metode_pembayaran,TUNAI|nullable|integer|min:0',
            'items'              => 'array',
            'items.*.id_produk'  => 'required_with:items|integer|exists:ms_produk,id_produk',
            'items.*.jumlah'     => 'required_with:items|integer|min:1',
        ]);

        $booking = TrTransaksi::where('id_transaksi', $id)->firstOrFail();

        try {
            $pdfRelativePath = $this->bookingService->finalisasiStruk(
                $booking,
                $validated['items'] ?? [],
                $validated['metode_pembayaran'],
                auth()->user()->nama_pengguna,
                auth()->id(),
                $validated['uang_diterima'] ?? null
            );
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors'  => $e->errors(),
            ], 422);
        }

        return response()->json([
            'success' => true,
            'message' => 'Struk berhasil dicetak.',
            'data'    => [
                'pdf_url' => asset($pdfRelativePath),
            ],
        ]);
    }

    // ============================================================
    // HELPER: Produk F&B + daftar kategori (dipakai index() & create())
    // ============================================================
    private function getProdukFnbData(): array
    {
        $produks = MsProduk::with('subKategori')
            ->where('is_active', 1)
            ->orderBy('nama_produk')
            ->get();

        $kategoriFnb = $produks
            ->pluck('subKategori.sub_kategori_produk')
            ->filter()
            ->unique()
            ->values();

        return [$produks, $kategoriFnb];
    }

    private function cancelExpiredBookings()
    {
        TrTransaksi::where('status_sewa', 'ditahan')
            ->where('sumber_booking', 'Online')
            ->where('created_at', '<', now()->subMinutes(30))
            ->update([
                'status_sewa'        => 'dibatalkan',
                'catatan_pembayaran' => 'Waktu pembayaran habis!',
            ]);
    }

    private function completeExpiredBookings()
    {
        TrTransaksi::where('status_sewa', 'dikonfirmasi')
            ->where('waktu_selesai', '<', now())
            ->update([
                'status_sewa' => 'selesai',
            ]);
    }

    public function getPaketByRuangan(Request $request): JsonResponse
    {
        $request->validate([
            'ruangan' => 'required|exists:ms_ruangan,id_ruangan',
        ]);

        $pakets = DB::table('penetapan_harga')
            ->join('ms_paket', 'penetapan_harga.id_paket', '=', 'ms_paket.id_paket')
            ->where('penetapan_harga.id_ruangan', $request->ruangan)
            ->where('ms_paket.is_active', 1)
            ->select('ms_paket.id_paket', 'ms_paket.nama_paket')
            ->distinct()
            ->orderBy('ms_paket.nama_paket')
            ->get();

        return response()->json(['pakets' => $pakets]);
    }
}