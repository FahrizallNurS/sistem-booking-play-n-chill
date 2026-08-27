@extends('adminlte::page')
@include('partials.sidebar-admin')
@section('title', 'Edit Booking')

@section('content_header')
    <h1>Edit Booking</h1>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        <form action="#" method="POST">
            @csrf
            @method('PUT')

            <h5 class="mb-3">Informasi Pelanggan</h5>
            <div class="form-group">
                <label>Nama Pelanggan</label>
                <input type="text" name="nama_pelanggan" class="form-control" value="John Doe" required>
            </div>
            <div class="form-group">
                <label>No. HP</label>
                <input type="text" name="no_hp" class="form-control" value="08123456789">
            </div>

            <hr>
            <h5 class="mb-3">Detail Booking</h5>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Ruangan</label>
                        <input type="text" class="form-control" value="Reguler - 01" disabled>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Paket</label>
                        <input type="text" class="form-control" value="Paket PS4 1 Jam" disabled>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Tanggal Booking</label>
                        <input type="date" name="tanggal_booking" class="form-control" value="2026-04-03" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Waktu Mulai</label>
                        <input type="time" name="waktu_mulai" class="form-control" value="13:00" required>
                    </div>
                </div>
            </div>

            <div class="row">
            <div class="col-md-6">
                    <div class="form-group">
                        <label>Status Booking (Sewa)</label>
                        <select name="status_sewa" class="form-control">
                            <option value="ditahan">Ditahan</option>
                            <option value="dikonfirmasi">Dikonfirmasi</option>
                            <option value="dibatalkan">Dibatalkan</option>
                            <option value="selesai">Selesai</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Status Pembayaran</label>
                        <select name="status_pembayaran" class="form-control">
                            <option value="menunggu">Menunggu</option>
                            <option value="dp">DP (Partial)</option>
                            <option value="lunas">Lunas</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>Catatan Pembayaran</label>
                <textarea name="catatan_pembayaran" class="form-control" rows="2"></textarea>
            </div>

            <a href="{{ url()->previous() }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
</div>
@stop