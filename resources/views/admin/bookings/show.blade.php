@extends('adminlte::page')

@section('title', 'Detail Booking')

@section('content_header')
    <h1>Detail Booking</h1>
@stop

@section('content')

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="row">

        {{-- Informasi Booking --}}
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Informasi Booking</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.booking.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="text-muted mb-3">Informasi Pelanggan</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <th width="130">Nama</th>
                                    <td>John Doe</td>
                                </tr>
                                <tr>
                                    <th>No. HP</th>
                                    <td>08123456789</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5 class="text-muted mb-3">Detail Booking</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <th width="130">Kode Booking</th>
                                    <td>BK-001</td>
                                </tr>
                                <tr>
                                    <th>Ruangan</th>
                                    <td>Reguler - 01</td>
                                </tr>
                                <tr>
                                    <th>Paket</th>
                                    <td>Paket PS4 1 Jam</td>
                                </tr>
                                <tr>
                                    <th>Total Harga</th>
                                    <td>Rp 50.000</td>
                                </tr>
                                <tr>
                                    <th>Opsi Bayar</th>
                                    <td>Down Payment</td>
                                </tr>
                                <tr>
                                    <th>Status Booking</th>
                                    <td><span class="badge badge-secondary">Pending</span></td>
                                </tr>
                                <tr>
                                    <th>Status Bayar</th>
                                    <td><span class="badge badge-warning">Unpaid</span></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Form Reschedule --}}
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Reschedule</h3>
                </div>
                <div class="card-body">
                    <form action="#" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tanggal Booking</label>
                                    <input type="date" name="tanggal_booking" class="form-control" value="2026-04-05">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Waktu Mulai</label>
                                    <input type="time" name="waktu_mulai" class="form-control" value="13:00">
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-calendar-alt"></i> Update Jadwal
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Panel Aksi --}}
        <div class="col-md-4">

            {{-- Input Pembayaran --}}
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h3 class="card-title">Input Pembayaran</h3>
                </div>
                <div class="card-body">
                    <form action="#" method="POST" id="formPembayaran">
                        @csrf
                        @method('PATCH')
                        <div class="form-group">
                            <label>Total Harga</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp</span>
                                </div>
                                <input type="text" class="form-control" value="50.000" disabled>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Jumlah Dibayar</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp</span>
                                </div>
                                <input type="number" name="jumlah_dp" class="form-control" 
                                    min="0" max="50000" placeholder="0" required>
                            </div>
                            <small class="text-muted">
                                Sistem otomatis set: <br>
                                • Bayar sebagian → <strong>Partial</strong><br>
                                • Bayar lunas → <strong>Paid</strong>
                            </small>
                        </div>
                        <div class="form-group">
                            <label>Catatan</label>
                            <textarea name="catatan_pembayaran" class="form-control" rows="2"
                                placeholder="Opsional"></textarea>
                        </div>
                        <button type="submit" class="btn btn-success btn-block">
                            <i class="fas fa-save"></i> Simpan Pembayaran
                        </button>
                    </form>
                </div>
            </div>

            {{-- Aksi Booking --}}
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title">Aksi Booking</h3>
                </div>
                <div class="card-body">

                    {{-- Konfirmasi --}}
                    <form action="#" method="POST" class="mb-2">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-success btn-block"
                            onclick="return confirm('Konfirmasi booking ini?')">
                            <i class="fas fa-check"></i> Konfirmasi Booking
                        </button>
                    </form>

                    {{-- Selesai --}}
                    <form action="#" method="POST" class="mb-2">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-info btn-block"
                            onclick="return confirm('Tandai booking ini selesai?')">
                            <i class="fas fa-flag-checkered"></i> Tandai Selesai
                        </button>
                    </form>

                    {{-- Tolak --}}
                    <form action="#" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-danger btn-block"
                            onclick="return confirm('Tolak booking ini?')">
                            <i class="fas fa-times"></i> Tolak Booking
                        </button>
                    </form>

                </div>
            </div>

        </div>
    </div>

@stop