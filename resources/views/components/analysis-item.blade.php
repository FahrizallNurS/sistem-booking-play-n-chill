@props([
    'label',
    'value',
    'percent',
    'color' => 'primary',
])

<div class="analysis-item mb-3">
    <div class="d-flex justify-content-between mb-1">
        <span class="analysis-label">{{ $label }}</span>
        <span class="analysis-value">{{ $value }}</span>
    </div>
    <x-progress-bar :percent="$percent" :color="$color" />
</div>