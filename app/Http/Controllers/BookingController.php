<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TrTransaksi;
use App\Models\MsRuangan;
use App\Models\MsPaket;
use App\Models\PenetapanHarga;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $tipe = $request->input('tipe', 'reguler');

        $kategoriMap = [
            'reguler' => 'REGULAR',
            'regular' => 'REGULAR',
            'vip'     => 'VIP',
            'vvip'    => 'VVIP',
        ];

        $kategori = $kategoriMap[strtolower($tipe)] ?? 'REGULAR';

        $rooms = MsRuangan::where('kategori', $kategori)
            ->where('is_active', 1)
            ->get();

        return view('pelanggan.booking', compact('rooms', 'tipe'));
    }

    public function paket(Request $request)
    {
        $roomId = $request->input('room');
        $tipe   = $request->input('tipe', 'reguler');

        $room = MsRuangan::findOrFail($roomId);

        $penetapanHarga = PenetapanHarga::with('paket')
            ->where('id_ruangan', $roomId)
            ->get()
            ->groupBy('id_paket');

        return view('pelanggan.booking-paket', compact('roomId', 'tipe', 'room', 'penetapanHarga'));
    }

    public function form(Request $request)
    {
        $roomId  = $request->input('room');
        $tipe    = $request->input('tipe');
        $paketId = $request->input('paket');

        $room  = MsRuangan::findOrFail($roomId);
        $paket = MsPaket::findOrFail($paketId);

        // Tambah ->get() dengan select eksplisit biar harga tidak terpotong
        $penetapanHarga = PenetapanHarga::where('id_ruangan', $roomId)
            ->where('id_paket', $paketId)
            ->select('id_penetapan_harga', 'id_ruangan', 'id_paket', 'harga', 'durasi_jam', 'tipe_hari')
            ->get();

        return view('pelanggan.booking-form', compact('room', 'tipe', 'paket', 'penetapanHarga'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_penetapan_harga' => 'required|exists:penetapan_harga,id_penetapan_harga',
            'tanggal'            => 'required|date|after_or_equal:today',
            'waktu_mulai'        => 'required',
            'opsi_pembayaran'    => 'required|in:full,dp',
            'jumlah_dp'          => 'required_if:opsi_pembayaran,dp|nullable|numeric|min:0',
        ]);

        $ph = PenetapanHarga::findOrFail($request->id_penetapan_harga);

        // Gabungin tanggal + waktu jadi DATETIME
        $waktuMulai   = Carbon::parse($request->tanggal . ' ' . str_replace('.', ':', $request->waktu_mulai));
        $waktuSelesai = $waktuMulai->copy()->addHours($ph->durasi_jam);

        // Generate kode sewa
        do {
            $kode = 'PNC-' . now()->format('Ymd') . '-' . strtoupper(Str::random(4));
        } while (TrTransaksi::where('kode_sewa', $kode)->exists());

        $jumlahDp  = $request->opsi_pembayaran === 'dp' ? $request->jumlah_dp : null;
        $sisaBayar = $request->opsi_pembayaran === 'dp' ? ($ph->harga - $request->jumlah_dp) : 0;

        TrTransaksi::create([
            'id_penetapan_harga' => $ph->id_penetapan_harga,
            'id_pengguna'        => Auth::id(),
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

        return redirect()->route('booking.status')
            ->with('success', 'Booking berhasil! Menunggu konfirmasi admin.');
    }

    public function status()
    {
        $bookings = TrTransaksi::with(['penetapanHarga.ruangan', 'penetapanHarga.paket'])
            ->where('id_pengguna', Auth::id())
            ->latest()
            ->get();

        return view('pelanggan.status-booking', compact('bookings'));
    }

    public function processToPayment(Request $request)
    {
        session(['booking_data' => $request->all()]);
        return redirect()->route('booking.payment.show');
    }

    public function showPayment()
    {
        if (!session()->has('booking_data')) {
            return redirect()->route('booking');
        }
        return view('pelanggan.payment');
    }
}