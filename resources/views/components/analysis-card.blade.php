@props([
    'title',
    'items' => [],
    'link' => '#',
])

<div class="card custom-card border-0 shadow-sm h-100">
    <div class="card-body p-4 d-flex flex-column justify-content-between">
        
        <div>
            {{-- 1. JUDUL CARD (Jarak sudah dibuat lega dan tidak rapat) --}}
            <h6 class="custom-analysis-title" style="margin-bottom: 1.75rem !important;">
                {{ $title }}
            </h6>

            {{-- 2. KONTAINER RESPONSIF (Mengunci ruang kosong setara maks 3 item) --}}
            <div class="d-flex flex-column justify-content-start" style="min-height: 165px;">
                @foreach ($items as $item)
                    <x-analysis-item
                        :label="$item['label']"
                        :value="$item['value']"
                        :percent="$item['percent']"
                        :color="$item['color']"
                    />
                @endforeach
            </div>
        </div>

        {{-- 3. FOOTER BUTTON (Dikunci otomatis selalu di dasar paling bawah card) --}}
        <div class="text-right mt-auto border-top pt-3">
            <a href="{{ $link }}" class="btn-view-more">
                Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>

    </div>
</div>