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
                            <button class="btn btn-sm px-3 rounded-pill shadow-sm" style="background-color: #6f42c1; color: white;">Makanan</button>
                            <button class="btn btn-sm btn-light text-muted px-3 rounded-pill shadow-sm">Minuman</button>
                            <button class="btn btn-sm btn-light text-muted px-3 rounded-pill shadow-sm">Snack</button>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <div class="card h-100 shadow-sm border-0" style="border-radius: 8px; overflow: hidden; cursor: pointer;">
                                    <div class="bg-secondary d-flex align-items-center justify-content-center" style="height: 130px; background-image: url('https://images.unsplash.com/photo-1585032226651-759b368d7246?w=400'); background-size: cover; background-position: center;"></div>
                                    <div class="card-body p-3 bg-white">
                                        <h6 class="font-weight-bold text-dark mb-1" style="font-size: 14px; line-height: 1.2;">Indomie Goreng</h6>
                                        <span class="font-weight-bold" style="color: #6f42c1; font-size: 13px;">Rp 15.000</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4 mb-3">
                                <div class="card h-100 shadow-sm border-0" style="border-radius: 8px; overflow: hidden; cursor: pointer;">
                                    <div class="bg-secondary d-flex align-items-center justify-content-center" style="height: 130px; background-image: url('https://images.unsplash.com/photo-1578985545062-69928b1d9587?w=400'); background-size: cover; background-position: center;"></div>
                                    <div class="card-body p-3 bg-white">
                                        <h6 class="font-weight-bold text-dark mb-1" style="font-size: 14px; line-height: 1.2;">Cake</h6>
                                        <span class="font-weight-bold" style="color: #6f42c1; font-size: 13px;">Rp 5.000</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4 mb-3">
                                <div class="card h-100 shadow-sm border-0" style="border-radius: 8px; overflow: hidden; cursor: pointer;">
                                    <div class="bg-secondary d-flex align-items-center justify-content-center" style="height: 130px; background-image: url('https://images.unsplash.com/photo-1573080496219-bb080dd4f877?w=400'); background-size: cover; background-position: center;"></div>
                                    <div class="card-body p-3 bg-white">
                                        <h6 class="font-weight-bold text-dark mb-1" style="font-size: 14px; line-height: 1.2;">Kentang Goreng</h6>
                                        <span class="font-weight-bold" style="color: #6f42c1; font-size: 13px;">Rp 12.000</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Sisi Kanan: Keranjang Pesanan --}}
                    <div class="col-md-5 p-4 bg-white d-flex flex-column justify-content-between border-left" style="min-height: 480px;">
                        
                        <div>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="font-weight-bold text-dark m-0" style="font-size: 16px;">Keranjang Pesanan</h6>
                                <small class="text-muted font-weight-bold">Kode Sewa: <span id="modalFBKodeSewa" class="text-secondary">PNC-20260529-TF5K</span></small>
                            </div>

                            <div id="cart-items-container" style="max-height: 280px; overflow-y: auto; padding-right: 4px;">
                                
                                {{-- Item 1: Indomie Goreng (Ditambahkan atribut data-price) --}}
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

                                {{-- Item 2: Es Kopi (Ditambahkan atribut data-price) --}}
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
                        </div>

                        {{-- Bagian Total & Submit --}}
                        <div class="border-top pt-3 mt-3">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="text-muted font-weight-bold" style="font-size: 14px;">Total F&B</span>
                                <h4 class="font-weight-bold mb-0 text-dark" id="grand-total-fnb" style="font-size: 22px;">Rp 35.000</h4>
                            </div>
                            <button type="button" id="btn-simpan-fnb" class="btn btn-success btn-block py-2 font-weight-bold shadow-sm" style="border-radius: 6px; font-size: 14px;">
                                <i class="fas fa-check mr-1"></i> Simpan Pesanan
                            </button>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- LOGIC JAVASCRIPT UNTUK HITUNG DINAMIS --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    
    // Helper function untuk format mata uang Rupiah standar Indonesia (Contoh: Rp 35.000)
    const formatRupiah = (number) => {
        return 'Rp ' + new Intl.NumberFormat('id-ID').format(number);
    };

    // Fungsi utama mengkalkulasi ulang Grand Total seluruh keranjang
    const updateGrandTotal = () => {
        let grandTotal = 0;
        document.querySelectorAll('.cart-item').forEach(item => {
            const price = parseInt(item.getAttribute('data-price')) || 0;
            const qty = parseInt(item.querySelector('.qty-value').textContent) || 0;
            grandTotal += price * qty;
        });
        document.getElementById('grand-total-fnb').textContent = formatRupiah(grandTotal);
    };

    // Event Listener menggunakan Delegation Pattern (Aman untuk integrasi data dinamis / Ajax)
    document.getElementById('cart-items-container').addEventListener('click', function (e) {
        
        // 1. Aksi Tombol Plus (+)
        if (e.target.closest('.btn-plus-fnb')) {
            const item = e.target.closest('.cart-item');
            const qtyEl = item.querySelector('.qty-value');
            let qty = parseInt(qtyEl.textContent) || 0;
            
            qty++;
            qtyEl.textContent = qty;

            // Hitung subtotal item tersebut
            const price = parseInt(item.getAttribute('data-price')) || 0;
            item.querySelector('.item-subtotal').textContent = formatRupiah(price * qty);
            
            // Perbarui Grand Total
            updateGrandTotal();
        }

        // 2. Aksi Tombol Minus (-)
        if (e.target.closest('.btn-minus-fnb')) {
            const item = e.target.closest('.cart-item');
            const qtyEl = item.querySelector('.qty-value');
            let qty = parseInt(qtyEl.textContent) || 0;
            
            // Batasi minimum kuantitas adalah 1 item
            if (qty > 1) {
                qty--;
                qtyEl.textContent = qty;

                // Hitung ulang subtotal item tersebut
                const price = parseInt(item.getAttribute('data-price')) || 0;
                item.querySelector('.item-subtotal').textContent = formatRupiah(price * qty);
                
                // Perbarui Grand Total
                updateGrandTotal();
            }
        }
    });
});
</script>