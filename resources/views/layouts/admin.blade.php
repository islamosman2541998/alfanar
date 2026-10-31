<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ is_rtl() ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow">
    <title>{{ isset($title) ? $title.' | ' : '' }}{{ __('Dashboard') }} - {{ setting('site_name', config('app.name')) }}</title>
    @include('partials.brand-head')
    @vite([is_rtl() ? 'resources/css/bootstrap.rtl.css' : 'resources/css/bootstrap.ltr.css', 'resources/css/admin.css'])
</head>
<body>
    @include('layouts.admin.sidebar')
    <div class="sidebar-backdrop"></div>

    <div class="main">
        @include('layouts.admin.topbar')

        <main class="content">
            {{ $slot }}
        </main>

        <footer class="admin-footer">
            {{ setting('site_name', config('app.name')) }} &middot; {{ now()->year }}
        </footer>
    </div>

    @php
        $i18n = [
            'confirmTitle' => __('Are you sure?'),
            'confirmText' => __('This action cannot be undone.'),
            'confirmButton' => __('Yes, delete'),
            'cancelButton' => __('Cancel'),
        ];
    @endphp
    <script>
        window.i18n = {{ Js::from($i18n) }};
        window.__toast = {{ Js::from(session('toast')) }};
    </script>
    @vite('resources/js/admin.js')
</body>
</html>
