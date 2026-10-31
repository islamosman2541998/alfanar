@props(['eyebrow' => null, 'title', 'text' => null, 'center' => false, 'light' => false])

<div {{ $attributes->class(['section-head', 'is-center' => $center, 'is-light' => $light]) }} data-reveal>
    <div class="section-head-text">
        @if ($eyebrow)
            <span class="eyebrow">{{ $eyebrow }}</span>
        @endif
        <h2 class="section-title">{{ $title }}</h2>
        @if ($text)
            <p class="section-text">{{ $text }}</p>
        @endif
    </div>
    @if ($slot->isNotEmpty())
        <div class="section-head-actions">{{ $slot }}</div>
    @endif
</div>
