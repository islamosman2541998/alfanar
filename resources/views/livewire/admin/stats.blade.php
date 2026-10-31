<div>
    <x-admin.page-header :title="__('Statistics')" :subtitle="__('Company numbers shown with a counting animation.')">
        <button type="button" class="btn btn-brand" wire:click="create"><i class="bi bi-plus-lg"></i> {{ __('Add number') }}</button>
    </x-admin.page-header>

    <div class="card">
        <x-admin.toolbar :selected="$selected" />
        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th style="width: 36px"></th>
                        <x-admin.th field="value" :$sortField :$sortDirection>{{ __('Number') }}</x-admin.th>
                        <th>{{ __('Label') }}</th>
                        <x-admin.th field="sort_order" :$sortField :$sortDirection>{{ __('Order') }}</x-admin.th>
                        <x-admin.th field="status" :$sortField :$sortDirection>{{ __('Status') }}</x-admin.th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($rows as $row)
                        <tr wire:key="row-{{ $row->id }}">
                            <td><input type="checkbox" class="form-check-input" value="{{ $row->id }}" wire:model.live="selected"></td>
                            <td class="fw-bold fs-5" dir="ltr">{{ number_format($row->value) }}{{ $row->suffix }}</td>
                            <td>{{ $row->tr('label') }}</td>
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

    <x-admin.modal :title="$editingId ? __('Edit number') : __('Add number')" size="">
        <x-admin.tr-input name="form.label" :label="__('Label')" required />
        <div class="row g-3">
            <div class="col-6">
                <label class="form-label">{{ __('Number') }} <span class="text-danger">*</span></label>
                <input type="number" min="0" class="form-control @error('form.value') is-invalid @enderror" wire:model="form.value">
                @error('form.value') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-6">
                <label class="form-label">{{ __('Suffix') }}</label>
                <input type="text" class="form-control" wire:model="form.suffix" placeholder="+ / %">
            </div>
            <div class="col-6">
                <label class="form-label">{{ __('Display order') }}</label>
                <input type="number" min="0" class="form-control" wire:model="form.sort_order">
            </div>
            <div class="col-6 d-flex align-items-end">
                <div class="form-check form-switch mb-2">
                    <input class="form-check-input" type="checkbox" id="s-status" wire:model="form.status">
                    <label class="form-check-label" for="s-status">{{ __('Active') }}</label>
                </div>
            </div>
        </div>
    </x-admin.modal>
</div>
