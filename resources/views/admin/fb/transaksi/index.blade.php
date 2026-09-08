@extends('adminlte::page')
@include('partials.sidebar-admin')

@section('title', 'Kelola Transaksi F&B - Play N Chill')

@section('content_header')
    <div class="px-2">
        <h1 class="text-dark fw-bold" style="font-size: 1.8rem;">Kelola Transaksi F&B</h1>
    </div>
@stop

@section('content')
<div class="container-fluid px-2 pb-4">
    
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 8px;">
        <div class="card-body py-3">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                
                <form action="{{ url()->current() }}" method="GET" class="d-flex flex-wrap align-items-center mb-0" style="gap: 12px;">
                    
                    <input type="date" name="tanggal" value="{{ request('tanggal') }}" class="form-control shadow-none text-muted" style="width: 170px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 0.9rem; padding: 0.45rem 0.75rem; height: 38px;">

                    <select name="sumber" class="form-select shadow-none text-muted" style="width: 150px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 0.9rem; cursor: pointer; padding: 0.45rem 2rem 0.45rem 0.75rem; height: 38px;">
                        <option value="">Semua Sumber</option>
                        <option value="Kasir" {{ request('sumber') == 'Kasir' ? 'selected' : '' }}>Kasir</option>
                        <option value="Online" {{ request('sumber') == 'Online' ? 'selected' : '' }}>Online</option>
                    </select>

                    <select name="status" class="form-select shadow-none text-muted" style="width: 150px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 0.9rem; cursor: pointer; padding: 0.45rem 2rem 0.45rem 0.75rem; height: 38px;">
                        <option value="">Semua Status</option>
                        <option value="Menunggu" {{ request('status') == 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
                        <option value="Selesai" {{ request('status') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                    </select>

                    <div class="d-flex" style="gap: 8px;">
                        <button type="submit" class="btn btn-primary px-3 shadow-sm" style="border-radius: 6px; font-weight: 500; background-color: #0084ff; border: none; font-size: 0.875rem; height: 38px; display: flex; align-items: center;">
                            <i class="fas fa-filter me-1"></i> Filter
                        </button>
                        <a href="{{ url()->current() }}" class="btn btn-secondary px-3 text-white shadow-sm" style="background-color: #6c757d; border-radius: 6px; border: none; font-weight: 500; font-size: 0.875rem; height: 38px; display: flex; align-items: center; text-decoration: none;">
                            <i class="fas fa-sync-alt me-1"></i> Reset
                        </a>
                    </div>
                </form>

                <div>
                    <a href="{{ url('/admin/fb/transaksi/create') }}" class="btn btn-purple px-4 w-100 shadow-sm text-decoration-none" style="border-radius: 6px; font-weight: 500; font-size: 0.875rem; height: 38px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-plus me-1"></i> Tambah Pesanan
                    </a>
                </div>

            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm" style="border-radius: 8px;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 text-center">
                    <thead class="bg-light text-dark fw-bold" style="font-size: 0.85rem;">
                        <tr>
                            <th class="py-3 border-0" style="width: 5%;">No</th>
                            <th class="border-0">ID POS/Struk</th>
                            <th class="border-0">Nama</th>
                            <th class="border-0">Waktu Pesan</th>
                            <th class="border-0">Sumber</th>
                            {{-- 🔹 DUA KOLOM STATUS YANG SUDAH DIPISAH 🔹 --}}
                            <th class="border-0">Status Pesanan</th>
                            <th class="border-0">Status Bayar</th>
                            <th class="border-0" style="width: 10%;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody style="font-size: 0.9rem; color: #374151;">
                        
                        @forelse($transaksiFb as $index => $item)
                        <tr>
                            <td>{{ $transaksiFb->firstItem() + $index }}</td>
                            <td class="fw-semibold text-primary">FNBPNC-{{ str_pad($item->id_pos, 3, '0', STR_PAD_LEFT) }}</td>
                            
                            <td class="fw-bold">
                                @php
                                    if (!empty($item->nama_pelanggan)) {
                                        $nama = $item->nama_pelanggan;
                                    } elseif ($item->id_pengguna) {
                                        $nama = \App\Models\User::where('id_pengguna', $item->id_pengguna)->value('nama_pengguna');
                                    } else {
                                        $nama = 'Pelanggan Umum';
                                    }
                                @endphp
                                {{ $nama }}
                            </td>
                            
                            <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i') }}</td>
                            
                            <td>
                                @if($item->sumber_pesanan === 'Online')
                                    <span class="badge text-white py-1 px-3" style="background-color: #0084ff; border-radius: 4px; font-weight: 600; font-size: 0.725rem;">
                                        <i class="fas fa-globe me-1"></i> ONLINE
                                    </span>
                                @else
                                    <span class="badge text-dark py-1 px-3 border" style="background-color: #f3f4f6; border-color: #d1d5db !important; border-radius: 4px; font-weight: 600; font-size: 0.725rem;">
                                        <i class="fas fa-desktop me-1 text-muted"></i> KASIR
                                    </span>
                                @endif
                            </td>
                            
                            <td>
                                @if($item->status_pesanan === 'Selesai')
                                    <span class="badge text-white py-1 px-3" style="background-color: #28a745; border-radius: 4px; font-weight: 600; font-size: 0.75rem;">SELESAI</span>
                                @elseif($item->status_pesanan === 'Dibatalkan')
                                    <span class="badge text-white py-1 px-3" style="background-color: #dc3545; border-radius: 4px; font-weight: 600; font-size: 0.75rem;">DIBATALKAN</span>
                                @else
                                    <span class="badge text-dark py-1 px-3" style="background-color: #ffc107; border-radius: 4px; font-weight: 600; font-size: 0.75rem;">MENUNGGU</span>
                                @endif
                            </td>

                            <td>
                                @if($item->status_pembayaran === 'lunas')
                                    <span class="badge bg-success py-1 px-3" style="border-radius: 4px; font-weight: 600; font-size: 0.725rem;">LUNAS</span>
                                @elseif($item->status_pembayaran === 'sudah-bayar')
                                    <span class="badge bg-info text-dark py-1 px-3 shadow-sm" style="border-radius: 4px; font-weight: 600; font-size: 0.725rem;"><i class="fas fa-bell me-1 text-danger"></i> CEK WA</span>
                                @elseif($item->status_pembayaran === 'kadaluarsa')
                                    <span class="badge bg-danger text-white py-1 px-3 shadow-sm" style="border-radius: 4px; font-weight: 600; font-size: 0.725rem;">KADALUARSA</span>
                                @else
                                    <span class="badge bg-secondary py-1 px-3" style="border-radius: 4px; font-weight: 600; font-size: 0.725rem;">BELUM BAYAR</span>
                                @endif
                            </td>

                            <td>
                                <div class="d-flex justify-content-center align-items-center" style="gap: 5px;">

                                    {{-- Tombol Cetak Struk --}}
                                 <button type="button" onclick="cetakStrukLangsung('{{ secure_url('admin/fb/transaksi/cetak-struk/' . $item->id_pos) }}')" class="btn btn-secondary btn-sm shadow-sm" style="border-radius: 4px; padding: 0.2rem 0.6rem;" title="Cetak Struk">
                                        <i class="fas fa-print"></i>
                                    </button>

                                    {{-- Tombol Mata memicu Modal --}}
                                    <button type="button" class="btn btn-primary btn-sm shadow-sm" style="border-radius: 4px; padding: 0.2rem 0.6rem;" 
                                        data-toggle="modal" data-target="#modalDetail{{ $item->id_pos }}"
                                        data-bs-toggle="modal" data-bs-target="#modalDetail{{ $item->id_pos }}" title="Detail Transaksi">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">Belum ada data transaksi F&B.</td>
                        </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>

            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center p-3 border-top bg-light">
                <span class="text-muted" style="font-size: 0.85rem;">
                    Menampilkan {{ $transaksiFb->firstItem() ?? 0 }} sampai {{ $transaksiFb->lastItem() ?? 0 }} dari {{ $transaksiFb->total() }} entri
                </span>
                <nav class="mt-2 mt-md-0">
                    {{ $transaksiFb->appends(request()->query())->links('pagination::bootstrap-4') }}
                </nav>
            </div>
            
        </div>
    </div>

</div>

@foreach($transaksiFb as $item)
    <div class="modal fade text-start" id="modalDetail{{ $item->id_pos }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content" style="border-radius: 8px; border: none;">
               <form action="{{ route('admin.fb.transaksi.update-status', $item->id_pos) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="modal-header border-bottom-0 pb-0 mt-2 mx-2">
                        <h5 class="modal-title fw-bold text-dark">Detail Transaksi POS</h5>
                        <button type="button" class="close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    
                    <div class="modal-body text-left px-4">
                        
                        {{-- INFORMASI TRANSAKSI --}}
                        <h6 class="fw-bold mb-3" style="font-size: 0.85rem; color: #4b5563;">INFORMASI TRANSAKSI</h6>
                        <div class="row mb-4" style="font-size: 0.9rem;">
                            <div class="col-md-6 mb-2">
                                <span class="text-muted d-block" style="font-size: 0.8rem;">ID POS</span>
                                <span class="fw-semibold text-dark">FNBPNC-{{ str_pad($item->id_pos, 3, '0', STR_PAD_LEFT) }}</span>
                            </div>
                            <div class="col-md-6 mb-2">
                                <span class="text-muted d-block" style="font-size: 0.8rem;">Tanggal Pesanan</span>
                                <span class="text-dark">{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i') }}</span>
                            </div>
                           <div class="col-md-6 mb-2">
                                <span class="text-muted d-block" style="font-size: 0.8rem;">Nama Pelanggan</span>
                                <span class="text-dark">
                                    @php
                                        if (!empty($item->nama_pelanggan)) {
                                            $namaModal = $item->nama_pelanggan;
                                        } elseif ($item->id_pengguna) {
                                            $namaModal = \App\Models\User::where('id_pengguna', $item->id_pengguna)->value('nama_pengguna');
                                        } else {
                                            $namaModal = 'Pelanggan Umum';
                                        }
                                    @endphp
                                    {{ $namaModal }}
                                </span>
                            </div>
                            <div class="col-md-6 mb-2">
                                <span class="text-muted d-block" style="font-size: 0.8rem;">Status Saat Ini</span>
                                <span class="badge {{ $item->status_pesanan === 'Selesai' ? 'bg-success' : 'bg-warning text-dark' }}">{{ strtoupper($item->status_pesanan) }}</span>
                                <span class="badge {{ $item->status_pembayaran === 'lunas' ? 'bg-success' : ($item->status_pembayaran === 'sudah-bayar' ? 'bg-info text-dark' : 'bg-secondary') }}">{{ strtoupper($item->status_pembayaran) }}</span>
                            </div>

                            <div class="col-md-12 mt-2">
                                <span class="text-muted d-block mb-1" style="font-size: 0.8rem;">Catatan Pesanan:</span>
                                @if($item->catatan)
                                    <div class="p-2 rounded shadow-sm" style="background-color: #fffbeb; border-left: 4px solid #f59e0b; color: #92400e;">
                                        <i class="fas fa-comment-dots me-1"></i> <em>"{{ $item->catatan }}"</em>
                                    </div>
                                @else
                                    <span class="text-dark" style="font-size: 0.85rem;"><em>- Tidak ada catatan -</em></span>
                                @endif
                            </div>

                        </div>

                        <h6 class="fw-bold mb-3" style="font-size: 0.85rem; color: #4b5563;">RINCIAN PESANAN</h6>
                        <div class="table-responsive mb-3">
                            <table class="table table-bordered table-sm text-center" style="font-size: 0.85rem;">
                                <thead class="bg-light">
                                    <tr>
                                        <th>No</th>
                                        <th class="text-start">Produk</th>
                                        <th>Harga</th>
                                        <th>Jumlah</th>
                                        <th class="text-end">Subtotal</th>
                                    </tr>
                                </thead>

                               <tbody>
                                    @php
                                        $rincian = \App\Models\TrPosDetail::where('id_pos', $item->id_pos)->get();
                                    @endphp
                                    
                                    @forelse($rincian as $idx => $detail)
                                        <tr>
                                            <td>{{ $idx + 1 }}</td>
                                            <td class="text-start">
                                                {{-- Ambil nama produk dari tabel MsProduk --}}
                                                {{ \App\Models\MsProduk::where('id_produk', $detail->id_produk)->value('nama_produk') ?? 'Produk Tidak Diketahui' }}
                                            </td>
                                            <td>Rp {{ number_format($detail->harga_satuan ?? 0, 0, ',', '.') }}</td>
                                            <td>{{ $detail->jumlah ?? 0 }}</td>
                                            <td class="text-end">Rp {{ number_format($detail->subtotal ?? 0, 0, ',', '.') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-muted py-3">
                                                Data rincian produk kosong.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>

                                <tfoot>
                                    <tr>
                                        <td colspan="4" class="text-end fw-bold">Total:</td>
                                        <td class="text-end fw-bold text-primary">Rp {{ number_format($item->total_pos, 0, ',', '.') }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <h6 class="fw-bold mb-2" style="font-size: 0.85rem; color: #4b5563;">EDIT STATUS</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted mb-1" style="font-size: 0.8rem;">Status Masakan</label>
                                <select name="status_pesanan" class="form-control shadow-none" style="font-size: 0.9rem;">
                                    <option value="Menunggu" {{ $item->status_pesanan === 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
                                    <option value="Selesai" {{ $item->status_pesanan === 'Selesai' ? 'selected' : '' }}>Selesai</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted mb-1" style="font-size: 0.8rem;">Status Pembayaran</label>
                                <select name="status_pembayaran" class="form-control shadow-none" style="font-size: 0.9rem;">
                                    <option value="belum-bayar" {{ $item->status_pembayaran === 'belum-bayar' ? 'selected' : '' }}>Belum Bayar</option>
                                    <option value="sudah-bayar" {{ $item->status_pembayaran === 'sudah-bayar' ? 'selected' : '' }}>Sudah Bayar (Cek WA)</option>
                                    <option value="lunas" {{ $item->status_pembayaran === 'lunas' ? 'selected' : '' }}>Lunas</option>
                                </select>
                            </div>
                        </div>

                    </div>
                    
                    <div class="modal-footer border-top-0 pt-0">
                        <button type="button" class="btn btn-light border shadow-sm" data-dismiss="modal" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-purple shadow-sm">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach

@stop

@push('css')
<style>
    .btn-purple {
        background-color: #5b21b6;
        color: #ffffff;
        font-weight: 500;
        border: none;
        transition: 0.2s;
    }
    .btn-purple:hover {
        background-color: #4c1d95;
        color: #ffffff;
    }
    
    .table td {
        border-bottom: 1px solid #f3f4f6;
        padding-top: 1rem;
        padding-bottom: 1rem;
    }
</style>
@endpush

@push('js')
<script>
    // 1. Pastikan nama fungsinya "cetakStrukLangsung" (L besar)
    function cetakStrukLangsung(url) {
        let iframe = document.getElementById('frameCetakStruk');
        if (!iframe) {
            // 2. Pastikan pakai tanda kutip ('iframe')
            iframe = document.createElement('iframe'); 
            iframe.id = 'frameCetakStruk';
            iframe.style.display = 'none';
            // 3. Kurang titik koma (;) di baris ini sebelumnya, sudah ditambahkan
            document.body.appendChild(iframe); 
        }

        iframe.src = url;

        iframe.onload = function() {
            setTimeout(function() {
                iframe.contentWindow.focus();
                iframe.contentWindow.print();
            }, 500);
        };
    }
</script>    

<script>
    function cetakStrukLangsung(url) {
        // Baris pamungkas: Paksa URL menjadi HTTPS apapun yang terjadi
        url = url.replace('http://', 'https://');

        let iframe = document.getElementById('frameCetakStruk');
        if (!iframe) {
            iframe = document.createElement('iframe'); 
            iframe.id = 'frameCetakStruk';
            iframe.style.display = 'none';
            document.body.appendChild(iframe); 
        }

        iframe.src = url;

        iframe.onload = function() {
            setTimeout(function() {
                iframe.contentWindow.focus();
                iframe.contentWindow.print();
            }, 500);
        };
    }
</script>
@endpush