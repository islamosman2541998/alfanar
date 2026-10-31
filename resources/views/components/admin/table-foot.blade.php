@props(['rows'])

<div class="table-foot">
    <div class="d-flex align-items-center gap-2 small text-muted">
        <select class="form-select form-select-sm" wire:model.live="perPage">
            @foreach ([10, 15, 25, 50, 100] as $size)
                <option value="{{ $size }}">{{ $size }}</option>
            @endforeach
        </select>
        {{ __('Total: :count', ['count' => $rows->total()]) }}
    </div>
    {{ $rows->onEachSide(1)->links(data: ['scrollTo' => false]) }}
</div>
