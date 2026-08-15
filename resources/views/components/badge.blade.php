@props([
    'variant' => 'secondary', // success, danger, warning, info, primary, secondary
    'label' => null,
])

{{--
    Komponen badge status generik. Dipakai untuk status produk (Aktif/Nonaktif),
    dan bisa dipakai ulang untuk status lain (mis. status booking, status pembayaran
    di laporan-sa) supaya tidak perlu tulis ulang <span class="badge badge-...">
    dan warna mapping-nya di tiap view.
--}}
<span {{ $attributes->merge(['class' => 'badge badge-' . $variant]) }} style="font-size: 11px; padding: 5px 10px; font-weight: 600;">
    {{ $label ?? $slot }}
</span>
