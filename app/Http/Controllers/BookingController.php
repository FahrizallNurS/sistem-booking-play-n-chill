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
    public function store(Request $request)
    {
        $request->validate([
            'room_id'    => ['required'],
            'tipe'       => ['required', 'in:reguler,vip,vvip'],
            'tanggal'    => ['required', 'date', 'after_or_equal:today'],
            'jam_mulai'  => ['required'],
            'jam_selesai'=> ['required'],
            'catatan'    => ['nullable', 'string', 'max:255'],
        ], [
            'room_id.required'     => 'Ruangan wajib dipilih.',
            'tanggal.required'     => 'Tanggal wajib diisi.',
            'tanggal.after_or_equal' => 'Tanggal tidak boleh sebelum hari ini.',
            'jam_mulai.required'   => 'Jam mulai wajib diisi.',
            'jam_selesai.required' => 'Jam selesai wajib diisi.',
        ]);

        // Simpan ke database
        // Uncomment setelah tabel bookings tersambung:
        // Booking::create([
        //     'user_id'     => auth()->id(),
        //     'room_id'     => $request->room_id,
        //     'tipe'        => $request->tipe,
        //     'tanggal'     => $request->tanggal,
        //     'jam_mulai'   => $request->jam_mulai,
        //     'jam_selesai' => $request->jam_selesai,
        //     'catatan'     => $request->catatan,
        //     'status'      => 'pending',
        // ]);

        return redirect()->route('booking.status')
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