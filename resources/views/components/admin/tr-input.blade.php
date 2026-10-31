@props(['name', 'label', 'required' => false, 'textarea' => false, 'rows' => 4, 'count' => null, 'hint' => null])

{{-- Arabic + English inputs for one translatable field, bound to $name.ar / $name.en --}}
<div class="row g-3 lang-pair mb-3">
    @foreach (['ar' => 'AR', 'en' => 'EN'] as $locale => $badge)
        @php $field = "{$name}.{$locale}"; @endphp
        <div class="col-md-6">
            <label class="form-label" for="{{ str_replace('.', '_', $field) }}">
                {{ $label }}<span class="lang-badge">{{ $badge }}</span>
                @if ($required) <span class="text-danger">*</span> @endif
            </label>
            @if ($textarea)
                <textarea id="{{ str_replace('.', '_', $field) }}" rows="{{ $rows }}" dir="{{ $locale === 'ar' ? 'rtl' : 'ltr' }}"
                          wire:model="{{ $field }}"
                          @class(['form-control', 'is-invalid' => $errors->has($field)])></textarea>
            @else
                <input type="text" id="{{ str_replace('.', '_', $field) }}" dir="{{ $locale === 'ar' ? 'rtl' : 'ltr' }}"
                       wire:model="{{ $field }}"
                       @class(['form-control', 'is-invalid' => $errors->has($field)])>
            @endif
            @if ($count)
                <div class="d-flex justify-content-end">
                    <span class="char-count" x-data
                          x-text="((($wire.{{ $name }} || {})['{{ $locale }}'] || '').length) + ' / {{ $count }}'"
                          :class="((($wire.{{ $name }} || {})['{{ $locale }}'] || '').length) > {{ $count }} && 'text-danger'"></span>
                </div>
            @endif
            @error($field) <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
        </div>
    @endforeach
    @if ($hint)
        <div class="col-12 mt-1"><div class="form-text">{{ $hint }}</div></div>
    @endif
</div>
