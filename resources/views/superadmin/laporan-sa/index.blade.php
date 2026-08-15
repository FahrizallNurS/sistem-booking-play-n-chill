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

@section('css')
    {{-- Sesuaikan path ini jika struktur asset AdminLTE di proyekmu berbeda --}}
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/plugins/daterangepicker/daterangepicker.css') }}">
    <style>
        .small-box {
            min-height: 140px;
        }

        .small-box .inner h3 {
            font-size: 28px;
            white-space: nowrap;
        }

        .small-box .icon i {
            font-size: 60px;
        }
    </style>
@stop

@section('js')
    {{-- Sesuaikan path ini jika struktur asset AdminLTE di proyekmu berbeda --}}
    <script src="{{ asset('vendor/adminlte/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('vendor/adminlte/plugins/daterangepicker/daterangepicker.js') }}"></script>
    <script>
        $(function () {
            $('#rentang_tanggal').daterangepicker({
                locale: {
                    format: 'DD MMM YYYY',
                    separator: ' - ',
                    applyLabel: 'Terapkan',
                    cancelLabel: 'Batal',
                },
                autoUpdateInput: true,
            });
        });
    </script>
@stop