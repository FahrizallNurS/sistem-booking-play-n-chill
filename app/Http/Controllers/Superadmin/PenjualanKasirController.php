<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Models\User;
use App\Models\TrPos;
use App\Models\TrTransaksi;

class PenjualanKasirController extends Controller
{
    // Palet warna khusus grafik pembanding (maks 5, samain sama warna asli halaman ini)
    private $colorPalette = ['#7c3aed', '#10b981', '#f59e0b', '#3b82f6', '#ef4444'];

    // =======================================================
    // HELPER: Rentang tanggal (sama seperti logic asli)
    // =======================================================
    private function getDateRange(Request $request): array
    {
        $periode = $request->input('periode', 'harian');

        if ($periode === 'harian') {
            $val = $request->input('tanggal', Carbon::now()->format('Y-m-d'));
            $baseDateStart = Carbon::parse($val);
            $start = $baseDateStart->copy()->startOfDay();
            $end   = $baseDateStart->copy()->endOfDay();
        } elseif ($periode === 'mingguan') {
            $val = $request->input('minggu', Carbon::now()->format('Y-\WW'));
            $baseDateStart = Carbon::now();
            if (preg_match('/^(\d{4})-W(\d{2})$/', $val, $matches)) {
                $baseDateStart->setISODate($matches[1], $matches[2]);
            }
            $start = $baseDateStart->copy()->startOfWeek();
            $end   = $baseDateStart->copy()->endOfWeek();
        } else {
            $val = $request->input('bulan', Carbon::now()->format('Y-m'));
            $baseDateStart = Carbon::parse($val . '-01');
            $start = $baseDateStart->copy()->startOfMonth();
            $end   = $baseDateStart->copy()->endOfMonth();
        }

        return [$start, $end, $periode];
    }

    // =======================================================
    // HELPER: Sumbu label + slot kosong buat chart
    // =======================================================
    private function buildAxes($start, $end, $periode): array
    {
        $labels = [];
        $slots = [];

        if ($periode === 'harian') {
            for ($i = 6; $i <= 23; $i++) {
                $hour = str_pad($i, 2, '0', STR_PAD_LEFT);
                $labels[] = $hour . ':00';
                $slots[$hour] = 0;
            }
        } elseif ($periode === 'mingguan') {
            foreach (CarbonPeriod::create($start, '1 day', $end) as $date) {
                $labels[] = $date->locale('id')->translatedFormat('l');
                $slots[$date->format('Y-m-d')] = 0;
            }
        } else {
            foreach (CarbonPeriod::create($start, '1 day', $end) as $date) {
                $labels[] = $date->format('d');
                $slots[$date->format('Y-m-d')] = 0;
            }
        }

        return ['labels' => $labels, 'slots' => $slots];
    }

    private function resolveSlotKey($rawDate, $periode)
    {
        $dt = Carbon::parse($rawDate);
        return $periode === 'harian' ? $dt->format('H') : $dt->format('Y-m-d');
    }

    // =======================================================
    // HELPER: 1 dataset garis chart untuk 1 kasir
    // =======================================================
    private function fetchDatasetForKasir($kasirId, $start, $end, $periode, $colorIndex)
    {
        $kasir = User::where('id_pengguna', $kasirId)->where('role', 'admin')->first();
        if (!$kasir) return null;

        $axes = $this->buildAxes($start, $end, $periode);
        $slots = $axes['slots'];

        $transaksiPos = TrPos::whereBetween('created_at', [$start, $end])
            ->whereNotIn('status_pesanan', ['Dibatalkan'])
            ->where('id_admin', $kasirId)
            ->get(['total_pos', 'created_at']);

        $transaksiBooking = TrTransaksi::whereBetween('created_at', [$start, $end])
            ->whereNotIn('status_sewa', ['Batal', 'Dibatalkan'])
            ->where('id_admin', $kasirId)
            ->get(['total_harga', 'created_at']);

        foreach ($transaksiPos as $trx) {
            $key = $this->resolveSlotKey($trx->created_at, $periode);
            if (array_key_exists($key, $slots)) {
                $slots[$key] += (float) $trx->total_pos;
            }
        }

        foreach ($transaksiBooking as $trx) {
            $key = $this->resolveSlotKey($trx->created_at, $periode);
            if (array_key_exists($key, $slots)) {
                $slots[$key] += (float) $trx->total_harga;
            }
        }

        return [
            'id_kasir' => (int) $kasirId,
            'label'    => $kasir->nama_pengguna,
            'data'     => array_values($slots),
            'color'    => $this->colorPalette[$colorIndex % count($this->colorPalette)],
        ];
    }

    // =======================================================
    // HELPER: Pool kasir yang "tersedia" sesuai filter NAMA KASIR
    // (perannya sama seperti kategori/sub_kategori di Produk Layanan)
    // =======================================================
    private function getAvailableKasirs(Request $request)
    {
        $kasirFilter = $request->input('nama_kasir', 'semua');

        $query = User::where('role', 'admin');
        if ($kasirFilter !== 'semua') {
            $query->where('id_pengguna', $kasirFilter);
        }

        return $query->get();
    }

    // =======================================================
    // HELPER: Tentukan kasir mana yang tampil di chart
    // =======================================================
    private function resolveKasirIds(Request $request, $start, $end, $availableKasirs): array
    {
        $availableIds = $availableKasirs->pluck('id_pengguna')->toArray();

        if ($request->has('kasir_ids')) {
            $raw = $request->input('kasir_ids', '');
            $ids = is_array($raw) ? $raw : explode(',', $raw);

            $filtered = array_values(array_unique(array_filter(
                array_map('intval', $ids),
                fn($id) => in_array($id, $availableIds)
            )));

            if (!empty($filtered)) {
                return $filtered;
            }
        }

        // Default: Top-4 kasir berdasar total pendapatan (booking + F&B) di periode aktif
        $revenuePerKasir = [];
        foreach ($availableIds as $id) {
            $fnb = TrPos::whereBetween('created_at', [$start, $end])
                ->whereNotIn('status_pesanan', ['Dibatalkan'])
                ->where('id_admin', $id)
                ->sum('total_pos');

            $booking = TrTransaksi::whereBetween('created_at', [$start, $end])
                ->whereNotIn('status_sewa', ['Batal', 'Dibatalkan'])
                ->where('id_admin', $id)
                ->sum('total_harga');

            $revenuePerKasir[$id] = $fnb + $booking;
        }

        arsort($revenuePerKasir);

        return array_slice(array_keys($revenuePerKasir), 0, 4);
    }

    // =======================================================
    // HELPER: Data tabel, HANYA untuk kasir yang aktif di chart
    // =======================================================
    private function buildTableData(array $kasirIds, $start, $end): array
    {
        if (empty($kasirIds)) return [];

        $tableData = [];

        $transaksiPos = TrPos::whereBetween('created_at', [$start, $end])
            ->whereNotIn('status_pesanan', ['Dibatalkan'])
            ->whereIn('id_admin', $kasirIds)
            ->get();

        $transaksiBooking = TrTransaksi::whereBetween('created_at', [$start, $end])
            ->whereNotIn('status_sewa', ['Batal', 'Dibatalkan'])
            ->whereIn('id_admin', $kasirIds)
            ->get();

        // Urutan tabel ikut urutan kasirIds (= urutan dataset di chart)
        $kasirs = User::whereIn('id_pengguna', $kasirIds)->get()->keyBy('id_pengguna');

        foreach ($kasirIds as $id) {
            $kasir = $kasirs[$id] ?? null;
            if (!$kasir) continue;

            $fnbKasir = $transaksiPos->where('id_admin', $id)->sum('total_pos');
            $bookingKasir = $transaksiBooking->where('id_admin', $id)->sum('total_harga');
            $refundKasir = 0;

            $tableData[] = [
                'nama'    => $kasir->nama_pengguna,
                'booking' => $bookingKasir,
                'fnb'     => $fnbKasir,
                'refund'  => $refundKasir,
                'total'   => $bookingKasir + $fnbKasir - $refundKasir,
            ];
        }

        return $tableData;
    }

    private function renderTableRows(array $tableData): string
    {
        $html = '';
        foreach ($tableData as $idx => $row) {
            $html .= '<tr>';
            $html .= '<td class="px-4 text-muted py-3" style="font-size: 13px;">'.($idx + 1).'</td>';
            $html .= '<td class="text-dark py-3" style="font-size: 13px;">'.e($row['nama']).'</td>';
            $html .= '<td class="text-muted text-right py-3" style="font-size: 13px;">Rp '.number_format($row['booking'], 0, ',', '.').'</td>';
            $html .= '<td class="text-muted text-right py-3" style="font-size: 13px;">Rp '.number_format($row['fnb'], 0, ',', '.').'</td>';
            $html .= '<td class="text-muted text-right py-3" style="font-size: 13px;">Rp '.number_format($row['refund'], 0, ',', '.').'</td>';
            $html .= '<td class="text-dark font-weight-bold text-right py-3" style="font-size: 13px;">Rp '.number_format($row['total'], 0, ',', '.').'</td>';
            $html .= '</tr>';
        }
        return $html;
    }

    // =======================================================
    // MAIN
    // =======================================================
    public function index(Request $request)
    {
        [$start, $end, $periode] = $this->getDateRange($request);

        $kasirDb = User::where('role', 'admin')->pluck('nama_pengguna', 'id_pengguna')->toArray();
        $kasirOptions = ['semua' => 'Semua Kasir'] + $kasirDb;

        $availableKasirs = $this->getAvailableKasirs($request);

        // Handler AJAX: tambah 1 baris pembanding ke chart (dipanggil dari card "+")
        if ($request->ajax() && $request->has('add_kasir_id')) {
            $availableIds = $availableKasirs->pluck('id_pengguna')->toArray();
            if (!in_array((int) $request->add_kasir_id, $availableIds)) {
                return response()->json(['success' => false, 'message' => 'Kasir tidak tersedia untuk filter saat ini.']);
            }

            $dataset = $this->fetchDatasetForKasir($request->add_kasir_id, $start, $end, $periode, $request->color_index ?? 0);
            return response()->json(['success' => true, 'dataset' => $dataset]);
        }

        $kasirIds = $this->resolveKasirIds($request, $start, $end, $availableKasirs);

        $axes = $this->buildAxes($start, $end, $periode);
        $chartLabels = $axes['labels'];
        $chartDatasets = [];

        foreach ($kasirIds as $index => $id) {
            $dataset = $this->fetchDatasetForKasir($id, $start, $end, $periode, $index);
            if ($dataset) {
                $chartDatasets[] = $dataset;
            }
        }

        // Tabel WAJIB ikut kasir yang lagi aktif di chart
        $tableData = $this->buildTableData($kasirIds, $start, $end);

        // Handler AJAX: submit filter (periode/tanggal/nama_kasir berubah)
        if ($request->ajax()) {
            return response()->json([
                'success'          => true,
                'labels'           => $chartLabels,
                'datasets'         => $chartDatasets,
                'availableKasirs'  => $availableKasirs->map(fn($k) => [
                    'id_pengguna'   => $k->id_pengguna,
                    'nama_pengguna' => $k->nama_pengguna,
                ])->values(),
                'html'             => $this->renderTableRows($tableData),
                'total'            => count($tableData),
            ]);
        }

        return view('superadmin.penjualan-kasir.index', compact(
            'chartLabels', 'chartDatasets', 'tableData', 'kasirOptions', 'availableKasirs'
        ));
    }
}