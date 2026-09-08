<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\TrTransaksi;
use App\Models\TrPos;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TransaksiExport;

class SATinjauLaporanController extends Controller
{

    private function getPeriodRange(Request $request): array
    {
        $periode = $request->input('periode', 'bulanan');

        if ($periode === 'mingguan') {
            $minggu = $request->input('minggu', now()->format('Y-\WW'));
            if (str_contains($minggu, '-W')) {
                [$year, $week] = explode('-W', $minggu);
                $start = Carbon::now()->setISODate((int) $year, (int) $week)->startOfWeek();
                $end   = Carbon::now()->setISODate((int) $year, (int) $week)->endOfWeek();
            } else {
                $start = Carbon::now()->startOfWeek();
                $end   = Carbon::now()->endOfWeek();
            }
        } elseif ($periode === 'bulanan') {
            $bulan = $request->input('bulan', now()->format('Y-m'));
            try {
                $start = Carbon::parse($bulan . '-01')->startOfMonth();
            } catch (Exception $e) {
                $start = Carbon::now()->startOfMonth();
            }
            $end = $start->copy()->endOfMonth();
        } else {
            // Default: Harian. Jam operasional 10:00 - 01:00 (lewat tengah malam).
            $tanggal = $request->input('tanggal', now()->format('Y-m-d'));
            try {
                $start = Carbon::parse($tanggal)->setTime(10, 0, 0);
                $end   = Carbon::parse($tanggal)->addDay()->setTime(1, 59, 59);
            } catch (Exception $e) {
                $start = Carbon::today()->setTime(10, 0, 0);
                $end   = Carbon::tomorrow()->setTime(1, 59, 59);
            }
        }

        return [$start, $end, $periode];
    }

    private function getQueryData(Request $request)
    {
        [$start, $end, $periode] = $this->getPeriodRange($request);

        $jenisTransaksi  = $request->input('jenis_transaksi', 'semua');
        $statusTransaksi = $request->input('status_transaksi', ''); // '' = Semua, 'dibatalkan' = Dibatalkan

        $bookingSelesaiRaw = TrTransaksi::with(['pengguna', 'admin', 'penetapanHarga.ruangan', 'penetapanHarga.paket'])
        ->where('status_pembayaran', 'lunas')
        ->whereBetween('struk_created_at', [$start, $end])
        ->get()
        ->map(function ($item) {
            $item->jenis_laporan   = 'Booking';
            $item->kasir           = $item->admin->nama_pengguna ?? null;
            $item->kode_transaksi  = $item->kode_sewa;
            $item->tanggal_laporan = $item->created_at;
            return $item;
        });

        $bookingDibatalkanRaw = TrTransaksi::with(['pengguna', 'admin', 'penetapanHarga.ruangan', 'penetapanHarga.paket'])
            ->where('status_sewa', 'dibatalkan')
            ->whereBetween('created_at', [$start, $end])
            ->get()
            ->map(function ($item) {
                $item->jenis_laporan   = 'Booking';
                $item->kasir           = $item->admin->nama_pengguna ?? null;
                $item->kode_transaksi  = $item->kode_sewa;
                $item->status_sewa     = strtolower($item->status_pesanan ?? '');
                $item->tanggal_laporan = $item->created_at;
                return $item;
            });

        $bookingRaw = $bookingSelesaiRaw->concat($bookingDibatalkanRaw)
            ->sortByDesc('waktu_mulai')
            ->values();

       $fnbSelesaiRaw = TrPos::with(['pengguna', 'admin', 'transaksi.penetapanHarga.ruangan', 'details.produk'])
        ->where('status_pembayaran', 'lunas')
        ->whereBetween('struk_created_at', [$start, $end])
        ->get()
        ->map(function ($item) {
            $item->jenis_laporan   = 'F&B';
            $item->waktu_mulai     = $item->struk_created_at;
            $item->tanggal_laporan = $item->struk_created_at;
            $item->kasir           = $item->admin->nama_pengguna ?? null;
            $item->kode_transaksi  = $item->kode_pos;
            $item->ruangan         = $item->transaksi->penetapanHarga->ruangan->nama_ruangan ?? '-';
            $item->status_sewa     = strtolower($item->status_pesanan ?? '');
            $item->total_harga     = $item->total_pos;
            $item->sumber_booking  = $item->sumber_pesanan;
            $item->items           = $item->details->map(function ($d) {
                return (object) [
                    'produk'   => $d->produk->nama_produk ?? 'Produk dihapus',
                    'harga'    => $d->harga_satuan,
                    'jumlah'   => $d->jumlah,
                    'subtotal' => $d->subtotal,
                ];
            });
            return $item;
        });

        $fnbDibatalkanRaw = TrPos::with(['pengguna', 'admin', 'transaksi.penetapanHarga.ruangan', 'details.produk'])
            ->where('status_pesanan', 'Dibatalkan')
            ->whereBetween('created_at', [$start, $end])
            ->get()
            ->map(function ($item) {
                $item->jenis_laporan   = 'F&B';
                $item->waktu_mulai     = $item->created_at;
                $item->tanggal_laporan = $item->created_at;
                $item->kasir           = $item->admin->nama_pengguna ?? null;
                $item->kode_transaksi  = $item->kode_pos;
                $item->ruangan         = $item->transaksi->penetapanHarga->ruangan->nama_ruangan ?? '-';
                $item->status_sewa     = strtolower($item->status_pesanan ?? '');
                $item->total_harga     = $item->total_pos;
                $item->sumber_booking  = $item->sumber_pesanan;
                $item->items           = $item->details->map(function ($d) {
                    return (object) [
                        'produk'   => $d->produk->nama_produk ?? 'Produk dihapus',
                        'harga'    => $d->harga_satuan,
                        'jumlah'   => $d->jumlah,
                        'subtotal' => $d->subtotal,
                    ];
                });
                return $item;
            });

        $fnbRaw = $fnbSelesaiRaw->concat($fnbDibatalkanRaw)
            ->sortByDesc('waktu_mulai')
            ->values();

        if ($statusTransaksi === 'selesai') {
            $bookingFiltered = $bookingSelesaiRaw->values();
            $fnbFiltered     = $fnbSelesaiRaw->values();
        } elseif ($statusTransaksi === 'dibatalkan') {
            $bookingFiltered = $bookingDibatalkanRaw->values();
            $fnbFiltered     = $fnbDibatalkanRaw->values();
        } else {
            $bookingFiltered = $bookingRaw;
            $fnbFiltered     = $fnbRaw;
        }

        $transaksis = match ($jenisTransaksi) {
            'booking' => $bookingFiltered,
            'fnb'     => $fnbFiltered,
            default   => $bookingFiltered->concat($fnbFiltered)->sortByDesc('waktu_mulai')->values(),
        };

        $totalBooking      = $bookingRaw->count();
        $bookingSelesai    = $bookingSelesaiRaw->count();
        $bookingDibatalkan = $bookingDibatalkanRaw->count();
        $pendapatanBooking = (float) $bookingSelesaiRaw->sum('total_harga');

        $totalFnb      = $fnbRaw->count();
        $fnbSelesai    = $fnbSelesaiRaw->count();
        $fnbDibatalkan = $fnbDibatalkanRaw->count();
        $pendapatanFnb = (float) $fnbSelesaiRaw->sum('total_pos');

        $totalPelanggan = User::where('role', 'pelanggan')->count();
        $totalAdmin     = User::where('role', 'admin')->count();

        return compact(
            'transaksis',
            'totalBooking', 'bookingSelesai', 'bookingDibatalkan', 'pendapatanBooking',
            'totalFnb', 'fnbSelesai', 'fnbDibatalkan', 'pendapatanFnb',
            'totalPelanggan', 'totalAdmin',
            'periode', 'start', 'end', 'jenisTransaksi'
        );
    }

    private function paginateTransaksis($transaksis, Request $request)
    {
        $perPage = 10;
        $page    = (int) $request->input('page', 1);
        $slice   = $transaksis->slice(($page - 1) * $perPage, $perPage)->values();

        return new \Illuminate\Pagination\LengthAwarePaginator(
            $slice,
            $transaksis->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );
    }

    public function index(Request $request)
    {
        $data = $this->getQueryData($request);
        $data['transaksisPaged'] = $this->paginateTransaksis($data['transaksis'], $request);

        if ($request->ajax() && $request->has('page')) {
            $html = view('superadmin.laporan-sa.partials.table-rows', [
                'transaksis' => $data['transaksisPaged'],
            ])->render();

            $pagination = view('superadmin.laporan-sa.partials.pagination-links', [
                'transaksisPaged' => $data['transaksisPaged'],
            ])->render();

            return response()->json([
                'html'       => $html,
                'pagination' => $pagination,
                'info'       => "Menampilkan {$data['transaksisPaged']->firstItem()} hingga {$data['transaksisPaged']->lastItem()} dari {$data['transaksisPaged']->total()} entri",
            ]);
        }

        return view('superadmin.laporan-sa.index', $data);
    }

    /**
     * Sumber data terpusat (Single Source of Truth) untuk export PDF dan Excel.
     */
    private function getFlattenedData($transaksis): array
    {
        $flatData = [];

        foreach ($transaksis as $t) {
            if ($t->jenis_laporan === 'Booking') {
                $flatData[] = [
                    'kode_transaksi'    => $t->kode_sewa,
                    'jenis_laporan'     => 'Booking',
                    'tanggal_transaksi' => $t->tanggal_laporan,
                    'pelanggan'         => $t->pengguna->nama_pengguna ?? '-',
                    'kasir'             => $t->kasir ?? '-',
                    'nama_produk'       => $t->penetapanHarga->paket->nama_paket ?? 'Paket Terhapus',
                    'jumlah'            => ($t->penetapanHarga->durasi_jam ?? 0) . ' Jam',
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
                            'tanggal_transaksi' => $t->tanggal_laporan,
                            'pelanggan'         => $t->pengguna->nama_pengguna ?? '-',
                            'kasir'             => $t->kasir ?? '-',
                            'nama_produk'       => $item->produk,
                            'jumlah'            => $item->jumlah . ' Item',
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
                        'tanggal_transaksi' => $t->tanggal_laporan,
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

    public function exportPdf(Request $request)
    {
        $data = $this->getQueryData($request);

        // Ambil data flat
        $flatData = $this->getFlattenedData($data['transaksis']);

        // Konversi array kembali ke object dengan key yang sesuai dengan PDF blade agar tidak merusak view
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

        $data['totalPendapatan'] = collect($data['rows'])
            ->where('status', 'selesai')
            ->sum('total');

        $data['totalSelesai']    = $data['bookingSelesai'] + $data['fnbSelesai'];
        $data['totalDibatalkan'] = $data['bookingDibatalkan'] + $data['fnbDibatalkan'];

        $data['tanggalCetak'] = now()->translatedFormat('d F Y H:i');
        $data['admin']        = auth()->user()->nama_pengguna;

        $pdf      = Pdf::loadView('admin.laporan.pdf', $data)->setPaper('a4', 'portrait');
        $filename = 'Laporan_Transaksi_' . $data['start']->format('Y-m-d') . '.pdf';

        return $pdf->download($filename);
    }

    public function exportExcel(Request $request)
    {
        $data = $this->getQueryData($request);
        
        // Ambil data flat
        $flatData = $this->getFlattenedData($data['transaksis']);

        $filename = 'Laporan_Transaksi_' . $data['start']->format('Y-m-d') . '.xlsx';
        return Excel::download(new TransaksiExport($flatData), $filename);
    }
}