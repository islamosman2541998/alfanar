<div>
    <x-admin.page-header :title="__('Profile')" />

    <div class="row g-3">
        <div class="col-lg-6">
            <form class="card h-100" wire:submit="saveProfile">
                <div class="card-header">{{ __('Account information') }}</div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">{{ __('Name') }}</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" wire:model="name">
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-4">
                        <label class="form-label">{{ __('Email') }}</label>
                        <input type="email" dir="ltr" class="form-control @error('email') is-invalid @enderror" wire:model="email">
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <button class="btn btn-brand" wire:loading.attr="disabled">{{ __('Save') }}</button>
                </div>
            </form>
        </div>
        <div class="col-lg-6">
            <form class="card h-100" wire:submit="savePassword">
                <div class="card-header">{{ __('Change password') }}</div>
                <div class="card-body">
                    @foreach (['current_password' => __('Current password'), 'password' => __('New password'), 'password_confirmation' => __('Confirm new password')] as $field => $label)
                        <div class="mb-3">
                            <label class="form-label">{{ $label }}</label>
                            <input type="password" dir="ltr" autocomplete="{{ $field === 'current_password' ? 'current-password' : 'new-password' }}"
                                   class="form-control @error($field) is-invalid @enderror" wire:model="{{ $field }}">
                            @error($field) <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    @endforeach
                    <button class="btn btn-dark" wire:loading.attr="disabled">{{ __('Change password') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
