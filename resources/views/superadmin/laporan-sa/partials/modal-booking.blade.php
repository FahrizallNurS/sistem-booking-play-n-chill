{{-- Modal khusus Booking. Menerima $t dari @include(..., ['t' => $t]) --}}
<div class="modal fade" id="modalBooking{{ $t->id_transaksi }}" tabindex="-1" role="dialog" aria-hidden="true" style="z-index: 1060;">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius: 8px;">

            <div class="modal-header border-bottom-0 pb-0 pt-3 px-4">
                <h5 class="modal-title" style="color: #4a5568; font-weight: 500;">Informasi Booking</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body pt-4 px-4 pb-4">
                <div class="row">

                    {{-- KOLOM KIRI: Informasi Pelanggan & Catatan --}}
                    <div class="col-md-5 border-right pr-4">
                        <h6 class="mb-3" style="color: #718096; font-size: 1.1rem; font-weight: 500;">Informasi Pelanggan</h6>
                        <table class="table table-sm table-borderless mb-4" style="font-size: 14.5px;">
                            <tr>
                                <th width="35%" class="pl-0 text-dark font-weight-bold">Nama</th>
                                <td class="text-secondary">{{ $t->pengguna->nama_pengguna ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th class="pl-0 text-dark font-weight-bold">Email</th>
                                <td class="text-secondary">{{ $t->pengguna->email ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th class="pl-0 text-dark font-weight-bold">No. HP</th>
                                <td class="text-secondary">{{ $t->pengguna->no_hp ?? '-' }}</td>
                            </tr>
                        </table>

                        <h6 class="mb-3" style="color: #718096; font-size: 1.1rem; font-weight: 500;">Catatan</h6>
                        <div class="border rounded" style="border-color: #cbd5e1;">
                            <div class="p-3 text-dark" style="min-height: 100px; font-size: 14px;">
                                {{ $t->catatan ?? 'Tidak ada catatan khusus.' }}
                            </div>
                            <div class="border-top text-center p-2" style="background-color: #f8fafc; font-weight: 600; color: #475569; font-size: 14px;">
                                Di kelola oleh : {{ $t->kasir ?? 'Admin 01' }}
                            </div>
                        </div>
                    </div>

                    {{-- KOLOM KANAN: Detail Booking --}}
                    <div class="col-md-7 pl-4">
                        <h6 class="mb-3" style="color: #718096; font-size: 1.1rem; font-weight: 500;">Detail Booking</h6>
                        <table class="table table-sm table-borderless" style="font-size: 14.5px;">
                            <tr>
                                <th width="35%" class="pl-0 text-dark font-weight-bold">Kode Sewa</th>
                                <td><span style="color: #e83e8c; font-weight: 500;">{{ $t->kode_sewa }}</span></td>
                            </tr>
                            <tr>
                                <th class="pl-0 text-dark font-weight-bold">Ruangan</th>
                                <td class="text-secondary">{{ $t->penetapanHarga->ruangan->nama_ruangan ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th class="pl-0 text-dark font-weight-bold">Kategori</th>
                                <td class="text-secondary">{{ $t->penetapanHarga->ruangan->kategori ?? 'REGULAR' }}</td>
                            </tr>
                            <tr>
                                <th class="pl-0 text-dark font-weight-bold">Paket</th>
                                <td class="text-secondary">{{ $t->penetapanHarga->paket->nama_paket ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th class="pl-0 text-dark font-weight-bold">Waktu Mulai</th>
                                <td class="text-secondary">{{ \Carbon\Carbon::parse($t->waktu_mulai)->format('d/m/Y H:i') }}</td>
                            </tr>
                            <tr>
                                <th class="pl-0 text-dark font-weight-bold">Waktu Selesai</th>
                                <td class="text-secondary">{{ \Carbon\Carbon::parse($t->waktu_selesai)->format('d/m/Y H:i') }}</td>
                            </tr>
                            <tr>
                                <th class="pl-0 text-dark font-weight-bold">Durasi</th>
                                <td class="text-secondary">
                                    @php
                                        $mulai   = \Carbon\Carbon::parse($t->waktu_mulai);
                                        $selesai = \Carbon\Carbon::parse($t->waktu_selesai);
                                        $durasi  = $mulai->diffInHours($selesai);
                                    @endphp
                                    {{ $durasi > 0 ? $durasi . ' Jam' : '-' }}
                                </td>
                            </tr>
                            <tr>
                                <th class="pl-0 text-dark font-weight-bold">Tipe Hari</th>
                                <td class="text-secondary">{{ $t->penetapanHarga->tipe_hari ?? 'Harian' }}</td>
                            </tr>
                            <tr>
                                <th class="pl-0 text-dark font-weight-bold">Total Harga</th>
                                <td class="text-secondary">Rp {{ number_format($t->total_harga, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <th class="pl-0 text-dark font-weight-bold">Metode Pembayaran</th>
                                <td class="text-secondary">{{ $t->metode_pembayaran ?? 'Cash' }}</td>
                            </tr>
                            <tr>
                                <th class="pl-0 text-dark font-weight-bold align-middle pt-3">Status Sewa</th>
                                <td class="pt-3"><x-status-badge :status="$t->status_sewa" /></td>
                            </tr>
                            <tr>
                                <th class="pl-0 text-dark font-weight-bold align-middle pt-2">Status Bayar</th>
                                <td class="pt-2"><x-status-badge :status="$t->status_pembayaran" /></td>
                            </tr>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>