@extends('adminlte::page')
@include('partials.sidebar-superadmin')

@section('title', 'Pendapatan per Kasir - Play N Chill')
@section('plugins.Chartjs', true)

@section('content_header')
<div class="container-fluid py-2">
    <div class="row align-items-center">
        <div class="col-sm-12">
            <h1 class="font-weight-bold text-dark m-0" style="font-size: 28px; letter-spacing: -0.5px;">Pendapatan per Kasir</h1>
            <p class="text-muted mb-0" style="font-size: 14px;">Analisis pendapatan per kasir.</p>
        </div>
    </div>
</div>
@stop

@section('content')
<div class="container-fluid pb-4">

    <div class="row mb-4">
        <div class="col-12">
            <x-filter-card id="filter-form" :action="url()->current()">
                <x-filter-select name="periode" label="PERIODE" :options="['harian' => 'Harian', 'mingguan' => 'Mingguan', 'bulanan' => 'Bulanan']" width="col-md-2 col-sm-6" default="harian"/>
                <x-filter-dynamic-date width="col-md-4 col-sm-6" />
                <x-filter-select name="nama_kasir" label="NAMA KASIR" :options="$kasirOptions" width="col-md-4 col-sm-6"/>
            </x-filter-card>
        </div>
    </div>

    <div class="row mb-2">
        <div class="col-12">
            <x-card>
                <x-slot name="title">Performa Kasir</x-slot>
                <x-slot name="subtitle">Akumulasi pendapatan harian (IDR)</x-slot>

                <x-slot name="header">
                    <div id="kasirChart-header-legend" class="chart-legend-wrap d-none d-md-flex align-items-center" style="font-size: 12px;"></div>
                </x-slot>

                <div class="chart-container mt-3" style="position: relative; height:400px; width:100%;">
                    <canvas id="kasirChart"></canvas>
                </div>

                <div id="kasir-legend-boxes" class="d-flex flex-wrap gap-3 mt-4"></div>
            </x-card>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <x-table>
                <x-slot name="head">
                    <tr style="background-color: #faf5ff;">
                        <th class="py-3 px-4 border-0 text-muted" style="font-size: 11px;">NO</th>
                        <th class="py-3 border-0 text-muted" style="font-size: 11px;">NAMA KASIR</th>
                        <th class="py-3 border-0 text-muted text-right" style="font-size: 11px;">TRANSAKSI BOOKING</th>
                        <th class="py-3 border-0 text-muted text-right" style="font-size: 11px;">TRANSAKSI F&B</th>
                        <th class="py-3 border-0 text-muted text-right" style="font-size: 11px;">JUMLAH REFUND</th>
                        <th class="py-3 border-0 text-muted text-right" style="font-size: 11px;">TOTAL PENDAPATAN</th>
                    </tr>
                </x-slot>

                <tbody id="kasir-table-body">
                    @foreach($tableData as $index => $row)
                        <tr>
                            <td class="px-4 text-muted py-3" style="font-size: 13px;">{{ $index + 1 }}</td>
                            <td class="text-dark py-3" style="font-size: 13px;">{{ $row['nama'] }}</td>
                            <td class="text-muted text-right py-3" style="font-size: 13px;">Rp {{ number_format($row['booking'], 0, ',', '.') }}</td>
                            <td class="text-muted text-right py-3" style="font-size: 13px;">Rp {{ number_format($row['fnb'], 0, ',', '.') }}</td>
                            <td class="text-muted text-right py-3" style="font-size: 13px;">Rp {{ number_format($row['refund'], 0, ',', '.') }}</td>
                            <td class="text-dark font-weight-bold text-right py-3" style="font-size: 13px;">Rp {{ number_format($row['total'], 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>

                <x-slot name="footer">
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <span id="footer-info" class="text-muted" style="font-size: 13px;">Menampilkan 1 hingga {{ count($tableData) }} dari {{ count($tableData) }} entri</span>
                        <div class="btn-group">
                            <button class="btn btn-sm btn-light border text-muted">Sebelumnya</button>
                            <button class="btn btn-sm btn-primary" style="background-color: #6f42c1; border-color: #6f42c1;">1</button>
                            <button class="btn btn-sm btn-light border text-muted">Selanjutnya</button>
                        </div>
                    </div>
                </x-slot>
            </x-table>
        </div>
    </div>

</div>
@stop

@section('css')
<style>
label {
    font-size: 11px !important;
    font-weight: 600 !important;
    color: #4a5568;
    margin-bottom: 4px;
}
.legend-box { transition: all 0.2s ease; user-select: none; border-top: 4px solid transparent; }
.legend-box:hover { transform: translateY(-2px); box-shadow: 0 4px 6px rgba(0,0,0,0.1) !important; }
.legend-box.hidden-dataset { opacity: 0.5; background-color: #f8f9fa !important; border-top-color: #d1d5db !important; }
.autocomplete-item { cursor: pointer; font-size: 13px; padding: 10px 15px; font-weight: 500; transition: 0.2s; }
.autocomplete-item:hover { background-color: #faf5ff; color: #6f42c1; padding-left: 20px; }
.chart-legend-wrap { flex-wrap: wrap; justify-content: flex-end; row-gap: 6px; column-gap: 12px; max-width: 100%; }

.add-legend-box {
    border: 2px dashed #d1d5db !important;
    background-color: #fafafa !important;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s ease;
    min-height: 62px;
}
.add-legend-box:hover { border-color: #6f42c1 !important; background-color: #faf5ff !important; }
.add-legend-box .add-icon-label { color: #9ca3af; font-weight: 600; font-size: 13px; display: flex; align-items: center; gap: 6px; }
.add-legend-box:hover .add-icon-label { color: #6f42c1; }
.add-legend-box.search-mode { cursor: default; border-style: solid !important; background-color: #fff !important; display: block; padding: 10px; }
.add-legend-box .search-results { max-height: 160px; overflow-y: auto; margin-top: 8px; }
</style>
@stop

@section('js')
<script>
    let availableKasirs = @json($availableKasirs->map(fn($k) => ['id_pengguna' => $k->id_pengguna, 'nama_pengguna' => $k->nama_pengguna])->values());
    const initialLabels = @json($chartLabels);
    const initialDatasets = @json($chartDatasets);

    let kasirChart;
    let addCardOpen = false;

    function formatDatasets(rawDatasets) {
        return rawDatasets.map((item) => ({
            label: item.label,
            data: item.data,
            borderColor: item.color,
            backgroundColor: 'transparent',
            borderWidth: 3,
            tension: 0.4,
            pointRadius: 0,
            pointHoverRadius: 5,
            pointHitRadius: 10,
            pointBackgroundColor: item.color,
            pointBorderColor: '#fff',
            pointBorderWidth: 2,
            id_kasir: item.id_kasir,
        }));
    }

    function getUnusedKasirs(keyword = '') {
        const usedIds = kasirChart.data.datasets.map(ds => ds.id_kasir);
        const kw = keyword.toLowerCase();
        return availableKasirs.filter(k =>
            !usedIds.includes(k.id_pengguna) &&
            k.nama_pengguna.toLowerCase().includes(kw)
        );
    }

    function renderLegends() {
        const datasets = kasirChart.data.datasets;
        let headerHTML = '';
        let boxesHTML = '';

        datasets.forEach((item) => {
            headerHTML += `
                <div class="d-flex align-items-center" style="gap:4px;">
                    <div class="rounded-circle" style="width: 10px; height: 10px; background-color: ${item.borderColor};"></div>
                    <span class="text-dark">${item.label}</span>
                </div>
            `;
        });

        datasets.forEach((item, index) => {
            const isHidden = !kasirChart.isDatasetVisible(index);
            const boxClass = isHidden ? 'hidden-dataset' : '';

            boxesHTML += `
                <div class="legend-box ${boxClass} border rounded-3 p-3 bg-white flex-fill" style="min-width: 170px; border-top-color: ${item.borderColor} !important;">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="font-weight-bold text-dark" style="font-size: 13px; cursor: pointer;" onclick="toggleDataset(${index})">${item.label}</span>
                        <i class="fas fa-times text-muted" style="font-size: 13px; cursor: pointer;" onclick="removeDataset(${index})" title="Hapus Pembanding"></i>
                    </div>
                </div>
            `;
        });

        if (datasets.length < 5) {
            boxesHTML += buildAddCardHTML();
        } else {
            addCardOpen = false;
        }

        $('#kasirChart-header-legend').html(headerHTML);
        $('#kasir-legend-boxes').html(boxesHTML);

        if (addCardOpen) {
            $('#add-search-input').trigger('focus');
        }
    }

    function buildAddCardHTML() {
        if (!addCardOpen) {
            return `
                <div class="legend-box add-legend-box border rounded-3 flex-fill" style="min-width: 170px;" onclick="openAddCard()">
                    <span class="add-icon-label"><i class="fas fa-plus"></i> Tambah Pembanding</span>
                </div>
            `;
        }

        const items = getUnusedKasirs('');
        return `
            <div class="legend-box add-legend-box search-mode border rounded-3 flex-fill" style="min-width: 220px;">
                <div class="input-group input-group-sm">
                    <div class="input-group-prepend">
                        <span class="input-group-text bg-white border-right-0"><i class="fas fa-search text-muted"></i></span>
                    </div>
                    <input type="text" id="add-search-input" class="form-control border-left-0 shadow-none" placeholder="Cari nama kasir..." autocomplete="off">
                </div>
                <div id="add-search-results" class="search-results list-group list-group-flush">
                    ${renderSearchResultsHTML(items)}
                </div>
            </div>
        `;
    }

    function renderSearchResultsHTML(items) {
        if (items.length === 0) {
            return `<div class="list-group-item border-0 text-muted text-center py-3" style="font-size: 12px;">Data tidak ditemukan</div>`;
        }
        return items.map(k =>
            `<div class="list-group-item autocomplete-item border-0 border-bottom" data-id="${k.id_pengguna}">${k.nama_pengguna}</div>`
        ).join('');
    }

    window.openAddCard = function () {
        addCardOpen = true;
        renderLegends();
    };

    window.toggleDataset = function(index) {
        const isVisible = kasirChart.isDatasetVisible(index);
        if (isVisible) kasirChart.hide(index); else kasirChart.show(index);
        renderLegends();
    }

    window.removeDataset = function(index) {
        kasirChart.data.datasets.splice(index, 1);
        kasirChart.update();
        renderLegends();
    }

    function addKasirToChart(kasirId) {
        let formData = $('#filter-form').serialize();
        let colorIndex = kasirChart.data.datasets.length;

        $.get(window.location.href, formData + '&add_kasir_id=' + kasirId + '&color_index=' + colorIndex, function(res) {
            if (res.success && res.dataset) {
                let formatted = formatDatasets([res.dataset])[0];
                kasirChart.data.datasets.push(formatted);
                addCardOpen = false;
                kasirChart.update();
                renderLegends();
                syncTableWithChart();
            } else {
                addCardOpen = false;
                renderLegends();
            }
        });
    }

    function getActiveKasirIds() {
        return kasirChart.data.datasets
            .map(ds => ds.id_kasir)
            .filter(id => id !== null && id !== undefined);
    }

    // Tabel harus selalu sinkron dengan kasir yang aktif di chart (tambah/hapus/toggle tidak memicu ini,
    // hanya tambah & submit filter yang memicu re-fetch tabel dari server)
    function syncTableWithChart() {
        let formData = $('#filter-form').serialize();
        let kasirIds = getActiveKasirIds();

        $.get(window.location.href, formData + '&kasir_ids=' + kasirIds.join(','), function(res) {
            if (res.success) {
                $('#kasir-table-body').html(res.html);
                $('#footer-info').text('Menampilkan 1 hingga ' + res.total + ' dari ' + res.total + ' entri');
            }
        });
    }

    $(document).ready(function() {
        $('.custom-input').css('pointer-events', 'auto').removeAttr('readonly');

        const ctx = document.getElementById('kasirChart').getContext('2d');
        kasirChart = new Chart(ctx, {
            type: 'line',
            data: { labels: initialLabels, datasets: formatDatasets(initialDatasets) },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function (ctx) {
                                return ctx.dataset.label + ': Rp ' + ctx.parsed.y.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                            }
                        }
                    }
                },
                scales: {
                    x: { grid: { display: false }, ticks: { color: '#8a949f', font: { size: 11 } } },
                    y: {
                        beginAtZero: true,
                        grid: { color: '#f0f0f5', drawBorder: false },
                        ticks: {
                            color: '#8a949f',
                            font: { size: 11 },
                            callback: function(value) {
                                if (value >= 1000000) return (value / 1000000).toFixed(2) + ' jt';
                                if (value >= 1000) return (value / 1000).toFixed(0) + ' rb';
                                return value;
                            }
                        }
                    }
                }
            }
        });

        renderLegends();

        $(document).on('click', '.add-legend-box .autocomplete-item', function() {
            addKasirToChart($(this).data('id'));
        });

        $(document).on('input', '#add-search-input', function() {
            const items = getUnusedKasirs($(this).val());
            $('#add-search-results').html(renderSearchResultsHTML(items));
        });

        $(document).on('click', function(e) {
            if (addCardOpen && !$(e.target).closest('.add-legend-box').length) {
                addCardOpen = false;
                renderLegends();
            }
        });

        $('#filter-form').on('submit', function(e) {
            e.preventDefault();

            let form = $(this);
            let btn = form.find('button[type="submit"]');
            let origBtn = btn.html();

            btn.html('<i class="fas fa-spinner fa-spin mr-2"></i> Memuat...').prop('disabled', true);

            // Filter (periode/tanggal/nama_kasir) berubah -> chart di-reset ke default Top-4
            // dari pool baru; kasir_ids TIDAK dikirim di sini biar backend hitung ulang default-nya.
            $.get(form.attr('action') || window.location.href, form.serialize(), function(res) {
                if (res.success) {
                    kasirChart.data.labels = res.labels;
                    kasirChart.data.datasets = formatDatasets(res.datasets);

                    if (res.availableKasirs) {
                        availableKasirs = res.availableKasirs;
                    }

                    $('#kasir-table-body').html(res.html);
                    $('#footer-info').text('Menampilkan 1 hingga ' + res.total + ' dari ' + res.total + ' entri');

                    addCardOpen = false;
                    kasirChart.update();
                    renderLegends();
                }

                btn.html(origBtn).prop('disabled', false);
            }).fail(function() {
                alert('Terjadi kesalahan saat memuat data filter kasir.');
                btn.html(origBtn).prop('disabled', false);
            });
        });
    });
</script>
@stop