@php
    $isHome = request()->routeIs('home');
    $preloader = setting('preloader', 'home');
    $showPreloader = $preloader === 'all' || ($preloader === 'home' && $isHome);
@endphp
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ is_rtl() ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="{{ setting('color_dark', '#1f1e1c') }}">
    <script>document.documentElement.classList.add('js')</script>
    @include('site.partials.seo')
    @include('partials.brand-head')
    @vite([is_rtl() ? 'resources/css/bootstrap.rtl.css' : 'resources/css/bootstrap.ltr.css', 'resources/css/site.css'])
    @stack('head')
    @include('site.partials.pixels-head')
</head>
<body @class(['is-home' => $isHome, 'is-loading' => $showPreloader])>
    @include('site.partials.pixels-body')

    @if ($showPreloader)
        @include('site.partials.preloader')
    @endif

    @include('site.partials.header', ['transparent' => $isHome])

    <main id="main">
        @yield('content')
    </main>

    @include('site.partials.footer')

    @if (setting('whatsapp_button', '1') && ($wa = whatsapp_url()))
        <a href="{{ $wa }}" class="whatsapp-float" target="_blank" rel="noopener" aria-label="WhatsApp">
            <x-icon name="whatsapp" :size="26" />
        </a>
    @endif

    @php
        $siteConfig = [
            'sliderDelay' => (int) setting('slider_delay', 6) * 1000,
            'cardsDelay' => (int) setting('cards_delay', 4) * 1000,
        ];
    @endphp
    <script>window.siteConfig = {{ Js::from($siteConfig) }};</script>
    @vite('resources/js/site.js')
    @stack('scripts')
    {!! setting('body_code') !!}
</body>
</html>
