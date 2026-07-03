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
        $this->completeExpiredBookings();
        $tipe = $request->input('tipe', 'reguler');

        $kategoriMap = [
            'reguler'       => 'REGULAR',
            'regular'       => 'REGULAR',
            'private-room'  => 'PRIVATE-ROOM',
            'private_room'  => 'PRIVATE-ROOM',
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

        // Filter paket yang is_active = 1
        $penetapanHarga = PenetapanHarga::with('paket.subKategori')
            ->where('id_ruangan', $roomId)
            ->whereHas('paket', function($query) {
                $query->where('is_active', 1);  // ← Hanya paket aktif
            })
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
        $paket   = MsPaket::where('id_paket', $paketId)
        ->where('is_active', 1)  
        ->firstOrFail();


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
                $currentSlot = Carbon::parse($tanggal . ' ' . str_replace('.', ':', $slot), 'Asia/Jakarta');
                $start = Carbon::parse($booking->waktu_mulai)->setTimezone('Asia/Jakarta');
                $end   = Carbon::parse($booking->waktu_selesai)->setTimezone('Asia/Jakarta');
                if ($currentSlot >= $start && $currentSlot < $end) {
                    $occupiedSlots[] = $slot;
                }
            }
        }

        return array_values(array_unique($occupiedSlots));
    }

    public function store(Request $request)
    {

        if (empty(Auth::user()->no_hp)) {
        $request->merge(['no_hp_required' => true]);
        }

        $request->validate([
        'id_penetapan_harga' => 'required|exists:penetapan_harga,id_penetapan_harga',
        'tanggal' => 'required|date|after_or_equal:today',
        'waktu_mulai' => ['required', 'regex:/^([01]?[0-9]|2[0-3])[.:][0-5][0-9]$/'],
        'opsi_pembayaran' => 'required|in:full,dp',

        'jumlah_dp' => [
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
        ]
        ], [
            'no_hp.required' => 'Nomor telepon wajib diisi.',
            'no_hp.regex' => 'Format nomor telepon tidak valid.',
        ]);

        if (empty(Auth::user()->no_hp) && $request->filled('no_hp')) {
        Auth::user()->update([
            'no_hp' => $request->no_hp
        ]);
         }

        return DB::transaction(function () use ($request) {
            $ph = PenetapanHarga::findOrFail($request->id_penetapan_harga);

            $jamInput     = str_replace('.', ':', $request->waktu_mulai);
            $waktuMulai   = Carbon::parse($request->tanggal . ' ' . $jamInput);
            $waktuSelesai = $waktuMulai->copy()->addHours($ph->durasi_jam);

            $hari = $waktuMulai->dayOfWeek;

            $jamBuka = match(true) {
                in_array($hari, [0, 6]) => '10:00',
                $hari === 5              => '13:00',
                default                  => '14:00',
            };

            $jamTutup = match(true) {
                in_array($hari, [0, 5, 6]) => '23:59',
                default                     => '22:00',
            };

            $bukaDt  = Carbon::parse($request->tanggal . ' ' . $jamBuka);
            $tutupDt = Carbon::parse($request->tanggal . ' ' . $jamTutup)->addMinute();

            if ($waktuMulai->lt($bukaDt) || $waktuSelesai->gt($tutupDt)) {
                $namaHari = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'][$hari];
                return back()->withInput()->withErrors([
                    'waktu_mulai' => "Hari {$namaHari} jam operasional {$jamBuka}–{$jamTutup}. Booking di luar jam operasional."
                ]);
            }

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
                    'waktu_mulai' => 'Waduh, jam segini ruangannya udah ada yang nempetin lads. Coba geser jamnya dikit!'
                ]);
            }

            do {
                    $kode = 'PNC-' . now()->format('Ymd') . '-' . strtoupper(Str::random(4));
            } while (TrTransaksi::where('kode_sewa', $kode)->exists());

            $jumlahDp  = null;
            $sisaBayar = 0;

            if ($request->opsi_pembayaran === 'dp') {
                $jumlahDp  = (int) str_replace('.', '', $request->jumlah_dp);
                $sisaBayar = $ph->harga - $jumlahDp;
            }

            $transaksi = TrTransaksi::create([
                'id_penetapan_harga' => $ph->id_penetapan_harga,
                'id_pengguna'        => Auth::user()->id_pengguna, // FIX: pakai id_pengguna
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

    public function status()
    {
        $this->cancelExpiredBookings();
        $this->completeExpiredBookings();
        $bookings = TrTransaksi::with(['penetapanHarga.ruangan', 'penetapanHarga.paket'])
            ->where('id_pengguna', Auth::user()->id_pengguna) // FIX
            ->latest()
            ->get();

        return view('pelanggan.status-booking', compact('bookings'));
    }

    public function processToPayment(Request $request)
    {
        session(['booking_data' => $request->all()]);
        return redirect()->route('booking.payment.show');
    }

    public function showPayment($id)
    {
        $this->cancelExpiredBookings();
        $this->completeExpiredBookings();
        $transaksi = TrTransaksi::with(['penetapanHarga.ruangan', 'penetapanHarga.paket'])
            ->where('id_transaksi', $id)
            ->where('id_pengguna', Auth::user()->id_pengguna) // FIX
            ->firstOrFail();

        return view('pelanggan.payment', compact('transaksi'));
    }

    private function cancelExpiredBookings()
    {
        TrTransaksi::where('status_sewa', 'ditahan')
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
}