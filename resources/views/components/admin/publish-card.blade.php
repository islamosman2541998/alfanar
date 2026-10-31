@props(['featured' => true, 'back'])

{{-- Status / featured / order + save button used by every content form --}}
<div class="card form-section">
    <div class="card-header">{{ __('Publishing') }}</div>
    <div class="card-body">
        <div class="form-check form-switch d-flex justify-content-between align-items-center ps-0 mb-3">
            <label class="form-check-label" for="status">{{ __('Active (visible on site)') }}</label>
            <input class="form-check-input ms-0" type="checkbox" role="switch" id="status" wire:model="status">
        </div>
        @if ($featured)
            <div class="form-check form-switch d-flex justify-content-between align-items-center ps-0 mb-3">
                <label class="form-check-label" for="is_featured">{{ __('Featured') }}</label>
                <input class="form-check-input ms-0" type="checkbox" role="switch" id="is_featured" wire:model="is_featured">
            </div>
        @endif
        <label class="form-label" for="sort_order">{{ __('Display order') }}</label>
        <input type="number" min="0" id="sort_order" class="form-control @error('sort_order') is-invalid @enderror" wire:model="sort_order">
        @error('sort_order') <div class="invalid-feedback">{{ $message }}</div> @enderror

        {{ $slot }}

        <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-brand flex-fill" wire:loading.attr="disabled" wire:target="save,coverFile,galleryUploads">
                <span wire:loading.remove wire:target="save">{{ __('Save') }}</span>
                <span wire:loading wire:target="save"><span class="spinner-border spinner-border-sm"></span> {{ __('Saving...') }}</span>
            </button>
            <a href="{{ $back }}" class="btn btn-light">{{ __('Back') }}</a>
        </div>
    </div>
</div>
