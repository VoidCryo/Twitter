@props([
    'type' => 'submit',
    'color' => 'primary',
    'label' => 'Submit',
])

<button
    type="{{ $type }}"
    {{ $attributes->merge(['class' => 'btn btn-' . $color]) }}
>
    {{ $slot->isEmpty() ? $label : $slot }}
</button>

