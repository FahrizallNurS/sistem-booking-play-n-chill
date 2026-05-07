@extends('superadmin.layouts.app')
@include('partials.sidebar-superadmin')
@section('title', 'Tinjau Laporan')

{{-- Masukkan CSS Custom agar tampilan Kotak Ungu muncul --}}
@section('css')
<style>
    .custom-container {
        background-color: #6c5ce7; /* Warna ungu modern */
        border-radius: 25px; /* Membuat sudut sangat bulat sesuai gambar */
        padding: 25px;
        color: white;
    }
    .custom-table {
        color: white !important;
        width: 100%;
    }
    .custom-table thead th {
        border-bottom: 2px solid rgba(255,255,255,0.2);
        font-size: 1.1rem;
        padding-bottom: 15px;
    }
    .custom-table tbody tr {
        border-bottom: 1px solid rgba(255,255,255,0.1);
    }
    .custom-table td {
        padding: 20px 10px;
        vertical-align: middle;
    }
    .status-selesai { color: #55efc4; font-weight: bold; }
    .status-cancel { color: #ff7675; font-weight: bold; }
    
    .btn-circle {
        border-radius: 50%;
        width: 35px;
        height: 35px;
        background: rgba(255,255,255,0.2);
        border: none;
        color: white;
        margin: 0 2px;
    }
</style>
@stop

@section('content')
<div class="pt-4">
    <div class="custom-container">
        <div class="table-responsive">
            <table class="table custom-table">
                <thead>
                    <tr>
                        <th>Id.</th>
                        <th>User Name</th>
                        <th>Kode Booking</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>BK-01</td>
                        <td>Gojo Satoru</td>
                        <td>PSHT1922</td>
                        <td>23/09/2026<br><small>14.00 WIB</small></td>
                        <td class="status-selesai">Selesai</td>
                        <td>
                            <button class="btn-circle"><i class="fas fa-eye"></i></button>
                            <button class="btn-circle"><i class="fas fa-pencil-alt"></i></button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@stop