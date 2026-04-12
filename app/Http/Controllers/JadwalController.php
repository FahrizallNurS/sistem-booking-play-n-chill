<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking; // Pastikan model Booking sudah ada

class JadwalController extends Controller
{
    public function index()
    {
        return view('pelanggan.jadwal');
    }

    public function store(Request $request)
    {
        // Validasi data yang masuk
        $request->validate([
            'tanggal' => 'required|date',
            'waktu_mulai' => 'required',
            'waktu_selesai' => 'required',
            'metode_pembayaran' => 'required',
            'paket_id' => 'required',
            'total_harga' => 'required'
        ]);
    
        // Simpan ke database
        $booking = new Booking();
        $booking->user_id = auth()->id(); 
        $booking->paket_id = $request->paket_id;
        $booking->tgl_booking = $request->tanggal;
        $booking->jam_mulai = $request->waktu_mulai;
        $booking->jam_selesai = $request->waktu_selesai;
        $booking->total_harga = $request->total_harga;
        $booking->metode_pembayaran = $request->metode_pembayaran;
        $booking->status = 'pending'; 
        $booking->save();
    
        return redirect()->route('payment.info')->with('success', 'Booking berhasil dibuat!');
    }
}