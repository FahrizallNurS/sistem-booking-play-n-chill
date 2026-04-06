@extends('adminlte::page')

@section('title', 'Kelola Booking')

@section('content_header')
    <h1>Kelola Booking</h1>
@stop

@section('content')

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Booking</h3>
            <div class="card-tools">
                <a href="{{ route('admin.booking.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Tambah Booking Manual
                </a>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Username</th>
                        <th>Kode Booking</th>
                        <th>Tanggal</th>
                        <th>Status Pembayaran</th>
                        <th>Status Booking</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- dummy --}}
                    <tr>
                        <td>1</td>
                        <td>johndoe</td>
                        <td>BK-001</td>
                        <td>03/04/2026</td>
                        <td><span class="badge badge-warning">Unpaid</span></td>
                        <td><span class="badge badge-secondary">Pending</span></td>
                        <td>

                            <a href="{{ route('admin.booking.show', 1) }}" class="btn btn-info btn-sm">
                                 <i class="fas fa-eye"></i> Detail
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>janedoe</td>
                        <td>BK-002</td>
                        <td>04/04/2026</td>
                        <td><span class="badge badge-success">Paid</span></td>
                        <td><span class="badge badge-success">Confirmed</span></td>
                        <td>
                            <a href="#" class="btn btn-info btn-sm">
                                <i class="fas fa-eye"></i> Detail
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

@stop