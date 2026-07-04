@extends('adminlte::page')
@include('partials.sidebar-admin')
@section('title', 'Kelola Video')

@section('content_header')
    <h1>Kelola Video Website</h1>
    <p class="text-muted mb-0">Manajemen video landing page</p>
@stop

@section('content')

    @include('admin.components.alert-session')

    @php
        $videos = [
            [
                'id' => 1,
                'link_video' => 'https://youtube.com-sjifx...',
                'thumbnail' => 'https://picsum.photos/seed/video1/200/120',
                'status' => 'aktif',
                'tanggal_upload' => '29/05/2026 20:30',
            ],
            // Tambahkan data dummy lainnya jika perlu
        ];
    @endphp

    {{-- Filter --}}
    <div class="card card-outline card-secondary mb-3">
        <div class="card-body">
            <form class="form-inline flex-wrap justify-content-between" style="gap: 8px;">
                <div class="d-flex flex-wrap" style="gap: 8px;">
                    <input type="text" class="form-control form-control-sm" style="min-width: 220px;"
                        placeholder="Cari link video...">

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

                <button type="button" class="btn btn-sm font-weight-bold" id="btnTambahVideo"
                    style="background-color: #6f42c1; color: #fff;">
                    <i class="fas fa-plus"></i> Tambah Video
                </button>
            </form>
        </div>
    </div>

    @include('admin.videos.partials.table-video')
    @include('admin.videos.partials.modal-form-video')

@stop

@section('js')
<script>
$(document).ready(function () {

    // ================= Tampilkan nama file yang dipilih =================
    $('#input_thumbnail_video').on('change', function () {
        var fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').html(fileName ? fileName : 'Pilih file...');
    });

    // ================= Buka modal mode TAMBAH =================
    $('#btnTambahVideo').on('click', function () {
        $('#formVideo')[0].reset();
        $('.custom-file-label').html('Pilih file...');
        $('#modalFormVideoLabel').text('Tambah Video');
        $('#uploadPreview').addClass('d-none');
        $('#uploadPlaceholder').removeClass('d-none');
        $('#modalFormVideo').modal('show');
    });

    // ================= Buka modal mode EDIT =================
    $('.btn-edit-video').on('click', function () {
        var link  = $(this).data('link');
        var status = $(this).data('status');

        $('#modalFormVideoLabel').text('Edit Video');
        $('#input_link_video').val(link);
        $('#input_status_video').val(status);
        $('.custom-file-label').html('Pilih file...');

        $('#modalFormVideo').modal('show');
    });

    // ================= Submit form =================
    $('#formVideo').on('submit', function (e) {
        e.preventDefault();
        $('#modalFormVideo').modal('hide');
    });

    // ================= Konfirmasi Hapus Video (SweetAlert2) =================
    $('.btn-hapus-video').on('click', function () {
        Swal.fire({
            title: 'Hapus Video?',
            text: 'Video ini akan dihapus. Data yang dihapus tidak bisa dikembalikan.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#6f42c1',
        }).then(function (result) {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Terhapus!',
                    text: 'Video berhasil dihapus.',
                    icon: 'success',
                    confirmButtonColor: '#6f42c1',
                });
            }
        });
    });

});
</script>
@stop