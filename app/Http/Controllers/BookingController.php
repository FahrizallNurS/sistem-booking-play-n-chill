<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TrTransaksi;
use App\Models\MsRuangan;
use App\Models\MsPaket;
use App\Models\MsPricing;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $tipe = $request->input('tipe', 'reguler');

        $kategoriMap = [
            'reguler' => 1,
            'vip'     => 2,
            'vvip'    => 3,
        ];

        $idKategori = $kategoriMap[$tipe] ?? 1;

        $rooms = MsRuangan::where('ms_kategori_id_kategori', $idKategori)
            ->where('is_active', 1)
            ->get();

        return view('pelanggan.booking', compact('rooms', 'tipe'));
    }

    public function paket(Request $request)
{
    $roomId = $request->input('room');
    $tipe   = $request->input('tipe', 'reguler');

    $room = MsRuangan::with('kategori')->findOrFail($roomId);

    $pricings = MsPricing::with(['paket.fasilitas'])
        ->where('ms_ruangan_id_ruangan', $roomId)
        ->get()
        ->groupBy('ms_paket_id_paket');

    return view('pelanggan.booking-paket', compact('roomId', 'tipe', 'room', 'pricings'));
    }

    
    public function form(Request $request)
    {
        $roomId  = $request->input('room');
        $tipe    = $request->input('tipe');
        $paketId = $request->input('paket');

        $room  = MsRuangan::findOrFail($roomId);
        $paket = MsPaket::findOrFail($paketId);

        $pricings = MsPricing::where('ms_ruangan_id_ruangan', $roomId)
            ->where('ms_paket_id_paket', $paketId)
            ->get();

        return view('pelanggan.booking-form', compact('room', 'tipe', 'paket', 'pricings'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'ms_id_ruangan'   => 'required|exists:ms_ruangan,id_ruangan',
            'ms_id_paket'     => 'required|exists:ms_paket,id_paket',
            'tanggal_booking' => 'required|date|after_or_equal:today',
            'waktu_mulai'     => 'required',
            'durasi_sewa'     => 'required|integer|min:30',
            'opsi_pembayaran' => 'required|in:full,dp',
            'jumlah_dp'       => 'required_if:opsi_pembayaran,dp|nullable|numeric|min:0',
        ], [
            'tanggal_booking.after_or_equal' => 'Tanggal tidak boleh sebelum hari ini.',
            'waktu_mulai.required'           => 'Jam main wajib dipilih.',
        ]);

        $hari = Carbon::parse($request->tanggal_booking)->isWeekend() ? 'weekend' : 'weekday';
        $pricing = MsPricing::where('ms_ruangan_id_ruangan', $request->ms_id_ruangan)
            ->where('ms_paket_id_paket', $request->ms_id_paket)
            ->where('hari_type', $hari)
            ->where('durasi_menit', $request->durasi_sewa)
            ->first();

        if (!$pricing) {
            return back()->withInput()->withErrors([
                'durasi_sewa' => 'Paket tidak tersedia untuk durasi dan hari tersebut.'
            ]);
        }

        do {
            $kode = 'PNC-' . now()->format('Ymd') . '-' . strtoupper(Str::random(4));
        } while (TrTransaksi::where('kode_booking', $kode)->exists());

        TrTransaksi::create([
            'kode_booking'         => $kode,
            'tanggal_booking'      => $request->tanggal_booking,
            'waktu_mulai'          => $request->waktu_mulai,
            'durasi_sewa'          => $request->durasi_sewa,
            'ms_id_ruangan'        => $request->ms_id_ruangan,
            'ms_id_paket'          => $request->ms_id_paket,
            'ms_id_pengguna'       => Auth::id(),
            'opsi_pembayaran'      => $request->opsi_pembayaran,
            'jumlah_dp'            => $request->opsi_pembayaran === 'dp' ? $request->jumlah_dp : null,
            'total_harga'          => $pricing->harga,
            'harga_saat_transaksi' => $pricing->harga,
            'status_booking'       => 'pending',
            'status_pembayaran'    => 'unpaid',
        ]);

        return redirect()->route('booking.status')
            ->with('success', 'Booking berhasil! Menunggu konfirmasi admin.');
    }

    public function status()
    {
        $bookings = TrTransaksi::with(['ruangan', 'paket'])
            ->where('ms_id_pengguna', Auth::id())
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