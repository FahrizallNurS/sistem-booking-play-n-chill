@props([
    'id',
    'labels' => '[]',
    'datasets' => '[]',
    'height' => '320px',
    'interactive' => false,
    'headerLegendId' => null,
    'legendBoxesId' => null,
    'showAddComparison' => true,
])

@php
    $headerLegendId = $headerLegendId ?? $id . '-header-legend';
    $legendBoxesId  = $legendBoxesId ?? $id . '-legend-boxes';
@endphp

<div class="chart-container" style="position: relative; height: {{ $height }}; width: 100%;">
    <canvas id="{{ $id }}"
            data-labels="{{ is_string($labels) ? $labels : json_encode($labels) }}"
            data-datasets="{{ is_string($datasets) ? $datasets : json_encode($datasets) }}"
            data-interactive="{{ $interactive ? '1' : '0' }}"
            data-header-legend-id="{{ $headerLegendId }}"
            data-legend-boxes-id="{{ $legendBoxesId }}"
            data-show-add-comparison="{{ $showAddComparison ? '1' : '0' }}">
    </canvas>
</div>

{{--
    Semua script & style di bawah ini dibungkus @once supaya walau <x-chart> dipakai
    berkali-kali dalam satu halaman (atau di banyak halaman berbeda), kode ini cuma
    ter-render SATU KALI. Ini yang membuat komponen ini reusable & DRY:
    - Mode biasa (interactive=false): dipakai di beranda & analisis-pendapatan.
    - Mode interaktif (interactive=true): dipakai di produk-layanan & produk F&B,
      menggantikan script custom yang sebelumnya ditulis ulang manual di tiap halaman.
--}}
@once
@push('css')
<style>
    .legend-box {
        transition: all 0.2s ease;
        user-select: none;
    }
    .legend-box:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px rgba(0,0,0,0.1) !important;
    }
    .legend-box.hidden-dataset {
        opacity: 0.5;
        background-color: #f8f9fa !important;
    }
</style>
@endpush

@push('js')
<script>
    // Registry instance Chart.js per-canvas, supaya aman kalau ada >1 chart dalam satu halaman.
    window.__pncCharts = window.__pncCharts || {};

    function pncFormatRibuan(value) {
        return Math.round(value).toLocaleString('id-ID');
    }

    function pncRenderChart(chartElement) {
        if (!chartElement || typeof Chart === 'undefined') return;

        const rawLabels   = JSON.parse(chartElement.getAttribute('data-labels') || '[]');
        const rawDatasets = JSON.parse(chartElement.getAttribute('data-datasets') || '[]');
        const isInteractive = chartElement.getAttribute('data-interactive') === '1';

        // Fungsi cerdas mendeteksi skala format angka terbesar dari dataset yang masuk
        let maxVal = 0;
        rawDatasets.forEach(dataset => {
            if (Array.isArray(dataset.data)) {
                const datasetMax = Math.max(...dataset.data.filter(v => typeof v === 'number'));
                if (datasetMax > maxVal) maxVal = datasetMax;
            }
        });
        const isMillionsMode = maxVal >= 1000000;

        const formattedDatasets = rawDatasets.map((item) => ({
            label: item.label,
            data: item.data,
            borderColor: item.color || '#6f42c1',
            backgroundColor: item.fill ? (item.color + '14') : 'transparent',
            borderWidth: item.fill ? 2.5 : (isInteractive ? 3 : 2),
            borderDash: item.dash ? [6, 4] : [],
            fill: item.fill || false,
            tension: 0.4,
            pointRadius: 0,
            pointHoverRadius: 5,
            pointHitRadius: 10,
            pointBackgroundColor: item.color || '#6f42c1',
            pointBorderColor: '#fff',
            pointBorderWidth: 2,
        }));

        const ctx = chartElement.getContext('2d');
        const chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: rawLabels,
                datasets: formattedDatasets
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function (ctx) {
                                const val = ctx.parsed.y;
                                if (isInteractive) {
                                    return ctx.dataset.label + ': Rp ' + pncFormatRibuan(val / 1000) + 'K';
                                }
                                if (isMillionsMode) {
                                    return ctx.dataset.label + ': Rp ' + (val / 1000000).toFixed(1) + ' Jt';
                                }
                                return ctx.dataset.label + ': Rp ' + val.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { autoSkip: true, maxTicksLimit: 10, color: '#8a949f', font: { size: 11 } }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: '#f0f0f5', drawBorder: false },
                        ticks: {
                            color: '#8a949f',
                            font: { size: 11 },
                            callback: function (value) {
                                if (isInteractive) {
                                    return 'RP' + pncFormatRibuan(value / 1000) + 'K';
                                }
                                if (isMillionsMode) {
                                    if (value >= 1000000) return (value / 1000000).toFixed(1) + ' jt';
                                    if (value >= 1000) return (value / 1000).toFixed(1) + ' rb';
                                    return value;
                                }
                                return 'Rp ' + (value / 1000) + 'K';
                            }
                        }
                    }
                }
            }
        });

        window.__pncCharts[chartElement.id] = chart;

        if (isInteractive) {
            pncRenderInteractiveLegend(chartElement, chart, rawDatasets);
        }
    }

    // Legend dot di header card + kotak legend (dengan toggle & tombol "Tambah Pembanding")
    // di bawah chart. Dipakai bareng oleh produk-layanan & produk F&B, jadi hanya ditulis 1x di sini.
    function pncRenderInteractiveLegend(chartElement, chart, rawDatasets) {
        const headerLegendId  = chartElement.getAttribute('data-header-legend-id');
        const legendBoxesId   = chartElement.getAttribute('data-legend-boxes-id');
        const showAddComparison = chartElement.getAttribute('data-show-add-comparison') === '1';

        const headerContainer = document.getElementById(headerLegendId);
        if (headerContainer) {
            headerContainer.innerHTML = rawDatasets.map(item => `
                <div class="d-flex align-items-center gap-1 mr-3">
                    <div class="rounded-circle" style="width: 10px; height: 10px; background-color: ${item.color};"></div>
                    <span class="text-dark">${item.label}</span>
                </div>
            `).join('');
        }

        const boxContainer = document.getElementById(legendBoxesId);
        if (!boxContainer) return;

        let html = rawDatasets.map((item, index) => `
            <div class="legend-box border rounded-3 p-3 bg-white shadow-sm flex-fill"
                 data-dataset-index="${index}"
                 style="min-width: 160px; border-top: 4px solid ${item.color} !important; cursor: pointer;">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <span class="font-weight-bold text-dark" style="font-size: 12px;">${item.label}</span>
                    <i class="fas fa-times text-muted" style="font-size: 12px;"></i>
                </div>
            </div>
        `).join('');

        if (showAddComparison) {
            html += `
                <div class="legend-box border rounded-3 p-3 bg-white shadow-sm flex-fill d-flex align-items-center justify-content-center js-add-comparison"
                     style="min-width: 160px; cursor: pointer;">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle d-flex align-items-center justify-content-center mr-2" style="width: 20px; height: 20px; background-color: #e5e7eb;">
                            <div class="rounded-circle" style="width: 12px; height: 12px; background-color: #a78bfa;"></div>
                        </div>
                        <span class="font-weight-bold" style="font-size: 13px; color: #374151;">Tambah Pembanding</span>
                    </div>
                </div>
            `;
        }

        boxContainer.innerHTML = html;

        boxContainer.querySelectorAll('.legend-box[data-dataset-index]').forEach(box => {
            box.addEventListener('click', function () {
                const idx = parseInt(this.getAttribute('data-dataset-index'), 10);
                if (chart.isDatasetVisible(idx)) {
                    chart.hide(idx);
                } else {
                    chart.show(idx);
                }
                this.classList.toggle('hidden-dataset');
            });
        });

        const addBtn = boxContainer.querySelector('.js-add-comparison');
        if (addBtn) {
            addBtn.addEventListener('click', function () {
                // TODO: ganti alert ini dengan modal/logic "Tambah Pembanding" beneran saat fitur sudah ada di Controller.
                alert('Fitur Tambah Pembanding segera hadir');
            });
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('canvas[data-labels]').forEach(pncRenderChart);
    });
</script>
@endpush
@endonce