<div class="text-center p-8">
    <span class="text-2xl font-semibold {{ $data < 0 ? 'text-red-600' : 'text-blue-600' }}">
        {{ number_format($data, isset($decimals) ? $decimals : 0) }}
        @if(isset($suffix))
            {{ $suffix }}
        @endif
    </span>
</div>
