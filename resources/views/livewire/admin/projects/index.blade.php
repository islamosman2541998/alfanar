<div>
    <x-admin.page-header :title="__('Projects')" :subtitle="__('Portfolio of completed work with photos and videos.')">
        <a href="{{ route('admin.projects.create') }}" class="btn btn-brand"><i class="bi bi-plus-lg"></i> {{ __('Add project') }}</a>
    </x-admin.page-header>

    <div class="card">
        <x-admin.toolbar :selected="$selected" featured>
            <select class="form-select" wire:model.live="service">
                <option value="">{{ __('All services') }}</option>
                @foreach ($services as $item)
                    <option value="{{ $item->id }}">{{ $item->tr('title') }}</option>
                @endforeach
            </select>
        </x-admin.toolbar>
        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th style="width: 36px"></th>
                        <th>{{ __('Image') }}</th>
                        <x-admin.th field="id" :$sortField :$sortDirection>{{ __('Title') }}</x-admin.th>
                        <th class="d-none d-md-table-cell">{{ __('Service') }}</th>
                        <x-admin.th field="year" :$sortField :$sortDirection class="d-none d-lg-table-cell">{{ __('Year') }}</x-admin.th>
                        <x-admin.th field="sort_order" :$sortField :$sortDirection>{{ __('Order') }}</x-admin.th>
                        <x-admin.th field="is_featured" :$sortField :$sortDirection>{{ __('Featured') }}</x-admin.th>
                        <x-admin.th field="status" :$sortField :$sortDirection>{{ __('Status') }}</x-admin.th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($rows as $row)
                        <tr wire:key="row-{{ $row->id }}">
                            <td><input type="checkbox" class="form-check-input" value="{{ $row->id }}" wire:model.live="selected"></td>
                            <td><img src="{{ $row->coverThumbUrl() }}" class="thumb" alt="" loading="lazy"></td>
                            <td class="title-cell">
                                <strong>{{ $row->tr('title') }}</strong>
                                <small>{{ $row->tr('location') }}{{ $row->tr('location') ? ' · ' : '' }}{{ trans_choice(':count media file|:count media files', $row->gallery_count) }}</small>
                            </td>
                            <td class="d-none d-md-table-cell">{{ $row->service?->tr('title') ?? '-' }}</td>
                            <td class="d-none d-lg-table-cell">{{ $row->year ?: '-' }}</td>
                            <td>{{ $row->sort_order }}</td>
                            <td><x-admin.switch :id="$row->id" field="is_featured" :checked="$row->is_featured" /></td>
                            <td><x-admin.switch :id="$row->id" :checked="$row->status" /></td>
                            <td><x-admin.row-actions :id="$row->id" :edit="route('admin.projects.edit', $row)" :view="$row->status ? $row->url() : null" /></td>
                        </tr>
                    @empty
                        <x-admin.empty :colspan="9" />
                    @endforelse
                </tbody>
            </table>
        </div>
        <x-admin.table-foot :rows="$rows" />
    </div>
</div>
