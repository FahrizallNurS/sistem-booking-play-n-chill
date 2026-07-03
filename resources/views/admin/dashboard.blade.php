@extends('adminlte::page')
@include('partials.sidebar-admin')
@section('title', 'Dashboard Admin')

@section('content_header')
    <h1 class="m-0 text-dark">Dashboard Admin</h1>
@stop

@section('content') 

    {{-- Statistik --}}
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $totalPelanggan }}</h3>
                    <p>Total Pelanggan</p>
                </div>
                <div class="icon"><i class="fas fa-users"></i></div>
                <a href="{{ url('admin/pelanggan') }}" class="small-box-footer">
                    Lihat Semua <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $totalBooking }}</h3>
                    <p>Total Booking</p>
                </div>
                <div class="icon"><i class="fas fa-calendar-check"></i></div>
                <a href="{{ url('admin/booking') }}" class="small-box-footer">
                    Lihat Semua <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $totalRuangan }}</h3>
                    <p>Total Ruangan</p>
                </div>
                <div class="icon"><i class="fas fa-door-open"></i></div>
                <a href="{{ url('admin/ruangan') }}" class="small-box-footer">
                    Lihat Semua <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>Rp {{ number_format($pendapatanTotal, 0, ',', '.') }}</h3>
                    <p>Total Pendapatan</p>
                </div>
                <div class="icon"><i class="fas fa-money-bill-wave"></i></div>
                <a href="{{ url('admin/laporan') }}" class="small-box-footer">
                    Lihat Laporan <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">Booking Terbaru</h3>
                    <a href="{{ url('admin/booking') }}" class="btn btn-sm btn-primary">Lihat Semua</a>
                </div>
                <div class="card-body p-0">
                    <table class="table table-bordered table-hover mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>NO</th>
                                <th>Kode</th>
                                <th>Pelanggan</th>
                                <th>Ruangan</th>
                                <th>Waktu Main</th>
                                <th>Total</th>
                                <th>Pembayaran</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bookingTerbaru as $i => $b)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td><code>{{ $b->kode_sewa }}</code></td>
                                <td>{{ $b->pengguna->nama_pengguna ?? '-' }}</td>
                                <td>{{ $b->penetapanHarga->ruangan->nama_ruangan ?? '-' }}</td>
                                <td>
                                    {{ \Carbon\Carbon::parse($b->waktu_mulai)->format('d/m/Y H:i') }}
                                    — {{ \Carbon\Carbon::parse($b->waktu_selesai)->format('H:i') }}
                                </td>
                                <td>Rp {{ number_format($b->total_harga, 0, ',', '.') }}</td>
                                <td>
                                    @if($b->status_pembayaran === 'lunas')
                                        <span class="badge badge-success">Lunas</span>
                                    @elseif($b->status_pembayaran === 'dp')
                                        <span class="badge badge-warning">DP</span>
                                    @else
                                        <span class="badge badge-secondary">Menunggu</span>
                                    @endif
                                </td>
                                <td>
                                    @if($b->status_sewa === 'dikonfirmasi')
                                        <span class="badge badge-success">Dikonfirmasi</span>
                                    @elseif($b->status_sewa === 'ditahan')
                                        <span class="badge badge-warning">Ditahan</span>
                                    @elseif($b->status_sewa === 'selesai')
                                        <span class="badge badge-primary">Selesai</span>
                                    @else
                                        <span class="badge badge-danger">Dibatalkan</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center">Belum ada data booking</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@stop