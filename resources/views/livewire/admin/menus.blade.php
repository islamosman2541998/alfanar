@php
    $locations = ['header' => __('Header menu'), 'footer_1' => __('Footer: first column'), 'footer_2' => __('Footer: second column')];
    $typeLabels = ['route' => __('Site page'), 'page' => __('Custom page'), 'service' => __('Service'), 'project' => __('Project'), 'url' => __('Custom link'), 'none' => __('Dropdown title only')];
    $describe = function ($item) use ($typeLabels, $linkTitles) {
        return $typeLabels[$item->type].': '.match ($item->type) {
            'route' => __(\App\Models\MenuItem::ROUTES[$item->route] ?? ''),
            'page', 'service', 'project' => $linkTitles[$item->type][$item->linkable_id] ?? __('Deleted'),
            'url' => $item->url,
            default => '',
        };
    };
@endphp

<div>
    <x-admin.page-header :title="__('Menus')" :subtitle="__('Control the navigation bar and footer links. Drag items to reorder or to move them inside a dropdown.')">
        <button type="button" class="btn btn-light" wire:click="openBulk"><i class="bi bi-collection"></i> {{ __('Add several links') }}</button>
        <button type="button" class="btn btn-brand" wire:click="create"><i class="bi bi-plus-lg"></i> {{ __('Add link') }}</button>
    </x-admin.page-header>

    <ul class="nav nav-pills settings-nav mb-3 gap-1">
        @foreach ($locations as $key => $label)
            <li class="nav-item">
                <button type="button" wire:click="setLocation('{{ $key }}')" @class(['nav-link', 'active' => $location === $key])>{{ $label }}</button>
            </li>
        @endforeach
    </ul>

    <div class="card">
        <div class="card-body">
            @if ($roots->isEmpty())
                <div class="empty-state"><i class="bi bi-list-nested"></i>{{ __('No links yet') }}</div>
            @endif

            <ul class="menu-tree" wire:sort="reorder" wire:sort:group="menu" wire:sort:group-id="root">
                @foreach ($roots as $root)
                    <li class="menu-node" wire:key="menu-{{ $root->id }}" wire:sort:item="{{ $root->id }}">
                        <div @class(['menu-row', 'inactive' => ! $root->status])>
                            <i class="bi bi-grip-vertical handle" wire:sort:handle></i>
                            <div class="flex-fill">
                                <div class="fw-semibold">{{ $root->tr('title') }}</div>
                                <div class="meta">{{ $describe($root) }}</div>
                            </div>
                            <div class="d-flex gap-1" wire:sort:ignore>
                                <button type="button" class="btn btn-light btn-sm d-none d-md-inline-block" wire:click="create({{ $root->id }})"><i class="bi bi-plus"></i> {{ __('Sub item') }}</button>
                                <x-admin.switch :id="$root->id" :checked="$root->status" />
                                <button type="button" class="btn btn-light btn-icon" wire:click="edit({{ $root->id }})"><i class="bi bi-pencil"></i></button>
                                <button type="button" class="btn btn-light btn-icon text-danger" x-on:click="confirmAction(() => $wire.delete({{ $root->id }}), { text: '{{ __('Sub items will be deleted too.') }}' })"><i class="bi bi-trash"></i></button>
                            </div>
                        </div>

                        <ul class="menu-tree" wire:sort="reorder" wire:sort:group="menu" wire:sort:group-id="{{ $root->id }}">
                            @foreach ($items->where('parent_id', $root->id) as $child)
                                <li class="menu-node" wire:key="menu-{{ $child->id }}" wire:sort:item="{{ $child->id }}">
                                    <div @class(['menu-row', 'inactive' => ! $child->status])>
                                        <i class="bi bi-grip-vertical handle" wire:sort:handle></i>
                                        <div class="flex-fill">
                                            <div class="fw-semibold">{{ $child->tr('title') }}</div>
                                            <div class="meta">{{ $describe($child) }}</div>
                                        </div>
                                        <div class="d-flex gap-1" wire:sort:ignore>
                                            <x-admin.switch :id="$child->id" :checked="$child->status" />
                                            <button type="button" class="btn btn-light btn-icon" wire:click="edit({{ $child->id }})"><i class="bi bi-pencil"></i></button>
                                            <button type="button" class="btn btn-light btn-icon text-danger" x-on:click="confirmAction(() => $wire.delete({{ $child->id }}))"><i class="bi bi-trash"></i></button>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    {{-- Add / edit link --}}
    <x-admin.modal :title="$editingId ? __('Edit link') : __('Add link')">
        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <label class="form-label">{{ __('Link type') }}</label>
                <select class="form-select" wire:model.live="form.type">
                    @foreach ($typeLabels as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                @if ($form['type'] === 'route')
                    <label class="form-label">{{ __('Page') }}</label>
                    <select class="form-select @error('form.route') is-invalid @enderror" wire:model.live="form.route">
                        @foreach (\App\Models\MenuItem::ROUTES as $route => $label)
                            <option value="{{ $route }}">{{ __($label) }}</option>
                        @endforeach
                    </select>
                @elseif (in_array($form['type'], ['page', 'service', 'project']))
                    <label class="form-label">{{ $typeLabels[$form['type']] }}</label>
                    <select class="form-select @error('form.linkable_id') is-invalid @enderror" wire:model.live="form.linkable_id">
                        <option value="">{{ __('Choose...') }}</option>
                        @foreach ($linkables[$form['type']] as $record)
                            <option value="{{ $record->id }}">{{ $record->tr('title') }}</option>
                        @endforeach
                    </select>
                    @error('form.linkable_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                @elseif ($form['type'] === 'url')
                    <label class="form-label">{{ __('URL') }}</label>
                    <input type="text" dir="ltr" class="form-control @error('form.url') is-invalid @enderror" wire:model="form.url" placeholder="https://">
                    @error('form.url') <div class="invalid-feedback">{{ $message }}</div> @enderror
                @else
                    <div class="form-text mt-4">{{ __('Used as a dropdown title for sub items.') }}</div>
                @endif
            </div>
        </div>

        <x-admin.tr-input name="form.title" :label="__('Link title')" required />

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">{{ __('Parent (dropdown)') }}</label>
                <select class="form-select @error('form.parent_id') is-invalid @enderror" wire:model="form.parent_id">
                    <option value="">{{ __('None (top level)') }}</option>
                    @foreach ($roots as $root)
                        @continue($root->id === $editingId)
                        <option value="{{ $root->id }}">{{ $root->tr('title') }}</option>
                    @endforeach
                </select>
                @error('form.parent_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6 d-flex flex-column justify-content-end gap-2">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="m-tab" wire:model="form.new_tab">
                    <label class="form-check-label" for="m-tab">{{ __('Open in new tab') }}</label>
                </div>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="m-status" wire:model="form.status">
                    <label class="form-check-label" for="m-status">{{ __('Active') }}</label>
                </div>
            </div>
        </div>
    </x-admin.modal>

    {{-- Bulk add children (e.g. services under "Services") --}}
    <x-admin.modal id="bulkModal" :title="__('Add several links')" submit="saveBulk">
        <p class="text-muted small">{{ __('Choose a top level item, then select the records to show in its dropdown.') }}</p>
        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <label class="form-label">{{ __('Parent (dropdown)') }}</label>
                <select class="form-select @error('bulk.parent_id') is-invalid @enderror" wire:model="bulk.parent_id">
                    <option value="">{{ __('Choose...') }}</option>
                    @foreach ($roots as $root)
                        <option value="{{ $root->id }}">{{ $root->tr('title') }}</option>
                    @endforeach
                </select>
                @error('bulk.parent_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">{{ __('Records') }}</label>
                <select class="form-select" wire:model.live="bulk.type">
                    <option value="service">{{ __('Services') }}</option>
                    <option value="page">{{ __('Pages') }}</option>
                    <option value="project">{{ __('Projects') }}</option>
                </select>
            </div>
        </div>
        <div class="border rounded p-2" style="max-height: 280px; overflow-y: auto">
            @foreach ($linkables[$bulk['type']] as $record)
                <div class="form-check py-1" wire:key="bulk-{{ $bulk['type'] }}-{{ $record->id }}">
                    <input class="form-check-input" type="checkbox" value="{{ $record->id }}" id="bulk-{{ $record->id }}" wire:model="bulk.ids">
                    <label class="form-check-label" for="bulk-{{ $record->id }}">{{ $record->tr('title') }}</label>
                </div>
            @endforeach
        </div>
        @error('bulk.ids') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
    </x-admin.modal>
</div>
