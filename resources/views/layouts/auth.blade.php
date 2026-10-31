<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ is_rtl() ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>{{ $title ?? __('Login') }} - {{ setting('site_name', config('app.name')) }}</title>
    @include('partials.brand-head')
    @vite([is_rtl() ? 'resources/css/bootstrap.rtl.css' : 'resources/css/bootstrap.ltr.css', 'resources/css/admin.css'])
</head>
<body>
    {{ $slot }}
    <script>window.__toast = {{ Js::from(session("toast")) }};</script>
    @vite('resources/js/admin.js')
</body>
</html>
