@extends('adminlte::page')
@include('partials.sidebar-superadmin')

@section('title', 'Produk F&B - Play N Chill')

@section('plugins.Chartjs', true)

@section('content_header')
<div class="container-fluid py-2">
    <div class="row align-items-center">
        <div class="col-sm-6">
            <h1 class="font-weight-bold text-dark m-0" style="font-size: 28px; letter-spacing: -0.5px;">Produk F&B</h1>
            <p class="text-muted mb-0" style="font-size: 14px;">Analisis performa pendapatan berdasarkan jenis produk F&B Play N Chill.</p>
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
                <x-filter-select name="periode" label="PERIODE" :options="['harian' => 'Harian', 'mingguan' => 'Mingguan', 'bulanan' => 'Bulanan']" width="col-md-2 col-sm-6" default="harian"/>
                
                <x-filter-dynamic-date width="col-md-4 col-sm-6" />
                
                <x-filter-select name="kategori" label="KATEGORI" :options="$kategoriOptions" width="col-md-3 col-sm-6"/>
                <x-filter-select name="sub_kategori" label="SUB KATEGORI" :options="$subKategoriOptions" width="col-md-3 col-sm-6"/>    
            </x-filter-card>
        </div>
    </div>

    {{-- BARIS 2: GRAFIK & TAMBAH PEMBANDING (ADAPTASI DARI LAYANAN) --}}
    <div class="row mb-2">
        <div class="col-12">
            <x-card>
                <x-slot name="title">
                    <span class="font-weight-bold text-purple" style="font-size: 1.1rem;">Grafik Penjualan Produk F&B</span>
                </x-slot>

                <x-slot name="header">
                    <div id="chart-header-legend" class="chart-legend-wrap d-none d-md-flex align-items-center" style="font-size: 12px;"></div>
                </x-slot>

                {{-- Canvas Area Manual (Menggantikan x-chart agar legend interaktif berjalan) --}}
                <div class="chart-container mt-3" style="position: relative; height:400px; width:100%;">
                    <canvas id="fnbAnalysisChart"></canvas>
                </div>

                {{-- Penampung Kotak Pembanding (Legend Dinamis) --}}
                <div id="custom-legend-boxes" class="d-flex flex-wrap gap-3 mt-4"></div>
            </x-card>
        </div>
    </div>

    {{-- BARIS 3: TABEL DATA PRODUK (DIKEMBALIKAN KE KODE ASLIMU) --}}
    <div class="row mt-4">
        <div class="col-12">
            <x-table>
                <x-slot name="head">
                    <tr style="background-color: #faf5ff;">
                        <th class="py-3 px-4 border-0 text-muted" style="font-size: 11px;">NO</th>
                        <th class="py-3 border-0 text-muted" style="font-size: 11px;">FOTO</th>
                        <th class="py-3 border-0 text-muted" style="font-size: 11px;">NAMA PRODUK</th>
                        <th class="py-3 border-0 text-muted" style="font-size: 11px;">KATEGORI PRODUK</th>
                        <th class="py-3 border-0 text-muted" style="font-size: 11px;">SUB.KATEGORI PRODUK</th>
                        <th class="py-3 border-0 text-muted text-right" style="font-size: 11px;">HARGA BELI</th>
                        <th class="py-3 border-0 text-muted text-right" style="font-size: 11px;">HARGA JUAL</th>
                        <th class="py-3 border-0 text-muted" style="font-size: 11px;">SKU</th>
                        <th class="py-3 border-0 text-muted text-center" style="font-size: 11px;">STOCK</th>
                        <th class="py-3 border-0 text-muted text-center" style="font-size: 11px;">STATUS</th>
                    </tr>
                </x-slot>

                <tbody id="fnb-table-body">
                    @if(isset($tableData) && count($tableData) > 0)
                        @foreach($tableData as $index => $row)
                            <tr>
                                <td class="px-4 text-muted py-2" style="font-size: 13px;">{{ $index + 1 }}</td>
                                <td class="py-2">
                                    @if (!empty($row['foto']))
                                        <img src="{{ $row['foto'] }}"
                                             alt="{{ $row['nama'] }}"
                                             class="rounded"
                                             style="width: 44px; height: 44px; object-fit: cover; border: 1px solid #e5e7eb;"
                                             onerror="this.onerror=null; this.src='{{ asset('images/logo_dumb.png') }}';">
                                    @else
                                        <div class="d-flex align-items-center justify-content-center rounded bg-light text-muted" style="width: 44px; height: 44px; border: 1px solid #e5e7eb;">
                                            <i class="fas fa-image"></i>
                                        </div>
                                    @endif
                                </td>

                                <td class="text-dark font-weight-bold py-2" style="font-size: 13px;">{{ $row['nama'] }}</td>
                                <td class="text-muted py-2" style="font-size: 13px;">{{ $row['kategori'] }}</td>
                                <td class="text-muted py-2" style="font-size: 13px;">{{ $row['sub_kategori'] }}</td>
                                <td class="text-muted text-right py-2" style="font-size: 13px;">Rp. {{ number_format($row['harga_beli'], 2, ',', '.') }}</td>
                                <td class="text-muted text-right py-2" style="font-size: 13px;">Rp. {{ number_format($row['harga_jual'], 2, ',', '.') }}</td>
                                <td class="text-muted py-2" style="font-size: 13px;">{{ $row['sku'] }}</td>
                                <td class="text-muted text-center py-2" style="font-size: 13px;">{{ $row['stock'] }}</td>
                                <td class="text-center py-2">
                                    <x-badge :variant="$statusBadgeVariant[$row['status']] ?? 'secondary'" :label="$row['status']" />
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr><td colspan="10" class="text-center py-4 text-muted">Data produk tidak ditemukan.</td></tr>
                    @endif
                </tbody>

                <x-slot name="footer">
                    <span id="footer-info" class="text-muted" style="font-size: 13px;">Menampilkan 1 hingga {{ count($tableData ?? []) }} dari {{ count($tableData ?? []) }} entri</span>
                    <div class="btn-group">
                        <button class="btn btn-sm btn-light border text-muted">Sebelumnya</button>
                        <button class="btn btn-sm btn-primary" style="background-color: #6f42c1; border-color: #6f42c1;">1</button>
                        <button class="btn btn-sm btn-light border text-muted">Selanjutnya</button>
                    </div>
                </x-slot>   
            </x-table>
        </div>
    </div>

</div>
@stop

@section('css')
<style>
.text-purple { color: #6f42c1; }

label {
    font-size: 11px !important;
    font-weight: 600 !important;
    color: #4a5568;
    margin-bottom: 4px;
}

/* CSS Khusus Tambah Pembanding */
.legend-box { transition: all 0.2s ease; user-select: none; border-top: 4px solid transparent; }
.legend-box:hover { transform: translateY(-2px); box-shadow: 0 4px 6px rgba(0,0,0,0.1) !important; }
.legend-box.hidden-dataset { opacity: 0.5; background-color: #f8f9fa !important; border-top-color: #d1d5db !important; }
.autocomplete-item { cursor: pointer; font-size: 13px; padding: 10px 15px; font-weight: 500; transition: 0.2s; }
.autocomplete-item:hover { background-color: #faf5ff; color: #6f42c1; padding-left: 20px; }
.chart-legend-wrap { flex-wrap: wrap; justify-content: flex-end; row-gap: 6px; column-gap: 12px; max-width: 100%; }

.add-legend-box { border: 2px dashed #d1d5db !important; background-color: #fafafa !important; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.2s ease; min-height: 62px; }
.add-legend-box:hover { border-color: #6f42c1 !important; background-color: #faf5ff !important; }
.add-legend-box .add-icon-label { color: #9ca3af; font-weight: 600; font-size: 13px; display: flex; align-items: center; gap: 6px; }
.add-legend-box:hover .add-icon-label { color: #6f42c1; }
.add-legend-box.search-mode { cursor: default; border-style: solid !important; background-color: #fff !important; display: block; padding: 10px; }
.add-legend-box .search-results { max-height: 160px; overflow-y: auto; margin-top: 8px; }
</style>
@stop

@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/locale/id.min.js"></script>

<script>
    let allProduks = @json($allProduks ?? []); 
    const initialLabels = @json($chartLabels ?? []);
    const initialDatasets = @json($chartDatasets ?? []);
    const initialSuggestedMax = {{ $suggestedMax ?? 0 }};

    let fnbChart;
    let addCardOpen = false; 

    function formatDatasets(rawDatasets) {
        return rawDatasets.map((item) => ({
            label: item.label,
            data: item.data,
            borderColor: item.color || '#6f42c1',
            backgroundColor: 'transparent',
            borderWidth: 2.5,
            tension: 0.4,
            pointRadius: 0,
            pointHoverRadius: 6,
            pointHitRadius: 10,
            produk_id: item.id_produk 
        }));
    }

    function getUnusedProduks(keyword = '') {
        const usedIds = fnbChart.data.datasets.map(ds => ds.produk_id);
        const kw = keyword.toLowerCase();
        return allProduks.filter(p =>
            !usedIds.includes(p.id_produk) &&
            p.nama_produk.toLowerCase().includes(kw)
        );
    }

    function renderLegends() {
        const datasets = fnbChart.data.datasets;
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
            const isHidden = !fnbChart.isDatasetVisible(index);
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

        // Maksimal 7 Pembanding untuk F&B (sesuai jumlah palet warna)
        if (datasets.length < 7) {
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

        const items = getUnusedProduks('');
        return `
            <div class="legend-box add-legend-box search-mode border rounded-3 flex-fill" style="min-width: 220px;">
                <div class="input-group input-group-sm">
                    <div class="input-group-prepend">
                        <span class="input-group-text bg-white border-right-0"><i class="fas fa-search text-muted"></i></span>
                    </div>
                    <input type="text" id="add-search-input" class="form-control border-left-0 shadow-none" placeholder="Cari nama produk..." autocomplete="off">
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
            `<div class="list-group-item autocomplete-item border-0 border-bottom" data-id="${p.id_produk}">${p.nama_produk}</div>`
        ).join('');
    }

    window.openAddCard = function () {
        addCardOpen = true;
        renderLegends();
    };

    window.toggleDataset = function(index) {
        const isVisible = fnbChart.isDatasetVisible(index);
        if (isVisible) fnbChart.hide(index); else fnbChart.show(index);
        renderLegends();
    }

    window.removeDataset = function(index) {
        fnbChart.data.datasets.splice(index, 1);
        fnbChart.update();
        renderLegends();
    }

    function addProdukToChart(produkId) {
        // Ambil elemen form yang benar
        let form = $('#filter-form').is('form') ? $('#filter-form') : $('#filter-form form');
        let formData = form.serialize();
        let colorIndex = fnbChart.data.datasets.length;

        $.get(window.location.href, formData + '&add_produk_id=' + produkId + '&color_index=' + colorIndex, function(res) {
            if (res.success && res.dataset) {
                let formatted = formatDatasets([res.dataset])[0];
                fnbChart.data.datasets.push(formatted);
                if (res.suggestedMax) fnbChart.options.scales.y.suggestedMax = res.suggestedMax;
                addCardOpen = false;
                fnbChart.update();
                renderLegends();
            } else {
                addCardOpen = false;
                renderLegends();
            }
        });
    }

    function getActiveProdukIds() {
        return fnbChart.data.datasets
            .map(ds => ds.produk_id)
            .filter(id => id !== null && id !== undefined);
    }

$(document).ready(function() {
    if ($('#rentang_tanggal').length) {
        $('#rentang_tanggal').daterangepicker({
            locale: {
                format: 'DD MMM YYYY',
                separator: ' - ',
                applyLabel: 'Pilih', cancelLabel: 'Batal', fromLabel: 'Dari', toLabel: 'Sampai',
                daysOfWeek: ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'],
                monthNames: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
                firstDay: 1
            }
        });
    }

    const ctx = document.getElementById('fnbAnalysisChart').getContext('2d');
    fnbChart = new Chart(ctx, {
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
                            if (value >= 1000000) return (value / 1000000).toFixed(2) + ' jt';
                            else if (value >= 1000) return (value / 1000).toFixed(0) + ' rb';
                            return value.toFixed(2);
                        }
                    }
                }
            }
        }
    });

    renderLegends();

    $(document).on('click', '.add-legend-box .autocomplete-item', function() {
        addProdukToChart($(this).data('id'));
    });

    $(document).on('input', '#add-search-input', function() {
        const items = getUnusedProduks($(this).val());
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
        if(form.length === 0) form = $(this); 

        let btn = form.find('button[type="submit"]');
        let origBtn = btn.html();

        btn.html('<i class="fas fa-spinner fa-spin mr-2"></i>').prop('disabled', true);

        let produkIds = getActiveProdukIds();
        let requestData = form.serialize() + '&produk_ids=' + produkIds.join(',');

        $.get(form.attr('action') || window.location.href, requestData, function(res) {
            if(res.success) {
                fnbChart.data.labels = res.labels;
                fnbChart.data.datasets = formatDatasets(res.datasets);
                fnbChart.options.scales.y.suggestedMax = res.suggestedMax;
                
                if (res.allProduks) allProduks = res.allProduks;
                
                fnbChart.update();
                addCardOpen = false;
                renderLegends();
                
                if(res.html !== undefined) {
                    $('#fnb-table-body').html(res.html);
                    $('#footer-info').text('Menampilkan 1 hingga ' + res.total + ' dari ' + res.total + ' entri');
                }
            }
            btn.html(origBtn).prop('disabled', false);
        }).fail(function() {
            alert('Terjadi kesalahan saat memuat data filter.');
            btn.html(origBtn).prop('disabled', false);
        });
    });
});
</script>
@stop