<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Superadmin - Play N Chill</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Arial', sans-serif;
            font-size: 10px;
            color: #333;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 3px solid #6f42c1;
            padding-bottom: 12px;
        }
        .header img {
            width: 70px;
            height: auto;
            margin-bottom: 8px;
        }
        .header h1 {
            color: #6f42c1;
            font-size: 18px;
            margin-bottom: 3px;
        }
        .header p {
            font-size: 11px;
            color: #666;
        }
        .info-section {
            margin-bottom: 15px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 4px;
        }
        .info-label {
            font-weight: bold;
            width: 140px;
        }
        .summary-box {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            gap: 8px;
        }
        .summary-item {
            flex: 1;
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            padding: 10px;
            text-align: center;
            border-radius: 3px;
        }
        .summary-item h3 {
            font-size: 16px;
            color: #6f42c1;
            margin-bottom: 4px;
        }
        .summary-item p {
            font-size: 9px;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        th {
            background-color: #6f42c1;
            color: white;
            padding: 7px;
            text-align: left;
            font-size: 9px;
        }
        td {
            padding: 5px 7px;
            border-bottom: 1px solid #dee2e6;
            font-size: 9px;
        }
        tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        .badge {
            padding: 2px 6px;
            border-radius: 2px;
            font-size: 8px;
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
            margin-top: 25px;
            padding-top: 12px;
            border-top: 2px solid #dee2e6;
            font-size: 9px;
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
        <h1>LAPORAN TRANSAKSI - SUPERADMIN</h1>
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
            <span>{{ $admin }} (Superadmin)</span>
        </div>
    </div>

    {{-- Summary - DENGAN TAMBAHAN --}}
    <div class="summary-box">
        <div class="summary-item">
            @if($jenisTransaksi === 'fnb')
                <h3>{{ $totalFnb }}</h3>
                <p>Total F&amp;B</p>
            @else
                <h3>{{ $totalBooking }}</h3>
                <p>Total Booking</p>
            @endif
        </div>

        <div class="summary-item">
            @if($jenisTransaksi === 'booking')
                <h3>{{ $bookingSelesai }}</h3>
                <p>Booking Selesai</p>
            @elseif($jenisTransaksi === 'fnb')
                <h3>{{ $fnbSelesai }}</h3>
                <p>F&amp;B Selesai</p>
            @else
                <h3>{{ $totalFnb }}</h3>
                <p>Total F&amp;B</p>
            @endif
        </div>

        <div class="summary-item">
            @if($jenisTransaksi === 'booking')
                <h3>{{ $bookingDibatalkan }}</h3>
                <p>Booking Dibatalkan</p>
            @elseif($jenisTransaksi === 'fnb')
                <h3>{{ $fnbDibatalkan }}</h3>
                <p>F&amp;B Dibatalkan</p>
            @else
                <h3>{{ $bookingDibatalkan + $fnbDibatalkan }}</h3>
                <p>Total Dibatalkan</p>
            @endif
        </div>

        <div class="summary-item">
            @if($jenisTransaksi === 'booking')
                <h3 style="font-size: 13px;">Rp {{ number_format($pendapatanBooking, 0, ',', '.') }}</h3>
                <p>Pendapatan Booking</p>
            @elseif($jenisTransaksi === 'fnb')
                <h3 style="font-size: 13px;">Rp {{ number_format($pendapatanFnb, 0, ',', '.') }}</h3>
                <p>Pendapatan F&amp;B</p>
            @else
                <h3 style="font-size: 13px;">Rp {{ number_format($pendapatanBooking + $pendapatanFnb, 0, ',', '.') }}</h3>
                <p>Total Pendapatan</p>
            @endif
        </div>
    </div>

    {{-- Tabel Transaksi --}}
    <table>
        <thead>
            <tr>
                <th style="width: 3%;">#</th>
                <th style="width: 10%;">Kode/Jenis</th>
                <th style="width: 13%;">Pelanggan</th>
                <th style="width: 10%;">Kasir</th>
                <th style="width: 16%;">Produk</th>
                <th style="width: 8%;">Jumlah</th>
                <th style="width: 9%;">Metode</th>
                <th style="width: 12%;">Total</th>
                <th style="width: 9%;">Sumber</th>
                <th style="width: 10%;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rows as $i => $r)
            @php
                $badgeStatus = match($r->status) {
                    'selesai'    => 'primary',
                    'dibatalkan' => 'danger',
                    default      => 'secondary',
                };
            @endphp
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>
                    {{ $jenisTransaksi === 'semua' ? $r->jenis_laporan : $r->kode_transaksi }}
                </td>
                <td>{{ $r->pelanggan }}</td>
                <td>{{ $r->kasir }}</td>
                <td>{{ $r->produk }}</td>
                <td>{{ $r->jumlah }}</td>
                <td>{{ $r->metode }}</td>
                <td>Rp {{ number_format($r->total, 0, ',', '.') }}</td>
                <td>{{ $r->sumber }}</td>
                <td><span class="badge badge-{{ $badgeStatus }}">{{ ucfirst($r->status) }}</span></td>
            </tr>
            @empty
            <tr>
                <td colspan="10" style="text-align: center; padding: 18px; color: #999;">
                    Tidak ada data untuk periode ini.
                </td>
            </tr>
            @endforelse
        </tbody>
        @if(count($rows) > 0)
        <tfoot>
            <tr class="total-row">
                <td colspan="7" class="text-right fw-bold">Total Pendapatan (Selesai):</td>
                <td colspan="3" class="fw-bold">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</td>
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