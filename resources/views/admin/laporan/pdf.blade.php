<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Booking Play N Chill</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Arial', sans-serif;
            font-size: 11px;
            color: #333;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #6f42c1;
            padding-bottom: 15px;
        }
        .header img {
            width: 80px;
            height: auto;
            margin-bottom: 10px;
        }
        .header h1 {
            color: #6f42c1;
            font-size: 20px;
            margin-bottom: 5px;
        }
        .header p {
            font-size: 12px;
            color: #666;
        }
        .info-section {
            margin-bottom: 20px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }
        .info-label {
            font-weight: bold;
            width: 150px;
        }
        .summary-box {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            gap: 10px;
        }
        .summary-item {
            flex: 1;
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            padding: 12px;
            text-align: center;
            border-radius: 4px;
        }
        .summary-item h3 {
            font-size: 18px;
            color: #6f42c1;
            margin-bottom: 5px;
        }
        .summary-item p {
            font-size: 10px;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th {
            background-color: #6f42c1;
            color: white;
            padding: 8px;
            text-align: left;
            font-size: 10px;
        }
        td {
            padding: 6px 8px;
            border-bottom: 1px solid #dee2e6;
            font-size: 10px;
        }
        tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        .badge {
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
            color: white;
        }
        .badge-success { background-color: #28a745; }
        .badge-primary { background-color: #007bff; }
        .badge-danger { background-color: #dc3545; }
        .badge-warning { background-color: #ffc107; color: #000; }
        .badge-info { background-color: #17a2b8; }
        .badge-secondary { background-color: #6c757d; }
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 2px solid #dee2e6;
            font-size: 10px;
            color: #666;
        }
        .text-right {
            text-align: right;
        }
        .fw-bold {
            font-weight: bold;
        }
        .total-row {
            background-color: #fff3cd;
            font-weight: bold;
        }
    </style>
</head>
<body>

    {{-- Header --}}
    <div class="header">
        <img src="{{ public_path('gambar/PNCLOGO.jpg') }}" alt="Play N Chill Logo">
        <h1>LAPORAN BOOKING</h1>
        <p>Play N Chill - Gaming & Entertainment Center</p>
    </div>

    {{-- Info Laporan --}}
    <div class="info-section">
        <div class="info-row">
            <span class="info-label">Periode Laporan:</span>
            <span>
                {{ $start->translatedFormat('d F Y') }}
                @if($start->format('Y-m-d') !== $end->format('Y-m-d'))
                    s/d {{ $end->translatedFormat('d F Y') }}
                @endif
            </span>
        </div>
        <div class="info-row">
            <span class="info-label">Tanggal Cetak:</span>
            <span>{{ $tanggalCetak }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Dicetak Oleh:</span>
            <span>{{ $admin }}</span>
        </div>
    </div>

    {{-- Summary --}}
    <div class="summary-box">
        <div class="summary-item">
            <h3>{{ $totalBooking }}</h3>
            <p>Total Booking</p>
        </div>
        <div class="summary-item">
            <h3>{{ $totalSelesai }}</h3>
            <p>Booking Selesai</p>
        </div>
        <div class="summary-item">
            <h3>{{ $totalDibatalkan }}</h3>
            <p>Booking Dibatalkan</p>
        </div>
        <div class="summary-item">
            <h3>Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h3>
            <p>Total Pendapatan</p>
        </div>
    </div>

    {{-- Tabel Transaksi --}}
    <table>
        <thead>
            <tr>
                <th style="width: 3%;">#</th>
                <th style="width: 12%;">Kode Booking</th>
                <th style="width: 15%;">Pelanggan</th>
                <th style="width: 10%;">Ruangan</th>
                <th style="width: 12%;">Paket</th>
                <th style="width: 15%;">Waktu Main</th>
                <th style="width: 8%;">Jenis Bayar</th>
                <th style="width: 12%;">Total Harga</th>
                <th style="width: 8%;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transaksis as $i => $t)
            @php
                $ph = $t->penetapanHarga;
                $badgeSewa = match($t->status_sewa) {
                    'dikonfirmasi' => 'success',
                    'dibatalkan'   => 'danger',
                    'selesai'      => 'primary',
                    default        => 'secondary',
                };
                $badgeBayar = match($t->status_pembayaran) {
                    'lunas'    => 'success',
                    'dp'       => 'info',
                    default    => 'warning',
                };
            @endphp
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $t->kode_sewa }}</td>
                <td>{{ $t->pengguna->nama_pengguna ?? '-' }}</td>
                <td>{{ $ph->ruangan->nama_ruangan ?? '-' }}</td>
                <td>{{ $ph->paket->nama_paket ?? '-' }}</td>
                <td>
                    {{ \Carbon\Carbon::parse($t->waktu_mulai)->format('d/m/Y H:i') }}
                    — {{ \Carbon\Carbon::parse($t->waktu_selesai)->format('H:i') }}
                </td>
                <td>
                    <span class="badge badge-{{ $t->opsi_pembayaran === 'full' ? 'primary' : 'info' }}">
                        {{ $t->opsi_pembayaran === 'full' ? 'Full' : 'DP' }}
                    </span>
                </td>
                <td>Rp {{ number_format($t->total_harga, 0, ',', '.') }}</td>
                <td>
                    <span class="badge badge-{{ $badgeSewa }}">{{ ucfirst($t->status_sewa) }}</span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" style="text-align: center; padding: 20px; color: #999;">
                    Tidak ada data untuk periode ini.
                </td>
            </tr>
            @endforelse
        </tbody>
        @if($transaksis->isNotEmpty())
        <tfoot>
            <tr class="total-row">
                <td colspan="7" class="text-right fw-bold">Total Pendapatan (Selesai):</td>
                <td colspan="2" class="fw-bold">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
        @endif
    </table>

    {{-- Footer --}}
    <div class="footer">
        <p>Dokumen ini dicetak secara otomatis oleh sistem Play N Chill.</p>
        <p>© {{ now()->year }} Play N Chill. All rights reserved.</p>
    </div>

</body>
</html>