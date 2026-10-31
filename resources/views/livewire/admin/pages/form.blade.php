<form wire:submit="save">
    <x-admin.page-header :title="$recordId ? __('Edit page') : __('Add page')" />

    <div class="row g-3">
        <div class="col-lg-8">
            <div class="card form-section">
                <div class="card-header">{{ __('Content') }}</div>
                <div class="card-body">
                    <x-admin.tr-input name="title" :label="__('Title')" required />
                    <x-admin.tr-input name="description" :label="__('Description')" textarea rows="9" :hint="__('Leave an empty line between paragraphs.')" />
                </div>
            </div>

            <div class="card form-section">
                <div class="card-header">{{ __('Gallery') }}</div>
                <div class="card-body">
                    <x-admin.gallery :items="$galleryItems" :uploads="$galleryUploads" />
                </div>
            </div>

            <x-admin.seo-card :path="'/p/'.$slug" fallback-description="description" />
        </div>

        <div class="col-lg-4">
            <div class="sticky-side">
                <div class="card form-section">
                    <div class="card-header">{{ __('Image or video') }}</div>
                    <div class="card-body">
                        <x-admin.media-input :current="$cover" :file="$coverFile" />
                    </div>
                </div>
                <x-admin.publish-card :back="route('admin.pages.index')">
                    <label class="form-label mt-3" for="slug">{{ __('Link (slug)') }}</label>
                    <input type="text" id="slug" dir="ltr" class="form-control @error('slug') is-invalid @enderror" wire:model="slug" placeholder="{{ __('Generated automatically') }}">
                    @error('slug') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    @if ($recordId)
                        <a href="{{ route('admin.menus') }}" class="d-block small mt-2 text-brand">{{ __('Add this page to the menu') }}</a>
                    @endif
                </x-admin.publish-card>
            </div>
        </div>
    </div>
</form>
