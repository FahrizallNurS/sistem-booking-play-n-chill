<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\Booking;

class BookingController extends Controller
{
    // =====================
    // Halaman pilih ruangan
    // =====================
    public function index(Request $request)
    {
        $tipe = $request->input('tipe', 'reguler'); // default: reguler

        // Ambil ruangan berdasarkan tipe dari database
        // Uncomment baris ini setelah tabel rooms tersedia:
        // $rooms = Room::where('tipe', $tipe)->get();

        // ── DATA DUMMY (hapus setelah database tersambung) ──
        // ── DATA DUMMY (hapus setelah database tersambung) ──
        $rooms = collect(match($tipe) {
            'vip' => [
                ['id' => 1, 'nama' => 'VIP 1', 'device' => 'PS4', 'status' => 'tersedia'],
                ['id' => 2, 'nama' => 'VIP 2', 'device' => 'PS4', 'status' => 'tersedia'],
            ],
            'vvip' => [
                ['id' => 1, 'nama' => 'VVIP 1', 'device' => 'PS5', 'status' => 'tersedia'],
                ['id' => 2, 'nama' => 'VVIP 2', 'device' => 'PS5', 'status' => 'tersedia'],
            ],
            default => [ // reguler
                ['id' => 1, 'nama' => 'Reguler 1', 'device' => 'PS3 - Tv 32" - Bean Bag', 'status' => 'tersedia'],
                ['id' => 2, 'nama' => 'Reguler 2', 'device' => 'PS3 - Tv 32" - Bean Bag', 'status' => 'tersedia'],
                ['id' => 3, 'nama' => 'Reguler 3', 'device' => 'PS4 - Tv 43" - Bean Bag', 'status' => 'penuh'],
                ['id' => 4, 'nama' => 'Reguler 4', 'device' => 'PS4 - Tv 43" - Bean Bag', 'status' => 'tersedia'],
                ['id' => 5, 'nama' => 'Reguler 5', 'device' => 'PS5 - Tv 43" - Bean Bag', 'status' => 'tersedia'],
                ['id' => 6, 'nama' => 'Reguler 6', 'device' => 'PS5 - Tv 43" - Bean Bag', 'status' => 'tersedia'],
            ],
        });
// ── AKHIR DATA DUMMY ──
        // ── AKHIR DATA DUMMY ──

        return view('pelanggan.booking', compact('rooms', 'tipe'));
    }

    public function paket(Request $request)
    {
        $roomId = $request->input('room');
        $tipe   = $request->input('tipe', 'vip');

        // ── KITA TAMBAHKAN DATA DUMMY ROOM DI SINI ──
        $room = [
            'id'     => $roomId,
            'nama'   => match($tipe) {
                'vip'  => 'VIP '.$roomId,
                'vvip' => 'VVIP '.$roomId,
                default => 'Reguler '.$roomId,
            },
            'tipe'   => $tipe,
        ];
        // ── AKHIR DATA DUMMY ──

        // Pastikan 'room' dimasukkan ke dalam compact
        return view('pelanggan.booking-paket', compact('roomId', 'tipe', 'room'));
    }

    // =====================
    // Halaman form booking
    // =====================
    public function form(Request $request)
    {
        $roomId = $request->input('room');
        $tipe   = $request->input('tipe', 'reguler');

        // Ambil detail ruangan dari database
        // Uncomment setelah database tersambung:
        // $room = Room::findOrFail($roomId);

        // ── DATA DUMMY ──
        $room = [
            'id'     => $roomId,
            'nama'   => match($tipe) {
                'vip'  => 'VIP '.$roomId,
                'vvip' => 'VVIP '.$roomId,
                default => 'Reguler '.$roomId,
            },
            'device' => 'PS5',
            'tipe'   => $tipe,
            'harga'  => match($tipe) {
                'vip'  => 50000,
                'vvip' => 100000,
                default => 30000,
            },
        ];
        // ── AKHIR DATA DUMMY ──

        return view('pelanggan.booking-form', compact('room', 'tipe'));
    }

    // =====================
    // Simpan booking
    // =====================
    // Tahap 1: dari booking-form → simpan ke session → redirect ke payment
    public function store(Request $request)
    {
        // Kalau dari konfirmasi payment (tahap 2)
        if ($request->input('confirm') == '1') {
            return $this->saveBooking($request);
        }

        // Tahap 1: validasi & simpan ke session
        $request->validate([
            'tanggal' => ['required', 'date', 'after_or_equal:today'],
            'waktu'   => ['required'],
        ], [
            'tanggal.required'       => 'Tanggal wajib diisi.',
            'tanggal.after_or_equal' => 'Tanggal tidak boleh sebelum hari ini.',
            'waktu.required'         => 'Jam main wajib dipilih.',
        ]);

        session([
            'booking_data' => [
                'room_id'           => $request->room_id,
                'tipe'              => $request->tipe,
                'paket'             => $request->paket,
                'kategori'          => $request->kategori,
                'tanggal'           => $request->tanggal,
                'waktu'             => $request->waktu,
                'metode_pembayaran' => $request->metode_pembayaran,
                'harga'             => 45000, // nanti dari database
            ]
        ]);

        return redirect()->route('payment.info');
    }

    // Tahap 2: simpan ke database setelah konfirmasi
    private function saveBooking(Request $request)
    {
        // Uncomment setelah database siap:
        // Booking::create([
        //     'user_id'           => auth()->id(),
        //     'room_id'           => $request->room_id,
        //     'tipe'              => $request->tipe,
        //     'tanggal'           => $request->tanggal,
        //     'waktu'             => $request->waktu,
        //     'metode_pembayaran' => $request->metode_pembayaran,
        //     'status'            => 'pending',
        // ]);

        // Hapus session setelah disimpan
        session()->forget('booking_data');

        return redirect()->route('pelanggan.status')
                        ->with('success', 'Booking berhasil! Menunggu konfirmasi admin.');
    }

    // =====================
    // Halaman status booking
    // =====================
    public function status()
    {
        // Ambil booking milik user yang login
        // Uncomment setelah database tersambung:
        // $bookings = Booking::where('user_id', auth()->id())
        //                    ->latest()
        //                    ->get();

        // ── DATA DUMMY ──
        $bookings = collect([
            [
                'id'          => 1,
                'ruangan'     => 'Reguler 1',
                'tipe'        => 'reguler',
                'tanggal'     => '2025-07-10',
                'jam_mulai'   => '14:00',
                'jam_selesai' => '16:00',
                'status'      => 'pending',
            ],
            [
                'id'          => 2,
                'ruangan'     => 'VIP 2',
                'tipe'        => 'vip',
                'tanggal'     => '2025-07-08',
                'jam_mulai'   => '10:00',
                'jam_selesai' => '12:00',
                'status'      => 'dikonfirmasi',
            ],
        ]);
        // ── AKHIR DATA DUMMY ──

        return view('pelanggan.booking-status', compact('bookings'));
    }
}