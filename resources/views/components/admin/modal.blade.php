@props(['id' => 'formModal', 'title', 'size' => 'modal-lg', 'submit' => 'save'])

<div class="modal fade" id="{{ $id }}" tabindex="-1" wire:ignore.self aria-hidden="true">
    <div class="modal-dialog {{ $size }} modal-dialog-centered modal-dialog-scrollable modal-fullscreen-sm-down">
        <form class="modal-content" wire:submit="{{ $submit }}">
            <div class="modal-header">
                <h5 class="modal-title">{{ $title }}</h5>
                <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="{{ __('Close') }}"></button>
            </div>
            <div class="modal-body">{{ $slot }}</div>
            @isset($footer)
                <div class="modal-footer">{{ $footer }}</div>
            @else
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="submit" class="btn btn-brand" wire:loading.attr="disabled" wire:target="{{ $submit }}">
                        <span wire:loading wire:target="{{ $submit }}" class="spinner-border spinner-border-sm"></span>
                        {{ __('Save') }}
                    </button>
                </div>
            @endisset
        </form>
    </div>
</div>
