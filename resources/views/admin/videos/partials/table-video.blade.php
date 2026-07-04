<div class="card border-0 shadow-sm" style="border-radius: 8px;">
    <div class="card-body p-0">
        <div class="table-responsive custom-scrollbar">
            <table class="table table-hover align-middle mb-0 table-nowrap">
                <thead class="text-dark fw-bold" style="font-size: 0.75rem; letter-spacing: 0.5px; background-color: #f9fafb;">
                    <tr>
                        <th class="text-center py-3 border-0" style="width: 50px;">NO</th>
                        <th class="text-center border-0" style="width: 150px;">THUMBNAIL</th>
                        <th class="text-center border-0">LINK VIDEO</th>
                        <th class="text-center border-0">TANGGAL UPLOAD</th>
                        <th class="text-center border-0">STATUS</th>
                        <th class="text-center border-0">AKSI</th>
                    </tr>
                </thead>
                <tbody style="font-size: 0.85rem; color: #4b5563;">
                    @forelse($videos as $index => $video)
                        <tr>
                            <td class="text-center fw-bold">{{ $index + 1 }}</td>
                            <td class="text-center">
                                <img src="{{ $video['thumbnail'] }}" alt="Thumbnail Video" class="preview-img shadow-sm" style="width: 120px; height: 60px; object-fit: cover; border-radius: 6px;" onerror="this.onerror=null; this.src='{{ asset('images/logo_dumb.png') }}';">
                            </td>
                            <td class="text-center fw-bold text-primary">{{ $video['link_video'] }}</td>
                            <td class="text-center">{{ $video['tanggal_upload'] }}</td>
                            <td class="text-center">
                                @if($video['status'] === 'aktif')
                                    <span id="status-video-{{ $video['id'] }}" class="badge bg-success px-2 py-1 rounded">Aktif</span>
                                @else
                                    <span id="status-video-{{ $video['id'] }}" class="badge bg-danger px-2 py-1 rounded">Nonaktif</span>
                                @endif
                            </td>
                            <td>
                                {{-- Tombol disamakan dengan tabel banner --}}
                                <div class="d-flex justify-content-center" style="gap: 6px;">
                                    <button type="button" class="btn btn-warning text-dark fw-bold btn-action btn-edit-video"
                                        data-id="{{ $video['id'] }}"
                                        data-link="{{ $video['link_video'] }}"
                                        data-status="{{ $video['status'] }}">
                                        <i class="fas fa-edit me-1"></i> Edit
                                    </button>
                                    <button type="button" class="btn btn-danger text-white fw-bold btn-action btn-hapus-video"
                                        data-id="{{ $video['id'] }}">
                                        <i class="fas fa-trash me-1"></i> Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-3">
                                Tidak ada data video.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
 
    {{-- BAGIAN PAGINATION BAWAH --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center p-3 border-top bg-light">
        <span class="text-muted" style="font-size: 0.85rem;">Menampilkan 1 hingga {{ count($videos) }} dari {{ count($videos) }} entri</span>
        <nav class="mt-2 mt-md-0">
            <ul class="pagination pagination-sm mb-0">
                <li class="page-item disabled"><a class="page-link text-muted" href="#">Sebelumnya</a></li>
                <li class="page-item active"><a class="page-link" href="#" style="background-color: #5b21b6; border-color: #5b21b6;">1</a></li>
                <li class="page-item disabled"><a class="page-link text-muted" href="#">Selanjutnya</a></li>
            </ul>
        </nav>
    </div>
</div>
 
@push('css')
<style>
    .table-nowrap th, .table-nowrap td {
        white-space: nowrap;
        vertical-align: middle;
    }
 
    .custom-scrollbar::-webkit-scrollbar {
        height: 8px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #d1d5db;
        border-radius: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #9ca3af;
    }

    /* CSS tambahan agar lebar tombol sama persis dengan banner */
    .btn-action {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        min-width: 90px;
    }
 
    .table td {
        border-bottom: 1px solid #f3f4f6;
    }
</style>
@endpush