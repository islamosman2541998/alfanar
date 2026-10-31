@props(['title' => 'meta_title', 'description' => 'meta_description', 'fallbackTitle' => null, 'fallbackDescription' => null, 'path' => '', 'locale' => 'ar'])

{{-- Live preview of how the page appears in Google results --}}
<div class="serp" dir="{{ $locale === 'ar' ? 'rtl' : 'ltr' }}"
     x-data="{
        get t() { return ($wire.{{ $title }}?.{{ $locale }} || {{ $fallbackTitle ? '$wire.'.$fallbackTitle.'?.'.$locale : "''" }} || '') },
        get d() { return ($wire.{{ $description }}?.{{ $locale }} || {{ $fallbackDescription ? '$wire.'.$fallbackDescription.'?.'.$locale : "''" }} || '') },
     }">
    <div class="serp-site">
        @if ($favicon = setting('favicon') ?: setting('logo'))
            <img src="{{ media_url($favicon) }}" alt="">
        @endif
        <div>
            {{ setting('site_name', config('app.name')) }}
            <small dir="ltr">{{ rtrim(config('app.url'), '/') }}/{{ $locale }}{{ $path }}</small>
        </div>
    </div>
    <div class="serp-title" x-text="t ? t + ' | {{ addslashes((\App\Support\Settings::raw('site_name') ?? [])[$locale] ?? config('app.name')) }}' : '{{ __('Page title') }}'"></div>
    <div class="serp-desc" x-text="d || '{{ __('Page description will appear here.') }}'"></div>
</div>
