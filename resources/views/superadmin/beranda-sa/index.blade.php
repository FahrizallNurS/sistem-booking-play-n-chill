@extends('superadmin.layouts.app')
@include('partials.sidebar-superadmin')

@section('title', 'Dashboard Superadmin')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Dashboard Superadmin</h1>
        </div>

        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
        </div>
    </div>
</div>
@stop

@section('content')

<div class="container-fluid">
    <div class="row">

        {{-- Total Pelanggan --}}
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info dashboard-card">

                <div class="inner">
                    <h3>{{ $totalPelanggan }}</h3>
                    <p>Total Pelanggan</p>
                </div>

                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>

                <a href="{{ route('superadmin.users.index') }}"
                class="small-box-footer">

                    Kelola User
                    <i class="fas fa-arrow-circle-right"></i>

                </a>

            </div>
        </div>

        {{-- Total Booking --}}
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning dashboard-card">

                <div class="inner">
                    <h3>{{ $totalBooking }}</h3>
                    <p>Total Booking</p>
                </div>

                <div class="icon">
                    <i class="fas fa-calendar-check"></i>
                </div>

                <a href="{{ route('superadmin.laporan.index') }}"
                class="small-box-footer">

                    Lihat Laporan
                    <i class="fas fa-arrow-circle-right"></i>

                </a>

            </div>
        </div>

        {{-- Booking Selesai --}}
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success dashboard-card">

                <div class="inner">
                    <h3>{{ $bookingSelesai }}</h3>
                    <p>Booking Selesai</p>
                </div>

                <div class="icon">
                    <i class="fas fa-check-circle"></i>
                </div>

                <a href="{{ route('superadmin.laporan.index', ['status_booking' => 'selesai']) }}"
                class="small-box-footer">
                    Detail Booking
                    <i class="fas fa-arrow-circle-right"></i>
                </a>

            </div>
        </div>

        {{-- Pendapatan --}}
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger dashboard-card">

                <div class="inner">
                    <h3 class="text-nowrap">
                        Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
                    </h3>

                    <p>Total Pendapatan</p>
                </div>

                <div class="icon">
                    <i class="fas fa-money-bill-wave"></i>
                </div>

                <a href="{{ route('superadmin.laporan.index') }}"
                class="small-box-footer">
                    Lihat Pendapatan
                    <i class="fas fa-arrow-circle-right"></i>
                </a>

            </div>
        </div>

    </div>

    {{-- User Terbaru --}}
    <div class="row">

        <div class="col-md-12">

            <div class="card card-outline card-primary">

                <div class="card-header">
                    <h3 class="card-title">
                        User Terbaru
                    </h3>
                </div>

                <div class="card-body p-0">

                    <table class="table table-bordered table-hover mb-0">

                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Terdaftar</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse($userTerbaru as $i => $user)

                            <tr>

                                <td>{{ $i + 1 }}</td>

                                <td>
                                    {{ $user->nama_pengguna }}
                                </td>

                                <td>
                                    {{ $user->email }}
                                </td>

                                <td>

                                    @if($user->role == 'superadmin')
                                        <span class="badge badge-danger">
                                            Superadmin
                                        </span>

                                    @elseif($user->role == 'admin')
                                        <span class="badge badge-warning">
                                            Admin
                                        </span>

                                    @else
                                        <span class="badge badge-info">
                                            Pelanggan
                                        </span>
                                    @endif

                                </td>

                                <td>

                                    @if($user->status == 1)
                                        <span class="badge badge-success">
                                            Aktif
                                        </span>
                                    @else
                                        <span class="badge badge-secondary">
                                            Nonaktif
                                        </span>
                                    @endif

                                </td>

                                <td>
                                    {{ $user->created_at->format('d/m/Y') }}
                                </td>

                            </tr>

                            @empty

                            <tr>
                                <td colspan="6"
                                    class="text-center text-muted">

                                    Belum ada data user

                                </td>
                            </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

                <div class="card-footer text-right">

                    <a href="{{ route('superadmin.users.index') }}"
                       class="btn btn-primary btn-sm">

                        Lihat Semua User

                    </a>

                </div>

            </div>

        </div>

    </div>

</div>

@stop

@section('css')
<style>

.small-box {
    border-radius: 10px;
    overflow: hidden;
}

.dashboard-card {
    height: 190px;
}

.dashboard-card .inner {
    padding: 20px;
}

.dashboard-card h3 {
    font-size: 32px;
    font-weight: bold;
    margin-bottom: 10px;
}

.dashboard-card p {
    font-size: 16px;
    margin: 0;
}

.small-box .icon i {
    font-size: 65px;
}

.small-box-footer {
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.card-title {
    font-weight: bold;
}

</style>
@stop