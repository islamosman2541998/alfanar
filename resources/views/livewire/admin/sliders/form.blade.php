<form wire:submit="save">
    <x-admin.page-header :title="$recordId ? __('Edit slide') : __('Add slide')" />

    <div class="row g-3">
        <div class="col-lg-8">
            <div class="card form-section">
                <div class="card-header">{{ __('Content') }}</div>
                <div class="card-body">
                    <x-admin.tr-input name="title" :label="__('Title')" required />
                    <x-admin.tr-input name="description" :label="__('Description')" textarea rows="3" />
                </div>
            </div>

            <div class="card form-section">
                <div class="card-header">{{ __('Button') }}</div>
                <div class="card-body">
                    <x-admin.tr-input name="button_text" :label="__('Button text')" :hint="__('Leave empty to hide the button.')" />
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label">{{ __('Button link') }}</label>
                            <input type="text" dir="ltr" class="form-control @error('button_link') is-invalid @enderror" wire:model="button_link" placeholder="/ar/contact  {{ __('or') }}  https://...">
                            <div class="form-text">{{ __('Internal links like /contact are opened in the current language automatically.') }}</div>
                            @error('button_link') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">{{ __('Button color') }}</label>
                            <div class="d-flex gap-2">
                                <input type="color" class="form-control form-control-color" wire:model.live="button_color">
                                <input type="text" dir="ltr" class="form-control @error('button_color') is-invalid @enderror" wire:model.live="button_color">
                            </div>
                            @error('button_color') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="sticky-side">
                <div class="card form-section">
                    <div class="card-header">{{ __('Image or video') }} <span class="text-danger">*</span></div>
                    <div class="card-body">
                        <x-admin.media-input :current="$cover" :file="$coverFile" :hint="__('Recommended: 1920 x 1080 image or MP4 video.')" />
                    </div>
                </div>
                <x-admin.publish-card :featured="false" :back="route('admin.sliders.index')" />
            </div>
        </div>
    </div>
</form>
