<div class="card card-outline card-secondary">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-striped mb-0 text-center">
                <thead style="background-color: #f8f9fa;">
                    <tr>
                        <th style="width: 5%;">NO</th>
                        <th style="width: 25%;">THUMBNAIL</th>
                        <th style="width: 30%;">LINK VIDEO</th>
                        <th style="width: 20%;">TANGGAL UPLOAD</th>
                        <th style="width: 10%;">STATUS</th>
                        <th style="width: 10%;">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($videos as $index => $video)
                        <tr>
                            <td class="align-middle">{{ $index + 1 }}</td>
                            
                            {{-- Menampilkan Gambar Asli --}}
                            <td class="align-middle">
                                @if($video->thumbnail)
                                    <img src="{{ asset('uploads/videos/' . $video->thumbnail) }}" alt="Thumbnail" style="width: 120px; height: 70px; object-fit: cover; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                                @else
                                    <span class="text-muted">Tidak ada gambar</span>
                                @endif
                            </td>
                            
                            {{-- Menampilkan Link --}}
                            <td class="align-middle text-left">
                                <a href="{{ $video->{'link-video'} }}" target="_blank" class="text-primary text-truncate d-inline-block" style="max-width: 250px;">
                                    {{ $video->{'link-video'} }}
                                </a>
                            </td>
                            
                            {{-- Menampilkan Tanggal Asli dari Database --}}
                            <td class="align-middle">
                                {{ \Carbon\Carbon::parse($video->created_at)->format('d/m/Y H:i') }}
                            </td>
                            
                            {{-- Menampilkan Status --}}
                            <td class="align-middle">
                                @if($video->is_active)
                                    <span class="badge badge-success px-2 py-1">Aktif</span>
                                @else
                                    <span class="badge badge-danger px-2 py-1">Nonaktif</span>
                                @endif
                            </td>
                            
                           {{-- Tombol Aksi --}}
                            
                           {{-- Tombol Aksi (Ditambah text-nowrap agar tidak turun baris) --}}
                            <td class="align-middle text-nowrap">
                                <div class="d-flex justify-content-center align-items-center" style="gap: 5px;">
                                    
                                    {{-- Tombol Edit --}}
                                    <button class="btn btn-sm btn-info btn-edit-video text-nowrap" 
                                        data-id="{{ $video->id_video }}"
                                        data-link="{{ $video->{'link-video'} }}" 
                                        data-status="{{ $video->is_active }}"
                                        data-thumbnail="{{ $video->thumbnail ? asset('uploads/videos/' . $video->thumbnail) : '' }}"
                                        title="Edit">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>

                                    {{-- TOMBOL TOGGLE STATUS --}}
                                    @if($video->is_active)
                                        {{-- Tombol Nonaktifkan --}}
                                        <button type="button" class="btn btn-sm btn-warning btn-toggle-status text-nowrap" 
                                            data-form-id="formToggleStatus_{{ $video->id_video }}"
                                            data-active="1"
                                            data-link="{{ $video->{'link-video'} }}"
                                            title="Nonaktifkan" style="color: #212529; font-weight: 500;">
                                            <i class="fas fa-ban"></i> Nonaktifkan
                                        </button>
                                    @else
                                        {{-- Tombol Aktifkan --}}
                                        <button type="button" class="btn btn-sm btn-success btn-toggle-status text-nowrap" 
                                            data-form-id="formToggleStatus_{{ $video->id_video }}"
                                            data-active="0"
                                            data-link="{{ $video->{'link-video'} }}"
                                            title="Aktifkan">
                                            <i class="fas fa-check"></i> Aktifkan
                                        </button>
                                    @endif
                                    
                                    {{-- Form Hapus Beneran --}}
                                    <form action="{{ route('admin.video.destroy', $video->id_video) }}" method="POST" id="formDeleteVideo_{{ $video->id_video }}" class="m-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-danger btn-hapus-video text-nowrap" data-id="{{ $video->id_video }}" title="Hapus">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </form>

                                    {{-- FORM HIDDEN UNTUK TOGGLE STATUS --}}
                                    <form action="{{ route('admin.video.toggle-status', $video->id_video) }}" method="POST" id="formToggleStatus_{{ $video->id_video }}" style="display:none;">
                                        @csrf
                                        @method('PATCH')
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                               Belum ada data video yang ditambahkan
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>