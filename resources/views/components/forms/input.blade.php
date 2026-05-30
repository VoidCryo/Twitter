@props([
    'name',
    'label' => '',
    'type' => 'text',
    'placeholder' => '',
    'required' => false,
    'icon' => '',
])

<div class="mb-3">
    @if($label)
        <label for="{{ $name }}" class="form-label">{{ $label }}</label>
    @endif

    @if($icon)
        <div class="input-group">
            <span class="input-group-text">
                <i class="{{ $icon }}"></i>
            </span>
            <input
                type="{{ $type }}"
                name="{{ $name }}"
                id="{{ $name }}"
                placeholder="{{ $placeholder }}"
                {{ $required ? 'required' : '' }}
                value="{{ old($name) }}"
                class="form-control @error($name) is-invalid @enderror"
                {{ $attributes->except(['name','label','type','placeholder','required','icon']) }}
            >
            @error($name)
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    @else
        <input
            type="{{ $type }}"
            name="{{ $name }}"
            id="{{ $name }}"
            placeholder="{{ $placeholder }}"
            {{ $required ? 'required' : '' }}
            value="{{ $type !== 'password' ? old($name) : '' }}"
            class="form-control @error($name) is-invalid @enderror"
            {{ $attributes->except(['name','label','type','placeholder','required','icon']) }}
        >
        @error($name)
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    @endif
</div>
