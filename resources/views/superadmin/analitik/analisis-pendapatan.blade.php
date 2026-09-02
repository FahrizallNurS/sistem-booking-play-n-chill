@extends('adminlte::page')
@include('partials.sidebar-superadmin')

@section('title', 'Analisis Pendapatan - Play N Chill')
@section('plugins.Chartjs', true)

@section('content_header')
<div class="container-fluid py-2">
    <div class="row align-items-center">
        <div class="col-sm-12">
            <h1 class="font-weight-bold text-dark m-0" style="font-size: 28px; letter-spacing: -0.5px;">Analisis Pendapatan</h1>
            <p class="text-muted mb-0" style="font-size: 14px;">Analisis performa pendapatan berdasarkan kategori bisnis Play N Chill (Booking &amp; F&amp;B).</p>
        </div>
    </div>
</div>
@stop

@section('content')
<div class="container-fluid pb-4">

    {{-- BARIS 1: FILTER --}}
    <div class="row mb-4">
        <div class="col-12">
            <x-filter-card id="filter-form" :action="url()->current()">
                <x-filter-select name="periode" label="PERIODE" :options="['harian' => 'Harian', 'mingguan' => 'Mingguan', 'bulanan' => 'Bulanan']" width="col-md-2 col-sm-6" default="bulanan"/>
                <x-filter-dynamic-date width="col-md-4 col-sm-6" />
                <x-filter-select name="kategori" label="KATEGORI" :options="$kategoriOptions" width="col-md-3 col-sm-6"/>
            </x-filter-card>
        </div>
    </div>

    {{-- BARIS 2: GRAFIK & TAMBAH PEMBANDING --}}
    <div class="row mb-2">
        <div class="col-12">
            <x-card>
                <x-slot name="title">Grafik Pendapatan per Kategori</x-slot>

                <x-slot name="header">
                    <div id="chart-header-legend" class="chart-legend-wrap d-none d-md-flex align-items-center" style="font-size: 12px;"></div>
                </x-slot>

                <div class="chart-container mt-3" style="position: relative; height:380px; width:100%;">
                    <canvas id="revenueAnalysisChart"></canvas>
                </div>

                <div id="custom-legend-boxes" class="d-flex flex-wrap gap-3 mt-4"></div>
            </x-card>
        </div>
    </div>

    {{-- BARIS 3: TABEL DATA --}}
    <div class="row mt-4">
        <div class="col-12">
            <x-card class="border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead style="background-color: #faf5ff;">
                            <tr>
                                <th class="py-3 px-4 border-0 text-muted" style="font-size: 11px;">NO</th>
                                <th class="py-3 border-0 text-muted" style="font-size: 11px;">KATEGORI</th>
                                <th class="py-3 border-0 text-muted" style="font-size: 11px;">PRODUK</th>
                                <th class="py-3 border-0 text-muted text-center" style="font-size: 11px;">JML TRANSAKSI</th>
                                <th class="py-3 border-0 text-muted text-right" style="font-size: 11px;">TOTAL PENDAPATAN</th>
                                <th class="py-3 border-0 text-muted text-center" style="font-size: 11px;">KONTRIBUSI %</th>
                                <th class="py-3 border-0 text-muted text-right" style="font-size: 11px;">RATA-RATA/TRX</th>
                            </tr>
                        </thead>
                        <tbody id="table-body">
                            @php $startNum = ($tableData->currentPage() - 1) * $tableData->perPage() + 1; @endphp
                            @foreach($tableData as $index => $row)
                                <tr>
                                    <td class="px-4 text-muted py-3" style="font-size: 13px;">{{ $startNum + $index }}</td>
                                    <td class="font-weight-bold text-purple py-3" style="font-size: 13px;">{{ $row['kategori'] }}</td>
                                    <td class="text-muted py-3" style="font-size: 13px;">{{ $row['produk'] }}</td>
                                    <td class="text-center py-3" style="font-size: 13px;">{{ $row['trx'] }}</td>
                                    <td class="text-right font-weight-bold py-3" style="font-size: 13px;">Rp {{ number_format($row['total'], 0, ',', '.') }}</td>
                                    <td class="text-center py-3" style="width: 150px;">
                                        <div class="progress" style="height:6px;">
                                            <div class="progress-bar" style="width:{{ $row['persen'] }}%; background-color: {{ $row['produk'] === 'Booking' ? '#6f42c1' : '#f97316' }};"></div>
                                        </div>
                                        <span class="font-weight-bold" style="font-size: 12px;">{{ $row['persen'] }}%</span>
                                    </td>
                                    <td class="text-right text-muted py-3" style="font-size: 13px;">Rp {{ number_format($row['rata'], 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between align-items-center p-3 bg-white border-top">
                    <span id="pagination-info" class="text-muted" style="font-size: 13px;">
                        Menampilkan {{ $tableData->firstItem() ?? 0 }} hingga {{ $tableData->lastItem() ?? 0 }} dari {{ $tableData->total() }} entri
                    </span>
                    <div id="pagination-links">
                        {{ $tableData->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </x-card>
        </div>
    </div>

</div>
@stop

@section('css')
<style>
.legend-box { transition: all 0.2s ease; user-select: none; border-top: 4px solid transparent; }
.legend-box:hover { transform: translateY(-2px); box-shadow: 0 4px 6px rgba(0,0,0,0.1) !important; }
.legend-box.hidden-dataset { opacity: 0.5; background-color: #f8f9fa !important; border-top-color: #d1d5db !important; }
.autocomplete-item { cursor: pointer; font-size: 13px; padding: 10px 15px; font-weight: 500; transition: 0.2s; }
.autocomplete-item:hover { background-color: #faf5ff; color: #6f42c1; padding-left: 20px; }
.text-purple { color: #6f42c1; }

.chart-legend-wrap {
    flex-wrap: wrap;
    justify-content: flex-end;
    row-gap: 6px;
    column-gap: 12px;
    max-width: 100%;
}

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
.add-legend-box:hover {
    border-color: #6f42c1 !important;
    background-color: #faf5ff !important;
}
.add-legend-box .add-icon-label {
    color: #9ca3af;
    font-weight: 600;
    font-size: 13px;
    display: flex;
    align-items: center;
    gap: 6px;
}
.add-legend-box:hover .add-icon-label { color: #6f42c1; }
.add-legend-box.search-mode {
    cursor: default;
    border-style: solid !important;
    background-color: #fff !important;
    display: block;
    padding: 10px;
}
.add-legend-box .search-results {
    max-height: 160px;
    overflow-y: auto;
    margin-top: 8px;
}

.btn-group-pagination {
    display: inline-flex;
    gap: 0;
}
.btn-group-pagination .btn,
.btn-group-pagination .btn.disabled {
    border-radius: 0 !important;
    margin-left: -1px !important;
    position: relative;
}
.btn-group-pagination .btn:hover,
.btn-group-pagination .btn:focus {
    z-index: 1;
}
.btn-group-pagination .btn:first-child {
    margin-left: 0 !important;
    border-top-left-radius: .2rem !important;
    border-bottom-left-radius: .2rem !important;
}
.btn-group-pagination .btn:last-child {
    border-top-right-radius: .2rem !important;
    border-bottom-right-radius: .2rem !important;
}
</style>
@stop

@section('js')
<script>
    // allKategori: daftar kategori yang tersedia sbg pembanding, MENGIKUTI filter aktif (kategori + rentang waktu).
    // Di-reassign (bukan const) karena akan di-refresh dari response AJAX tiap kali filter disubmit,
    // tanpa reload halaman.
    let allKategori = @json($allKategori);
    const initialLabels = @json($chartLabels);
    const initialDatasets = @json($chartDatasets);
    const initialSuggestedMax = {{ $suggestedMax }};

    let revenueChart;
    let addCardOpen = false;

    function formatDatasets(rawDatasets) {
        return rawDatasets.map((item) => ({
            label: item.label,
            data: item.data,
            borderColor: item.color,
            backgroundColor: 'transparent',
            borderWidth: 2.5,
            tension: 0.4,
            pointRadius: 0,
            pointHoverRadius: 6,
            pointHitRadius: 10,
            kategori_key: item.kategori_key
        }));
    }

    function getUnusedKategori(keyword = '') {
        const usedKeys = revenueChart.data.datasets.map(ds => ds.kategori_key);
        const kw = keyword.toLowerCase();
        return allKategori.filter(k =>
            !usedKeys.includes(k.kategori_key) &&
            k.label.toLowerCase().includes(kw)
        );
    }

    function renderLegends() {
        const datasets = revenueChart.data.datasets;
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
            const isHidden = !revenueChart.isDatasetVisible(index);
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

        $('#chart-header-legend').html(headerHTML);
        $('#custom-legend-boxes').html(boxesHTML);

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

        const items = getUnusedKategori('');
        return `
            <div class="legend-box add-legend-box search-mode border rounded-3 flex-fill" style="min-width: 220px;">
                <div class="input-group input-group-sm">
                    <div class="input-group-prepend">
                        <span class="input-group-text bg-white border-right-0"><i class="fas fa-search text-muted"></i></span>
                    </div>
                    <input type="text" id="add-search-input" class="form-control border-left-0 shadow-none" placeholder="Cari kategori..." autocomplete="off">
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
            `<div class="list-group-item autocomplete-item border-0 border-bottom" data-key="${k.kategori_key}">${k.label}</div>`
        ).join('');
    }

    window.openAddCard = function () {
        addCardOpen = true;
        renderLegends();
    };

    window.toggleDataset = function(index) {
        const isVisible = revenueChart.isDatasetVisible(index);
        if (isVisible) revenueChart.hide(index); else revenueChart.show(index);
        renderLegends();
    }

    window.removeDataset = function(index) {
        revenueChart.data.datasets.splice(index, 1);
        revenueChart.update();
        renderLegends();
    }

    function addKategoriToChart(kategoriKey) {
        let form = $('#filter-form').is('form') ? $('#filter-form') : $('#filter-form form');
        let formData = form.serialize();
        let colorIndex = revenueChart.data.datasets.length;

        $.get(window.location.href, formData + '&add_kategori_key=' + encodeURIComponent(kategoriKey) + '&color_index=' + colorIndex, function(res) {
            if (res.success && res.dataset) {
                let formatted = formatDatasets([res.dataset])[0];
                revenueChart.data.datasets.push(formatted);
                if (res.suggestedMax) revenueChart.options.scales.y.suggestedMax = res.suggestedMax;
                addCardOpen = false;
                revenueChart.update();
                renderLegends();
            } else {
                // Kategori sudah tidak match filter aktif (mis. filter baru saja diganti) -> batalkan diam-diam,
                // renderLegends() akan otomatis menampilkan opsi yang sudah ter-update.
                addCardOpen = false;
                renderLegends();
            }
        });
    }

    function getActiveKategoriKeys() {
        return revenueChart.data.datasets
            .map(ds => ds.kategori_key)
            .filter(key => key !== null && key !== undefined);
    }

    $(document).ready(function() {
        const ctx = document.getElementById('revenueAnalysisChart').getContext('2d');
        revenueChart = new Chart(ctx, {
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
                        suggestedMax: initialSuggestedMax,
                        grid: { color: '#f0f0f5', drawBorder: false },
                        ticks: {
                            color: '#8a949f',
                            font: { size: 11 },
                            callback: function(value) {
                                if (value >= 1000000) {
                                    return (value / 1000000).toFixed(2) + ' jt';
                                } else if (value >= 1000) {
                                    return (value / 1000).toFixed(0) + ' rb';
                                }
                                return value.toFixed(2);
                            }
                        }
                    }
                }
            }
        });

        renderLegends();

        $(document).on('click', '.add-legend-box .autocomplete-item', function() {
            addKategoriToChart($(this).data('key'));
        });

        $(document).on('input', '#add-search-input', function() {
            const items = getUnusedKategori($(this).val());
            $('#add-search-results').html(renderSearchResultsHTML(items));
        });

        $(document).on('click', function(e) {
            if (addCardOpen && !$(e.target).closest('.add-legend-box').length) {
                addCardOpen = false;
                renderLegends();
            }
        });

        $(document).on('submit', '#filter-form, #filter-form form', function(e) {
            e.preventDefault();

            let form = $(this).is('form') ? $(this) : $(this).find('form');
            if (form.length === 0) form = $(this);

            let btn = form.find('button[type="submit"]');
            let origBtn = btn.html();

            btn.html('<i class="fas fa-spinner fa-spin"></i>').prop('disabled', true);

            let kategoriKeys = getActiveKategoriKeys();
            let requestData = form.serialize() + '&kategori_keys=' + kategoriKeys.map(encodeURIComponent).join(',');

            $.get(form.attr('action') || window.location.href, requestData, function(res) {
                if (res.success) {
                    revenueChart.data.labels = res.labels;
                    revenueChart.data.datasets = formatDatasets(res.datasets);
                    revenueChart.options.scales.y.suggestedMax = res.suggestedMax;

                    // Server sudah menyesuaikan opsi pembanding dengan filter kategori + rentang waktu terbaru.
                    if (res.allKategori) {
                        allKategori = res.allKategori;
                    }

                    // Tabel ikut di-refresh (reset ke halaman 1) supaya sesuai filter periode/kategori terbaru --
                    // sebelumnya cuma chart yang update, tabel diam.
                    if (res.table) {
                        $('#table-body').html(res.table.html);
                        $('#pagination-links').html(res.table.pagination);
                        $('#pagination-info').text(res.table.info);
                    }

                    addCardOpen = false;
                    revenueChart.update();
                    renderLegends();
                }
                btn.html(origBtn).prop('disabled', false);
            });
        });

        $(document).on('click', '#pagination-links a', function(e) {
            e.preventDefault();
            let url = $(this).attr('href');

            let form = $('#filter-form').is('form') ? $('#filter-form') : $('#filter-form form');
            let formData = form.serialize();

            let kategoriKeys = getActiveKategoriKeys();
            let requestData = formData + '&kategori_keys=' + kategoriKeys.map(encodeURIComponent).join(',');
            url += (url.includes('?') ? '&' : '?') + requestData;

            $.get(url, function(res) {
                if (res.html) {
                    $('#table-body').html(res.html);
                    $('#pagination-links').html(res.pagination);
                    $('#pagination-info').text(res.info);
                }
            });
        });
    });
</script>
@stop