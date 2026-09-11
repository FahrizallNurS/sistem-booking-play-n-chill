<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penjualan F&B - Play N Chill</title>
    <style>
        /* CSS KHUSUS DOMPDF */
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 12px;
            color: #334155;
            margin: 0;
            padding: 0;
        }
        
        /* HEADER */
        .header-container {
            text-align: center;
            margin-bottom: 20px;
        }
        .header-container img {
            max-width: 120px;
            margin-bottom: 10px;
        }
        .report-title {
            color: #5b21b6; /* Warna Ungu Play N Chill */
            font-size: 20px;
            font-weight: bold;
            margin: 0 0 5px 0;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .report-subtitle {
            color: #64748b;
            font-size: 12px;
            margin: 0;
        }
        .divider {
            border-bottom: 2px solid #5b21b6;
            margin: 15px 0;
        }

        /* META INFO */
        .meta-info {
            width: 100%;
            margin-bottom: 20px;
            font-size: 11px;
        }
        .meta-info td {
            padding: 3px 0;
        }
        .meta-info .label {
            font-weight: bold;
            width: 110px;
        }

        /* SUMMARY BOXES (Menggunakan tabel agar sejajar di PDF) */
        .summary-container {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: separate;
            border-spacing: 0 10px; /* Jarak antar kotak */
        }
        .summary-box {
            border: 1px solid #e2e8f0;
            background-color: #f8fafc;
            text-align: center;
            padding: 15px;
            border-radius: 4px;
        }
        .summary-value {
            font-size: 18px;
            font-weight: bold;
            color: #5b21b6;
            margin-bottom: 4px;
        }
        .summary-label {
            font-size: 11px;
            color: #64748b;
        }

        /* TABEL DATA */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .data-table th {
            background-color: #5b21b6;
            color: #ffffff;
            padding: 10px;
            font-size: 11px;
            text-align: left;
            border: 1px solid #5b21b6;
        }
        .data-table td {
            border: 1px solid #e2e8f0;
            border-bottom: 1px solid #cbd5e1;
            padding: 10px;
            font-size: 11px;
            vertical-align: middle;
        }
        
        /* STATUS BADGE */
        .status-badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
            color: #ffffff;
            text-align: center;
            display: inline-block;
        }
        .bg-blue { background-color: #3b82f6; }
        .bg-success { background-color: #10b981; }
        .bg-danger { background-color: #ef4444; }

        /* FOOTER TOTAL */
        .total-row {
            background-color: #fef08a; /* Warna kuning soft seperti di gambar */
        }
        .total-row td {
            font-weight: bold;
            font-size: 12px;
            color: #1e293b;
        }

        /* WATERMARK FOOTER BAWAH */
        .page-footer {
            margin-top: 40px;
            font-size: 10px;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
            padding-top: 10px;
        }
    </style>
</head>
<body>

    {{-- 1. BAGIAN HEADER --}}
    <div class="header-container">
        {{-- Menggunakan trik Base64 agar DOMPDF pasti bisa membaca gambarnya --}}
        <?php
            $imagePath = public_path('gambar/Logo-PNC01.png');
            $imageData = base64_encode(file_get_contents($imagePath));
            $src = 'data:image/png;base64,' . $imageData;
        ?>
        <img src="{{ $src }}" alt="Logo Play N Chill">
        
        <h1 class="report-title">LAPORAN PENJUALAN F&B</h1>

    {{-- 2. BAGIAN META INFORMASI --}}
    <table class="meta-info">
        <tr>
            <td class="label">Periode Laporan</td>
            <td>: {{ $periode_awal ?? 'Semua' }} - {{ $periode_akhir ?? 'Semua' }}</td>
        </tr>
        <tr>
            <td class="label">Tanggal Cetak</td>
            <td>: {{ \Carbon\Carbon::now()->format('d F Y H:i') }}</td>
        </tr>
        <tr>
            <td class="label">Dicetak Oleh</td>
            <td>: {{ auth()->user()->name ?? 'Administrator' }}</td>
        </tr>
    </table>

    {{-- 3. BAGIAN SUMMARY / RINGKASAN --}}
    <table class="summary-container">
        <tr>
            <td class="summary-box">
                <div class="summary-value">{{ $total_transaksi ?? 0 }}</div>
                <div class="summary-label">Total Pesanan F&B</div>
            </td>
        </tr>
        <tr>
            <td class="summary-box">
                <div class="summary-value">{{ $transaksi_selesai ?? 0 }}</div>
                <div class="summary-label">Pesanan Selesai</div>
            </td>
        </tr>
        <tr>
            <td class="summary-box">
                <div class="summary-value">{{ $transaksi_batal ?? 0 }}</div>
                <div class="summary-label">Pesanan Dibatalkan</div>
            </td>
        </tr>
        <tr>
            <td class="summary-box" style="border: 2px solid #5b21b6; background-color: #faf5ff;">
                <div class="summary-value">Rp {{ number_format($total_pendapatan ?? 0, 0, ',', '.') }}</div>
                <div class="summary-label">Total Pendapatan F&B</div>
            </td>
        </tr>
    </table>

    {{-- 4. BAGIAN TABEL DATA --}}
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 5%; text-align: center;">#</th>
                <th style="width: 20%;">Kode Pesanan</th>
                <th style="width: 20%;">Pelanggan</th>
                <th style="width: 20%;">Waktu Pesan</th>
                <th style="width: 15%;">Sumber</th>
                <th style="width: 15%;">Total Harga</th>
                <th style="width: 10%; text-align: center;">Status</th>
            </tr>
        </thead>
       <tbody>
            {{-- Asumsi variabel yang dilempar dari Controller adalah $data_fb --}}
            @forelse($data_fb as $index => $item)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    
                    {{-- Menyesuaikan dengan nama kolom POS --}}
                    <td>{{ $item->kode_pos ?? $item->kode_transaksi ?? $item->kode_pesanan ?? '-' }}</td>
                    
                    {{-- Mengambil nama dari relasi pengguna, atau fallback ke 'Walk-in' --}}
                    <td>{{ optional($item->pengguna)->nama_pengguna ?? optional($item->pengguna)->name ?? $item->nama_pelanggan ?? 'Walk-in' }}</td>
                    
                    <td>
                        {{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y') }}<br>
                        <span style="color: #64748b;">{{ \Carbon\Carbon::parse($item->created_at)->format('H:i') }} WIB</span>
                    </td>
                    
                    <td>{{ $item->sumber_pesanan ?? 'Kasir POS' }}</td>
                    
                    {{-- Menggunakan total_pos sesuai controller --}}
                    <td>Rp {{ number_format($item->total_pos ?? 0, 0, ',', '.') }}</td>
                    
                    <td style="text-align: center;">
                        {{-- Menggunakan status_pesanan sesuai controller --}}
                        @if(strtolower($item->status_pesanan) == 'selesai')
                            <span class="status-badge bg-success">Selesai</span>
                        @else
                            <span class="status-badge bg-danger">{{ ucfirst($item->status_pesanan ?? 'Batal') }}</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; color: #64748b;">Tidak ada data transaksi F&B pada periode ini.</td>
                </tr>
            @endforelse
            
            {{-- BARIS TOTAL --}}
            @if(count($data_fb ?? []) > 0)
            <tr class="total-row">
                <td colspan="5" style="text-align: right;">Total Pendapatan (Selesai):</td>
                <td colspan="2">Rp {{ number_format($total_pendapatan ?? 0, 0, ',', '.') }}</td>
            </tr>
            @endif
        </tbody>
    </table>

    {{-- 5. FOOTER DOKUMEN --}}
    <div class="page-footer">
        Dokumen ini dicetak secara otomatis oleh sistem Play N Chill Madiun.<br>
        &copy; {{ date('Y') }} Play N Chill. All rights reserved.
    </div>

</body>
</html>