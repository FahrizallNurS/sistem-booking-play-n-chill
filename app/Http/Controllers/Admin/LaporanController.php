<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TrTransaksi;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        // 1. Ambil input filter
        $periode = $request->get('periode', 'harian');
        $status = $request->get('status_booking');
        
        $query = TrTransaksi::with(['user', 'penetapanHarga.ruangan', 'penetapanHarga.paket']);

        // 2. Filter berdasarkan Periode
        if ($periode == 'harian') {
            $tanggal = $request->get('tanggal', date('Y-m-d'));
            $query->whereDate('waktu_mulai', $tanggal);
        } elseif ($periode == 'mingguan') {
            if ($request->filled('minggu')) {
                // Format input week: 2026-W18
                $year = substr($request->minggu, 0, 4);
                $week = substr($request->minggu, 6);
                $query->whereRaw('YEAR(waktu_mulai) = ?', [$year])
                      ->whereRaw('WEEK(waktu_mulai) = ?', [$week]);
            }
        } elseif ($periode == 'bulanan') {
            $bulan = $request->get('bulan', date('Y-m'));
            $query->whereRaw("DATE_FORMAT(waktu_mulai, '%Y-%m') = ?", [$bulan]);
        }

        // 3. Filter berdasarkan Status
        if ($request->filled('status_booking')) {
            $query->where('status_sewa', $status);
        }

        $laporan = $query->latest()->get();

        // 4. Hitung Summary
        // Gunakan logika ini di dalam index LaporanController kamu
        $summary = [
            'total_booking' => $laporan->count(),
            'confirmed'     => $laporan->where('status_sewa', 'dikonfirmasi')->count(),
            'cancelled'     => $laporan->where('status_sewa', 'dibatalkan')->count(),
            'pendapatan'    => $laporan->where('status_sewa', 'selesai')->sum('total_harga'),
        ];

        return view('admin.laporan.index', compact('laporan', 'summary'));
    }
}