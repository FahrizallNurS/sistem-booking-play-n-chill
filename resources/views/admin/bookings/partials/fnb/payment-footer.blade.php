{{-- Disembunyikan lewat JS (modal-fb.blade.php) saat modal ini dibuka dari
     halaman "Tambah Booking" — metode bayar F&B mengikuti metode bayar yang
     sudah dipilih di form booking utama, tidak perlu dipilih dua kali.
     Tetap dipakai apa adanya untuk alur tambah F&B ke booking yang sudah ada. --}}
<div id="fnb-payment-section">
    <label class="font-weight-bold mb-2" style="font-size: 13px;">Pilih Metode Pembayaran</label>

    <div class="fnb-payment-option" data-value="QRIS">
        <div class="fnb-payment-icon"><i class="fas fa-qrcode"></i></div>
        <div class="fnb-payment-text">
            <div class="fnb-payment-label">QRIS</div>
            <div class="fnb-payment-desc">Scan barcode dari HP</div>
        </div>
        <div class="fnb-payment-radio"></div>
    </div>

    <div class="fnb-payment-option" data-value="TUNAI">
        <div class="fnb-payment-icon"><i class="fas fa-money-bill-wave"></i></div>
        <div class="fnb-payment-text">
            <div class="fnb-payment-label">TUNAI</div>
            <div class="fnb-payment-desc">Bayar langsung di kasir</div>
        </div>
        <div class="fnb-payment-radio"></div>
    </div>
</div>

<input type="hidden" id="fnb-metode-pembayaran" value="">

<div class="d-flex justify-content-between align-items-center mt-3 mb-3">
    <span class="font-weight-bold text-dark">Total F&amp;B</span>
    <span class="font-weight-bold" id="fnb-total" style="font-size: 16px; color: #28a745;">Rp 0</span>
</div>

<button type="button" id="btn-simpan-fnb" class="btn btn-success btn-block font-weight-bold" disabled>
    <i class="fas fa-check mr-1"></i> Simpan Pesanan
</button>