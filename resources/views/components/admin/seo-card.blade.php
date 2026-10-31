@props(['path' => '', 'fallbackTitle' => 'title', 'fallbackDescription' => null])

<div class="card form-section">
    <div class="card-header">{{ __('Search engine (SEO)') }}</div>
    <div class="card-body">
        <p class="form-text mt-0 mb-3">{{ __('Leave empty to use the title and summary automatically.') }}</p>
        <x-admin.tr-input name="meta_title" :label="__('Meta title')" :count="60" />
        <x-admin.tr-input name="meta_description" :label="__('Meta description')" textarea rows="3" :count="160" />
        <label class="form-label">{{ __('Google result preview') }}</label>
        <div class="row g-3">
            @foreach (['ar', 'en'] as $locale)
                <div class="col-lg-6">
                    <x-admin.serp-preview :locale="$locale" :path="$path" :fallback-title="$fallbackTitle" :fallback-description="$fallbackDescription" />
                </div>
            @endforeach
        </div>
    </div>
</div>
