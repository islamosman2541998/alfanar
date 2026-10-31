@props(['field', 'sortField', 'sortDirection'])

<th class="sortable" wire:click="sortBy('{{ $field }}')" {{ $attributes }}>
    {{ $slot }}
    @if ($sortField === $field)
        <i class="bi bi-caret-{{ $sortDirection === 'asc' ? 'up' : 'down' }}-fill"></i>
    @endif
</th>
