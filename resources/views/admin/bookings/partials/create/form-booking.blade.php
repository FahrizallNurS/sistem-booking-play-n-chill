<div class="card card-outline card-secondary mb-3">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-calendar-check mr-1"></i> Informasi Booking
        </h3>
    </div>
    <div class="card-body">

        {{-- Ruangan & Paket --}}
        <div class="row">
            <div class="col-12 col-md-6 form-group">
                <label>Ruangan</label>
                <select name="ruangan" id="select_ruangan" class="form-control select-pnc">
                    <option value="">Pilih Ruangan</option>
                    @foreach($ruangans as $r)
                        <option value="{{ $r->id_ruangan }}">{{ $r->nama_ruangan }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-md-6 form-group">
                <label>Paket</label>
                <select name="paket" id="select_paket" class="form-control select-pnc">
                    <option value="">Pilih Paket</option>
                    @foreach($pakets as $p)
                        <option value="{{ $p->id_paket }}">{{ $p->nama_paket }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- Durasi & Harga (dimuat otomatis setelah Ruangan + Paket dipilih) --}}
        <div class="row">
            <div class="col-12 form-group">
                <label>Durasi & Harga</label>
                <input type="hidden" name="id_penetapan_harga" id="id_penetapan_harga">
                <div id="durasi-options" class="d-flex flex-wrap" style="gap: 10px;">
                    <small class="text-muted">Pilih ruangan &amp; paket terlebih dahulu.</small>
                </div>
            </div>
        </div>

        {{-- Waktu Mulai & Waktu Selesai (otomatis) --}}
        <div class="row">
            <div class="col-12 col-md-6 form-group">
                <label>Waktu Mulai</label>
                <input type="datetime-local" name="waktu_mulai" id="waktu_mulai" class="form-control">
            </div>
            <div class="col-12 col-md-6 form-group">
                <label>Waktu Selesai <small class="text-muted">(otomatis)</small></label>
                <input type="text" id="waktu_selesai_preview" class="form-control" value="-" readonly
                    style="background-color: #f8f7fc;">
            </div>
        </div>

        {{-- Metode Pembayaran: Cash (bawah Durasi) & QRIS (bawah Waktu Mulai) --}}
        <div class="row">
            <div class="col-12 col-md-6 form-group">
                <label>Metode Pembayaran</label>
                <button type="button" class="btn btn-payment-option btn-block" data-value="TUNAI">
                    <i class="fas fa-money-bill-wave mr-2"></i> Tunai
                </button>
            </div>
            <div class="col-12 col-md-6 form-group">
                <label class="d-none d-md-block">&nbsp;</label>
                <button type="button" class="btn btn-payment-option btn-block" data-value="QRIS">
                    <i class="fas fa-qrcode mr-2"></i> QRIS
                </button>
            </div>
        </div>
        <input type="hidden" name="metode_pembayaran" id="metode_pembayaran" value="">

        {{-- Catatan (opsional) --}}
        <div class="row">
            <div class="col-12 form-group mb-0">
                <label>Catatan <small class="text-muted">(opsional, tampil di struk)</small></label>
                <textarea name="catatan" id="catatan" class="form-control" rows="2" maxlength="100"
                    placeholder="Contoh: Request ganti controller, dsb."></textarea>
            </div>
        </div>

    </div>
</div>