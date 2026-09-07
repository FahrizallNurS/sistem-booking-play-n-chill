@php
    $jenis = request('jenis_transaksi', 'semua');
@endphp

@forelse($transaksis as $i => $t)
    @php
        $modalId = $t->jenis_laporan === 'Booking'
            ? 'modalBooking' . $t->id_transaksi
            : 'modalFNB' . $t->id_pos;
    @endphp
    <tr>
        <td class="align-middle py-3">{{ $transaksis instanceof \Illuminate\Pagination\LengthAwarePaginator ? (($transaksis->currentPage() - 1) * $transaksis->perPage() + $i + 1) : $i + 1 }}</td>

        @if($jenis === 'booking')
            <td class="align-middle py-3" style="color: #e83e8c;">{{ $t->kode_transaksi }}</td>
        @elseif($jenis === 'fnb')
            <td class="align-middle py-3" style="color: #e83e8c;">{{ $t->kode_transaksi }}</td>
        @else
            <td class="align-middle py-3 font-weight-bold">{{ $t->jenis_laporan }}</td>
        @endif

        <td class="align-middle py-3">{{ $t->pengguna->nama_pengguna ?? '-' }}</td>
        <td class="align-middle py-3">{{ $t->kasir ?? '-' }}</td>
        <td class="align-middle py-3">{{ $t->metode_pembayaran ?? 'Cash' }}</td>
        <td class="align-middle py-3 font-weight-bold text-dark">
            Rp {{ number_format($t->total_harga, 0, ',', '.') }}
        </td>
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
        <td colspan="9" class="text-center text-muted py-4">Tidak ada data untuk filter ini.</td>
    </tr>
@endforelse