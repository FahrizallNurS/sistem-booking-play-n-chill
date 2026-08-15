<div class="card border-0 shadow-sm" style="border-radius: 8px;">
    <div class="card-body p-0">
        <div class="table-responsive custom-scrollbar">
            <table class="table table-hover align-middle mb-0 table-nowrap">
                <thead class="bg-light text-dark fw-bold" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                    <tr>
                        <th class="text-center py-3 border-0" style="width: 50px;">NO</th>
                        <th class="border-0" style="width: 80px;">PREVIEW BANNER</th>
                        <th class="border-0">TITLE BANNER</th>
                        <th class="border-0 text-center">STATUS</th>
                        <th class="border-0">TANGGAL UPLOAD</th>
                        <th class="border-0 text-center">AKSI</th>
                    </tr>
                </thead>
                <tbody style="font-size: 0.85rem; color: #4b5563;">
                    @forelse($banners as $index => $banner)
                        <tr>
                            <td class="text-center">{{ $banners->firstItem() + $index }}</td>
                            <td>
                                <img src="{{ asset($banner->file_foto) }}" alt="{{ $banner->judul_foto }}"
                                    class="preview-img shadow-sm"
                                    onerror="this.onerror=null; this.src='{{ asset('images/logo_dumb.png') }}';">
                            </td>
                            <td class="text-dark fw-bold">{{ $banner->judul_foto }}</td>
                            <td class="text-center">
                                @if($banner->is_active == 1)
                                    <span class="badge bg-success px-2 py-1 rounded">Aktif</span>
                                @else
                                    <span class="badge bg-danger px-2 py-1 rounded">Nonaktif</span>
                                @endif
                            </td>
                            <td>{{ \Carbon\Carbon::parse($banner->created_at)->format('d/m/Y H:i') }}</td>
                            <td>
                                <div class="d-flex justify-content-center" style="gap: 6px;">
                                    <button type="button" class="btn btn-warning text-dark fw-bold btn-action btn-edit-banner"
                                        data-id="{{ $banner->id_galeri }}"
                                        data-title="{{ $banner->judul_foto }}"
                                        data-status="{{ $banner->is_active }}"
                                        data-image="{{ asset($banner->file_foto) }}">
                                        <i class="fas fa-edit me-1"></i> Edit
                                    </button>

                                    <form action="{{ url('/admin/banner/' . $banner->id_galeri) }}" method="POST" class="form-hapus-banner d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger text-white fw-bold btn-action btn-delete-trigger" data-title="{{ $banner->judul_foto }}">
                                            <i class="fas fa-trash me-1"></i> Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-3">
                                Tidak ada data banner.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center p-3 border-top bg-light">
        <span class="text-muted" style="font-size: 0.85rem;">
            Menampilkan {{ $banners->firstItem() ?? 0 }} hingga {{ $banners->lastItem() ?? 0 }}
            dari {{ $banners->total() }} entri
        </span>
        <nav class="mt-2 mt-md-0">
            {{ $banners->onEachSide(1)->links() }}
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

    .preview-img {
        width: 90px;
        height: 45px;
        object-fit: cover;
        background-color: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 4px;
        padding: 2px;
    }

    .btn-action {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        min-width: 90px;
    }

    .table td {
        border-bottom: 1px solid #f3f4f6;
    }

    .pagination .page-item.active .page-link {
        background-color: #5b21b6;
        border-color: #5b21b6;
    }
    .pagination .page-link {
        color: #5b21b6;
    }
</style>
@endpush