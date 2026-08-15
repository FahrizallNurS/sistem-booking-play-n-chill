<div class="table-responsive">
    <table class="table table-bordered table-hover mb-0">
        <thead class="thead-light">
            <tr>
                <th>NO</th>

                @if($jenisTransaksi === 'booking')
                    <th>Kode Booking</th>
                    <th>Pelanggan</th>
                    <th>Ruangan</th>
                    <th>Paket</th>
                    <th>Waktu Main</th>
                @elseif($jenisTransaksi === 'fnb')
                    <th>Kode POS</th>
                    <th>Pelanggan</th>
                    <th>Terkait Booking</th>
                    <th>Waktu Pesan</th>
                @else
                    <th>Jenis</th>
                    <th>Kode</th>
                    <th>Pelanggan</th>
                    <th>Waktu</th>
                @endif

                <th>Sumber</th>
                <th>Total</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transaksis as $i => $t)
                @php
                    $isBooking = $t->jenis_laporan === 'Booking';
                    $modalId   = $isBooking ? 'modalBooking' . $t->id_transaksi : 'modalFNB' . $t->id_pos;
                @endphp
                <tr>
                    <td>{{ $i + 1 }}</td>

                    @if($jenisTransaksi === 'booking')
                        <td><code>{{ $t->kode_sewa }}</code></td>
                        <td>{{ $t->pengguna->nama_pengguna ?? '-' }}</td>
                        <td>{{ $t->penetapanHarga->ruangan->nama_ruangan ?? '-' }}</td>
                        <td>{{ $t->penetapanHarga->paket->nama_paket ?? '-' }}</td>
                        <td>{{ \Carbon\Carbon::parse($t->waktu_mulai)->format('d/m/Y H:i') }}</td>
                    @elseif($jenisTransaksi === 'fnb')
                        <td><code>{{ $t->kode_pos }}</code></td>
                        <td>{{ $t->pengguna->nama_pengguna ?? '-' }}</td>
                        <td>{{ $t->transaksi->kode_sewa ?? '-' }}</td>
                        <td>{{ $t->created_at->format('d/m/Y H:i') }}</td>
                    @else
                        <td>
                            <span class="badge badge-{{ $isBooking ? 'primary' : 'secondary' }}">
                                {{ $t->jenis_laporan }}
                            </span>
                        </td>
                        <td><code>{{ $isBooking ? $t->kode_sewa : $t->kode_pos }}</code></td>
                        <td>{{ $t->pengguna->nama_pengguna ?? '-' }}</td>
                        <td>{{ $isBooking ? \Carbon\Carbon::parse($t->waktu_mulai)->format('d/m/Y H:i') : $t->created_at->format('d/m/Y H:i') }}</td>
                    @endif

                    <td>
                        @php $sumber = $isBooking ? ($t->sumber_booking ?? 'Kasir') : ($t->sumber_pesanan ?? 'Kasir'); @endphp
                        @if($sumber === 'Online')
                            <span class="badge text-white" style="background-color:#0084ff;"><i class="fas fa-globe"></i> Online</span>
                        @else
                            <span class="badge text-dark border" style="background-color:#f3f4f6;"><i class="fas fa-desktop"></i> Kasir</span>
                        @endif
                    </td>

                    <td class="font-weight-bold">
                        Rp {{ number_format($isBooking ? $t->total_harga : $t->total_pos, 0, ',', '.') }}
                    </td>

                    <td>
                        <x-status-badge :status="$isBooking ? $t->status_sewa : $t->status_pesanan" />
                    </td>

                    <td>
                        <button type="button" class="btn btn-link btn-sm font-weight-bold p-0"
                            data-toggle="modal" data-target="#{{ $modalId }}">
                            Detail <i class="fas fa-eye ml-1"></i>
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center text-muted py-4">Tidak ada data untuk filter ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Modal per baris --}}
@foreach($transaksis as $t)
    @if($t->jenis_laporan === 'Booking')
        @include('admin.laporan.partials.modal-booking', ['t' => $t])
    @else
        @include('admin.laporan.partials.modal-fnb', ['t' => $t])
    @endif
@endforeach