<form wire:submit="save">
    <x-admin.page-header :title="$recordId ? __('Edit project') : __('Add project')" />

    <div class="row g-3">
        <div class="col-lg-8">
            <div class="card form-section">
                <div class="card-header">{{ __('Content') }}</div>
                <div class="card-body">
                    <x-admin.tr-input name="title" :label="__('Title')" required />
                    <x-admin.tr-input name="excerpt" :label="__('Summary')" textarea rows="2" :hint="__('Short text shown on cards.')" />
                    <x-admin.tr-input name="description" :label="__('Description')" textarea rows="7" :hint="__('Leave an empty line between paragraphs.')" />
                    <x-admin.tr-input name="location" :label="__('Location')" />
                </div>
            </div>

            <div class="card form-section">
                <div class="card-header">{{ __('Gallery') }}</div>
                <div class="card-body">
                    <x-admin.gallery :items="$galleryItems" :uploads="$galleryUploads" />
                </div>
            </div>

            <x-admin.seo-card :path="'/projects/'.$slug" fallback-description="excerpt" />
        </div>

        <div class="col-lg-4">
            <div class="sticky-side">
                <div class="card form-section">
                    <div class="card-header">{{ __('Image or video') }}</div>
                    <div class="card-body">
                        <x-admin.media-input :current="$cover" :file="$coverFile" />
                    </div>
                </div>
                <x-admin.publish-card :back="route('admin.projects.index')">
                    <label class="form-label mt-3" for="service_id">{{ __('Service') }}</label>
                    <select id="service_id" class="form-select @error('service_id') is-invalid @enderror" wire:model="service_id">
                        <option value="">{{ __('None') }}</option>
                        @foreach ($services as $service)
                            <option value="{{ $service->id }}">{{ $service->tr('title') }}</option>
                        @endforeach
                    </select>

                    <label class="form-label mt-3" for="year">{{ __('Year') }}</label>
                    <input type="text" id="year" class="form-control" wire:model="year" placeholder="{{ now()->year }}">

                    <label class="form-label mt-3" for="slug">{{ __('Link (slug)') }}</label>
                    <input type="text" id="slug" dir="ltr" class="form-control @error('slug') is-invalid @enderror" wire:model="slug" placeholder="{{ __('Generated automatically') }}">
                    @error('slug') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </x-admin.publish-card>
            </div>
        </div>
    </div>
</form>
