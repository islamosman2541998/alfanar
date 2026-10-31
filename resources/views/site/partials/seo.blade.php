@php
    $seo = seo();
    $title = $seo->fullTitle();
    $description = $seo->metaDescription();
    $image = $seo->imageUrl();
    $canonical = url()->current().((int) request()->query('page') > 1 ? '?page='.(int) request()->query('page') : '');
@endphp
<title>{{ $title }}</title>
<meta name="description" content="{{ $description }}">
@if ($keywords = setting('seo_keywords'))
    <meta name="keywords" content="{{ $keywords }}">
@endif
<meta name="robots" content="{{ setting('seo_indexing', '1') ? 'index, follow, max-image-preview:large' : 'noindex, nofollow' }}">
<link rel="canonical" href="{{ $canonical }}">
@foreach (array_keys(locales()) as $locale)
    <link rel="alternate" hreflang="{{ $locale }}" href="{{ localized_url($locale) }}">
@endforeach
<link rel="alternate" hreflang="x-default" href="{{ localized_url(setting('default_locale', 'ar')) }}">

<meta property="og:type" content="{{ $seo->type }}">
<meta property="og:site_name" content="{{ setting('site_name') }}">
<meta property="og:title" content="{{ $title }}">
<meta property="og:description" content="{{ $description }}">
<meta property="og:url" content="{{ $canonical }}">
<meta property="og:locale" content="{{ is_rtl() ? 'ar_EG' : 'en_US' }}">
<meta property="og:locale:alternate" content="{{ is_rtl() ? 'en_US' : 'ar_EG' }}">
@if ($image)
    <meta property="og:image" content="{{ $image }}">
    <meta name="twitter:image" content="{{ $image }}">
@endif
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $title }}">
<meta name="twitter:description" content="{{ $description }}">

@if ($google = setting('google_verification'))
    <meta name="google-site-verification" content="{{ $google }}">
@endif
@if ($bing = setting('bing_verification'))
    <meta name="msvalidate.01" content="{{ $bing }}">
@endif

{!! \App\Support\Seo::jsonLd($seo->graph()) !!}
