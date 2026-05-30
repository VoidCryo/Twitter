@props([
    'type' => 'button',
    'variant' => 'brand',
    'loading' => false,
])

<button
    type="{{ $type }}"
    {{ $attributes->merge(['class' => 'btn-' . $variant]) }}
    {{ $loading ? 'disabled' : '' }}
>
    @if($loading)
        <span class="spinner-border spinner-border-sm me-2" role="status"></span>
    @endif
    {{ $slot }}
</button>
