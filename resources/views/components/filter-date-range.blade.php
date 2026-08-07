@props([
    'name',
    'label' => 'Rentang Tanggal',
    'placeholder' => null,
    'width' => 'col-md-4 col-sm-6',
])

<div class="{{ $width }} mb-3 mb-md-0">
    <label for="{{ $name }}" class="custom-label">{{ $label }}</label>
    <div class="input-group">
        <div class="input-group-prepend">
            <span class="input-group-text bg-white border-right-0 custom-input">
                <i class="far fa-calendar-alt text-muted"></i>
            </span>
        </div>
        <input type="text" name="{{ $name }}" id="{{ $name }}"
            class="form-control custom-input border-left-0"
            value="{{ request($name) }}"
            placeholder="{{ $placeholder }}"
            readonly
            autocomplete="off"
            style="background-color: #fff; cursor: pointer;">
    </div>
</div>
{{-- Style .custom-label & .custom-input dibawa oleh x-filter-card (wajib jadi wrapper field ini) --}}
{{-- Sudah pakai plugin bootstrap-daterangepicker. Aktifkan di halaman pemanggil dengan
     @section('plugins.Daterangepicker') supaya moment.js & daterangepicker ke-load. --}}

@push('js')
<script>
    $(function () {
        $('#{{ $name }}').daterangepicker({
            autoUpdateInput: false,
            opens: 'left',
            locale: {
                format: 'DD MMM YYYY',
                applyLabel: 'Terapkan',
                cancelLabel: 'Batal',
                fromLabel: 'Dari',
                toLabel: 'Sampai',
                customRangeLabel: 'Custom',
                daysOfWeek: ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'],
                monthNames: [
                    'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                    'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
                ],
            },
            ranges: {
               'Hari Ini': [moment(), moment()],
               'Kemarin': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
               '7 Hari Terakhir': [moment().subtract(6, 'days'), moment()],
               '30 Hari Terakhir': [moment().subtract(29, 'days'), moment()],
               'Bulan Ini': [moment().startOf('month'), moment().endOf('month')],
               'Bulan Lalu': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
            },
        });

        $('#{{ $name }}').on('apply.daterangepicker', function (e, picker) {
            $(this).val(picker.startDate.format('DD MMM YYYY') + ' - ' + picker.endDate.format('DD MMM YYYY'));
        });

        $('#{{ $name }}').on('cancel.daterangepicker', function () {
            $(this).val('');
        });
    });
</script>
@endpush