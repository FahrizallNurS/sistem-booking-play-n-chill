@extends('adminlte::page')
@include('partials.sidebar-admin')
@section('title', 'Kelola Banner')

@section('content_header')
    <h1>Kelola banner website</h1>
    <p class="text-muted mb-0">Manajemen banner website</p>
@stop

@section('content')

    @include('admin.components.alert-session')

    @php
        // =========================================================
        // DUMMY DATA — sementara, sebelum halaman ini terhubung ke database asli
        // =========================================================
        $banners = [
            [
                'id' => 1,
                'title' => 'Promo Weekend 50% Off',
                'gambar' => 'https://picsum.photos/seed/banner1/200/120',
                'status' => 'aktif',
                'tanggal_upload' => '29/05/2026 20:30',
            ],
            [
                'id' => 2,      
                'title' => 'New F&B Menu Release',
                'gambar' => 'https://picsum.photos/seed/banner2/200/120',
                'status' => 'aktif',
                'tanggal_upload' => '29/05/2026 20:30',
            ],
            [
                'id' => 3,
                'title' => 'Tournament Recap 2023',
                'gambar' => 'https://picsum.photos/seed/banner3/200/120',
                'status' => 'nonaktif',
                'tanggal_upload' => '29/01/2026 20:30',
            ],
        ];
    @endphp

    {{-- Filter --}}
    <div class="card card-outline card-secondary mb-3">
        <div class="card-body">
            <form class="form-inline flex-wrap justify-content-between" style="gap: 8px;">
                <div class="d-flex flex-wrap" style="gap: 8px;">
                    <input type="text" class="form-control form-control-sm" style="min-width: 220px;"
                        placeholder="Cari nama banner...">

                    <div class="d-flex align-items-center" style="gap: 8px;">
                        <label class="mb-0">Status:</label>
                        <select class="form-control form-control-sm">
                            <option>Semua</option>
                            <option value="aktif">Aktif</option>
                            <option value="nonaktif">Nonaktif</option>
                        </select>
                    </div>

                    <button type="button" class="btn btn-sm btn-primary">
                        <i class="fas fa-search"></i> Filter
                    </button>
                    <button type="button" class="btn btn-sm btn-secondary">
                        <i class="fas fa-sync"></i> Reset
                    </button>
                </div>

                <button type="button" class="btn btn-sm font-weight-bold" id="btnTambahBanner"
                    style="background-color: #6f42c1; color: #fff;">
                    <i class="fas fa-plus"></i> Tambah Banner
                </button>
            </form>
        </div>
    </div>

    @include('admin.banners.partials.table-banner')
    @include('admin.banners.partials.modal-form-banner')

@stop

@section('js')
<script>
$(document).ready(function () {

    // ================= Tampilkan nama file yang dipilih di custom-file-label =================
    $('#input_gambar_banner').on('change', function () {
        var fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').html(fileName ? fileName : 'Pilih file...');
    });

    // ================= Buka modal mode TAMBAH =================
    $('#btnTambahBanner').on('click', function () {
        $('#formBanner')[0].reset();
        $('.custom-file-label').html('Pilih file...');
        $('#modalFormBannerLabel').text('Tambah Banner');
        $('#modalFormBanner').modal('show');
    });

    // ================= Buka modal mode EDIT (terisi data baris) =================
    $('.btn-edit-banner').on('click', function () {
        var title  = $(this).data('title');
        var status = $(this).data('status');

        $('#modalFormBannerLabel').text('Edit Banner');
        $('#input_title_banner').val(title);
        $('#input_status_banner').val(status);
        $('.custom-file-label').html('Pilih file...');

        $('#modalFormBanner').modal('show');
    });

    // ================= Submit form (belum ada backend, cuma tutup modal) =================
    $('#formBanner').on('submit', function (e) {
        e.preventDefault();
        $('#modalFormBanner').modal('hide');
    });

    // ================= Konfirmasi Hapus Banner (SweetAlert2) =================
    $('.btn-hapus-banner').on('click', function () {
        var title = $(this).data('title');

        Swal.fire({
            title: 'Hapus Banner?',
            text: 'Banner "' + title + '" akan dihapus. Data yang dihapus tidak bisa dikembalikan.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#6f42c1',
        }).then(function (result) {
            if (result.isConfirmed) {
                // Masih dummy — belum ada request ke backend, baris tabel tetap ada
                Swal.fire({
                    title: 'Terhapus!',
                    text: 'Banner berhasil dihapus.',
                    icon: 'success',
                    confirmButtonColor: '#6f42c1',
                });
            }
        });
    });

});
</script>
@stop