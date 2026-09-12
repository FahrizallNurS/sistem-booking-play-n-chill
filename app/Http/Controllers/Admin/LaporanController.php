<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TrTransaksi;
use App\Models\TrPos;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TransaksiExport;

class LaporanController extends Controller
{
    private function getDateRange(Request $request): array
    {
        $periode = $request->input('periode', 'harian');

        switch ($periode) {
            case 'mingguan':
                $minggu = $request->input('minggu', now()->format('Y-\WW'));
                if (str_contains($minggu, '-W')) {
                    [$year, $week] = explode('-W', $minggu);
                    $start = Carbon::now()->setISODate((int)$year, (int)$week)->startOfWeek();
                    $end   = Carbon::now()->setISODate((int)$year, (int)$week)->endOfWeek();
                } else {
                    $start = Carbon::now()->startOfWeek();
                    $end   = Carbon::now()->endOfWeek();
                }
                break;

            case 'bulanan':
                $bulan = $request->input('bulan', now()->format('Y-m'));
                try {
                    $start = Carbon::parse($bulan . '-01')->startOfMonth();
                } catch (\Exception $e) {
                    $start = Carbon::now()->startOfMonth();
                }
                $end = $start->copy()->endOfMonth();
                break;

            default:
                // Samakan jam operasional dengan SA: 10:00 - 01:59
                $tanggal = $request->input('tanggal', now()->format('Y-m-d'));
                try {
                    $start = Carbon::parse($tanggal)->setTime(10, 0, 0);
                    $end   = Carbon::parse($tanggal)->addDay()->setTime(1, 59, 59);
                } catch (\Exception $e) {
                    $start = Carbon::today()->setTime(10, 0, 0);
                    $end   = Carbon::tomorrow()->setTime(1, 59, 59);
                }
                break;
        }

        return [$start, $end, $periode];
    }

    private function getReportData(Request $request): array
    {
        [$start, $end, $periode] = $this->getDateRange($request);

        $jenisTransaksi  = $request->input('jenis_transaksi', 'semua');
        $statusTransaksi = $request->input('status_transaksi', ''); 
        $sumber          = $request->input('sumber', ''); // Filter khusus admin

        // --- Booking (Samakan waktu_selesai dan mapping datanya) ---
        $bookingQuery = TrTransaksi::with(['pengguna', 'admin', 'penetapanHarga.ruangan', 'penetapanHarga.paket'])
            ->whereBetween('waktu_selesai', [$start, $end]);

        if ($sumber) {
            $bookingQuery->where('sumber_booking', $sumber);
        }

        $bookingRaw = $bookingQuery->latest('waktu_mulai')->get()
            ->map(function ($item) {
                $item->jenis_laporan = 'Booking';
                $item->kasir = $item->admin->nama_pengguna ?? null;
                $item->kode_transaksi = $item->kode_sewa;
                return $item;
            });

        // --- F&B (Samakan mapping struktur datanya) ---
        $fnbQuery = TrPos::with(['pengguna', 'admin', 'transaksi.penetapanHarga.ruangan', 'details.produk'])
            ->whereBetween('created_at', [$start, $end]);

        if ($sumber) {
            $fnbQuery->where('sumber_pesanan', $sumber);
        }

        $fnbRaw = $fnbQuery->latest('created_at')->get()
            ->map(function ($item) {
                $item->jenis_laporan = 'F&B';
                $item->waktu_mulai    = $item->created_at;
                $item->kasir          = $item->admin->nama_pengguna ?? null;
                $item->kode_transaksi = $item->kode_pos;
                $item->ruangan        = $item->transaksi->penetapanHarga->ruangan->nama_ruangan ?? '-';
                $item->status_sewa    = strtolower($item->status_pesanan ?? '');
                $item->total_harga    = $item->total_pos;
                $item->sumber_booking = $item->sumber_pesanan;
                
                // Samakan mapping array F&B item
                $item->items          = $item->details->map(function ($d) {
                    return (object) [
                        'produk'   => $d->produk->nama_produk ?? 'Produk dihapus',
                        'harga'    => $d->harga_satuan,
                        'jumlah'   => $d->jumlah,
                        'subtotal' => $d->subtotal,
                    ];
                });
                return $item;
            });

        // --- Logika Filter Status Batal / Selesai (Sama dengan SA) ---
        $isFnbSelesai = fn ($item) => $item->status_sewa === 'selesai'
            && in_array($item->status_pembayaran, ['sudah-bayar', 'lunas']);
        $isFnbDibatalkan = fn ($item) => $item->status_sewa === 'dibatalkan';

        if ($statusTransaksi === 'selesai') {
            $bookingFiltered = $bookingRaw->where('status_sewa', 'selesai')->values();
            $fnbFiltered     = $fnbRaw->filter($isFnbSelesai)->values();
        } elseif ($statusTransaksi === 'dibatalkan') {
            $bookingFiltered = $bookingRaw->where('status_sewa', 'dibatalkan')->values();
            $fnbFiltered     = $fnbRaw->filter($isFnbDibatalkan)->values();
        } else {
            $bookingFiltered = $bookingRaw->whereIn('status_sewa', ['selesai', 'dibatalkan'])->values();
            $fnbFiltered     = $fnbRaw->filter(
                fn ($item) => $isFnbSelesai($item) || $isFnbDibatalkan($item)
            )->values();
        }

        $transaksis = match ($jenisTransaksi) {
            'booking' => $bookingFiltered,
            'fnb'     => $fnbFiltered,
            default   => $bookingFiltered->concat($fnbFiltered)->sortByDesc('waktu_mulai')->values(),
        };

        // --- Summary Variables (Menggunakan raw data agar hitungan akurat) ---
        $totalBooking      = $bookingRaw->whereIn('status_sewa', ['selesai', 'dibatalkan'])->count();
        $totalFnb          = $fnbRaw->count();
        $bookingSelesai    = $bookingRaw->where('status_sewa', 'selesai')->count();
        $fnbSelesai        = $fnbRaw->filter(fn ($item) => $item->status_sewa === 'selesai')->count();
        $bookingDibatalkan = $bookingRaw->where('status_sewa', 'dibatalkan')->count();
        $fnbDibatalkan     = $fnbRaw->filter(fn ($item) => $item->status_sewa === 'dibatalkan')->count();

        $pendapatanBooking = (float) $bookingRaw->where('status_sewa', 'selesai')->sum('total_harga');
        $pendapatanFnb     = (float) $fnbRaw->filter($isFnbSelesai)->sum('total_pos');

        $totalSelesai    = $bookingSelesai + $fnbSelesai;
        $totalDibatalkan = $bookingDibatalkan + $fnbDibatalkan;
        $totalPendapatan = $pendapatanBooking + $pendapatanFnb;

        return compact(
            'transaksis', 'jenisTransaksi', 'sumber', 'statusTransaksi',
            'totalBooking', 'totalFnb', 'bookingSelesai', 'fnbSelesai',
            'bookingDibatalkan', 'fnbDibatalkan', 'pendapatanBooking', 'pendapatanFnb',
            'totalSelesai', 'totalDibatalkan', 'totalPendapatan',
            'periode', 'start', 'end'
        );
    }

    // --- Pagination (Sama persis dengan Superadmin) ---
    private function paginateTransaksis($transaksis, Request $request)
    {
        $perPage = 10;
        $page    = (int) $request->input('page', 1);
        $slice   = $transaksis->slice(($page - 1) * $perPage, $perPage)->values();

        return new \Illuminate\Pagination\LengthAwarePaginator(
            $slice, $transaksis->count(), $perPage, $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );
    }

    public function index(Request $request)
    {
        $data = $this->getReportData($request);
        
        // Paginasi Data
        $data['transaksisPaged'] = $this->paginateTransaksis($data['transaksis'], $request);

        // Jika Anda menggunakan AJAX/Infinite Scroll di Admin (seperti SA)
        if ($request->ajax() && $request->has('page')) {
            $html = view('admin.laporan.partials.table-rows', [
                'transaksis' => $data['transaksisPaged'],
            ])->render();

            $pagination = view('admin.laporan.partials.pagination-links', [
                'transaksisPaged' => $data['transaksisPaged'],
            ])->render();

            return response()->json([
                'html'       => $html,
                'pagination' => $pagination,
                'info'       => "Menampilkan {$data['transaksisPaged']->firstItem()} hingga {$data['transaksisPaged']->lastItem()} dari {$data['transaksisPaged']->total()} entri",
            ]);
        }

        // Jangan lupa pastikan view Admin membaca dari 'transaksisPaged' jika menggunakan pagination
        return view('admin.laporan.index', $data);
    }

    // --- Export Logic (Diadopsi dari refactoring yang kita lakukan sebelumnya) ---
    private function getFlattenedData($transaksis): array
    {
        $flatData = [];

        foreach ($transaksis as $t) {
            if ($t->jenis_laporan === 'Booking') {
                $flatData[] = [
                    'kode_transaksi'    => $t->kode_sewa,
                    'jenis_laporan'     => 'Booking',
                    'tanggal_transaksi' => $t->waktu_selesai, // Sudah disamakan dengan SA
                    'pelanggan'         => $t->pengguna->nama_pengguna ?? '-',
                    'kasir'             => $t->kasir ?? '-',
                    'nama_produk'       => $t->penetapanHarga->paket->nama_paket ?? 'Paket Terhapus',
                    'jumlah' => $t->penetapanHarga->durasi_jam ?? 0,
                    'nominal_transaksi' => $t->total_harga,
                    'metode_pembayaran' => $t->metode_pembayaran ?? 'Cash',
                    'sumber_booking'    => $t->sumber_booking ?? 'Kasir',
                    'status_sewa'       => strtolower($t->status_sewa),
                ];
            } else {
                if (isset($t->items) && count($t->items) > 0) {
                    foreach ($t->items as $item) {
                        $flatData[] = [
                            'kode_transaksi'    => $t->kode_transaksi,
                            'jenis_laporan'     => 'F&B',
                            'tanggal_transaksi' => $t->created_at,
                            'pelanggan'         => $t->pengguna->nama_pengguna ?? '-',
                            'kasir'             => $t->kasir ?? '-',
                            'nama_produk'       => $item->produk,
                            'jumlah' => $item->jumlah,
                            'nominal_transaksi' => $item->subtotal,
                            'metode_pembayaran' => $t->metode_pembayaran ?? 'Cash',
                            'sumber_booking'    => $t->sumber_booking ?? 'Kasir',
                            'status_sewa'       => strtolower($t->status_sewa),
                        ];
                    }
                } else {
                    $flatData[] = [
                        'kode_transaksi'    => $t->kode_transaksi,
                        'jenis_laporan'     => 'F&B',
                        'tanggal_transaksi' => $t->created_at,
                        'pelanggan'         => $t->pengguna->nama_pengguna ?? '-',
                        'kasir'             => $t->kasir ?? '-',
                        'nama_produk'       => '-',
                        'jumlah'            => '-',
                        'nominal_transaksi' => $t->total_harga,
                        'metode_pembayaran' => $t->metode_pembayaran ?? 'Cash',
                        'sumber_booking'    => $t->sumber_booking ?? 'Kasir',
                        'status_sewa'       => strtolower($t->status_sewa),
                    ];
                }
            }
        }

        return $flatData;
    }

    public function exportExcel(Request $request)
    {
        $data = $this->getReportData($request);
        $flatData = $this->getFlattenedData($data['transaksis']);
        $filename = 'Laporan_Transaksi_' . $data['start']->format('Y-m-d') . '.xlsx';
        return Excel::download(new TransaksiExport($flatData), $filename);
    }

    public function exportPdf(Request $request)
    {
        $data = $this->getReportData($request);
        
        // Memakai array flat dan dikonversi ulang ke nama key milik PDF
        $flatData = $this->getFlattenedData($data['transaksis']);
        $data['rows'] = array_map(function($item) {
            return (object) [
                'kode_transaksi' => $item['kode_transaksi'],
                'jenis_laporan'  => $item['jenis_laporan'],
                'pelanggan'      => $item['pelanggan'],
                'kasir'          => $item['kasir'],
                'produk'         => $item['nama_produk'],
                'jumlah'         => $item['jumlah'],
                'total'          => $item['nominal_transaksi'],
                'metode'         => $item['metode_pembayaran'],
                'sumber'         => $item['sumber_booking'],
                'status'         => $item['status_sewa'],
            ];
        }, $flatData);

        $data['tanggalCetak'] = now()->translatedFormat('d F Y H:i');
        $data['admin']        = auth()->user()->nama_pengguna;

        $pdf      = Pdf::loadView('admin.laporan.pdf', $data)->setPaper('a4', 'portrait');
        $filename = 'Laporan_Transaksi_' . $data['start']->format('Y-m-d') . '.pdf';

        return $pdf->download($filename);
    }
}