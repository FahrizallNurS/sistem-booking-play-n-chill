@extends('adminlte::page')
@include('partials.sidebar-superadmin')

@section('title', $judul)

@section('content_header')
    <h1>{{ $judul }}</h1>
@stop

@section('content')

    <div class="card">
        <div class="card-body text-center py-5">
            <i class="fas fa-tools fa-3x text-muted mb-3"></i>
            <h4>Halaman "{{ $judul }}" sedang dikembangkan</h4>
            <p class="text-muted mb-0">
                Fitur ini belum tersedia. Menu sudah disiapkan di sidebar
                supaya navigasinya siap begitu fiturnya selesai dibangun.
            </p>
        </div>
    </div>

@stop