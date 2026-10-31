@props(['id', 'edit' => null, 'editClick' => null, 'view' => null])

<div class="row-actions">
    {{ $slot }}
    @if ($view)
        <a href="{{ $view }}" target="_blank" class="btn btn-light btn-icon" title="{{ __('View') }}"><i class="bi bi-eye"></i></a>
    @endif
    @if ($edit)
        <a href="{{ $edit }}" class="btn btn-light btn-icon" title="{{ __('Edit') }}"><i class="bi bi-pencil"></i></a>
    @elseif ($editClick)
        <button type="button" wire:click="{{ $editClick }}" class="btn btn-light btn-icon" title="{{ __('Edit') }}"><i class="bi bi-pencil"></i></button>
    @endif
    <button type="button" class="btn btn-light btn-icon text-danger" title="{{ __('Delete') }}"
            x-on:click="confirmAction(() => $wire.delete({{ $id }}))">
        <i class="bi bi-trash"></i>
    </button>
</div>
