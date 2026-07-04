<!-- Modal Detail Pesanan -->
<div class="modal fade" id="modalDetailPesanan" tabindex="-1" role="dialog" aria-labelledby="modalDetailLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius: 8px; border: none;">
            
            {{-- Header Modal --}}
            <div class="modal-header border-bottom">
                <h6 class="modal-title font-weight-bold text-dark" id="modalDetailLabel">Detail Pesanan</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" style="font-size: 24px;">&times;</span>
                </button>
            </div>

            {{-- Body Modal (Otomatis Scrollable jika konten panjang) --}}
            <div class="modal-body p-4">
                <div class="row">
                    
                    {{-- Kiri: Informasi Pelanggan --}}
                    <div class="col-md-5 mb-4 mb-md-0">
                        <h6 class="text-muted mb-3" style="font-size: 14px;">Informasi Pelanggan</h6>
                        
                        {{-- Gunakan Nested Row untuk mengunci jarak Label dan Value --}}
                        <div class="row mb-2" style="font-size: 13px;">
                            <div class="col-4 font-weight-bold text-dark">Nama</div>
                            {{-- text-break memastikan teks panjang akan turun ke bawah, tidak mendorong kolom --}}
                            <div class="col-8 text-dark text-break">Fahrizal Nur Syaifudin</div>
                        </div>
                        <div class="row mb-2" style="font-size: 13px;">
                            <div class="col-4 font-weight-bold text-dark">Email</div>
                            <div class="col-8 text-dark text-break">rizalkhadam@gmail.com</div>
                        </div>
                        <div class="row mb-2" style="font-size: 13px;">
                            <div class="col-4 font-weight-bold text-dark">No. HP</div>
                            <div class="col-8 text-dark text-break">081234567891</div>
                        </div>
                    </div>

                    {{-- Kanan: Detail Booking & F&B --}}
                    <div class="col-md-7 pl-md-4">
                        <h6 class="text-muted mb-3" style="font-size: 14px;">Detail Booking</h6>
                        
                        <div class="row mb-2" style="font-size: 13px;">
                            <div class="col-4 font-weight-bold text-dark">Kode Sewa</div>
                            <div class="col-8 font-weight-bold text-break" style="color: #e83e8c;">PNC-20260634-NYDP</div>
                        </div>
                        <div class="row mb-2" style="font-size: 13px;">
                            <div class="col-4 font-weight-bold text-dark">Ruangan</div>
                            <div class="col-8 text-dark text-break">R01</div>
                        </div>
                        <div class="row mb-2" style="font-size: 13px;">
                            <div class="col-4 font-weight-bold text-dark">Kategori</div>
                            <div class="col-8 text-dark text-break">REGULAR</div>
                        </div>
                        <div class="row mb-2" style="font-size: 13px;">
                            <div class="col-4 font-weight-bold text-dark">Sub Kategori</div>
                            <div class="col-8 text-dark text-break">PS3-Regular</div>
                        </div>
                        <div class="row mb-2" style="font-size: 13px;">
                            <div class="col-4 font-weight-bold text-dark">Paket</div>
                            <div class="col-8 text-dark text-break">Paket PS3</div>
                        </div>
                        <div class="row mb-2" style="font-size: 13px;">
                            <div class="col-4 font-weight-bold text-dark">Waktu Mulai</div>
                            <div class="col-8 text-dark text-break">24/06/2026 14:30</div>
                        </div>
                        <div class="row mb-2" style="font-size: 13px;">
                            <div class="col-4 font-weight-bold text-dark">Waktu Selesai</div>
                            <div class="col-8 text-dark text-break">24/06/2026 16:30</div>
                        </div>
                        <div class="row mb-2" style="font-size: 13px;">
                            <div class="col-4 font-weight-bold text-dark">Durasi</div>
                            <div class="col-8 text-dark text-break">2 Jam</div>
                        </div>
                        <div class="row mb-2" style="font-size: 13px;">
                            <div class="col-4 font-weight-bold text-dark">Tipe Hari</div>
                            <div class="col-8 text-dark text-break">Harian</div>
                        </div>
                        <div class="row mb-2" style="font-size: 13px;">
                            <div class="col-4 font-weight-bold text-dark">Total Harga</div>
                            <div class="col-8 text-dark text-break">Rp 38.000</div>
                        </div>
                        <div class="row mb-2 align-items-center" style="font-size: 13px;">
                            <div class="col-4 font-weight-bold text-dark">Status Sewa</div>
                            <div class="col-8">
                                <span class="badge badge-secondary py-1 px-2">Ditahan</span>
                            </div>
                        </div>
                        <div class="row mb-4 align-items-center" style="font-size: 13px;">
                            <div class="col-4 font-weight-bold text-dark">Status Bayar</div>
                            <div class="col-8">
                                <span class="badge badge-warning py-1 px-2 text-dark">Menunggu</span>
                            </div>
                        </div>

                        {{-- Garis Pemisah --}}
                        <div class="border-top pt-4 mb-3"></div>

                        {{-- Rincian Pesanan F&B --}}
                        <h6 class="text-muted mb-3" style="font-size: 14px;">Rincian Pesanan F&B</h6>
                        
                        <div class="d-flex justify-content-between mb-2" style="font-size: 13px;">
                            <div class="d-flex pr-3" style="min-width: 0;">
                                <span class="mr-2">1x</span>
                                <span class="text-dark text-truncate" title="Kopi Hitam">Kopi Hitam</span>
                            </div>
                            <div class="text-dark font-weight-normal text-nowrap">Rp 10.000</div>
                        </div>
                        <div class="d-flex justify-content-between mb-3" style="font-size: 13px;">
                            <div class="d-flex pr-3" style="min-width: 0;">
                                <span class="mr-2">2x</span>
                                <span class="text-dark text-truncate" title="Indomie Goreng">Indomie Goreng</span>
                            </div>
                            <div class="text-dark font-weight-normal text-nowrap">Rp 30.000</div>
                        </div>

                        {{-- Total Keseluruhan F&B --}}
                        <div class="text-right border-top pt-3">
                            <span class="font-weight-bold text-dark" style="font-size: 13px;">Total F&B: Rp 40.000</span>
                        </div>

                    </div>
                </div>
            </div>

            {{-- Footer Modal: Tombol Aksi Akhir --}}
            <div class="modal-footer bg-light border-top-0 d-flex justify-content-between">
                <div class="d-flex align-items-center">
                    <h5 class="mb-0 font-weight-bold text-dark mr-2">Grand Total:</h5>
                    <h5 class="mb-0 font-weight-bold" style="color: #6f42c1;">Rp 78.000</h5>
                </div>
                <div>
                    <button type="button" class="btn btn-outline-secondary font-weight-bold mr-2" data-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary font-weight-bold shadow-sm" style="background-color: #6f42c1; border-color: #6f42c1;">
                        <i class="fas fa-print mr-1"></i> Cetak Struk
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>