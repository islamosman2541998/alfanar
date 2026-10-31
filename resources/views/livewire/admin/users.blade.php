<div>
    <x-admin.page-header :title="__('Admins')" :subtitle="__('People who can sign in to the dashboard.')">
        <button type="button" class="btn btn-brand" wire:click="create"><i class="bi bi-plus-lg"></i> {{ __('Add admin') }}</button>
    </x-admin.page-header>

    <div class="card">
        <x-admin.toolbar :selected="$selected" />
        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th style="width: 36px"></th>
                        <x-admin.th field="name" :$sortField :$sortDirection>{{ __('Name') }}</x-admin.th>
                        <x-admin.th field="email" :$sortField :$sortDirection>{{ __('Email') }}</x-admin.th>
                        <x-admin.th field="created_at" :$sortField :$sortDirection class="d-none d-md-table-cell">{{ __('Created at') }}</x-admin.th>
                        <x-admin.th field="is_active" :$sortField :$sortDirection>{{ __('Status') }}</x-admin.th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($rows as $row)
                        <tr wire:key="row-{{ $row->id }}">
                            <td><input type="checkbox" class="form-check-input" value="{{ $row->id }}" wire:model.live="selected" @disabled($row->id === auth()->id())></td>
                            <td class="fw-semibold">{{ $row->name }} @if ($row->id === auth()->id()) <span class="badge badge-soft ms-1">{{ __('You') }}</span> @endif</td>
                            <td dir="ltr" class="text-start">{{ $row->email }}</td>
                            <td class="d-none d-md-table-cell small text-muted">{{ $row->created_at?->format('Y-m-d') }}</td>
                            <td><x-admin.switch :id="$row->id" field="is_active" :checked="$row->is_active" /></td>
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

    <x-admin.modal :title="$editingId ? __('Edit admin') : __('Add admin')" size="">
        <div class="mb-3">
            <label class="form-label">{{ __('Name') }} <span class="text-danger">*</span></label>
            <input type="text" class="form-control @error('form.name') is-invalid @enderror" wire:model="form.name">
            @error('form.name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="mb-3">
            <label class="form-label">{{ __('Email') }} <span class="text-danger">*</span></label>
            <input type="email" dir="ltr" class="form-control @error('form.email') is-invalid @enderror" wire:model="form.email">
            @error('form.email') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="mb-3">
            <label class="form-label">{{ __('Password') }} @unless ($editingId) <span class="text-danger">*</span> @endunless</label>
            <input type="password" dir="ltr" autocomplete="new-password" class="form-control @error('form.password') is-invalid @enderror" wire:model="form.password">
            @if ($editingId) <div class="form-text">{{ __('Leave empty to keep the current password.') }}</div> @endif
            @error('form.password') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" id="u-active" wire:model="form.is_active">
            <label class="form-check-label" for="u-active">{{ __('Active') }}</label>
        </div>
    </x-admin.modal>
</div>
