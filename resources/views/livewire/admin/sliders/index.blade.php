<div>
    <x-admin.page-header :title="__('Slider')" :subtitle="__('Full screen slides at the top of the home page.')">
        <a href="{{ route('admin.sliders.create') }}" class="btn btn-brand"><i class="bi bi-plus-lg"></i> {{ __('Add slide') }}</a>
    </x-admin.page-header>

    <div class="card">
        <x-admin.toolbar :selected="$selected" />
        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th style="width: 36px"></th>
                        <th>{{ __('Media') }}</th>
                        <x-admin.th field="id" :$sortField :$sortDirection>{{ __('Title') }}</x-admin.th>
                        <th class="d-none d-md-table-cell">{{ __('Button') }}</th>
                        <x-admin.th field="sort_order" :$sortField :$sortDirection>{{ __('Order') }}</x-admin.th>
                        <x-admin.th field="status" :$sortField :$sortDirection>{{ __('Status') }}</x-admin.th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($rows as $row)
                        <tr wire:key="row-{{ $row->id }}">
                            <td><input type="checkbox" class="form-check-input" value="{{ $row->id }}" wire:model.live="selected"></td>
                            <td>
                                @if ($row->coverIsVideo() && ! $row->coverThumbUrl())
                                    <span class="thumb-video"><i class="bi bi-play-fill"></i></span>
                                @else
                                    <img src="{{ $row->coverThumbUrl() }}" class="thumb" alt="" loading="lazy">
                                @endif
                            </td>
                            <td class="title-cell">
                                <strong>{{ $row->tr('title') }}</strong>
                                <small>{{ $row->coverIsVideo() ? __('Video') : __('Image') }}</small>
                            </td>
                            <td class="d-none d-md-table-cell">
                                @if ($row->tr('button_text'))
                                    <span class="badge" style="background: {{ $row->button_color ?: '#e3a01b' }}">{{ $row->tr('button_text') }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>{{ $row->sort_order }}</td>
                            <td><x-admin.switch :id="$row->id" :checked="$row->status" /></td>
                            <td><x-admin.row-actions :id="$row->id" :edit="route('admin.sliders.edit', $row)" /></td>
                        </tr>
                    @empty
                        <x-admin.empty :colspan="7" />
                    @endforelse
                </tbody>
            </table>
        </div>
        <x-admin.table-foot :rows="$rows" />
    </div>
</div>
