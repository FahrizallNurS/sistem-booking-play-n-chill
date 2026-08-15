@foreach ($items as $item)
    <x-analysis-item
        :label="$item['label']"
        :value="$item['value']"
        :percent="$item['percent']"
        :color="$item['color']"
    />
@endforeach