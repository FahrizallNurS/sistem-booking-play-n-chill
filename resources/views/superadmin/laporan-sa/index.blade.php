@extends('adminlte::page')
@include('partials.sidebar-superadmin')

@section('title', 'Laporan Superadmin')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Laporan Transaksi</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Laporan</li>
                </ol>
            </div>
        </div>
    </div>
@stop

@section('content')
<div class="container-fluid">

    {{-- Filter — disamakan persis dengan pola filter di Beranda Superadmin --}}
    <div class="mb-4">
        <x-filter-card :action="route('superadmin.laporan.index')">
            <x-filter-select
                name="periode"
                label="Periode"
                :options="['harian' => 'Harian', 'mingguan' => 'Mingguan', 'bulanan' => 'Bulanan']"
                width="col-md-3 col-sm-6"
                default="bulanan"
            />
            <x-filter-dynamic-date width="col-md-3 col-sm-6" />
            <x-filter-select
                name="jenis_transaksi"
                label="Jenis Transaksi"
                :options="['semua' => 'Semua', 'booking' => 'Booking', 'fnb' => 'F&B']"
                width="col-md-3 col-sm-6"
                default="semua"
            />
            <x-filter-select
                name="status_transaksi"
                label="Status Transaksi"
                :options="['' => 'Semua', 'selesai' => 'Selesai', 'dibatalkan' => 'Dibatalkan']"
                width="col-md-3 col-sm-6"
                default=""
            />
        </x-filter-card>
    </div>

    @include('superadmin.laporan-sa.partials.summary-cards')

    @include('superadmin.laporan-sa.partials.table-transaksi')

    {{-- Modal detail per baris — varian modal ditentukan oleh jenis_laporan tiap transaksi --}}
    @foreach($transaksis as $t)
        @if($t->jenis_laporan === 'Booking')
            @include('superadmin.laporan-sa.partials.modal-booking', ['t' => $t])
        @else
            @include('superadmin.laporan-sa.partials.modal-fnb', ['t' => $t])
        @endif
    @endforeach

</div>
@stop