<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TrTransaksi;
use App\Models\MsRuangan;
use App\Models\MsPaket;
use App\Models\PenetapanHarga;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

class BookingController extends Controller
{
    // ============================================================
    // INDEX
    //   ============================================================
    public function index(Request $request)
    {
        $query = TrTransaksi::with(['penetapanHarga.ruangan', 'penetapanHarga.paket', 'pengguna'])
            ->latest('created_at');

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

        return view('admin.bookings.index', compact('bookings', 'ruangans'));
    }


    // ============================================================
    // STORE
    // ============================================================
    public function store(Request $request)
    {
        $request->validate([
            'id_penetapan_harga' => 'required|exists:penetapan_harga,id_penetapan_harga',
            'tanggal'            => 'required|date|after_or_equal:today',
            'waktu_mulai'        => 'required',
            'opsi_pembayaran'    => 'required|in:full,dp',
            'jumlah_dp'          => 'required_if:opsi_pembayaran,dp|nullable|numeric|min:0',
            'id_pengguna'        => 'nullable|exists:users,id_pengguna',
        ]);

        $ph = PenetapanHarga::findOrFail($request->id_penetapan_harga);

        $waktuMulai   = Carbon::parse($request->tanggal . ' ' . str_replace('.', ':', $request->waktu_mulai));
        $waktuSelesai = $waktuMulai->copy()->addHours($ph->durasi_jam);

        // Validasi jam operasional ─
        $hari = $waktuMulai->dayOfWeek; 

        $jamBuka = match(true) {
            in_array($hari, [1, 2, 3, 4]) => '14:00', // Senin–Kamis
            $hari === 5                    => '14:00', // Jumat
            in_array($hari, [0, 6])        => '10:00', // Sabtu–Minggu
        };

        $jamTutup = match(true) {
            in_array($hari, [1, 2, 3, 4]) => '22:00', // Senin–Kamis
            $hari === 5                    => '23:00', // Jumat
            in_array($hari, [0, 6])        => '23:00', // Sabtu–Minggu
        };

        $bukaDt  = Carbon::parse($request->tanggal . ' ' . $jamBuka);
        $tutupDt = Carbon::parse($request->tanggal . ' ' . $jamTutup);

        if ($waktuMulai->lt($bukaDt) || $waktuSelesai->gt($tutupDt)) {
            $namaHari = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'][$hari];
            return back()->withInput()->withErrors([
                'waktu_mulai' => "Hari {$namaHari} jam operasional {$jamBuka}–{$jamTutup}. Booking kamu ({$waktuMulai->format('H:i')}–{$waktuSelesai->format('H:i')}) di luar jam operasional."
            ]);
        }

        if ($waktuSelesai->gt($tutupDt)) {
            $namaHari = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'][$hari];
            return back()->withInput()->withErrors([
                'waktu_mulai' => "Booking berakhir jam {$waktuSelesai->format('H:i')}, melebihi jam tutup {$jamTutup} hari {$namaHari}."
            ]);
        }

        // Cek konflik jadwal
        $konflik = TrTransaksi::where('id_penetapan_harga', $ph->id_penetapan_harga)
            ->whereIn('status_sewa', ['ditahan', 'dikonfirmasi'])
            ->where(function ($q) use ($waktuMulai, $waktuSelesai) {
                $q->whereBetween('waktu_mulai', [$waktuMulai, $waktuSelesai])
                  ->orWhereBetween('waktu_selesai', [$waktuMulai, $waktuSelesai]);
            })->exists();

        if ($konflik) {
            return back()->withInput()
                ->withErrors(['waktu_mulai' => 'Ruangan sudah dibooking di jam tersebut.']);
        }

        $jumlahDp  = $request->opsi_pembayaran === 'dp' ? $request->jumlah_dp : null;
        $sisaBayar = $request->opsi_pembayaran === 'dp' ? ($ph->harga - $request->jumlah_dp) : 0;

        do {
            $kode = 'PNC-' . now()->format('Ymd') . '-' . strtoupper(Str::random(4));
        } while (TrTransaksi::where('kode_sewa', $kode)->exists());

        TrTransaksi::create([
            'id_penetapan_harga' => $ph->id_penetapan_harga,
            'id_pengguna'        => $request->id_pengguna ?? auth()->user()->id_pengguna,
            'kode_sewa'          => $kode,
            'waktu_mulai'        => $waktuMulai,
            'waktu_selesai'      => $waktuSelesai,
            'total_harga'        => $ph->harga,
            'opsi_pembayaran'    => $request->opsi_pembayaran,
            'jumlah_dp'          => $jumlahDp,
            'status_sewa'        => 'ditahan',
            'status_pembayaran'  => $request->opsi_pembayaran === 'full' ? 'menunggu' : 'dp',
            'sisa_bayar'         => $sisaBayar,
        ]);

        return redirect()->route('admin.booking.index')
            ->with('success', 'Booking berhasil ditambahkan.');
    }

    // ============================================================
    // SHOW
    // ============================================================
    public function show($id)
    {
        $booking = TrTransaksi::with(['penetapanHarga.ruangan', 'penetapanHarga.paket', 'pengguna'])
            ->where('id_transaksi', $id)
            ->firstOrFail();

        return view('admin.bookings.show', compact('booking'));
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

        $booking->update([
            'status_sewa'        => 'dikonfirmasi',
            'status_pembayaran'  => $booking->opsi_pembayaran === 'full' ? 'lunas' : 'dp',
        ]);

        return redirect()->route('admin.booking.index')
            ->with('success', "Booking {$booking->kode_sewa} berhasil dikonfirmasi.");
    }

    // ============================================================
    // TOLAK
    // ============================================================
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
            'status_sewa'          => 'dibatalkan',
            'catatan_pembayaran'   => $request->alasan_tolak,
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

    public function pembayaran(Request $request, $id)
    {
        $booking = TrTransaksi::findOrFail($id);
        $booking->update([
            'status_pembayaran'  => $request->status_pembayaran,
            'catatan_pembayaran' => $request->catatan_pembayaran,
            'sisa_bayar'         => $request->status_pembayaran === 'lunas' ? 0 : $booking->sisa_bayar,
        ]);
        return redirect()->route('admin.booking.show', $id)
            ->with('success', 'Pembayaran berhasil diupdate.');
    }

    public function selesai($id)
    {
        $booking = TrTransaksi::findOrFail($id);
        $booking->update(['status_sewa' => 'selesai']);
        return redirect()->route('admin.booking.show', $id)
            ->with('success', 'Booking ditandai selesai.');
    }
}