<div>
    <x-admin.page-header :title="__('Pages')" :subtitle="__('Custom pages. Add them to the menu from the Menus screen.')">
        <a href="{{ route('admin.pages.create') }}" class="btn btn-brand"><i class="bi bi-plus-lg"></i> {{ __('Add page') }}</a>
    </x-admin.page-header>

    <div class="card">
        <x-admin.toolbar :selected="$selected" featured />
        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th style="width: 36px"></th>
                        <th>{{ __('Media') }}</th>
                        <x-admin.th field="id" :$sortField :$sortDirection>{{ __('Title') }}</x-admin.th>
                        <th class="d-none d-md-table-cell">{{ __('Link') }}</th>
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
                            <td>
                                @if ($row->coverThumbUrl())
                                    <img src="{{ $row->coverThumbUrl() }}" class="thumb" alt="" loading="lazy">
                                @elseif ($row->hasCover())
                                    <span class="thumb-video"><i class="bi bi-play-fill"></i></span>
                                @else
                                    <span class="thumb-video bg-light text-muted"><i class="bi bi-file-earmark"></i></span>
                                @endif
                            </td>
                            <td class="title-cell">
                                <strong>{{ $row->tr('title') }}</strong>
                                <small>{{ trans_choice(':count media file|:count media files', $row->gallery_count) }}</small>
                            </td>
                            <td class="d-none d-md-table-cell small text-muted" dir="ltr">/p/{{ $row->slug }}</td>
                            <td>{{ $row->sort_order }}</td>
                            <td><x-admin.switch :id="$row->id" field="is_featured" :checked="$row->is_featured" /></td>
                            <td><x-admin.switch :id="$row->id" :checked="$row->status" /></td>
                            <td><x-admin.row-actions :id="$row->id" :edit="route('admin.pages.edit', $row)" :view="$row->status ? $row->url() : null" /></td>
                        </tr>
                    @empty
                        <x-admin.empty :colspan="8" />
                    @endforelse
                </tbody>
            </table>
        </div>
        <x-admin.table-foot :rows="$rows" />
    </div>
</div>
