<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        /* Margin kertas diminimalkan biar nggak buang ruang */
        @page { 
    size: 46mm 200mm;   /* BARU — 46mm = 42mm konten + 2mm margin kiri-kanan, masih di bawah 48mm area cetak fisik. Tinggi dibuat longgar (200mm) karena driver zj-58 kamu udah dioptimasi buat skip baris kosong, jadi nggak buang kertas percuma */
    margin: 2mm; 
}
body {
    font-family: 'Courier New', monospace;
    font-size: 11px;
    width: 42mm;
    margin: 0 auto;   /* diubah dari margin: 0 — jaring pengaman, biar konten selalu ke-center apa pun lebar halaman yang akhirnya kepakai */
    padding: 0;
    line-height: 1.2;
    color: #000;
}
        .center { text-align: center; }
        .left { text-align: left; }
        .right { text-align: right; white-space: nowrap; }
        .bold { font-weight: bold; }

        /* Helper class untuk spacing baru */
        .mt-10 { margin-top: 10px; }
        .mb-10 { margin-bottom: 10px; }

        /* Garis pembatas elegan (dashed) */
        .line-dashed {
            border-top: 1px dashed #000;
            margin: 3px 0; /* Tetap rapat, jarak diatur di elemen bawahnya */
        }

        /* Garis total ganda untuk pemisah tagihan bawah */
        .line-double {
            border-top: 3px double #000;
            margin: 10px 0; /* Kasih jarak agak lega atas bawah */
        }

        table { width: 100%; border-collapse: collapse; }
        td { padding: 1px 0; vertical-align: top; }
        
        /* Alignment presisi dengan fixed width untuk Header Info */
        .label { width: 45px; text-align: left; }
        .sep { width: 5px; text-align: center; }
        .val { text-align: left; }

        /* Tabel khusus untuk Item (3 Kolom: Qty, Nama, Harga) */
        .table-item td { padding-bottom: 2px; }
        .col-qty { width: 10%; text-align: left; }
        .col-name { width: 52%; text-align: left; padding-right: 2px; }
        .col-price { width: 38%; text-align: right; white-space: nowrap; }

        /* Sub-item (kayak tipe hari) masuk sejajar dengan nama item */
        .item-sub {
            padding-left: 8%; 
            color: #000;
            font-size: 9px;
        }
        
        .footer-info {
            margin-top: 10px;
            font-size: 10px;
            text-align: center; /* Dibuat center sesuai contoh */
        }
        .footer-info div {
            margin-bottom: 2px;
        }
        .footer-slogan {
            font-size: 10px;
            font-style: italic;
        }
        .footer-sosmed {
            font-size: 9px;
        }
    </style>
</head>
<body>

    @if(isset($pengaturan->logo_struk) && $pengaturan->logo_struk)
        <div class="center">
            <img src="{{ public_path('assets/img/' . $pengaturan->logo_struk) }}" style="max-width: 70px; margin-bottom: 2px;">
        </div>
    @endif

    <div class="center bold mb-10" style="font-size: 13px;">{{ $pengaturan->nama_toko }}</div>
    <div class="center mb-10">{!! nl2br(e($pengaturan->alamat_toko)) !!}</div>

    <div class="line-dashed"></div>

    {{-- ============= Info Transaksi ============= --}}
    <table>
        <tr><td class="label">Nota</td><td class="sep">:</td><td class="val">{{ $nomorNota ?? $kodeSewa }}</td></tr>
        <tr><td class="label">Waktu</td><td class="sep">:</td><td class="val">{{ $waktu }}</td></tr>
        <tr><td class="label">Kasir</td><td class="sep">:</td><td class="val">{{ $kasir }}</td></tr>
        <tr><td class="label">Cust</td><td class="sep">:</td><td class="val">{{ $customer }}</td></tr>
    </table>

    <div class="line-dashed"></div>

    {{-- ============= Daftar Item (Layout 3 Kolom) ============= --}}
    <!-- Tambah mt-10 agar ada jarak setelah garis putus-putus -->
    <table class="table-item mt-10">
        @foreach($items as $item)
            @php
                $namaItemBersih = str_replace('Jam - ', '', $item['nama']);
            @endphp
            <tr>
                <td class="col-qty">{{ $item['qty'] }}</td>
                <td class="col-name">{{ $namaItemBersih }}</td>
                <td class="col-price">{{ number_format($item['subtotal'], 0, ',', '.') }}</td>
            </tr>
            @if(!empty($item['sub']))
                <tr>
                    <td colspan="3" class="item-sub">{{ $item['sub'] }}</td>
                </tr>
            @endif
        @endforeach
    </table>

    <div class="line-dashed"></div>

    {{-- ============= Total ============= --}}
    <!-- Tambah mt-10 agar ada jarak setelah garis putus-putus -->
    <table class="mt-10">
        <tr>
            <td>Subtotal {{ count($items) }}</td>
            <td class="right">{{ number_format($subTotal, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="bold">Total Tagihan</td>
            <td class="right bold">{{ number_format($totalTagihan, 0, ',', '.') }}</td>
        </tr>
        
        @if($jumlahDp > 0)
            <tr>
                <td>Sudah DP</td>
                <td class="right">- {{ number_format($jumlahDp, 0, ',', '.') }}</td>
            </tr>
        @endif
    </table>

    <div class="line-dashed"></div>

    <!-- Tambah mt-10 agar ada jarak setelah garis putus-putus -->
        <!-- Tambah mt-10 agar ada jarak setelah garis putus-putus -->
    <table class="mt-10">
        <tr>
            <td>{{ $metodePembayaran }}</td>
            <td class="right">{{ number_format($totalBayar, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="bold" style="font-size: 12px;">Total Bayar</td>
            <td class="right bold" style="font-size: 12px;">{{ number_format($totalBayar, 0, ',', '.') }}</td>
        </tr>
        @if(!empty($kembalian) && $kembalian > 0)
            <tr>
                <td>Tunai</td>
                <td class="right">{{ number_format($uangDiterima, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td class="bold">Kembali</td>
                <td class="right bold">{{ number_format($kembalian, 0, ',', '.') }}</td>
            </tr>
        @endif
    </table>

    @if($catatan)
        <div class="line-dashed"></div>
        <!-- Tambah mt-10 dan mb-10 agar terpisah rapi dari garis dan footer -->
        <div class="mt-10">Catatan :</div>
        <div class="mb-10">{{ $catatan }}</div>
    @endif

    <div class="line-double"></div>

    {{-- ============= Footer ============= --}}
    {{-- Urutan: Wifi Pass -> Slogan -> Sosmed -> Dicetak oleh --}}
    <div class="footer-info">
        <div>Wifi Pass : {{ $pengaturan->wifi_password }}</div>

        @if(!empty($pengaturan->slogan_header))
            <div class="footer-slogan mt-10 mb-10">{{ $pengaturan->slogan_header }}</div>
        @endif

        {{-- PERBAIKAN LAYOUT SOSMED: Dibuat bersusun ke bawah (vertikal) --}}
        <div class="footer-sosmed mb-10">
            @if(!empty($pengaturan->ig))
                <div>IG : {{ $pengaturan->ig }}</div>
            @endif
            
            @if(!empty($pengaturan->wa))
                <div>WA : {{ $pengaturan->wa }}</div>
            @endif
            
            @if(!empty($pengaturan->tiktok))
                <div>Tiktok : {{ $pengaturan->tiktok }}</div>
            @endif
        </div>

        <div>Dicetak : {{ $dicetakOleh }}</div>
    </div>

</body>
</html>