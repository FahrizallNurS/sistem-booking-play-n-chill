@extends('adminlte::page')
@include('partials.sidebar-superadmin')

@section('title', 'Pendapatan per Kasir - Play N Chill')
@section('plugins.Chartjs', true)

@php
    // DUMMY DATA (sementara). Nanti hapus blok ini, ganti data asli dari Controller
    // via compact('chartLabels', 'chartDatasets', 'tableData').

    $chartLabels = [];
    $dataAdmin01 = [];
    $dataAdmin02 = [];

    for ($i = 0; $i < 30; $i++) {
        $chartLabels[] = \Carbon\Carbon::create(2026, 10, $i + 1)->translatedFormat('d M');
        $dataAdmin01[] = rand(2000000, 32000000);
        $dataAdmin02[] = rand(1000000, 16000000);
    }

    $chartDatasets = [
        ['label' => 'Admin 01', 'data' => $dataAdmin01, 'color' => '#7c3aed', 'dash' => false],
        ['label' => 'Admin 02', 'data' => $dataAdmin02, 'color' => '#10b981', 'dash' => true],
    ];

    $tableData = [
        ['nama' => 'Andi Saputra', 'booking' => 6250000, 'fnb' => 1350000, 'refund' => 50000, 'total' => 7550000],
        ['nama' => 'Budi Santoso', 'booking' => 5100000, 'fnb' => 980000,  'refund' => 0,     'total' => 6080000],
    ];
@endphp

@section('content_header')
<div class="container-fluid py-2">
    <div class="row align-items-center">
        <div class="col-sm-12">
            <h1 class="font-weight-bold text-dark m-0" style="font-size: 28px; letter-spacing: -0.5px;">Pendapatan per Kasir</h1>
            <p class="text-muted mb-0" style="font-size: 14px;">Analisis pendapatan per kasir.</p>
        </div>
    </div>
</div>
@stop

@section('content')
<div class="container-fluid pb-4">

    <div class="row mb-4">
        <div class="col-12">
            <x-filter-card :action="url()->current()">
                <x-filter-select name="periode" label="PERIODE" :options="['harian' => 'Harian', 'mingguan' => 'Mingguan', 'bulanan' => 'Bulanan']" width="col-md-3 col-sm-6"/>
                <x-filter-select name="nama_kasir" label="NAMA KASIR" :options="['semua' => 'Semua Kasir']" width="col-md-4 col-sm-6"/>
            </x-filter-card>
        </div>
    </div>

    <div class="row mb-2">
        <div class="col-12">
            <x-card>
                <x-slot name="title">Performa Kasir</x-slot>
                <x-slot name="subtitle">Akumulasi pendapatan harian (IDR)</x-slot>

                <x-slot name="header">
                    <div id="kasirChart-header-legend" class="d-none d-md-flex gap-3 align-items-center" style="font-size: 12px;"></div>
                </x-slot>

                <div class="chart-container mt-3" style="position: relative; height:400px; width:100%;">
                    <x-chart id="kasirChart" :labels="$chartLabels" :datasets="$chartDatasets" height="400px" :interactive="true" />
                </div>

                <div id="kasirChart-legend-boxes" class="d-flex flex-wrap gap-3 mt-4"></div>
            </x-card>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <x-table>
                <x-slot name="head">
                    <tr style="background-color: #faf5ff;">
                        <th class="py-3 px-4 border-0 text-muted" style="font-size: 11px;">NO</th>
                        <th class="py-3 border-0 text-muted" style="font-size: 11px;">NAMA KASIR</th>
                        <th class="py-3 border-0 text-muted text-right" style="font-size: 11px;">TRANSAKSI BOOKING</th>
                        <th class="py-3 border-0 text-muted text-right" style="font-size: 11px;">TRANSAKSI F&B</th>
                        <th class="py-3 border-0 text-muted text-right" style="font-size: 11px;">JUMLAH REFUND</th>
                        <th class="py-3 border-0 text-muted text-right" style="font-size: 11px;">TOTAL PENDAPATAN</th>
                    </tr>
                </x-slot>

                @foreach($tableData as $index => $row)
                    <tr>
                        <td class="px-4 text-muted py-3" style="font-size: 13px;">{{ $index + 1 }}</td>
                        <td class="text-dark py-3" style="font-size: 13px;">{{ $row['nama'] }}</td>
                        <td class="text-muted text-right py-3" style="font-size: 13px;">Rp {{ number_format($row['booking'], 0, ',', '.') }}</td>
                        <td class="text-muted text-right py-3" style="font-size: 13px;">Rp {{ number_format($row['fnb'], 0, ',', '.') }}</td>
                        <td class="text-muted text-right py-3" style="font-size: 13px;">Rp {{ number_format($row['refund'], 0, ',', '.') }}</td>
                        <td class="text-dark font-weight-bold text-right py-3" style="font-size: 13px;">Rp {{ number_format($row['total'], 0, ',', '.') }}</td>
                    </tr>
                @endforeach

                <x-slot name="footer">
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <span class="text-muted" style="font-size: 13px;">Menampilkan 1 hingga {{ count($tableData) }} dari {{ count($tableData) }} entri</span>
                        <div class="btn-group">
                            <button class="btn btn-sm btn-light border text-muted">Sebelumnya</button>
                            <button class="btn btn-sm btn-primary" style="background-color: #6f42c1; border-color: #6f42c1;">1</button>
                            <button class="btn btn-sm btn-light border text-muted">Selanjutnya</button>
                        </div>
                    </div>
                </x-slot>
            </x-table>
        </div>
    </div>

</div>
@stop

@section('css')
<style>
label {
    font-size: 11px !important;
    font-weight: 600 !important;
    color: #4a5568;
    margin-bottom: 4px;
}
</style>
@stop