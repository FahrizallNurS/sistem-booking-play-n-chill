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
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $this->cancelExpiredBookings();
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
            ->get()
            ->map(function ($room) {
                $sedangDipakai = TrTransaksi::whereHas('penetapanHarga', function ($q) use ($room) {
                        $q->where('id_ruangan', $room->id_ruangan);
                    })
                    ->whereIn('status_sewa', ['ditahan', 'dikonfirmasi'])
                    ->where('waktu_mulai', '<=', now())
                    ->where('waktu_selesai', '>=', now())
                    ->exists();

                $room->tersedia = !$sedangDipakai;
                return $room;
            });

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
        $tanggal = now()->format('Y-m-d');
        $room    = MsRuangan::findOrFail($roomId);
        $paket   = MsPaket::findOrFail($paketId);

        $penetapanHarga = PenetapanHarga::where('id_ruangan', $roomId)
            ->where('id_paket', $paketId)
            ->get();

        $jamTerpakai = $this->calculateOccupiedSlots($roomId, $tanggal);

        return view('pelanggan.booking-form', compact('room', 'tipe', 'paket', 'penetapanHarga', 'jamTerpakai'));
    }

    public function getJamTerpakai(Request $request): JsonResponse
    {
        $roomId  = $request->query('room');
        $tanggal = $request->query('tanggal');
        $terpakai = $this->calculateOccupiedSlots($roomId, $tanggal);
        return response()->json(['terpakai' => $terpakai]);
    }

    private function calculateOccupiedSlots($roomId, $tanggal)
    {
        $allSlots = [
            '10.00','10.30','11.00','11.30','12.00','12.30','13.00','13.30',
            '14.00','14.30','15.00','15.30','16.00','16.30','17.00','17.30',
            '18.00','18.30','19.00','19.30','20.00','20.30','21.00','21.30',
            '22.00','22.30','23.00','23.30'
        ];

        $occupiedSlots = [];

        $bookings = TrTransaksi::whereHas('penetapanHarga', function ($q) use ($roomId) {
                $q->where('id_ruangan', $roomId);
            })
            ->whereDate('waktu_mulai', $tanggal)
            ->whereIn('status_sewa', ['ditahan', 'dikonfirmasi'])
            ->get(['waktu_mulai', 'waktu_selesai']);

        foreach ($bookings as $booking) {
            $start = Carbon::parse($booking->waktu_mulai);
            $end   = Carbon::parse($booking->waktu_selesai);

            foreach ($allSlots as $slot) {
                $currentSlot = Carbon::parse($tanggal . ' ' . str_replace('.', ':', $slot));
                if ($currentSlot >= $start && $currentSlot < $end) {
                    $occupiedSlots[] = $slot;
                }
            }
        }

        return array_values(array_unique($occupiedSlots));
    }

    public function store(Request $request)
{
    $request->validate([
        'id_penetapan_harga' => 'required|exists:penetapan_harga,id_penetapan_harga',
        'tanggal'            => 'required|date|after_or_equal:today',
        'waktu_mulai'        => ['required', 'regex:/^([01]?[0-9]|2[0-3])[.:][0-5][0-9]$/'],
        'opsi_pembayaran'    => 'required|in:full,dp',
        'jumlah_dp'          => [
            'required_if:opsi_pembayaran,dp',
            'nullable',
            function ($attribute, $value, $fail) use ($request) {
                if ($request->opsi_pembayaran === 'dp') {
                    $dp = (int) str_replace('.', '', $value);
                    $ph = PenetapanHarga::find($request->id_penetapan_harga);
                    if ($dp <= 0) {
                        $fail('Jumlah DP harus lebih dari 0.');
                    }
                    if ($ph && $dp >= $ph->harga) {
                        $fail('Jumlah DP tidak boleh melebihi atau sama dengan total harga.');
                    }
                }
            }
        ],
    ]);

    return DB::transaction(function () use ($request) {
        $ph = PenetapanHarga::findOrFail($request->id_penetapan_harga);

        $jamInput     = str_replace('.', ':', $request->waktu_mulai);
        $waktuMulai   = Carbon::parse($request->tanggal . ' ' . $jamInput);
        $waktuSelesai = $waktuMulai->copy()->addHours($ph->durasi_jam);

        $konflik = TrTransaksi::whereHas('penetapanHarga', function ($q) use ($ph) {
                $q->where('id_ruangan', $ph->id_ruangan);
            })
            ->whereIn('status_sewa', ['ditahan', 'dikonfirmasi'])
            ->where(function ($query) use ($waktuMulai, $waktuSelesai) {
                $query->where('waktu_mulai', '<', $waktuSelesai)
                      ->where('waktu_selesai', '>', $waktuMulai);
            })
            ->lockForUpdate()
            ->exists();

        if ($konflik) {
            return back()->withInput()->withErrors([
                'waktu_mulai' => 'Waduh, jam segini ruangannya udah ada yang nempetin, bro. Coba geser jamnya dikit!'
            ]);
        }

        $kode = 'PNC-' . now()->format('Ymd') . '-' . strtoupper(Str::random(4));

        $jumlahDp  = null;
        $sisaBayar = 0;

        if ($request->opsi_pembayaran === 'dp') {
            $jumlahDp  = (int) str_replace('.', '', $request->jumlah_dp);
            $sisaBayar = $ph->harga - $jumlahDp;
        }

        $transaksi = TrTransaksi::create([
            'id_penetapan_harga' => $ph->id_penetapan_harga,
            'id_pengguna'        => Auth::user()->id_pengguna,
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

        return redirect()->route('booking.payment.show', $transaksi->id_transaksi);
    });
}

private function cancelExpiredBookings()
    {
        // Mencari transaksi yang masih 'ditahan' dan sudah melewati batas waktu bayar (misal 15 menit)
        // Jika belum bayar dalam 15 menit, status otomatis diubah jadi 'dibatalkan'
        $limit = now()->subMinutes(15);

        TrTransaksi::where('status_sewa', 'ditahan')
            ->where('status_pembayaran', 'menunggu')
            ->where('created_at', '<', $limit)
            ->update([
                'status_sewa' => 'dibatalkan'
            ]);
    }
}