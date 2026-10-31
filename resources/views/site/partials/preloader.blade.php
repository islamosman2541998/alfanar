<div class="preloader" id="preloader" role="status" aria-label="{{ txt('general.loading') }}">
    <div class="preloader-mark">
        <svg class="preloader-ring" viewBox="0 0 120 120" aria-hidden="true">
            <circle cx="60" cy="60" r="56" />
            <circle class="arc" cx="60" cy="60" r="56" />
        </svg>
        @if ($logo = setting('logo'))
            <img src="{{ media_url($logo) }}" alt="" width="84" height="84">
        @endif
    </div>
    <div class="preloader-name">{{ setting('site_name') }}</div>
</div>
