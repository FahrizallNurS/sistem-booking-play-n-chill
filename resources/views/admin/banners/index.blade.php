@extends('adminlte::page')
@include('partials.sidebar-admin')
@section('title', 'Kelola Banner')

@section('content_header')
    <h1>Kelola banner website</h1>
    <p class="text-muted mb-0">Manajemen banner website</p>
@stop

@section('content')

    @include('admin.components.alert-session')

    {{-- Filter --}}
    <div class="card card-outline card-secondary mb-3">
        <div class="card-body">
            <form id="formFilterBanner" class="form-inline flex-wrap justify-content-between" style="gap: 8px;">
                <div class="d-flex flex-wrap" style="gap: 8px;">
                    <input type="text" id="filterSearch" name="search" class="form-control form-control-sm"
                        style="min-width: 220px;" placeholder="Cari nama banner...">

                    <div class="d-flex align-items-center" style="gap: 8px;">
                        <label class="mb-0">Status:</label>
                        <select id="filterStatus" name="status" class="form-control form-control-sm">
                            <option value="">Semua</option>
                            <option value="aktif">Aktif</option>
                            <option value="nonaktif">Nonaktif</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-sm btn-primary" id="btnFilterBanner">
                        <i class="fas fa-search"></i> Filter
                    </button>
                    <button type="button" class="btn btn-sm btn-secondary" id="btnResetFilterBanner">
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

    <div id="bannerTableWrapper">
        @include('admin.banners.partials.table-banner')
    </div>

    @include('admin.banners.partials.modal-form-banner')

@stop

@section('js')
<script>
$(function () {

    const baseUrl = '{{ url("/admin/banner") }}';
    const $wrapper = $('#bannerTableWrapper');

    // ============================================================
    // Function Membersihkan Error Validasi
    // ============================================================
    function clearValidation() {
        $('#formBanner .is-invalid').removeClass('is-invalid');
        $('#formBanner .invalid-feedback').remove();

        $('#error_file_foto').remove();
        $('#uploadDropzone').css('border-color', '#d1d5db');
    }

    // ============================================================
    // FILTERING (AJAX)
    // ============================================================
    function loadBanners(url, params) {

        $wrapper.css('opacity', 0.5);

        $.ajax({

            url: url,
            type: 'GET',
            data: params || {},

            success: function (response) {
                $wrapper.html(response.html);
            },

            error: function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Gagal memuat data banner.'
                });
            },

            complete: function () {
                $wrapper.css('opacity', 1);
            }

        });

    }

    function currentFilterParams() {
        return {
            search: $('#filterSearch').val(),
            status: $('#filterStatus').val()
        };
    }

    // Submit form filter
    $('#formFilterBanner').on('submit', function (e) {
        e.preventDefault();
        loadBanners(baseUrl, currentFilterParams());
    });

    // Reset filter
    $('#btnResetFilterBanner').on('click', function () {
        $('#filterSearch').val('');
        $('#filterStatus').val('');
        loadBanners(baseUrl, {});
    });

    // Klik link pagination hasil AJAX -> tetap AJAX, bukan reload
    $(document).on('click', '#bannerTableWrapper .pagination a.page-link', function (e) {
        e.preventDefault();

        let href = $(this).attr('href');
        if (!href) return;

        loadBanners(href, currentFilterParams());
    });

    // ============================================================
    // TAMBAH BANNER
    // ============================================================
    $('#btnTambahBanner').on('click', function () {

        $('#formBanner')[0].reset();

        clearValidation();

        $('#formBanner').attr('action', baseUrl);
        $('#formMethod').val('POST');

        $('#wrapper_status_banner').addClass('d-none');
        $('#textBantuanFoto').addClass('d-none');

        $('#input_status_banner').val('');

        $('#input_gambar_banner').prop('required', true);

        $('#uploadPreview')
            .attr('src', '')
            .addClass('d-none');

        $('#uploadPlaceholder').removeClass('d-none');

        $('#modalFormBannerLabel').text('Tambah Banner');

        $('#modalFormBanner').modal('show');

    });

    // ============================================================
    // EDIT BANNER
    // ============================================================
    $(document).on('click', '.btn-edit-banner', function () {

        clearValidation();

        let id = $(this).data('id');
        let title = $(this).data('title');
        let status = $(this).data('status');
        let image = $(this).data('image');

        $('#formBanner').attr('action', baseUrl + '/' + id);
        $('#formMethod').val('PUT');

        $('#input_title_banner').val(title);
        $('#input_status_banner').val(status);

        $('#wrapper_status_banner').removeClass('d-none');
        $('#textBantuanFoto').removeClass('d-none');

        $('#input_gambar_banner').prop('required', false);

        $('#uploadPreview')
            .attr('src', image)
            .removeClass('d-none');

        $('#uploadPlaceholder').addClass('d-none');

        $('#modalFormBannerLabel').text('Edit Banner');

        $('#modalFormBanner').modal('show');

    });

    // ============================================================
    // SUBMIT AJAX (form tambah/edit banner)
    // ============================================================
    $('#formBanner').on('submit', function (e) {

        e.preventDefault();

        clearValidation();

        let form = $(this);
        let url = form.attr('action');
        let formData = new FormData(this);
        let btnSubmit = form.find('button[type="submit"]');
        let btnText = btnSubmit.html();

        btnSubmit
            .prop('disabled', true)
            .html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');

        $.ajax({

            url: url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,

            success: function (response) {

                $('#modalFormBanner').modal('hide');

                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: response.message ?? 'Data berhasil disimpan.',
                    timer: 1500,
                    showConfirmButton: false
                }).then(function () {
                    // Refresh tabel dengan filter aktif saat ini, tanpa reload full page
                    loadBanners(baseUrl, currentFilterParams());
                });

            },

            error: function (xhr) {

                btnSubmit
                    .prop('disabled', false)
                    .html(btnText);

                if (xhr.status === 422) {

                    let errors = xhr.responseJSON.errors;

                    if (errors.judul_foto) {
                        $('#input_title_banner')
                            .addClass('is-invalid')
                            .after('<div class="invalid-feedback">' + errors.judul_foto[0] + '</div>');
                    }

                    if (errors.file_foto) {
                        $('#input_gambar_banner').addClass('is-invalid');
                        $('#uploadDropzone')
                            .css('border-color', '#dc3545')
                            .after('<div id="error_file_foto" class="text-danger mt-1 text-sm">' + errors.file_foto[0] + '</div>');
                    }

                    if (errors.is_active) {
                        $('#input_status_banner')
                            .addClass('is-invalid')
                            .after('<div class="invalid-feedback">' + errors.is_active[0] + '</div>');
                    }

                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Terjadi kesalahan pada server.'
                    });
                }

            }

        });

    });

    // ============================================================
    // RESET MODAL
    // ============================================================
    $('#modalFormBanner').on('hidden.bs.modal', function () {

        clearValidation();

        $('#formBanner')[0].reset();

        $('#uploadPreview')
            .attr('src', '')
            .addClass('d-none');

        $('#uploadPlaceholder').removeClass('d-none');

    });

    // ============================================================
    // HAPUS BANNER
    // ============================================================
    $(document).on('click', '.btn-delete-trigger', function () {

        let form = $(this).closest('form');
        let title = $(this).data('title');

        Swal.fire({

            title: 'Hapus Banner?',
            text: 'Banner "' + title + '" akan dihapus secara permanen.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#dc3545'

        }).then(function (result) {
            if (result.isConfirmed) {
                form.submit();
            }
        });

    });

    // ============================================================
    // PREVIEW GAMBAR
    // ============================================================
    $(document).on('change', '#input_gambar_banner', function (e) {

        const file = e.target.files[0];
        if (!file) return;

        const reader = new FileReader();

        reader.onload = function (event) {
            $('#uploadPreview')
                .attr('src', event.target.result)
                .removeClass('d-none');

            $('#uploadPlaceholder').addClass('d-none');
        };

        reader.readAsDataURL(file);

    });

});
</script>
@stop