<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\TrTransaksi;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class SATinjauLaporanController extends Controller
{
    private function getQueryData(Request $request)
    {
        $periode        = $request->input('periode', 'harian');
        $jenisTransaksi = $request->input('jenis_transaksi', 'semua'); // Tambahan Filter Jenis Transaksi
        $statusBooking  = $request->input('status_booking', '');
        $statusBayar    = $request->input('status_bayar', '');
        $jenisBayar     = $request->input('jenis_bayar', '');

        // Logika Tanggal (Tetap dipertahankan)
        switch ($periode) {
            case 'mingguan':
                $minggu = $request->input('minggu', now()->format('Y-\WW'));
                [$year, $week] = explode('-W', $minggu);
                $start = Carbon::now()->setISODate($year, $week)->startOfWeek();
                $end   = Carbon::now()->setISODate($year, $week)->endOfWeek();
                break;
            case 'bulanan':
                $bulan = $request->input('bulan', now()->format('Y-m'));
                $start = Carbon::parse($bulan . '-01')->startOfMonth();
                $end   = Carbon::parse($bulan . '-01')->endOfMonth();
                break;
            default:
                $tanggal = $request->input('tanggal', now()->format('Y-m-d'));
                $start   = Carbon::parse($tanggal)->startOfDay();
                $end     = Carbon::parse($tanggal)->endOfDay();
                break;
        }

        // --- 1. MENGAMBIL DATA ASLI BOOKING ---
        $query = TrTransaksi::with(['pengguna', 'penetapanHarga.ruangan', 'penetapanHarga.paket'])
            ->whereBetween('waktu_mulai', [$start, $end]);

        if ($statusBooking) $query->where('status_sewa', $statusBooking);
        if ($statusBayar)   $query->where('status_pembayaran', $statusBayar);
        if ($jenisBayar)    $query->where('opsi_pembayaran', $jenisBayar);

        // Tambahkan properti 'jenis_laporan' agar seragam
        $bookingData = $query->latest('waktu_mulai')->get()->map(function ($item) {
            $item->jenis_laporan = 'Booking';
            return $item;
        });

       // --- 2. MEMBUAT DATA DUMMY F&B ---
        $dummyFNB = collect([
            (object)[
                'jenis_laporan'   => 'F&B',
                'id_pos'          => 'POS-001', // Sesuaikan gambar
                'kode_booking'    => 'PNC-20260619-6WKM',
                'ruangan'         => 'R01',
                'waktu_mulai'     => '2026-06-19 14:15:00', 
                'status_sewa'     => 'Selesai',
                'total_harga'     => 30000,
                'opsi_pembayaran' => 'Cash',
                'pengguna'        => (object)['nama_pengguna' => 'Fahrizal Nur S'],
                'kasir'           => 'Admin 01',
                'items'           => [
                    (object)['produk' => 'Indomie Goreng Telur', 'harga' => 15000, 'jumlah' => 1, 'subtotal' => 15000],
                    (object)['produk' => 'Kopi Hitam', 'harga' => 5000, 'jumlah' => 2, 'subtotal' => 10000],
                    (object)['produk' => 'Teh Manis', 'harga' => 5000, 'jumlah' => 1, 'subtotal' => 5000],
                ]
            ],
            (object)[
                'jenis_laporan'   => 'F&B',
                'id_pos'          => 'POS-09876-0990',
                'kode_booking'    => '-',
                'ruangan'         => '-',
                'waktu_mulai'     => now()->subHours(2),
                'status_sewa'     => 'Selesai',
                'total_harga'     => 85000,
                'opsi_pembayaran' => 'Static QRIS',
                'pengguna'        => (object)['nama_pengguna' => 'Rian Kurnia'],
                'kasir'           => 'Admin 02',
                'items'           => [
                    (object)['produk' => 'Kopi Hitam', 'harga' => 5000, 'jumlah' => 1, 'subtotal' => 5000],
                    (object)['produk' => 'Singkong Goreng', 'harga' => 80000, 'jumlah' => 1, 'subtotal' => 80000],
                ]
            ]
        ]);

        // --- 3. FILTER & PENGGABUNGAN DATA (CONCAT) ---
        $transaksis = collect();
        if ($jenisTransaksi === 'semua') {
            // Gabungkan Booking Asli dengan Dummy F&B, lalu urutkan terbaru
            $transaksis = $bookingData->concat($dummyFNB)->sortByDesc('waktu_mulai')->values();
        } elseif ($jenisTransaksi === 'booking') {
            $transaksis = $bookingData;
        } elseif ($jenisTransaksi === 'fnb') {
            $transaksis = $dummyFNB;
        }

        // Perhitungan Summary Box (Khusus Booking - Sesuai Permintaan)
        $totalBooking    = $bookingData->count();
        $totalSelesai    = $bookingData->where('status_sewa', 'selesai')->count();
        $totalDibatalkan = $bookingData->where('status_sewa', 'dibatalkan')->count();
        $totalPendapatan = $bookingData->where('status_sewa', 'selesai')->sum('total_harga');
        
        $totalPelanggan  = User::where('role', 'pelanggan')->count();
        $totalAdmin      = User::where('role', 'admin')->count();

        return compact(
            'transaksis', 'totalBooking', 'totalSelesai',
            'totalDibatalkan', 'totalPendapatan',
            'totalPelanggan', 'totalAdmin',
            'periode', 'start', 'end', 'jenisTransaksi'
        );
    }

    public function index(Request $request)
    {
        $data = $this->getQueryData($request);
        return view('superadmin.laporan-sa.index', $data); 
    }

    public function exportPdf(Request $request)
    {
        // Tetap menggunakan logic Anda
        $data = $this->getQueryData($request);
        $data['tanggalCetak'] = now()->translatedFormat('d F Y H:i');
        $data['admin']        = auth()->user()->nama_pengguna;

        $pdf      = Pdf::loadView('admin.laporan.pdf', $data)->setPaper('a4', 'portrait');
        $filename = 'Laporan_Booking_' . $data['start']->format('Y-m-d') . '.pdf';

        return $pdf->download($filename);
    }
}