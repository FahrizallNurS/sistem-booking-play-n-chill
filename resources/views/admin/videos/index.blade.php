@extends('adminlte::page')
@include('partials.sidebar-admin')
@section('title', 'Kelola Video')

@section('content_header')
    <h1>Kelola Video Website</h1>
    <p class="text-muted mb-0">Manajemen video landing page</p>
@stop

@section('content')

    @include('admin.components.alert-session')

    {{-- BLOK @php DATA DUMMY TELAH DIHAPUS DARI SINI --}}

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

    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 3000);

    $('#btnTambahVideo').on('click', function () {
        $('#formVideo')[0].reset();
        $('#formVideo').attr('action', "{{ route('admin.video.store') }}");
        $('#formVideo').find('input[name="_method"]').remove();
        $('#input_thumbnail_video').attr('required', 'required');
        $('.custom-file-label').html('Pilih file...');
        $('#modalFormVideoLabel').text('Tambah Video');
        $('#uploadPreview').addClass('d-none').attr('src', '');
        $('#uploadPlaceholder').removeClass('d-none');
        $('#modalFormVideo').modal('show');
    });

    $('.btn-edit-video').on('click', function () {
        var id = $(this).data('id');
        var link  = $(this).data('link');
        var thumbnail = $(this).data('thumbnail');

        $('#modalFormVideoLabel').text('Edit Video');
        $('#input_link_video').val(link);

        var updateUrl = "{{ url('admin/video') }}/" + id;
        $('#formVideo').attr('action', updateUrl);

        if ($('#formVideo').find('input[name="_method"]').length === 0) {
            $('#formVideo').append('<input type="hidden" name="_method" value="PUT">');
        }

        $('#input_thumbnail_video').removeAttr('required');

        if (thumbnail) {
            $('#uploadPreview').attr('src', thumbnail).removeClass('d-none');
            $('#uploadPlaceholder').addClass('d-none');
        } else {
            $('#uploadPreview').addClass('d-none');
            $('#uploadPlaceholder').removeClass('d-none');
        }

        $('#modalFormVideo').modal('show');
    });

   
    $('.btn-toggle-status').on('click', function (e) {
        e.preventDefault();
        
        var formId = $(this).data('form-id');
        var sedangAktif = $(this).data('active') == 1; 
        var linkUrl = $(this).data('link') || 'video';

        if (typeof konfirmasiToggleStatusSubmit === "function") {
            konfirmasiToggleStatusSubmit(formId, sedangAktif, 'Video');
        } else {
            document.getElementById(formId).submit();
        }

    });

    $('.btn-hapus-video').on('click', function (e) {
        e.preventDefault();
        var id = $(this).data('id');
        var formId = 'formDeleteVideo_' + id;

        if (typeof konfirmasiHapusSubmit === "function") {
            konfirmasiHapusSubmit(formId, 'video ini');
        } else {
            document.getElementById(formId).submit();
        }
    });
</script>
@stop