@php
    $key = $field['key'];
    $model = 'data.'.$key;
    $hint = isset($field['hint']) ? __($field['hint']) : null;
@endphp

@if (! empty($field['tr']))
    <x-admin.tr-input :name="$model" :label="__($field['label'])" :textarea="$field['type'] === 'textarea'" rows="3"
                      :count="! empty($field['serp']) ? ($field['type'] === 'textarea' ? 160 : 60) : null" :hint="$hint" />
@else
    <div class="mb-3">
        @if ($field['type'] === 'toggle')
            <div class="form-check form-switch d-flex justify-content-between align-items-center ps-0">
                <label class="form-check-label fw-semibold" for="{{ $key }}">{{ __($field['label']) }}</label>
                <input class="form-check-input ms-0" type="checkbox" role="switch" id="{{ $key }}" wire:model="{{ $model }}">
            </div>
        @else
            <label class="form-label" for="{{ $key }}">{{ __($field['label']) }}</label>

            @switch($field['type'])
                @case('select')
                    <select id="{{ $key }}" class="form-select @error($model) is-invalid @enderror" wire:model="{{ $model }}">
                        @foreach ($field['options'] as $value => $label)
                            <option value="{{ $value }}">{{ __($label) }}</option>
                        @endforeach
                    </select>
                    @break

                @case('color')
                    <div class="d-flex gap-2" style="max-width: 260px">
                        <input type="color" class="form-control form-control-color" wire:model.live="{{ $model }}">
                        <input type="text" id="{{ $key }}" dir="ltr" class="form-control @error($model) is-invalid @enderror" wire:model.live="{{ $model }}">
                    </div>
                    @break

                @case('code')
                    <textarea id="{{ $key }}" rows="5" class="form-control code-input @error($model) is-invalid @enderror" wire:model="{{ $model }}" spellcheck="false"></textarea>
                    @break

                @case('image')
                    @php $upload = $uploads[$key] ?? null; $current = $data[$key] ?? null; @endphp
                    <div class="d-flex align-items-center gap-3 flex-wrap">
                        <div class="media-box has-media p-1" style="width: 140px">
                            @if ($upload && $upload->isPreviewable())
                                <img src="{{ $upload->temporaryUrl() }}" alt="" style="height: 90px; object-fit: contain; background: #f1efea">
                            @elseif ($current)
                                <img src="{{ media_url($current) }}" alt="" style="height: 90px; object-fit: contain; background: #f1efea">
                            @else
                                <div class="text-muted small py-4"><i class="bi bi-image fs-4 d-block"></i>{{ __('No image') }}</div>
                            @endif
                        </div>
                        <div class="d-flex flex-column gap-2">
                            <label class="btn btn-light btn-sm">
                                <i class="bi bi-upload"></i> {{ __('Choose image') }}
                                <input type="file" accept="image/*" class="d-none" wire:model="uploads.{{ $key }}">
                            </label>
                            @if ($upload || $current)
                                <button type="button" class="btn btn-light btn-sm text-danger" wire:click="removeImage('{{ $key }}')"><i class="bi bi-trash"></i> {{ __('Remove') }}</button>
                            @endif
                            <span wire:loading wire:target="uploads.{{ $key }}" class="small text-muted"><span class="spinner-border spinner-border-sm"></span> {{ __('Uploading...') }}</span>
                        </div>
                    </div>
                    @error('uploads.'.$key) <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    @break

                @default
                    <input type="{{ in_array($field['type'], ['email', 'url', 'number']) ? $field['type'] : 'text' }}" id="{{ $key }}"
                           @if (in_array($field['type'], ['email', 'url', 'number']) || in_array($key, ['phone', 'phone_2', 'whatsapp']) || str_ends_with($key, '_id') || str_ends_with($key, '_pixel') || str_ends_with($key, 'verification')) dir="ltr" @endif
                           class="form-control @error($model) is-invalid @enderror" wire:model="{{ $model }}">
            @endswitch
        @endif

        @if ($hint && $field['type'] !== 'toggle')
            <div class="form-text">{{ $hint }}</div>
        @endif
        @error($model) <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
    </div>
@endif
