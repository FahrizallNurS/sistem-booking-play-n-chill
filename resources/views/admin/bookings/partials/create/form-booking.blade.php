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
                <select name="ruangan" id="select_ruangan" class="form-control">
                    <option value="">Pilih Ruangan</option>
                    @foreach($ruangans as $r)
                        <option value="{{ $r['id'] }}">{{ $r['nama_ruangan'] }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-md-6 form-group">
                <label>Paket</label>
                <select name="paket" id="select_paket" class="form-control">
                    <option value="">Select Package</option>
                    @foreach($pakets as $p)
                        <option value="{{ $p['id'] }}">{{ $p['nama_paket'] }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- Durasi & Waktu Mulai --}}
        <div class="row">
            <div class="col-12 col-md-6 form-group">
                <label>Durasi</label>
                <select name="durasi" id="select_durasi" class="form-control">
                    @foreach($durasiOptions as $d)
                        <option value="{{ $d }}">{{ $d }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-md-6 form-group">
                <label>Waktu Mulai</label>
                <input type="datetime-local" name="waktu_mulai" id="waktu_mulai" class="form-control">
            </div>
        </div>

        {{-- Metode Pembayaran: Cash (bawah Durasi) & QRIS (bawah Waktu Mulai) --}}
        <div class="row">
            <div class="col-12 col-md-6 form-group">
                <label>Metode Pembayaran</label>
                <button type="button" class="btn btn-payment-option btn-block" data-value="cash">
                    <i class="fas fa-money-bill-wave mr-2"></i> Cash
                </button>
            </div>
            <div class="col-12 col-md-6 form-group">
                <label class="d-none d-md-block">&nbsp;</label>
                <button type="button" class="btn btn-payment-option btn-block" data-value="qris">
                    <i class="fas fa-qrcode mr-2"></i> QRIS
                </button>
            </div>
        </div>
        <input type="hidden" name="metode_pembayaran" id="metode_pembayaran" value="">

        {{-- Preview Info Paket --}}
        <div class="table-responsive">
            <table class="table table-sm table-bordered text-center mb-0" style="background-color: #f8f7fc;">
                <thead>
                    <tr>
                        <th>Category</th>
                        <th>Sub Category</th>
                        <th>SKU</th>
                        <th>Price</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td id="preview_category">-</td>
                        <td id="preview_subcategory">-</td>
                        <td id="preview_sku">-</td>
                        <td id="preview_price">-</td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>
</div>