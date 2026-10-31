<div>
    <x-admin.page-header :title="__('Testimonials')" :subtitle="__('Client reviews shown on the home and about pages.')">
        <button type="button" class="btn btn-brand" wire:click="create"><i class="bi bi-plus-lg"></i> {{ __('Add testimonial') }}</button>
    </x-admin.page-header>

    <div class="card">
        <x-admin.toolbar :selected="$selected" />
        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th style="width: 36px"></th>
                        <x-admin.th field="id" :$sortField :$sortDirection>{{ __('Client') }}</x-admin.th>
                        <th class="d-none d-md-table-cell">{{ __('Review') }}</th>
                        <x-admin.th field="rating" :$sortField :$sortDirection>{{ __('Rating') }}</x-admin.th>
                        <x-admin.th field="sort_order" :$sortField :$sortDirection>{{ __('Order') }}</x-admin.th>
                        <x-admin.th field="status" :$sortField :$sortDirection>{{ __('Status') }}</x-admin.th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($rows as $row)
                        <tr wire:key="row-{{ $row->id }}">
                            <td><input type="checkbox" class="form-check-input" value="{{ $row->id }}" wire:model.live="selected"></td>
                            <td class="title-cell">
                                <div class="d-flex align-items-center gap-2">
                                    @if ($row->photo)
                                        <img src="{{ media_url($row->photo) }}" alt="" width="36" height="36" class="rounded-circle object-fit-cover">
                                    @endif
                                    <div><strong>{{ $row->tr('name') }}</strong><small>{{ $row->tr('position') }}</small></div>
                                </div>
                            </td>
                            <td class="d-none d-md-table-cell text-muted small" style="max-width: 360px">{{ \Illuminate\Support\Str::limit($row->tr('content'), 110) }}</td>
                            <td class="text-nowrap text-brand">{{ str_repeat('★', $row->rating) }}</td>
                            <td>{{ $row->sort_order }}</td>
                            <td><x-admin.switch :id="$row->id" :checked="$row->status" /></td>
                            <td><x-admin.row-actions :id="$row->id" :edit-click="'edit('.$row->id.')'" /></td>
                        </tr>
                    @empty
                        <x-admin.empty :colspan="7" />
                    @endforelse
                </tbody>
            </table>
        </div>
        <x-admin.table-foot :rows="$rows" />
    </div>

    <x-admin.modal :title="$editingId ? __('Edit testimonial') : __('Add testimonial')">
        <x-admin.tr-input name="form.name" :label="__('Client name')" required />
        <x-admin.tr-input name="form.position" :label="__('Position / area')" />
        <x-admin.tr-input name="form.content" :label="__('Review')" textarea rows="3" required />
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">{{ __('Rating') }}</label>
                <select class="form-select" wire:model="form.rating">
                    @for ($i = 5; $i >= 1; $i--)
                        <option value="{{ $i }}">{{ $i }} / 5</option>
                    @endfor
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">{{ __('Display order') }}</label>
                <input type="number" min="0" class="form-control" wire:model="form.sort_order">
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <div class="form-check form-switch mb-2">
                    <input class="form-check-input" type="checkbox" id="t-status" wire:model="form.status">
                    <label class="form-check-label" for="t-status">{{ __('Active') }}</label>
                </div>
            </div>
            <div class="col-12">
                <label class="form-label">{{ __('Photo (optional)') }}</label>
                <div class="d-flex align-items-center gap-3">
                    @if ($photo && $photo->isPreviewable())
                        <img src="{{ $photo->temporaryUrl() }}" width="56" height="56" class="rounded-circle object-fit-cover" alt="">
                    @elseif (! empty($form['photo']))
                        <img src="{{ media_url($form['photo']) }}" width="56" height="56" class="rounded-circle object-fit-cover" alt="">
                    @endif
                    <input type="file" accept="image/*" class="form-control @error('photo') is-invalid @enderror" wire:model="photo">
                    @if ($photo || ! empty($form['photo']))
                        <button type="button" class="btn btn-light btn-icon text-danger flex-shrink-0" wire:click="removePhoto"><i class="bi bi-trash"></i></button>
                    @endif
                </div>
                @error('photo') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
            </div>
        </div>
    </x-admin.modal>
</div>
