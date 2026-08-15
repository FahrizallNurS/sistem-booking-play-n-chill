{{--
    Area Tabel.
    $jenis dipakai untuk menentukan judul card, header kolom ke-2, dan isi kolom ke-2.
    Tombol Excel & PDF sekarang tampil di ketiga kondisi (sebelumnya Excel sempat
    hilang khusus saat filter F&B — sudah tidak ada cabang terpisah untuk itu lagi).
--}}
@php
    $jenis = request('jenis_transaksi', 'semua');
@endphp

<div class="card">
    <div class="card-header d-flex align-items-center">
        <h3 class="card-title font-weight-bold mb-0">
            @if($jenis === 'booking')
                Tabel Booking
            @elseif($jenis === 'fnb')
                Tabel F&amp;B
            @else
                Daftar Transaksi
            @endif
        </h3>
        <div class="card-tools ml-auto">
            <button class="btn btn-default btn-sm mr-1"><i class="fas fa-file-excel"></i> EXCEL</button>
            <a href="{{ route('superadmin.laporan.export-pdf', request()->all()) }}" class="btn btn-default btn-sm" target="_blank">
                <i class="fas fa-file-pdf"></i> PDF
            </a>
        </div>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 text-center align-middle" style="font-size: 14px;">
                <thead style="background-color: #f4f0fb; color: #495057;">
                    <tr>
                        <th class="border-top-0 border-bottom-0 py-3">NO.</th>

                        @if($jenis === 'booking')
                            <th class="border-top-0 border-bottom-0 py-3">KODE BOOKING</th>
                        @elseif($jenis === 'fnb')
                            <th class="border-top-0 border-bottom-0 py-3">KODE POS</th>
                        @else
                            <th class="border-top-0 border-bottom-0 py-3">JENIS TRANSAKSI</th>
                        @endif

                        <th class="border-top-0 border-bottom-0 py-3">PELANGGAN</th>
                        <th class="border-top-0 border-bottom-0 py-3">KASIR</th>
                        <th class="border-top-0 border-bottom-0 py-3">METODE</th>
                        <th class="border-top-0 border-bottom-0 py-3">TOTAL</th>
                        <th class="border-top-0 border-bottom-0 py-3">SUMBER</th> {{-- Header Baru --}}
                        <th class="border-top-0 border-bottom-0 py-3">STATUS TRANSAKSI</th>
                        <th class="border-top-0 border-bottom-0 py-3">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transaksis as $i => $t)
                        @php
                            $modalId = $t->jenis_laporan === 'Booking'
                                ? 'modalBooking' . $t->id_transaksi
                                : 'modalFNB' . $t->id_pos;
                        @endphp
                        <tr>
                            <td class="align-middle py-3">{{ $i + 1 }}</td>

                            @if($jenis === 'booking')
                                <td class="align-middle py-3" style="color: #e83e8c;">{{ $t->kode_sewa }}</td>
                            @elseif($jenis === 'fnb')
                                <td class="align-middle py-3" style="color: #e83e8c;">{{ $t->id_pos }}</td>
                            @else
                                <td class="align-middle py-3 font-weight-bold">{{ $t->jenis_laporan }}</td>
                            @endif

                            <td class="align-middle py-3">{{ $t->pengguna->nama_pengguna ?? '-' }}</td>
                            <td class="align-middle py-3">{{ $t->kasir ?? 'Admin 01' }}</td>
                            <td class="align-middle py-3">{{ $t->metode_pembayaran ?? 'Cash' }}</td>
                            <td class="align-middle py-3 font-weight-bold text-dark">
                                Rp {{ number_format($t->total_harga, 0, ',', '.') }}
                            </td>
                            
                            {{-- Kolom Tampilan Badge Sumber Transaksi (Baru) --}}
                            <td class="align-middle py-3">
                                @if(($t->sumber_booking ?? 'Kasir') === 'Online')
                                    <span class="badge text-white py-1 px-2" style="background-color: #0084ff; border-radius: 4px; font-weight: 600; font-size: 0.75rem;">
                                        <i class="fas fa-globe me-1"></i> ONLINE
                                    </span>
                                @else
                                    <span class="badge text-dark py-1 px-2 border" style="background-color: #f3f4f6; border-color: #d1d5db !important; border-radius: 4px; font-weight: 600; font-size: 0.75rem;">
                                        <i class="fas fa-desktop me-1 text-muted"></i> KASIR
                                    </span>
                                @endif
                            </td>

                            <td class="align-middle py-3">
                                <x-status-badge :status="$t->status_sewa" />
                            </td>
                            <td class="align-middle py-3">
                                <button
                                    type="button"
                                    class="btn btn-link btn-sm font-weight-bold p-0"
                                    style="color: #6f42c1; text-decoration: none;"
                                    data-toggle="modal"
                                    data-target="#{{ $modalId }}"
                                >
                                    Detail <i class="fas fa-eye ml-1"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            {{-- Colspan diubah menjadi 9 karena penambahan kolom SUMBER --}}
                            <td colspan="9" class="text-center text-muted py-4">Tidak ada data untuk filter ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>