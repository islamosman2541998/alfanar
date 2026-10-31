<div>
    <x-admin.page-header :title="__('Why us')" :subtitle="__('Reasons to choose the company, shown on the about page and home page.')">
        <button type="button" class="btn btn-brand" wire:click="create"><i class="bi bi-plus-lg"></i> {{ __('Add item') }}</button>
    </x-admin.page-header>

    <div class="card">
        <x-admin.toolbar :selected="$selected" />
        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th style="width: 36px"></th>
                        <x-admin.th field="id" :$sortField :$sortDirection>{{ __('Title') }}</x-admin.th>
                        <th class="d-none d-md-table-cell">{{ __('Description') }}</th>
                        <x-admin.th field="sort_order" :$sortField :$sortDirection>{{ __('Order') }}</x-admin.th>
                        <x-admin.th field="status" :$sortField :$sortDirection>{{ __('Status') }}</x-admin.th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($rows as $row)
                        <tr wire:key="row-{{ $row->id }}">
                            <td><input type="checkbox" class="form-check-input" value="{{ $row->id }}" wire:model.live="selected"></td>
                            <td class="fw-semibold">{{ $row->tr('title') }}</td>
                            <td class="d-none d-md-table-cell text-muted small">{{ \Illuminate\Support\Str::limit($row->tr('description'), 110) }}</td>
                            <td>{{ $row->sort_order }}</td>
                            <td><x-admin.switch :id="$row->id" :checked="$row->status" /></td>
                            <td><x-admin.row-actions :id="$row->id" :edit-click="'edit('.$row->id.')'" /></td>
                        </tr>
                    @empty
                        <x-admin.empty :colspan="6" />
                    @endforelse
                </tbody>
            </table>
        </div>
        <x-admin.table-foot :rows="$rows" />
    </div>

    <x-admin.modal :title="$editingId ? __('Edit item') : __('Add item')">
        <x-admin.tr-input name="form.title" :label="__('Title')" required />
        <x-admin.tr-input name="form.description" :label="__('Description')" textarea rows="3" />
        <div class="row g-3">
            <div class="col-6">
                <label class="form-label">{{ __('Display order') }}</label>
                <input type="number" min="0" class="form-control" wire:model="form.sort_order">
            </div>
            <div class="col-6 d-flex align-items-end">
                <div class="form-check form-switch mb-2">
                    <input class="form-check-input" type="checkbox" id="f-status" wire:model="form.status">
                    <label class="form-check-label" for="f-status">{{ __('Active') }}</label>
                </div>
            </div>
        </div>
    </x-admin.modal>
</div>
