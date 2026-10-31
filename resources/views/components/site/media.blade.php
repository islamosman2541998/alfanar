@props(['media' => null, 'eager' => false, 'thumb' => false, 'video' => 'inline', 'alt' => '', 'sizes' => '100vw'])

{{--
    Renders a cover array {type, path, thumb, poster} or a Media model.
    thumb:  use the card-size image
    video:  inline (muted loop, played by the slider) | inview (muted loop, plays while on screen)
            | controls | poster (image only)
--}}
@php
    $m = $media instanceof \App\Models\Media ? $media->only(['type', 'path', 'thumb', 'poster']) : (array) $media;
    $isVideo = ($m['type'] ?? null) === 'video';
    $full = media_url($m['path'] ?? null);
    $small = media_url($m['thumb'] ?? null);
    $poster = media_url($m['poster'] ?? null);
@endphp

@if ($full)
    @if ($isVideo && $video !== 'poster')
        <video {{ $attributes->merge(['class' => 'media-el']) }}
               @if ($poster) poster="{{ $poster }}" @endif
               playsinline muted
               @if ($video === 'controls') controls preload="metadata" @else loop preload="{{ $eager ? 'auto' : 'none' }}" @if ($eager) autoplay @endif @if ($video === 'inview') data-inview @endif @endif>
            <source src="{{ $full }}{{ $poster ? '' : '#t=0.5' }}" type="video/{{ pathinfo($full, PATHINFO_EXTENSION) === 'webm' ? 'webm' : 'mp4' }}">
        </video>
    @elseif ($isVideo)
        @if ($poster)
            <img src="{{ $poster }}" alt="{{ $alt }}" loading="{{ $eager ? 'eager' : 'lazy' }}" decoding="async" {{ $attributes->merge(['class' => 'media-el']) }}>
        @else
            <video src="{{ $full }}#t=0.5" preload="metadata" muted playsinline {{ $attributes->merge(['class' => 'media-el']) }}></video>
        @endif
    @else
        <img src="{{ $thumb && $small ? $small : $full }}"
             @if ($small && ! $thumb) srcset="{{ $small }} 900w, {{ $full }} 2400w" sizes="{{ $sizes }}" @endif
             alt="{{ $alt }}" loading="{{ $eager ? 'eager' : 'lazy' }}" decoding="async"
             @if ($eager) fetchpriority="high" @endif
             {{ $attributes->merge(['class' => 'media-el']) }}>
    @endif
@endif
