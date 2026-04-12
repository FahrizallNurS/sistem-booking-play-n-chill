<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TrTransaksi;
use App\Models\MsRuangan;
use App\Models\MsPaket;
use App\Models\User;
use App\Models\MsPricing;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

class BookingController extends Controller
{
    // ============================================================
    // INDEX — List semua booking dengan filter
    // Route: GET /admin/booking
    // ============================================================
    public function index(Request $request)
    {
        $query = TrTransaksi::with(['pengguna', 'ruangan', 'paket'])
            ->latest('created_at');

        if ($request->filled('status_booking')) {
            $query->where('status_booking', $request->status_booking);
        }

        if ($request->filled('status_pembayaran')) {
            $query->where('status_pembayaran', $request->status_pembayaran);
        }

        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal_booking', $request->tanggal);
        }

        if ($request->filled('ruangan')) {
            $query->where('ms_id_ruangan', $request->ruangan);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kode_booking', 'like', "%{$search}%")
                  ->orWhereHas('pengguna', fn ($u) => $u->where('nama_pengguna', 'like', "%{$search}%"))
                  ->orWhere('walkin_name', 'like', "%{$search}%");
            });
        }

        $bookings = $query->paginate(15)->withQueryString();
        $ruangans = MsRuangan::where('is_active', 1)->get();

        return view('admin.bookings.index', compact('bookings', 'ruangans'));
    }

    // ============================================================
    // CREATE — Form tambah booking baru (walk-in dari admin)
    // Route: GET /admin/booking/create
    // ============================================================
    public function create()
    {
        $ruangans   = MsRuangan::where('is_active', 1)->with('kategori')->get();
        $pakets     = MsPaket::where('is_active', 1)->get();
        $pelanggans = User::where('role', 'pelanggan')->get();

        return view('admin.bookings.create', compact('ruangans', 'pakets', 'pelanggans'));
    }

    // ============================================================
    // STORE — Simpan booking baru
    // Route: POST /admin/booking
    // ============================================================
    public function store(Request $request)
    {
        $request->validate([
            'tanggal_booking' => 'required|date|after_or_equal:today',
            'waktu_mulai'     => 'required',
            'durasi_sewa'     => 'required',
            'ms_id_ruangan'   => 'required|exists:ms_ruangan,id_ruangan',
            'ms_id_paket'     => 'required|exists:ms_paket,id_paket',
            'opsi_pembayaran' => 'required|in:full,dp',
            'jumlah_dp'       => 'required_if:opsi_pembayaran,dp|nullable|numeric|min:0',
            'ms_id_pengguna'  => 'nullable|exists:users,id',
            'walkin_name'     => 'required_without:ms_id_pengguna|nullable|string|max:100',
            'walkin_phone'    => 'required_without:ms_id_pengguna|nullable|string|max:20',
        ], [
            'walkin_name.required_without'  => 'Nama wajib diisi untuk pelanggan walk-in.',
            'walkin_phone.required_without' => 'No. HP wajib diisi untuk pelanggan walk-in.',
        ]);

        $konflik = $this->cekKonflikJadwal(
            $request->ms_id_ruangan,
            $request->tanggal_booking,
            $request->waktu_mulai,
            $request->durasi_sewa
        );

        if ($konflik) {
            return back()->withInput()
                ->withErrors(['waktu_mulai' => 'Ruangan sudah dibooking di jam tersebut. Pilih waktu lain.']);
        }

       $hari = Carbon::parse($request->tanggal_booking)->isWeekend() ? 'weekend' : 'weekday';
        $pricing = MsPricing::where('ms_ruangan_id_ruangan', $request->ms_id_ruangan)
            ->where('ms_paket_id_paket', $request->ms_id_paket)
            ->where('hari_type', $hari)
            ->where('durasi_menit', $request->durasi_sewa)
            ->first();
            
        if (!$pricing) {
            return back()->withInput()->withErrors([
                'durasi_sewa' => 'Pricing tidak ditemukan untuk durasi tersebut'
            ]);
        }

        $hargaSaatTransaksi = $pricing->harga;

        TrTransaksi::create([
            'kode_booking'         => $this->generateKodeBooking(),
            'tanggal_booking'      => $request->tanggal_booking,
            'waktu_mulai'          => $request->waktu_mulai,
            'durasi_sewa'          => $request->durasi_sewa,
            'ms_id_ruangan'        => $request->ms_id_ruangan,
            'ms_id_paket'          => $request->ms_id_paket,
            'ms_id_pengguna'       => $request->ms_id_pengguna,
            'walkin_name'          => $request->ms_id_pengguna ? null : $request->walkin_name,
            'walkin_phone'         => $request->ms_id_pengguna ? null : $request->walkin_phone,
            'opsi_pembayaran'      => $request->opsi_pembayaran,
            'jumlah_dp'            => $request->opsi_pembayaran === 'dp' ? $request->jumlah_dp : null,
            'total_harga'          => $hargaSaatTransaksi,
            'harga_saat_transaksi' => $hargaSaatTransaksi,
            'status_booking'       => 'pending',
            'status_pembayaran'    => 'belum_bayar',
        ]);

        return redirect()->route('admin.booking.index')
            ->with('success', 'Booking berhasil ditambahkan.');
    }

    // ============================================================
    // SHOW — Detail booking
    // Route: GET /admin/booking/{id}
    // ============================================================
    public function show($id)
    {
        $booking = TrTransaksi::with(['pengguna', 'ruangan', 'paket'])
            ->where('id_transaksi', $id)
            ->firstOrFail();

        return view('admin.bookings.show', compact('booking'));
    }

    // ============================================================
    // EDIT — Form edit booking (hanya kalau masih pending)
    // Route: GET /admin/booking/{id}/edit
    // ============================================================
    public function edit($id)
    {
        $booking = TrTransaksi::where('id_transaksi', $id)->firstOrFail();

        if ($booking->status_booking !== 'pending') {
            return redirect()->route('admin.booking.show', $id)
                ->with('error', 'Booking yang sudah dikonfirmasi atau ditolak tidak dapat diedit.');
        }

        $ruangans   = MsRuangan::where('is_active', 1)->with('kategori')->get();
        $pakets     = MsPaket::where('is_active', 1)->get();
        $pelanggans = User::where('role', 'pelanggan')->get();

        return view('admin.bookings.edit', compact('booking', 'ruangans', 'pakets', 'pelanggans'));
    }

    // ============================================================
    // UPDATE — Simpan perubahan booking
    // Route: PUT /admin/booking/{id}
    // ============================================================
    public function update(Request $request, $id)
    {
        $booking = TrTransaksi::where('id_transaksi', $id)->firstOrFail();

        if ($booking->status_booking !== 'pending') {
            return redirect()->route('admin.booking.index')
                ->with('error', 'Booking yang sudah dikonfirmasi atau ditolak tidak dapat diubah.');
        }

        $request->validate([
            'tanggal_booking' => 'required|date',
            'waktu_mulai'     => 'required',
            'durasi_sewa'     => 'required',
            'ms_id_ruangan'   => 'required|exists:ms_ruangan,id_ruangan',
            'ms_id_paket'     => 'required|exists:ms_paket,id_paket',
            'opsi_pembayaran' => 'required|in:full,dp',
            'jumlah_dp'       => 'required_if:opsi_pembayaran,dp|nullable|numeric|min:0',
            'walkin_name'     => 'required_without:ms_id_pengguna|nullable|string|max:100',
            'walkin_phone'    => 'required_without:ms_id_pengguna|nullable|string|max:20',
        ]);

        $konflik = $this->cekKonflikJadwal(
            $request->ms_id_ruangan,
            $request->tanggal_booking,
            $request->waktu_mulai,
            $request->durasi_sewa,
            $id
        );

        if ($konflik) {
            return back()->withInput()
                ->withErrors(['waktu_mulai' => 'Ruangan sudah dibooking di jam tersebut. Pilih waktu lain.']);
        }

        $booking->update([
            'tanggal_booking' => $request->tanggal_booking,
            'waktu_mulai'     => $request->waktu_mulai,
            'durasi_sewa'     => $request->durasi_sewa,
            'ms_id_ruangan'   => $request->ms_id_ruangan,
            'ms_id_paket'     => $request->ms_id_paket,
            'ms_id_pengguna'  => $request->ms_id_pengguna,
            'walkin_name'     => $request->ms_id_pengguna ? null : $request->walkin_name,
            'walkin_phone'    => $request->ms_id_pengguna ? null : $request->walkin_phone,
            'opsi_pembayaran' => $request->opsi_pembayaran,
            'jumlah_dp'       => $request->opsi_pembayaran === 'dp' ? $request->jumlah_dp : null,
        ]);

        return redirect()->route('admin.booking.index')
            ->with('success', 'Booking berhasil diperbarui.');
    }

    // ============================================================
    // KONFIRMASI — Admin approve booking
    // Route: PATCH /admin/booking/{id}/konfirmasi
    // ============================================================
    public function konfirmasi($id)
    {
        $booking = TrTransaksi::where('id_transaksi', $id)->firstOrFail();

        if ($booking->status_booking !== 'pending') {
            return redirect()->route('admin.booking.index')
                ->with('error', 'Hanya booking berstatus pending yang bisa dikonfirmasi.');
        }

        $booking->update(['status_booking' => 'confirmed']);

        return redirect()->route('admin.booking.index')
            ->with('success', "Booking {$booking->kode_booking} berhasil dikonfirmasi.");
    }

    // ============================================================
    // TOLAK — Admin reject booking
    // Route: PATCH /admin/booking/{id}/tolak
    // ============================================================
    public function tolak(Request $request, $id)
    {
        $request->validate([
            'alasan_tolak' => 'required|string|max:255',
        ], [
            'alasan_tolak.required' => 'Alasan penolakan wajib diisi.',
        ]);

        $booking = TrTransaksi::where('id_transaksi', $id)->firstOrFail();

        if ($booking->status_booking !== 'pending') {
            return redirect()->route('admin.booking.index')
                ->with('error', 'Hanya booking berstatus pending yang bisa ditolak.');
        }

        $booking->update([
            'status_booking'     => 'rejected',
            'catatan_pembayaran' => $request->alasan_tolak,
        ]);

        return redirect()->route('admin.booking.index')
            ->with('success', "Booking {$booking->kode_booking} telah ditolak.");
    }

    // ============================================================
    // DESTROY — Hapus booking (hanya pending)
    // Route: DELETE /admin/booking/{id}
    // ============================================================
    public function destroy($id)
    {
        $booking = TrTransaksi::where('id_transaksi', $id)->firstOrFail();

        if ($booking->status_booking !== 'pending') {
            return redirect()->route('admin.booking.index')
                ->with('error', 'Hanya booking berstatus pending yang dapat dihapus.');
        }

        $booking->delete();

        return redirect()->route('admin.booking.index')
            ->with('success', 'Booking berhasil dihapus.');
    }

    // ============================================================
    // CHECK SLOT — AJAX cek ketersediaan ruangan
    // ============================================================
    public function checkSlot(Request $request)
    {
        $request->validate([
            'ms_id_ruangan'   => 'required',
            'tanggal_booking' => 'required|date',
            'waktu_mulai'     => 'required',
            'durasi_sewa'     => 'required',
        ]);

        $konflik = $this->cekKonflikJadwal(
            $request->ms_id_ruangan,
            $request->tanggal_booking,
            $request->waktu_mulai,
            $request->durasi_sewa,
            $request->exclude_id
        );

        return response()->json([
            'tersedia' => !$konflik,
            'pesan'    => $konflik
                ? 'Ruangan sudah dibooking di jam tersebut.'
                : 'Ruangan tersedia.',
        ]);
    }

    // ============================================================
    // PRIVATE HELPERS
    // ============================================================

    private function generateKodeBooking(): string
    {
        do {
            $kode = 'PNC-' . now()->format('Ymd') . '-' . strtoupper(Str::random(4));
        } while (TrTransaksi::where('kode_booking', $kode)->exists());

        return $kode;
    }

    private function cekKonflikJadwal(
    int $ruanganId,
    string $tanggal,
    string $waktuMulai,
    int $durasiSewa,
    ?int $excludeId = null
    ): bool {
        $mulai   = Carbon::parse("{$tanggal} {$waktuMulai}");
        $selesai = $mulai->copy()->addMinutes($durasiSewa);

        $query = TrTransaksi::where('ms_id_ruangan', $ruanganId)
            ->whereDate('tanggal_booking', $tanggal)
            ->whereIn('status_booking', ['pending', 'confirmed']);

        if ($excludeId) {
            $query->where('id_transaksi', '!=', $excludeId);
        }

        return $query->get()->contains(function ($trx) use ($mulai, $selesai) {
            $trxMulai   = Carbon::parse($trx->tanggal_booking . ' ' . $trx->waktu_mulai);
            $trxSelesai = $trxMulai->copy()->addMinutes($trx->durasi_sewa);

            return ($mulai < $trxSelesai && $selesai > $trxMulai);
        });
    }
}