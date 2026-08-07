<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        /* Margin kertas diminimalkan biar nggak buang ruang */
        @page { margin: 2mm; }
        body {
            font-family: 'Courier New', monospace;
            font-size: 11px; /* Ukuran font dinaikin dikit biar kebaca jelas */
            width: 54mm;
            margin: 0;
            padding: 0;
            line-height: 1.2; /* Line spacing dirapatkan biar khas struk kasir */
            color: #000;
        }
        .center { text-align: center; }
        .left { text-align: left; }
        .right { text-align: right; }
        .bold { font-weight: bold; }

        /* Garis pembatas elegan (dashed) pengganti ==== */
        .line-dashed {
            border-top: 1px dashed #000;
            margin: 3px 0;
        }

        /* Garis total ganda untuk pemisah tagihan bawah */
        .line-double {
            border-top: 3px double #000;
            margin: 3px 0;
        }

        table { width: 100%; border-collapse: collapse; }
        td { padding: 1px 0; vertical-align: top; }
        
        /* Alignment presisi dengan fixed width untuk Header Info */
        .label { width: 45px; text-align: left; }
        .sep { width: 5px; text-align: center; }
        .val { text-align: left; }

        /* Tabel khusus untuk Item (3 Kolom: Qty, Nama, Harga) */
        .table-item td { padding-bottom: 2px; }
        .col-qty { width: 8%; text-align: left; }
        .col-name { width: 62%; text-align: left; padding-right: 2px; }
        .col-price { width: 30%; text-align: right; }

        /* Sub-item (kayak tipe hari) masuk sejajar dengan nama item */
        .item-sub {
            padding-left: 8%; 
            color: #000;
            font-size: 9px;
        }
        
        .footer-info {
            margin-top: 5px;
            font-size: 10px;
        }
    </style>
</head>
<body>

    @if(isset($pengaturan->logo_struk) && $pengaturan->logo_struk)
        <div class="center">
            <img src="{{ public_path('assets/img/' . $pengaturan->logo_struk) }}" style="max-width: 70px; margin-bottom: 2px;">
        </div>
    @endif

    <div class="center bold" style="font-size: 13px;">{{ $pengaturan->nama_toko }}</div>
    <div class="center">{!! nl2br(e($pengaturan->alamat_toko)) !!}</div>
    <div class="center">{{ $pengaturan->slogan_header }}</div>

    <div class="line-dashed"></div>

    {{-- ============= Info Transaksi ============= --}}
    <table>
        <tr><td class="label">Nota</td><td class="sep">:</td><td class="val">{{ $kodeSewa }}</td></tr>
        <tr><td class="label">Waktu</td><td class="sep">:</td><td class="val">{{ $waktu }}</td></tr>
        <tr><td class="label">Kasir</td><td class="sep">:</td><td class="val">{{ $kasir }}</td></tr>
        <tr><td class="label">Cust</td><td class="sep">:</td><td class="val">{{ $customer }}</td></tr>
    </table>

    <div class="line-dashed"></div>

    {{-- ============= Daftar Item (Layout 3 Kolom) ============= --}}
    <table class="table-item">
        @foreach($items as $item)
            @php
                // Menghapus kata "Jam -" jika ada bawaan dari BookingService
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
    <table>
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

    <table>
        <tr>
            <td>{{ $metodePembayaran }}</td>
            <td class="right">{{ number_format($totalBayar, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="bold" style="font-size: 12px;">Total Bayar</td>
            <td class="right bold" style="font-size: 12px;">{{ number_format($totalBayar, 0, ',', '.') }}</td>
        </tr>
    </table>

    @if($catatan)
        <div class="line-dashed"></div>
        <div>Catatan :</div>
        <div>{{ $catatan }}</div>
    @endif

    <div class="line-double"></div>

    {{-- ============= Footer ============= --}}
    <div class="footer-info">
        <table>
            <tr><td style="width: 35%;">Wifi SSID</td><td style="width: 5%;">:</td><td>{{ $pengaturan->wifi_ssid }}</td></tr>
            <tr><td>Wifi Pass</td><td>:</td><td>{{ $pengaturan->wifi_password }}</td></tr>
            <tr><td>Terbayar</td><td>:</td><td>{{ $waktuPembayaran }}</td></tr>
            <tr><td>Dicetak</td><td>:</td><td>{{ $dicetakOleh }}</td></tr>
        </table>
    </div>

</body>
</html>