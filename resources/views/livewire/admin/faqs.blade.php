<div>
    <x-admin.page-header :title="__('FAQ')" :subtitle="__('Frequently asked questions page.')">
        <button type="button" class="btn btn-brand" wire:click="create"><i class="bi bi-plus-lg"></i> {{ __('Add question') }}</button>
    </x-admin.page-header>

    <div class="card">
        <x-admin.toolbar :selected="$selected" />
        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th style="width: 36px"></th>
                        <x-admin.th field="id" :$sortField :$sortDirection>{{ __('Question') }}</x-admin.th>
                        <th class="d-none d-md-table-cell">{{ __('Answer') }}</th>
                        <x-admin.th field="sort_order" :$sortField :$sortDirection>{{ __('Order') }}</x-admin.th>
                        <x-admin.th field="status" :$sortField :$sortDirection>{{ __('Status') }}</x-admin.th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($rows as $row)
                        <tr wire:key="row-{{ $row->id }}">
                            <td><input type="checkbox" class="form-check-input" value="{{ $row->id }}" wire:model.live="selected"></td>
                            <td class="fw-semibold">{{ $row->tr('question') }}</td>
                            <td class="d-none d-md-table-cell text-muted small" style="max-width: 380px">{{ \Illuminate\Support\Str::limit($row->tr('answer'), 110) }}</td>
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

    <x-admin.modal :title="$editingId ? __('Edit question') : __('Add question')">
        <x-admin.tr-input name="form.question" :label="__('Question')" required />
        <x-admin.tr-input name="form.answer" :label="__('Answer')" textarea rows="4" required />
        <div class="row g-3">
            <div class="col-6">
                <label class="form-label">{{ __('Display order') }}</label>
                <input type="number" min="0" class="form-control" wire:model="form.sort_order">
            </div>
            <div class="col-6 d-flex align-items-end">
                <div class="form-check form-switch mb-2">
                    <input class="form-check-input" type="checkbox" id="q-status" wire:model="form.status">
                    <label class="form-check-label" for="q-status">{{ __('Active') }}</label>
                </div>
            </div>
        </div>
    </x-admin.modal>
</div>
