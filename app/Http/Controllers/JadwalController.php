<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use Illuminate\Support\Facades\Session;

class JadwalController extends Controller
{
    public function index()
    {
        return view('pelanggan.jadwal');
    }

    // 1. Fungsi saat klik "Lanjutkan" di halaman Jadwal
    public function checkout(Request $request)
    {
        // Simpan semua data input ke dalam session dengan nama 'booking_data'
        $data = $request->only([
            'tanggal', 'waktu_mulai', 'waktu_selesai', 
            'metode_pembayaran', 'paket_id', 'total_harga'
        ]);
        
        // Tambahkan label tambahan untuk tampilan di payment (opsional)
        $data['paket_nama'] = "Paket Couple"; 
        $data['ruangan'] = "VIP PlayStation 4";

        Session::put('booking_data', $data);

        // Arahkan ke halaman payment
        return redirect()->route('payment.info');
    }

    // 2. Menampilkan halaman payment
    public function paymentInfo()
    {
        $booking = Session::get('booking_data');

        if (!$booking) {
            return redirect()->route('pelanggan.jadwal')->with('error', 'Sesi booking habis.');
        }

        return view('pelanggan.payment', compact('booking'));
    }

    // 3. Fungsi saat klik "Konfirmasi Pembayaran" di halaman Payment
    public function store(Request $request)
    {
        $data = Session::get('booking_data');

        if (!$data) {
            return redirect()->route('pelanggan.jadwal');
        }

        // Simpan ke database
        $booking = new Booking();
        $booking->user_id = auth()->id();
        $booking->paket_id = $data['paket_id'];
        $booking->tgl_booking = $data['tanggal'];
        $booking->jam_mulai = $data['waktu_mulai'];
        $booking->jam_selesai = $data['waktu_selesai'];
        $booking->total_harga = $data['total_harga'];
        $booking->metode_pembayaran = $data['metode_pembayaran'];
        $booking->status = 'pending';
        $booking->save();

        // Hapus session setelah berhasil simpan
        Session::forget('booking_data');

        return redirect()->route('status.booking')->with('success', 'Booking berhasil dikonfirmasi!');
    }
}