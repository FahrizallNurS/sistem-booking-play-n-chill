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
        $tipe   = $request->input('tipe', 'reguler'); 

        // Pastikan data room ini lengkap karena akan dipakai di link "Pilih Paket"
        $room = [
            'id'     => $roomId,
            'nama'   => match($tipe) {
                'vip'   => 'VIP Room ' . $roomId,
                'vvip'  => 'VVIP Room ' . $roomId,
                default => 'Reguler Room ' . $roomId,
            },
            'tipe'   => $tipe,
        ];

        // Mengirim data ke view
        return view('pelanggan.booking-paket', compact('roomId', 'tipe', 'room'));
    }

    // =====================
    // Halaman form booking
    // =====================
    public function form(Request $request)
    {
        $roomId = $request->input('room');
        $tipe   = $request->input('tipe');
        $paket  = $request->input('paket');
        $kategori = $request->input('kategori');

        // Tentukan harga dasar berdasarkan paket yang dipilih
        // Kamu bisa menggunakan database nanti, sekarang pakai logika match:
        $hargaDasar = match($paket) {
            'Paket Couple' => ($kategori == 'Playstation' ? 7000 : 30000),
            'Paket Group'  => ($kategori == 'Playstation' ? 45000 : 50000),
            'Paket Party'  => ($kategori == 'Playstation' ? 125000 : 85000),
            default        => 30000,
        };

        $room = [
            'id'    => $roomId,
            'nama'  => match($tipe) {
                'vip'   => 'VIP Room ' . $roomId,
                'vvip'  => 'VVIP Room ' . $roomId,
                default => 'Reguler Room ' . $roomId,
            },
            'harga' => $hargaDasar, // Harga dasar paket
        ];

        return view('pelanggan.booking-form', compact('room', 'tipe', 'paket', 'kategori'));
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

        // Tahap 1: Validasi
        $request->validate([
            'room_id' => 'required',
            'durasi'  => 'required|numeric|min:1',
            'tanggal' => ['required', 'date', 'after_or_equal:today'],
            'waktu'   => ['required'],
        ], [
            'tanggal.required'       => 'Tanggal wajib diisi.',
            'tanggal.after_or_equal' => 'Tanggal tidak boleh sebelum hari ini.',
            'waktu.required'         => 'Jam main wajib dipilih.',
        ]);

        // ── LOGIKA HARGA DINAMIS ──
        // Kita ambil harga dasar berdasarkan paket (mirip logika di fungsi form)
        $paket = $request->paket;
        $kategori = $request->kategori;
        
        $hargaDasar = match($paket) {
            'Paket Couple' => ($kategori == 'Playstation' ? 7000 : 30000),
            'Paket Group'  => ($kategori == 'Playstation' ? 45000 : 50000),
            'Paket Party'  => ($kategori == 'Playstation' ? 125000 : 85000),
            default        => 30000,
        };

        $totalHarga = $hargaDasar * $request->durasi;

        // Simpan data lengkap ke session
        session([
            'booking_data' => [
                'room_id'           => $request->room_id,
                'tipe'              => $request->tipe,
                'paket'             => $paket,    // Menyimpan nama paket
                'kategori'          => $kategori, // Menyimpan kategori (PS/Karaoke/Bioskop)
                'tanggal'           => $request->tanggal,
                'waktu'             => $request->waktu,
                'durasi'            => $request->durasi,
                'metode_pembayaran' => $request->metode_pembayaran,
                'harga'             => $totalHarga, 
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

        return view('pelanggan.status-booking', compact('bookings'));
    }

    public function paymentInfo(Request $request)
    {
        // Ambil data dari form sebelumnya
        $data = [
            'room_id'  => $request->room_id,
            'tipe'     => $request->tipe,
            'paket'    => $request->paket,    // Pastikan ini ada
            'kategori' => $request->kategori, // Pastikan ini ada
            'tanggal'  => $request->tanggal,
            'waktu'    => $request->waktu,
            'durasi'   => $request->durasi,
            'harga'    => $request->harga, // Total harga yang sudah dikali durasi
        ];

        // Simpan ke session agar bisa dibaca di payment.blade.php
        session(['booking_data' => $data]);

        return view('pelanggan.payment');
    }

    // Tambahkan ini di dalam class BookingController
    public function processToPayment(Request $request)
    {
        // 1. Ambil harga dasar berdasarkan paket (untuk keamanan data)
        $paket = $request->paket;
        $kategori = $request->kategori;
        
        $hargaDasar = match($paket) {
            'Paket Couple' => ($kategori == 'Playstation' ? 7000 : 30000),
            'Paket Group'  => ($kategori == 'Playstation' ? 45000 : 50000),
            'Paket Party'  => ($kategori == 'Playstation' ? 125000 : 85000),
            default        => 30000,
        };

        // 2. Hitung total harga berdasarkan durasi
        $totalHarga = $hargaDasar * $request->durasi;

        // 3. Simpan semua data ke dalam Session agar bisa dibaca di halaman Payment
        $bookingData = [
            'room_id'           => $request->room_id,
            'tipe'              => $request->tipe,
            'paket'             => $paket,
            'kategori'          => $kategori,
            'tanggal'           => $request->tanggal,
            'waktu'             => $request->waktu,
            'durasi'            => $request->durasi,
            'metode_pembayaran' => $request->metode_pembayaran,
            'harga'             => $totalHarga,
        ];

        session(['booking_data' => $bookingData]);

        // 4. Arahkan ke rute tampilan pembayaran
        return redirect()->route('booking.payment.show');
    }

    public function showPayment()
    {
        // Cek apakah ada data di session, jika kosong balikkan ke awal
        if (!session()->has('booking_data')) {
            return redirect()->route('booking.index');
        }

        return view('pelanggan.payment');
    }
}