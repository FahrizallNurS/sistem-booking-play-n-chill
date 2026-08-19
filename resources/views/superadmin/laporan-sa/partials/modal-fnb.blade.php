{{-- Modal khusus F&B. Menerima $t (instance TrPos) dari @include(..., ['t' => $t]) --}}
<div class="modal fade" id="modalFNB{{ $t->id_pos }}" tabindex="-1" role="dialog" aria-hidden="true" style="z-index: 1060;">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius: 8px;">

            <div class="modal-header border-bottom px-4 pt-4 pb-3">
                <h5 class="modal-title font-weight-bold" style="color: #2d3748;">Detail Transaksi POS</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body px-4 pt-4 pb-4">

                {{-- Bagian 1: INFORMASI TRANSAKSI --}}
                <h6 class="font-weight-bold mb-3" style="color: #4a5568; letter-spacing: 0.5px;">INFORMASI TRANSAKSI</h6>
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <small class="text-muted d-block mb-1">ID POS</small>
                            <span class="text-dark">{{ $t->kode_pos ?? '-' }}</span>
                        </div>
                        <div class="mb-3">
                            <small class="text-muted d-block mb-1">Nama Pelanggan</small>
                            <span class="text-dark">{{ $t->pengguna->nama_pengguna ?? '-' }}</span>
                        </div>
                        <div class="mb-0">
                            <small class="text-muted d-block mb-1">Tanggal Pesanan</small>
                            <span class="text-dark">{{ $t->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <small class="text-muted d-block mb-1">Kode Booking</small>
                            <span style="color: #e83e8c;">{{ $t->transaksi->kode_sewa ?? 'Mandiri (tanpa booking)' }}</span>
                        </div>
                        <div class="mb-3">
                            <small class="text-muted d-block mb-1">Ruangan</small>
                            <span class="text-dark">{{ $t->transaksi->penetapanHarga->ruangan->nama_ruangan ?? '-' }}</span>
                        </div>
                        <div class="mb-0">
                            <small class="text-muted d-block mb-1">Status Pesanan</small>
                            <x-status-badge :status="$t->status_pesanan" />
                        </div>
                    </div>
                </div>

                {{-- Bagian 2: RINCIAN PESANAN --}}
                <h6 class="font-weight-bold mb-3 mt-4" style="color: #4a5568; letter-spacing: 0.5px;">RINCIAN PESANAN</h6>
                <div class="table-responsive border rounded mb-3">
                    <table class="table table-borderless table-sm mb-0">
                        <thead style="background-color: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                            <tr>
                                <th class="py-2 pl-3 text-dark font-weight-bold" width="5%">No</th>
                                <th class="py-2 text-dark font-weight-bold">Produk</th>
                                <th class="py-2 text-dark font-weight-bold" width="20%">Harga</th>
                                <th class="py-2 text-dark font-weight-bold" width="15%">Jumlah</th>
                                <th class="py-2 pr-3 text-dark font-weight-bold text-right" width="25%">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($t->details as $idx => $item)
                                <tr style="border-bottom: 1px solid #e2e8f0;">
                                    <td class="py-2 pl-3 text-muted">{{ $idx + 1 }}</td>
                                    <td class="py-2 text-muted">{{ $item->produk->nama_produk ?? 'Produk dihapus' }}</td>
                                    <td class="py-2 text-muted">Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
                                    <td class="py-2 text-muted">{{ $item->jumlah }}</td>
                                    <td class="py-2 pr-3 text-muted text-right">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-3">Tidak ada item.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Kalkulasi Total --}}
                <div class="d-flex justify-content-end mb-4">
                    <table class="table-borderless table-sm text-right" style="width: 250px;">
                        <tr>
                            <td class="text-muted pb-1">Subtotal:</td>
                            <td class="text-dark pb-1">Rp {{ number_format($t->total_pos, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold text-dark pt-1" style="font-size: 1.1rem;">Total:</td>
                            <td class="font-weight-bold pt-1" style="color: #6f42c1; font-size: 1.2rem;">Rp {{ number_format($t->total_pos, 0, ',', '.') }}</td>
                        </tr>
                    </table>
                </div>

                {{-- Footer Pengelola --}}
                <div class="border rounded text-center py-2 mx-auto" style="width: 60%; background-color: #fff; font-weight: bold; color: #4a5568;">
                    Di kelola oleh : {{ $t->admin->nama_pengguna ?? 'Sistem (Online)' }}
                </div>

            </div>
        </div>
    </div>
</div>