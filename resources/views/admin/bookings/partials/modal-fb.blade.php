<div class="modal fade" id="modalFB" tabindex="-1" role="dialog" aria-labelledby="modalFBLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius: 8px; overflow: hidden;">
            
            {{-- Header Modal --}}
            <div class="modal-header bg-white border-bottom-0 py-3">
                <h5 class="modal-title font-weight-bold text-dark" id="modalFBLabel">
                    <i class="fas fa-shopping-basket text-muted mr-2"></i> Menu F&B
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" style="font-size: 28px; line-height: 20px;">&times;</span>
                </button>
            </div>

            <div class="modal-body p-0">
                <div class="row no-gutters">
                    
                    {{-- Sisi Kiri: Daftar Menu --}}
                    <div class="col-md-7 p-4" style="background-color: #f8f9fa;">
                        <div class="d-flex mb-4" style="gap: 8px;">
                            <button class="btn btn-sm btn-light text-muted px-3 rounded-pill shadow-sm">Semua</button>
                            <button class="btn btn-sm px-3 rounded-pill shadow-sm" style="background-color: #28a745; color: white;">Makanan</button>
                            <button class="btn btn-sm btn-light text-muted px-3 rounded-pill shadow-sm">Minuman</button>
                            <button class="btn btn-sm btn-light text-muted px-3 rounded-pill shadow-sm">Snack</button>
                        </div>

                        <div class="row">
                            {{-- Card Menu 1 --}}
                            <div class="col-md-4 mb-3">
                                <div class="card h-100 shadow-sm border-0" style="border-radius: 8px; overflow: hidden; cursor: pointer;">
                                    <div class="bg-secondary d-flex align-items-center justify-content-center" style="height: 130px; background-image: url('https://images.unsplash.com/photo-1585032226651-759b368d7246?w=400'); background-size: cover; background-position: center;"></div>
                                    <div class="card-body p-3 bg-white">
                                        <h6 class="font-weight-bold text-dark mb-1" style="font-size: 14px; line-height: 1.2;">Indomie Goreng</h6>
                                        <span class="font-weight-bold text-success" style="font-size: 13px;">Rp 15.000</span>
                                    </div>
                                </div>
                            </div>

                            {{-- Card Menu 2 --}}
                            <div class="col-md-4 mb-3">
                                <div class="card h-100 shadow-sm border-0" style="border-radius: 8px; overflow: hidden; cursor: pointer;">
                                    <div class="bg-secondary d-flex align-items-center justify-content-center" style="height: 130px; background-image: url('https://images.unsplash.com/photo-1578985545062-69928b1d9587?w=400'); background-size: cover; background-position: center;"></div>
                                    <div class="card-body p-3 bg-white">
                                        <h6 class="font-weight-bold text-dark mb-1" style="font-size: 14px; line-height: 1.2;">Cake</h6>
                                        <span class="font-weight-bold text-success" style="font-size: 13px;">Rp 5.000</span>
                                    </div>
                                </div>
                            </div>

                            {{-- Card Menu 3 --}}
                            <div class="col-md-4 mb-3">
                                <div class="card h-100 shadow-sm border-0" style="border-radius: 8px; overflow: hidden; cursor: pointer;">
                                    <div class="bg-secondary d-flex align-items-center justify-content-center" style="height: 130px; background-image: url('https://images.unsplash.com/photo-1573080496219-bb080dd4f877?w=400'); background-size: cover; background-position: center;"></div>
                                    <div class="card-body p-3 bg-white">
                                        <h6 class="font-weight-bold text-dark mb-1" style="font-size: 14px; line-height: 1.2;">Kentang Goreng</h6>
                                        <span class="font-weight-bold text-success" style="font-size: 13px;">Rp 12.000</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Sisi Kanan: Keranjang Pesanan & Checkout --}}
                    <div class="col-md-5 p-4 bg-white d-flex flex-column border-left" style="max-height: 85vh; overflow-y: auto;">
                        
                        {{-- Header Keranjang --}}
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="font-weight-bold text-dark m-0" style="font-size: 16px;">Keranjang Pesanan</h6>
                            <small class="text-muted font-weight-bold">Kode Sewa: <span id="modalFBKodeSewa" class="text-secondary">PNC-20260529-TF5K</span></small>
                        </div>

                        {{-- Area Item Keranjang --}}
                        <div id="cart-items-container" style="flex-grow: 1; padding-right: 4px; padding-bottom: 10px;">
                            
                            {{-- Item 1 --}}
                            <div class="card mb-2 border shadow-none cart-item" style="border-radius: 6px;" data-price="15000">
                                <div class="card-body p-2">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col-5 pr-2">
                                            <h6 class="mb-0 font-weight-bold text-dark text-truncate" style="font-size: 13px;" title="Indomie Goreng">Indomie Goreng</h6>
                                            <small class="text-muted" style="font-size: 11px;">Rp 15.000</small>
                                        </div>
                                        <div class="col-4 d-flex justify-content-center">
                                            <div style="display: flex !important; align-items: center !important; justify-content: center !important; border: 1px solid #ced4da !important; border-radius: 4px !important; overflow: hidden !important; height: 28px !important; width: 90px !important; box-sizing: border-box !important; background-color: #ffffff !important;">
                                                <div class="btn-minus-fnb" style="position: relative !important; width: 28px !important; height: 28px !important; background-color: #f8f9fa !important; cursor: pointer !important; border-right: 1px solid #ced4da !important; user-select: none !important;">
                                                    <i class="fas fa-minus" style="position: absolute !important; top: 50% !important; left: 50% !important; transform: translate(-50%, -50%) !important; font-size: 10px !important; color: #6c757d !important; line-height: 0 !important;"></i>
                                                </div>
                                                <div class="qty-value" style="flex: 1 !important; height: 28px !important; display: flex !important; align-items: center !important; justify-content: center !important; font-weight: bold !important; font-size: 13px !important; color: #212529 !important; background-color: #ffffff !important; text-align: center !important; user-select: none !important;">
                                                    2
                                                </div>
                                                <div class="btn-plus-fnb" style="position: relative !important; width: 28px !important; height: 28px !important; background-color: #f8f9fa !important; cursor: pointer !important; border-left: 1px solid #ced4da !important; user-select: none !important;">
                                                    <i class="fas fa-plus" style="position: absolute !important; top: 50% !important; left: 50% !important; transform: translate(-50%, -50%) !important; font-size: 10px !important; color: #6c757d !important; line-height: 0 !important;"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-3 text-right">
                                            <span class="font-weight-bold text-dark item-subtotal" style="font-size: 13px;">Rp 30.000</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Item 2 --}}
                            <div class="card mb-2 border shadow-none cart-item" style="border-radius: 6px;" data-price="5000">
                                <div class="card-body p-2">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col-5 pr-2">
                                            <h6 class="mb-0 font-weight-bold text-dark text-truncate" style="font-size: 13px;" title="Es Kopi">Es Kopi</h6>
                                            <small class="text-muted" style="font-size: 11px;">Rp 5.000</small>
                                        </div>
                                        <div class="col-4 d-flex justify-content-center">
                                            <div style="display: flex !important; align-items: center !important; justify-content: center !important; border: 1px solid #ced4da !important; border-radius: 4px !important; overflow: hidden !important; height: 28px !important; width: 90px !important; box-sizing: border-box !important; background-color: #ffffff !important;">
                                                <div class="btn-minus-fnb" style="position: relative !important; width: 28px !important; height: 28px !important; background-color: #f8f9fa !important; cursor: pointer !important; border-right: 1px solid #ced4da !important; user-select: none !important;">
                                                    <i class="fas fa-minus" style="position: absolute !important; top: 50% !important; left: 50% !important; transform: translate(-50%, -50%) !important; font-size: 10px !important; color: #6c757d !important; line-height: 0 !important;"></i>
                                                </div>
                                                <div class="qty-value" style="flex: 1 !important; height: 28px !important; display: flex !important; align-items: center !important; justify-content: center !important; font-weight: bold !important; font-size: 13px !important; color: #212529 !important; background-color: #ffffff !important; text-align: center !important; user-select: none !important;">
                                                    1
                                                </div>
                                                <div class="btn-plus-fnb" style="position: relative !important; width: 28px !important; height: 28px !important; background-color: #f8f9fa !important; cursor: pointer !important; border-left: 1px solid #ced4da !important; user-select: none !important;">
                                                    <i class="fas fa-plus" style="position: absolute !important; top: 50% !important; left: 50% !important; transform: translate(-50%, -50%) !important; font-size: 10px !important; color: #6c757d !important; line-height: 0 !important;"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-3 text-right">
                                            <span class="font-weight-bold text-dark item-subtotal" style="font-size: 13px;">Rp 5.000</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Bagian Pilih Metode Pembayaran --}}
                        <div class="mt-2 border-top pt-3">
                            <h6 class="text-dark font-weight-bold mb-3" style="font-size: 13px;">Pilih Metode Pembayaran</h6>
                            
                            {{-- Opsi QRIS (Default Aktif) --}}
                            <div class="payment-option card mb-2" data-method="qris" style="border-radius: 6px; cursor: pointer; border: 2px solid #28a745;">
                                <div class="card-body p-2 d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center">
                                        <div style="width: 35px; text-align: center;">
                                            <i class="payment-icon fas fa-qrcode text-success" style="font-size: 22px;"></i>
                                        </div>
                                        <div class="ml-2">
                                            <h6 class="mb-0 font-weight-bold text-dark" style="font-size: 13px;">QRIS</h6>
                                            <small class="text-muted" style="font-size: 11px;">Scan barcode dari HP</small>
                                        </div>
                                    </div>
                                    <i class="payment-radio fas fa-dot-circle text-success" style="font-size: 18px;"></i>
                                </div>
                            </div>

                            {{-- Opsi TUNAI --}}
                            <div class="payment-option card mb-3" data-method="tunai" style="border-radius: 6px; cursor: pointer; border: 1px solid #ced4da;">
                                <div class="card-body p-2 d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center">
                                        <div style="width: 35px; text-align: center;">
                                            <i class="payment-icon fas fa-money-bill-wave text-secondary" style="font-size: 18px;"></i>
                                        </div>
                                        <div class="ml-2">
                                            <h6 class="mb-0 font-weight-bold text-dark" style="font-size: 13px;">TUNAI</h6>
                                            <small class="text-muted" style="font-size: 11px;">Bayar langsung di kasir</small>
                                        </div>
                                    </div>
                                    <i class="payment-radio far fa-circle text-muted" style="font-size: 18px;"></i>
                                </div>
                            </div>
                        </div>

                        {{-- Bagian Rekap Total & Submit --}}
                        <div class="border-top pt-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="text-muted" style="font-size: 13px;">Total Item</span>
                                <span class="text-dark font-weight-bold" id="summary-total-item" style="font-size: 13px;">3</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="text-muted" style="font-size: 13px;">Subtotal</span>
                                <span class="text-dark font-weight-bold" id="summary-subtotal" style="font-size: 13px;">Rp 35.000</span>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h6 class="font-weight-bold text-dark mb-0" style="font-size: 15px;">Grand Total</h6>
                                <h5 class="font-weight-bold mb-0 text-success" id="grand-total-fnb" style="font-size: 20px;">Rp 35.000</h5>
                            </div>

<button type="button" id="btnSimpanPesanan" class="btn w-100 py-2 shadow-sm fw-bold text-white d-flex justify-content-center align-items-center" style="background-color: #f07b55; border-radius: 6px; border: none; font-size: 0.95rem;">
    <i class="fas fa-check me-2"></i> Simpan Pesanan
</button>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- LOGIC JAVASCRIPT --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    
    // Helper function format Rupiah
    const formatRupiah = (number) => {
        return 'Rp ' + new Intl.NumberFormat('id-ID').format(number);
    };

    // Fungsi Update Grand Total (Termasuk Total Item & Subtotal)
    const updateGrandTotal = () => {
        let grandTotal = 0;
        let totalItemCount = 0;
        
        document.querySelectorAll('.cart-item').forEach(item => {
            const price = parseInt(item.getAttribute('data-price')) || 0;
            const qty = parseInt(item.querySelector('.qty-value').textContent) || 0;
            
            grandTotal += price * qty;
            totalItemCount += qty; // Hitung total qty (porsi)
        });
        
        const stringTotal = formatRupiah(grandTotal);

        // Update nilai di antarmuka
        document.getElementById('summary-total-item').textContent = totalItemCount;
        document.getElementById('summary-subtotal').textContent = stringTotal;
        document.getElementById('grand-total-fnb').textContent = stringTotal;
    };

    // Inisialisasi awal saat modal dibuka
    updateGrandTotal();

    // 1. EVENT DELEGATION: Tombol Plus / Minus F&B
    document.getElementById('cart-items-container').addEventListener('click', function (e) {
        
        if (e.target.closest('.btn-plus-fnb')) {
            const item = e.target.closest('.cart-item');
            const qtyEl = item.querySelector('.qty-value');
            let qty = parseInt(qtyEl.textContent) || 0;
            
            qty++;
            qtyEl.textContent = qty;

            const price = parseInt(item.getAttribute('data-price')) || 0;
            item.querySelector('.item-subtotal').textContent = formatRupiah(price * qty);
            
            updateGrandTotal();
        }

        if (e.target.closest('.btn-minus-fnb')) {
            const item = e.target.closest('.cart-item');
            const qtyEl = item.querySelector('.qty-value');
            let qty = parseInt(qtyEl.textContent) || 0;
            
            if (qty > 1) {
                qty--;
                qtyEl.textContent = qty;

                const price = parseInt(item.getAttribute('data-price')) || 0;
                item.querySelector('.item-subtotal').textContent = formatRupiah(price * qty);
                
                updateGrandTotal();
            }
        }
    });

    // 2. EVENT LISTENER: Pilihan Metode Pembayaran
    const paymentOptions = document.querySelectorAll('.payment-option');
    let selectedPaymentMethod = 'qris'; // Default value

    paymentOptions.forEach(option => {
        option.addEventListener('click', function() {
            // Ambil metode yang diklik
            selectedPaymentMethod = this.getAttribute('data-method');
            
            // a. Reset semua kotak kembali menjadi abu-abu
            paymentOptions.forEach(opt => {
                opt.style.border = '1px solid #ced4da'; // Border abu-abu biasa
                
                // Reset ikon radio di kanan
                const radio = opt.querySelector('.payment-radio');
                radio.classList.remove('fas', 'fa-dot-circle', 'text-success');
                radio.classList.add('far', 'fa-circle', 'text-muted');
                
                // Reset ikon utama di kiri
                const icon = opt.querySelector('.payment-icon');
                icon.classList.remove('text-success');
                icon.classList.add('text-secondary');
            });

            // b. Berikan gaya "Aktif" (Hijau tebal) pada kotak yang diklik
            this.style.border = '2px solid #28a745';
            
            const activeRadio = this.querySelector('.payment-radio');
            activeRadio.classList.remove('far', 'fa-circle', 'text-muted');
            activeRadio.classList.add('fas', 'fa-dot-circle', 'text-success');
            
            const activeIcon = this.querySelector('.payment-icon');
            activeIcon.classList.remove('text-secondary');
            activeIcon.classList.add('text-success');
            
            // Console log untuk testing ketersediaan variabel jika di-post pakai AJAX
            console.log("Metode bayar terpilih:", selectedPaymentMethod);
        });
    });

});
</script>