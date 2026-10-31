{{-- Inner pages header with breadcrumb --}}
@php
    $bg = $image ?? media_url(setting('page_header_image'));
@endphp
<section class="page-hero">
    @if ($bg)
        <img src="{{ $bg }}" alt="" class="page-hero-bg" fetchpriority="high" decoding="async">
    @endif
    <div class="page-hero-overlay"></div>
    <div class="container page-hero-content">
        <nav aria-label="breadcrumb">
            <ol class="crumbs">
                <li><a href="{{ route('home') }}">{{ txt('nav.home') }}</a></li>
                @foreach (seo()->breadcrumbs as $crumb)
                    <li @if ($loop->last) aria-current="page" @endif>
                        @if ($crumb['url'] && ! $loop->last)
                            <a href="{{ $crumb['url'] }}">{{ $crumb['label'] }}</a>
                        @else
                            {{ $crumb['label'] }}
                        @endif
                    </li>
                @endforeach
            </ol>
        </nav>
        <h1 class="page-hero-title">{{ $title }}</h1>
        @if (! empty($subtitle))
            <p class="page-hero-text">{{ $subtitle }}</p>
        @endif
    </div>
</section>
