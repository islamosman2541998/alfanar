@php
    $pageNames = ['home' => __('Home'), 'services' => __('Services'), 'projects' => __('Projects'), 'about' => __('About us'), 'contact' => __('Contact us'), 'faq' => __('FAQ')];
    $pagePaths = ['home' => '', 'services' => '/services', 'projects' => '/projects', 'about' => '/about', 'contact' => '/contact', 'faq' => '/faq'];
    $general = collect($fields)->whereNull('page');
    $perPage = collect($fields)->whereNotNull('page')->groupBy('page');
@endphp

<div>
    <x-admin.page-header :title="__('Settings')" :subtitle="__('Control website information, appearance, SEO and tracking.')" />

    <div class="row g-3">
        <div class="col-lg-3">
            <nav class="nav flex-column settings-nav card p-2">
                @foreach ($tabs as $key => $item)
                    <a href="{{ route('admin.settings', $key) }}" @class(['nav-link', 'active' => $tab === $key])>
                        <i class="bi {{ $item['icon'] }}"></i> {{ __($item['label']) }}
                    </a>
                @endforeach
            </nav>
        </div>

        <div class="col-lg-9">
            <form wire:submit="save">
                <div class="card">
                    <div class="card-header">{{ __($tabs[$tab]['label']) }}</div>
                    <div class="card-body">
                        @foreach ($general as $field)
                            @include('livewire.admin.settings.field', ['field' => $field])
                        @endforeach

                        @if ($tab === 'seo')
                            <div class="mb-4">
                                <label class="form-label">{{ __('Google result preview') }}</label>
                                <div class="row g-3">
                                    @foreach (['ar', 'en'] as $locale)
                                        <div class="col-lg-6"><x-admin.serp-preview :locale="$locale" title="data.seo_title" description="data.seo_description" /></div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                @foreach ($perPage as $page => $pageFields)
                    <div class="card form-section">
                        <div class="card-header">{{ __('SEO') }}: {{ $pageNames[$page] }}</div>
                        <div class="card-body">
                            @foreach ($pageFields as $field)
                                @include('livewire.admin.settings.field', ['field' => $field])
                            @endforeach
                            <div class="row g-3">
                                @foreach (['ar', 'en'] as $locale)
                                    <div class="col-lg-6">
                                        <x-admin.serp-preview :locale="$locale" :path="$pagePaths[$page]" :title="'data.seo_'.$page.'_title'" :description="'data.seo_'.$page.'_description'" />
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach

                <div class="d-flex justify-content-end mt-3">
                    <button type="submit" class="btn btn-brand px-4" wire:loading.attr="disabled" wire:target="save,uploads">
                        <span wire:loading wire:target="save" class="spinner-border spinner-border-sm"></span>
                        {{ __('Save settings') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
