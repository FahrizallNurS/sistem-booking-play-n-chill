@props([
    'width' => 'col-md-4 col-sm-6'
])

<div class="{{ $width }} mb-3 mb-md-0">
    {{-- Label Dinamis --}}
    <label id="dynamic_date_label" class="custom-label">Tanggal</label>
    
    {{-- Input Harian (Date Picker) --}}
    <div id="filter_tanggal_wrapper">
        <input type="date" name="tanggal" id="input_tanggal" class="form-control custom-input"
            value="{{ request('tanggal', now()->format('Y-m-d')) }}">
    </div>

    {{-- Input Mingguan (Week Picker) --}}
    <div id="filter_minggu_wrapper" style="display: none;">
        <input type="week" name="minggu" id="input_minggu" class="form-control custom-input"
            value="{{ request('minggu', now()->format('Y-\WW')) }}">
    </div>

    {{-- Input Bulanan (Month Picker) --}}
    <div id="filter_bulan_wrapper" style="display: none;">
        <input type="month" name="bulan" id="input_bulan" class="form-control custom-input"
            value="{{ request('bulan', now()->format('Y-m')) }}">
    </div>
</div>

@push('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const periodeSelect = document.getElementById('periode'); 
        const labelDinamis = document.getElementById('dynamic_date_label');

        const wrapTanggal = document.getElementById('filter_tanggal_wrapper');
        const wrapMinggu  = document.getElementById('filter_minggu_wrapper');
        const wrapBulan   = document.getElementById('filter_bulan_wrapper');

        const inputTanggal = document.getElementById('input_tanggal');
        const inputMinggu  = document.getElementById('input_minggu');
        const inputBulan   = document.getElementById('input_bulan');

        function updateDynamicDateFilter(val) {
            wrapTanggal.style.display = 'none'; inputTanggal.disabled = true;
            wrapMinggu.style.display  = 'none'; inputMinggu.disabled  = true;
            wrapBulan.style.display   = 'none'; inputBulan.disabled   = true;

            if (val === 'mingguan') {
                wrapMinggu.style.display = 'block'; 
                inputMinggu.disabled     = false;
                if (labelDinamis) labelDinamis.textContent = 'Pilih Minggu';
            } else if (val === 'bulanan') {
                wrapBulan.style.display  = 'block'; 
                inputBulan.disabled      = false;
                if (labelDinamis) labelDinamis.textContent = 'Pilih Bulan';
            } else {
                wrapTanggal.style.display = 'block'; 
                inputTanggal.disabled    = false;
                if (labelDinamis) labelDinamis.textContent = 'Pilih Tanggal';
            }
        }

        if (periodeSelect) {
            updateDynamicDateFilter(periodeSelect.value);
            periodeSelect.addEventListener('change', function() {
                updateDynamicDateFilter(this.value);
            });
        }
    });
</script>
@endpush