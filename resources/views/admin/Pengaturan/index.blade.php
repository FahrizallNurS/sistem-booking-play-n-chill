@extends('adminlte::page')
@include('partials.sidebar-admin')

@section('title', 'Pengaturan Sistem & Struk')

@section('content_header')
    <h1>Pengaturan Sistem & Struk</h1>
@stop

@section('content')
<div class="row">
    {{-- ===================== FORM ===================== --}}
    <div class="col-md-8">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Informasi Toko & Struk</h3>
            </div>

            {{-- Wajib ada enctype="multipart/form-data" biar bisa upload gambar --}}
            <form action="{{ route('admin.pengaturan.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PATCH')

                <div class="card-body">

                    {{-- Alert kalau sukses update --}}
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <h5><i class="icon fas fa-check"></i> Sukses!</h5>
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="form-group">
                        <label for="nama_toko">Nama Toko/Cabang</label>
                        <input type="text" class="form-control @error('nama_toko') is-invalid @enderror" id="nama_toko" name="nama_toko" value="{{ old('nama_toko', $pengaturan->nama_toko) }}" placeholder="Contoh: Play n Chill Madiun">
                        @error('nama_toko') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label for="alamat_toko">Alamat Toko</label>
                        <textarea class="form-control @error('alamat_toko') is-invalid @enderror" id="alamat_toko" name="alamat_toko" rows="2" placeholder="Contoh: Jl. Margobawero No.46">{{ old('alamat_toko', $pengaturan->alamat_toko) }}</textarea>
                        @error('alamat_toko') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label for="slogan_header">Slogan / Info Tambahan</label>
                        <input type="text" class="form-control @error('slogan_header') is-invalid @enderror" id="slogan_header" name="slogan_header" value="{{ old('slogan_header', $pengaturan->slogan_header) }}" placeholder="Contoh: Play, Chill, Repeat!">
                        @error('slogan_header') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        <small class="text-muted">Sekarang tampil di bagian bawah (footer) struk, bukan di header.</small>
                    </div>

                    <hr>

                    <div class="form-group">
                        <label for="logo_struk">Logo Struk (Hitam Putih disarankan)</label>
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input @error('logo_struk') is-invalid @enderror" id="logo_struk" name="logo_struk" accept="image/png, image/jpeg, image/jpg">
                                <label class="custom-file-label" for="logo_struk">Pilih file logo...</label>
                            </div>
                        </div>
                        <small class="text-muted">Maksimal 2MB. Format: JPG, PNG. Biarkan kosong jika tidak ingin mengubah logo.</small>
                        <small id="logo_struk_error" class="text-danger d-block mt-1" style="display:none;"></small>
                        @error('logo_struk') <span class="text-danger d-block mt-1">{{ $message }}</span> @enderror

                        {{-- Tampilan preview logo lama kalau udah ada --}}
                        @if($pengaturan->logo_struk)
                            <div class="mt-2">
                                <p class="mb-1">Logo saat ini:</p>
                                <img src="{{ asset('assets/img/' . $pengaturan->logo_struk) }}" alt="Logo" style="max-height: 80px; border: 1px solid #ddd; padding: 3px;">
                            </div>
                        @endif
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="wifi_ssid">Nama WiFi (SSID)</label>
                                <input type="text" class="form-control @error('wifi_ssid') is-invalid @enderror" id="wifi_ssid" name="wifi_ssid" value="{{ old('wifi_ssid', $pengaturan->wifi_ssid) }}">
                                @error('wifi_ssid') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                <small class="text-muted">SSID tidak dicetak di struk, hanya dipakai di halaman lain.</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="wifi_password">Password WiFi</label>
                                <input type="text" class="form-control @error('wifi_password') is-invalid @enderror" id="wifi_password" name="wifi_password" value="{{ old('wifi_password', $pengaturan->wifi_password) }}">
                                @error('wifi_password') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <hr>

                    <label class="d-block mb-2">Media Sosial <small class="text-muted">(tampil di footer struk)</small></label>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="ig">Instagram</label>
                                <input type="text" class="form-control @error('ig') is-invalid @enderror" id="ig" name="ig" value="{{ old('ig', $pengaturan->ig) }}" placeholder="@pncjogja">
                                @error('ig') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="wa">WhatsApp</label>
                                <input type="text" class="form-control @error('wa') is-invalid @enderror" id="wa" name="wa" value="{{ old('wa', $pengaturan->wa) }}" placeholder="0812xxxxxxx">
                                @error('wa') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="tiktok">TikTok</label>
                                <input type="text" class="form-control @error('tiktok') is-invalid @enderror" id="tiktok" name="tiktok" value="{{ old('tiktok', $pengaturan->tiktok) }}" placeholder="@pncjogja">
                                @error('tiktok') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Simpan Pengaturan</button>
                </div>
            </form>
        </div>
    </div>

    <div class="col-md-4">
        <div class="struk-preview-wrapper">
            <p class="text-muted small mb-2">
                <i class="fas fa-eye"></i> Pratinjau Struk
                <span class="d-block" style="font-size: 11px;">Contoh: booking + F&amp;B (data ilustrasi)</span>
            </p>

            <div id="struk-preview" class="struk-preview">
                <div class="center">
                    <img id="preview-logo"
                         src="{{ $pengaturan->logo_struk ? asset('assets/img/' . $pengaturan->logo_struk) : '' }}"
                         alt="Logo"
                         style="{{ $pengaturan->logo_struk ? '' : 'display:none;' }} max-width: 70px; margin-bottom: 2px;">
                </div>

                <div class="center bold" id="preview-nama-toko" style="font-size: 13px;">{{ $pengaturan->nama_toko ?: 'Nama Toko' }}</div>
                <div class="center mb-10" id="preview-alamat-toko">{!! nl2br(e($pengaturan->alamat_toko ?: 'Alamat toko')) !!}</div>

                <div class="line-dashed"></div>

                <table>
                    <tr><td class="label">Nota</td><td class="sep">:</td><td class="val" id="preview-nota"></td></tr>
                    <tr><td class="label">Waktu</td><td class="sep">:</td><td class="val" id="preview-waktu"></td></tr>
                    <tr><td class="label">Kasir</td><td class="sep">:</td><td class="val">Admin</td></tr>
                    <tr><td class="label">Cust</td><td class="sep">:</td><td class="val">Contoh Pelanggan</td></tr>
                </table>

                <div class="line-dashed"></div>

                <table class="table-item mt-10" id="preview-items"></table>

                <div class="line-dashed"></div>

                <table class="mt-10">
                    <tr>
                        <td id="preview-subtotal-label"></td>
                        <td class="right" id="preview-subtotal-value"></td>
                    </tr>
                    <tr>
                        <td class="bold">Total Tagihan</td>
                        <td class="right bold" id="preview-total-tagihan"></td>
                    </tr>
                </table>

                <div class="line-dashed"></div>

                <table class="mt-10">
                    <tr>
                        <td>TUNAI</td>
                        <td class="right" id="preview-total-bayar-1"></td>
                    </tr>
                    <tr>
                        <td class="bold" style="font-size: 12px;">Total Bayar</td>
                        <td class="right bold" style="font-size: 12px;" id="preview-total-bayar-2"></td>
                    </tr>
                </table>

                <div class="line-double"></div>

                {{-- Footer: Wifi Pass -> Slogan -> Sosmed -> Dicetak oleh (samain persis sama struk-pdf.blade.php) --}}
                <div class="footer-info">
                    <div>Wifi Pass : <span id="preview-wifi-pass"></span></div>
                    <div class="footer-slogan" id="preview-slogan-footer"></div>
                    <div class="footer-sosmed" id="preview-sosmed"></div>
                    <div>Dicetak : Admin</div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
<style>

    .struk-preview-wrapper {
        position: sticky;
        top: 20px;
    }

    .struk-preview {
        font-family: 'Courier New', monospace;
        font-size: 11px;
        line-height: 1.2;
        color: #000;
        background: #fff;
        width: 100%;
        max-width: 260px;
        margin: 0 auto;
        padding: 12px 10px;
        border: 1px solid #ddd;
        border-radius: 2px;
        box-shadow: 0 1px 4px rgba(0,0,0,0.08);
    }

    .struk-preview .center { text-align: center; }
    .struk-preview .left { text-align: left; }
    .struk-preview .right { text-align: right; white-space: nowrap; }
    .struk-preview .bold { font-weight: bold; }

    .struk-preview .mt-10 { margin-top: 10px; }
    .struk-preview .mb-10 { margin-bottom: 10px; }

    .struk-preview .line-dashed {
        border-top: 1px dashed #000;
        margin: 3px 0;
    }

    .struk-preview .line-double {
        border-top: 3px double #000;
        margin: 10px 0;
    }

    .struk-preview table { width: 100%; border-collapse: collapse; }
    .struk-preview td { padding: 1px 0; vertical-align: top; }

    .struk-preview .label { width: 45px; text-align: left; }
    .struk-preview .sep { width: 5px; text-align: center; }
    .struk-preview .val { text-align: left; word-break: break-word; }

    .struk-preview .table-item td { padding-bottom: 2px; }
    .struk-preview .col-qty { width: 10%; text-align: left; }
    .struk-preview .col-name { width: 52%; text-align: left; padding-right: 2px; }
    .struk-preview .col-price { width: 38%; text-align: right; white-space: nowrap; }

    .struk-preview .item-sub {
        padding-left: 8%;
        color: #000;
        font-size: 9px;
    }

    .struk-preview .footer-info {
        margin-top: 10px;
        font-size: 10px;
        text-align: center;
    }

    .struk-preview .footer-info div {
        margin-bottom: 2px;
    }

    .struk-preview .footer-slogan {
        font-size: 10px;
        font-style: italic;
    }

    .struk-preview .footer-sosmed {
        font-size: 9px;
    }

    @media (max-width: 767px) {
        .struk-preview-wrapper { position: static; margin-top: 20px; }
    }
</style>
@stop

@section('js')
<script>
    // Script buat nampilin nama file yang di-upload di input file (style bootstrap/adminlte)
    $('.custom-file-input').on('change', function() {
        let fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').addClass("selected").html(fileName);
    });
</script>

<script>
(function () {
    'use strict';
    // nama item dummy sengaja pakai format "X Jam - Nama Paket" biar sama persis
    // dengan yang dikirim BookingService::generateStrukPdf(), lalu di-strip
    // "Jam - " di renderItems() supaya preview match sama struk-pdf.blade.php
    // (lihat str_replace('Jam - ', '', $item['nama']) di template PDF asli).
    var DUMMY_ITEMS = [
        { qty: 1, nama: '2 Jam - Paket Reguler', sub: 'Senin-Kamis', subtotal: 50000 },
        { qty: 2, nama: 'Es Teh Manis', sub: null, subtotal: 16000 },
        { qty: 1, nama: 'Kentang Goreng', sub: null, subtotal: 18000 }
    ];

    // Format nomor nota dummy samain sama GeneratesStrukPdf::generateNomorNota()
    // di backend: YYMMDD/KODECABANG/001
    var DUMMY_NOMOR_NOTA = formatTanggalKodeSingkat(new Date()) + '/' + (window.STRUK_KODE_CABANG || 'PNC01') + '/001';

    var MAX_LOGO_SIZE = 2 * 1024 * 1024; // 2MB, samain sama rule validasi server
    var ALLOWED_LOGO_TYPES = ['image/jpeg', 'image/png', 'image/jpg'];

    // Cache elemen form
    var elNamaToko   = document.getElementById('nama_toko');
    var elAlamatToko = document.getElementById('alamat_toko');
    var elSlogan     = document.getElementById('slogan_header');
    var elIg         = document.getElementById('ig');
    var elWa         = document.getElementById('wa');
    var elTiktok     = document.getElementById('tiktok');
    var elWifiPass   = document.getElementById('wifi_password');
    var elLogoInput  = document.getElementById('logo_struk');
    var elLogoError  = document.getElementById('logo_struk_error');

    // Cache elemen preview
    var pv = {
        logo: document.getElementById('preview-logo'),
        namaToko: document.getElementById('preview-nama-toko'),
        alamatToko: document.getElementById('preview-alamat-toko'),
        sloganFooter: document.getElementById('preview-slogan-footer'),
        sosmed: document.getElementById('preview-sosmed'),
        nota: document.getElementById('preview-nota'),
        waktu: document.getElementById('preview-waktu'),
        items: document.getElementById('preview-items'),
        subtotalLabel: document.getElementById('preview-subtotal-label'),
        subtotalValue: document.getElementById('preview-subtotal-value'),
        totalTagihan: document.getElementById('preview-total-tagihan'),
        totalBayar1: document.getElementById('preview-total-bayar-1'),
        totalBayar2: document.getElementById('preview-total-bayar-2'),
        wifiPass: document.getElementById('preview-wifi-pass')
    };

    // Logo asli dari database, dipakai sebagai fallback kalau user batal pilih file
    var logoAsliSrc = pv.logo.getAttribute('src') || '';

    function formatTanggalKodeSingkat(date) {
        // YYMMDD -- samain sama now()->format('ymd') di generateNomorNota()
        var y = String(date.getFullYear()).slice(-2);
        var m = String(date.getMonth() + 1).padStart(2, '0');
        var d = String(date.getDate()).padStart(2, '0');
        return '' + y + m + d;
    }

    function formatRupiah(angka) {
        return angka.toLocaleString('id-ID');
    }

    function formatWaktuSekarang() {
        var d = new Date();
        var pad = function (n) { return String(n).padStart(2, '0'); };
        return pad(d.getDate()) + '/' + pad(d.getMonth() + 1) + '/' + d.getFullYear()
            + ' ' + pad(d.getHours()) + ':' + pad(d.getMinutes());
    }

    function setText(el, value, fallback) {
        el.textContent = (value && String(value).trim() !== '') ? value : (fallback || '');
    }

    // Sama seperti nl2br(e(...)) di struk-pdf.blade.php, tapi versi client-side.
    function setMultilineText(el, value, fallback) {
        var raw = (value && String(value).trim() !== '') ? value : (fallback || '');
        var div = document.createElement('div');
        div.textContent = raw; // escape dulu
        el.innerHTML = div.innerHTML.replace(/\n/g, '<br>');
    }

    function renderItems() {
        pv.items.innerHTML = '';

        DUMMY_ITEMS.forEach(function (item) {
            var row = document.createElement('tr');

            var tdQty = document.createElement('td');
            tdQty.className = 'col-qty';
            tdQty.textContent = item.qty;

            var tdName = document.createElement('td');
            tdName.className = 'col-name';
            // samain dengan str_replace('Jam - ', '', $item['nama']) di server
            tdName.textContent = item.nama.replace('Jam - ', '');

            var tdPrice = document.createElement('td');
            tdPrice.className = 'col-price';
            tdPrice.textContent = formatRupiah(item.subtotal);

            row.appendChild(tdQty);
            row.appendChild(tdName);
            row.appendChild(tdPrice);
            pv.items.appendChild(row);

            if (item.sub) {
                var subRow = document.createElement('tr');
                var subTd = document.createElement('td');
                subTd.colSpan = 3;
                subTd.className = 'item-sub';
                subTd.textContent = item.sub;
                subRow.appendChild(subTd);
                pv.items.appendChild(subRow);
            }
        });
    }

    function renderTotals() {
        var subTotal = DUMMY_ITEMS.reduce(function (sum, item) {
            return sum + item.subtotal;
        }, 0);
        var totalTagihan = subTotal; // tidak ada DP di skenario dummy ini

        setText(pv.subtotalLabel, 'Subtotal ' + DUMMY_ITEMS.length);
        setText(pv.subtotalValue, formatRupiah(subTotal));
        setText(pv.totalTagihan, formatRupiah(totalTagihan));
        setText(pv.totalBayar1, formatRupiah(totalTagihan));
        setText(pv.totalBayar2, formatRupiah(totalTagihan));
    }

    function renderInfoTransaksi() {
        setText(pv.nota, DUMMY_NOMOR_NOTA);
        var waktuSekarang = formatWaktuSekarang();
        setText(pv.waktu, waktuSekarang);
    }

    function updateNamaToko() {
        setText(pv.namaToko, elNamaToko.value, 'Nama Toko');
    }

    function updateAlamatToko() {
        setMultilineText(pv.alamatToko, elAlamatToko.value, 'Alamat toko');
    }

    function updateSlogan() {
        setText(pv.sloganFooter, elSlogan.value, '');
    }

    function updateSosmed() {
        var parts = [];
        if (elIg.value.trim())     parts.push('IG ' + elIg.value.trim());
        if (elWa.value.trim())     parts.push('WA ' + elWa.value.trim());
        if (elTiktok.value.trim()) parts.push('TT ' + elTiktok.value.trim());
        setText(pv.sosmed, parts.join(' | '), '');
    }

    function updateWifiPass() {
        setText(pv.wifiPass, elWifiPass.value, '-');
    }

    function tampilkanErrorLogo(pesan) {
        elLogoError.textContent = pesan;
        elLogoError.style.display = pesan ? 'block' : 'none';
    }

    function resetLogoKeAsli() {
        if (logoAsliSrc) {
            pv.logo.src = logoAsliSrc;
            pv.logo.style.display = '';
        } else {
            pv.logo.removeAttribute('src');
            pv.logo.style.display = 'none';
        }
    }

    function updateLogoPreview() {
        var file = elLogoInput.files && elLogoInput.files[0];

        if (!file) {
            resetLogoKeAsli();
            tampilkanErrorLogo('');
            return;
        }

        if (ALLOWED_LOGO_TYPES.indexOf(file.type) === -1) {
            tampilkanErrorLogo('Format file harus JPG atau PNG.');
            resetLogoKeAsli();
            return;
        }

        if (file.size > MAX_LOGO_SIZE) {
            tampilkanErrorLogo('Ukuran file melebihi 2MB.');
            resetLogoKeAsli();
            return;
        }

        tampilkanErrorLogo('');

        var reader = new FileReader();
        reader.onload = function (e) {
            pv.logo.src = e.target.result;
            pv.logo.style.display = '';
        };
        reader.onerror = function () {
            tampilkanErrorLogo('Gagal membaca file gambar.');
            resetLogoKeAsli();
        };
        reader.readAsDataURL(file);
    }

    elNamaToko.addEventListener('input', updateNamaToko);
    elAlamatToko.addEventListener('input', updateAlamatToko);
    elSlogan.addEventListener('input', updateSlogan);
    elIg.addEventListener('input', updateSosmed);
    elWa.addEventListener('input', updateSosmed);
    elTiktok.addEventListener('input', updateSosmed);
    elWifiPass.addEventListener('input', updateWifiPass);
    elLogoInput.addEventListener('change', updateLogoPreview);

    renderItems();
    renderTotals();
    renderInfoTransaksi();
    updateNamaToko();
    updateAlamatToko();
    updateSlogan();
    updateSosmed();
    updateWifiPass();
})();
</script>
@stop