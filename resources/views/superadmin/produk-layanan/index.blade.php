@extends('adminlte::page')
@include('partials.sidebar-superadmin')

@section('title', 'Produk Layanan - Play N Chill')
@section('plugins.Chartjs', true)

@section('content_header')
<div class="container-fluid py-2">
    <div class="row align-items-center">
        <div class="col-sm-12">
            <h1 class="font-weight-bold text-dark m-0" style="font-size: 28px; letter-spacing: -0.5px;">Produk Layanan</h1>
            <p class="text-muted mb-0" style="font-size: 14px;">Analisis performa pendapatan berdasarkan jenis layanan Play N Chill.</p>
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
                <x-filter-select name="kategori" label="KATEGORI" :options="$kategoriOptions" width="col-md-2 col-sm-6"/>
                <x-filter-select name="sub_kategori" label="SUB KATEGORI" :options="$subKategoriOptions" width="col-md-2 col-sm-6"/>
            </x-filter-card>
        </div>
    </div>

    {{-- BARIS 2: GRAFIK & TAMBAH PEMBANDING --}}
    <div class="row mb-2">
        <div class="col-12">
            <x-card>
                {{-- FIX: Judul harus teks murni agar komponen x-card tidak rusak --}}
                <x-slot name="title">Grafik Penjualan Layanan</x-slot>
                
                <x-slot name="header">
                    <div id="chart-header-legend" class="chart-legend-wrap d-none d-md-flex align-items-center" style="font-size: 12px;"></div>
                </x-slot>

                {{-- Canvas Area --}}
                <div class="chart-container mt-3" style="position: relative; height:380px; width:100%;">
                    <canvas id="serviceAnalysisChart"></canvas>
                </div>

                {{-- Penampung Kotak Pembanding (Legend Dinamis) --}}
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
                                <th class="py-3 border-0 text-muted" style="font-size: 11px;">NAMA PAKET</th>
                                <th class="py-3 border-0 text-muted" style="font-size: 11px;">KATEGORI</th>
                                <th class="py-3 border-0 text-muted" style="font-size: 11px;">SUB KATEGORI</th>
                                <th class="py-3 border-0 text-muted" style="font-size: 11px;">HARGA JUAL</th>
                                <th class="py-3 border-0 text-muted text-center" style="font-size: 11px;">SATUAN JAM</th>
                                <th class="py-3 border-0 text-muted" style="font-size: 11px;">SKU</th>
                            </tr>
                        </thead>
                        <tbody id="table-body">
                            @php $startNum = ($tableData->currentPage() - 1) * $tableData->perPage() + 1; @endphp
                            @foreach($tableData as $index => $row)
                                <tr>
                                    <td class="px-4 text-muted py-3" style="font-size: 13px;">{{ $startNum + $index }}</td>
                                    <td class="text-dark py-3 font-weight-bold" style="font-size: 13px;">{{ $row->paket }}</td>
                                    <td class="text-muted py-3" style="font-size: 13px;">{{ $row->kategori }}</td>
                                    <td class="text-muted py-3" style="font-size: 13px;">{{ $row->sub }}</td>
                                    <td class="text-muted py-3" style="font-size: 13px;">Rp {{ number_format($row->harga, 0, ',', '.') }}</td>
                                    <td class="text-muted py-3 text-center" style="font-size: 13px;">{{ $row->jam }}</td>
                                    <td class="text-muted py-3" style="font-size: 13px;">{{ $row->sku }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination Footer --}}
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

/* Card Tambah Pembanding */
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
</style>
@stop

@section('js')
<script>
    // Diubah jadi let, karena akan ditimpa JSON dari response saat submit filter
    let allPakets = @json($allPakets);
    const initialLabels = @json($chartLabels);
    const initialDatasets = @json($chartDatasets);
    const initialSuggestedMax = {{ $suggestedMax }};

    let serviceChart;
    let addCardOpen = false; // status card "+" : idle atau lagi mode search

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
            paket_id: item.id_paket
        }));
    }

    function getUnusedPakets(keyword = '') {
        const usedIds = serviceChart.data.datasets.map(ds => ds.paket_id);
        const kw = keyword.toLowerCase();
        return allPakets.filter(p =>
            !usedIds.includes(p.id_paket) &&
            p.nama_paket.toLowerCase().includes(kw)
        );
    }

    function renderLegends() {
        const datasets = serviceChart.data.datasets;
        let headerHTML = '';
        let boxesHTML = '';

        // 1. Legend ringkas di header chart
        datasets.forEach((item) => {
            headerHTML += `
                <div class="d-flex align-items-center" style="gap:4px;">
                    <div class="rounded-circle" style="width: 10px; height: 10px; background-color: ${item.borderColor};"></div>
                    <span class="text-dark">${item.label}</span>
                </div>
            `;
        });

        // 2. Card pembanding aktif
        datasets.forEach((item, index) => {
            const isHidden = !serviceChart.isDatasetVisible(index);
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

        // 3. Card "+" (hanya kalau dataset < 5)
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

        const items = getUnusedPakets('');
        return `
            <div class="legend-box add-legend-box search-mode border rounded-3 flex-fill" style="min-width: 220px;">
                <div class="input-group input-group-sm">
                    <div class="input-group-prepend">
                        <span class="input-group-text bg-white border-right-0"><i class="fas fa-search text-muted"></i></span>
                    </div>
                    <input type="text" id="add-search-input" class="form-control border-left-0 shadow-none" placeholder="Cari nama paket..." autocomplete="off">
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
        return items.map(p =>
            `<div class="list-group-item autocomplete-item border-0 border-bottom" data-id="${p.id_paket}">${p.nama_paket}</div>`
        ).join('');
    }

    window.openAddCard = function () {
        addCardOpen = true;
        renderLegends();
    };

    window.toggleDataset = function(index) {
        const isVisible = serviceChart.isDatasetVisible(index);
        if (isVisible) serviceChart.hide(index); else serviceChart.show(index);
        renderLegends();
    }

    window.removeDataset = function(index) {
        serviceChart.data.datasets.splice(index, 1);
        serviceChart.update();
        renderLegends();
    }

    function addPaketToChart(paketId) {
        let formData = $('#filter-form form').serialize();
        let colorIndex = serviceChart.data.datasets.length;

        $.get(window.location.href, formData + '&add_paket_id=' + paketId + '&color_index=' + colorIndex, function(res) {
            if (res.success && res.dataset) {
                let formatted = formatDatasets([res.dataset])[0];
                serviceChart.data.datasets.push(formatted);
                if (res.suggestedMax) serviceChart.options.scales.y.suggestedMax = res.suggestedMax;
                addCardOpen = false;
                serviceChart.update();
                renderLegends();
            } else {
                // Berarti Paket sudah tidak relevan dengan filter form
                addCardOpen = false;
                renderLegends();
            }
        });
    }

    function getActivePaketIds() {
        return serviceChart.data.datasets
            .map(ds => ds.paket_id)
            .filter(id => id !== null && id !== undefined);
    }

    $(document).ready(function() {
        // 1. INISIASI CHART
        const ctx = document.getElementById('serviceAnalysisChart').getContext('2d');
        serviceChart = new Chart(ctx, {
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

        // 2. EVENT: Klik hasil pencarian card "+"
        $(document).on('click', '.add-legend-box .autocomplete-item', function() {
            addPaketToChart($(this).data('id'));
        });

        // 3. EVENT: Ketik di search box card "+"
        $(document).on('input', '#add-search-input', function() {
            const items = getUnusedPakets($(this).val());
            $('#add-search-results').html(renderSearchResultsHTML(items));
        });

        // 4. EVENT: Klik di luar card "+" saat mode search -> tutup balik ke idle
        $(document).on('click', function(e) {
            if (addCardOpen && !$(e.target).closest('.add-legend-box').length) {
                addCardOpen = false;
                renderLegends();
            }
        });

        // 5. AJAX FILTER & PAGINATION
        $(document).on('submit', '#filter-form, #filter-form form', function(e) {
            e.preventDefault();
            
            // Pastikan kita menangkap elemen <form> yang benar
            let form = $(this).is('form') ? $(this) : $(this).find('form');
            if(form.length === 0) form = $(this); 
            
            let btn = form.find('button[type="submit"]');
            let origBtn = btn.html();

            btn.html('<i class="fas fa-spinner fa-spin"></i>').prop('disabled', true);

            let paketIds = getActivePaketIds();
            let requestData = form.serialize() + '&paket_ids=' + paketIds.join(',');

            $.get(form.attr('action') || window.location.href, requestData, function(res) {
                if (res.success) {
                    serviceChart.data.labels = res.labels;
                    serviceChart.data.datasets = formatDatasets(res.datasets);
                    serviceChart.options.scales.y.suggestedMax = res.suggestedMax;
                    
                    // PENTING: Update array Paket yang tersedia untuk Dropdown search
                    if (res.allPakets) {
                        allPakets = res.allPakets;
                    }
                    
                    // PENTING: Update Tabel data
                    if (res.table) {
                        $('#table-body').html(res.table.html);
                        $('#pagination-links').html(res.table.pagination);
                        $('#pagination-info').text(res.table.info);
                    }

                    addCardOpen = false;
                    serviceChart.update();
                    renderLegends();
                }
                btn.html(origBtn).prop('disabled', false);
            });
        });

        // Menyesuaikan penarikan data form untuk Pagination
        $(document).on('click', '#pagination-links a', function(e) {
            e.preventDefault();
            let url = $(this).attr('href');
            
            let form = $('#filter-form').is('form') ? $('#filter-form') : $('#filter-form form');
            let formData = form.serialize();
            
            let paketIds = getActivePaketIds();
            let requestData = formData + '&paket_ids=' + paketIds.join(',');
            url += (url.includes('?') ? '&' : '?') + requestData;

            $.get(url, function(res) {
                if(res.html) {
                    $('#table-body').html(res.html);
                    $('#pagination-links').html(res.pagination);
                    $('#pagination-info').text(res.info);
                }
            });
        });
    });
</script>
@stop