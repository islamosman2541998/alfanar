@props(['id', 'field' => 'status', 'checked' => false])

<div class="form-check form-switch m-0">
    <input class="form-check-input" type="checkbox" role="switch" @checked($checked)
           wire:click="toggle({{ $id }}, '{{ $field }}')" wire:loading.attr="disabled" wire:target="toggle">
</div>
