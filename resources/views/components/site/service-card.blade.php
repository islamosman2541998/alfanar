@props(['service', 'index' => 0])

<article class="service-card">
    <a href="{{ $service->url() }}" class="service-card-media" tabindex="-1" aria-hidden="true">
        <x-site.media :media="$service->cover" thumb video="inview" :alt="$service->tr('title')" />
    </a>
    <div class="service-card-body">
        <span class="card-index">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
        <h3 class="card-title"><a href="{{ $service->url() }}">{{ $service->tr('title') }}</a></h3>
        @if ($excerpt = $service->tr('excerpt'))
            <p class="card-text">{{ $excerpt }}</p>
        @endif
        <a href="{{ $service->url() }}" class="link-line">{{ txt('buttons.details') }}</a>
    </div>
</article>
