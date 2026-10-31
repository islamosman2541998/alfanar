@props(['status' => true, 'featured' => false, 'export' => true, 'selected' => [], 'placeholder' => null])

{{-- Search, filters and actions shared by every listing (DataTable) --}}
<div class="toolbar">
    <div class="search">
        <i class="bi bi-search"></i>
        <input type="search" class="form-control" wire:model.live.debounce.400ms="search" placeholder="{{ $placeholder ?? __('Search...') }}">
    </div>

    @if ($status)
        <select class="form-select" wire:model.live="status">
            <option value="">{{ __('All statuses') }}</option>
            <option value="1">{{ __('Active') }}</option>
            <option value="0">{{ __('Hidden') }}</option>
        </select>
    @endif

    @if ($featured)
        <select class="form-select" wire:model.live="featured">
            <option value="">{{ __('Featured: all') }}</option>
            <option value="1">{{ __('Featured') }}</option>
            <option value="0">{{ __('Not featured') }}</option>
        </select>
    @endif

    {{ $slot }}

    <div class="end">
        @if (count($selected))
            <button type="button" class="btn btn-outline-danger btn-sm"
                    x-on:click="confirmAction(() => $wire.deleteSelected())">
                <i class="bi bi-trash"></i> {{ __('Delete selected') }} ({{ count($selected) }})
            </button>
        @endif
        <button type="button" class="btn btn-light btn-sm" wire:click="resetFilters">
            <i class="bi bi-x-circle"></i> {{ __('Reset') }}
        </button>
        @if ($export)
            <button type="button" class="btn btn-light btn-sm" wire:click="export" wire:loading.attr="disabled" wire:target="export">
                <span wire:loading.remove wire:target="export"><i class="bi bi-file-earmark-excel text-success"></i></span>
                <span wire:loading wire:target="export" class="spinner-border spinner-border-sm"></span>
                {{ __('Export Excel') }}
            </button>
        @endif
    </div>
</div>
