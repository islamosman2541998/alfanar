@if ($sliders->isNotEmpty())
    <section class="hero" aria-label="{{ setting('site_name') }}">
        <div class="swiper hero-swiper" id="heroSwiper">
            <div class="swiper-wrapper">
                @foreach ($sliders as $slide)
                    <div class="swiper-slide hero-slide">
                        <div class="hero-media">
                            <x-site.media :media="$slide->cover" :eager="$loop->first" :alt="$slide->tr('title')" />
                        </div>
                        <div class="hero-overlay"></div>
                        <div class="container hero-content">
                            <div class="hero-text-wrap">
                                <span class="hero-tag">{{ setting('site_name') }}</span>
                                @if ($loop->first)
                                    <h1 class="hero-title">{{ $slide->tr('title') }}</h1>
                                @else
                                    <h2 class="hero-title">{{ $slide->tr('title') }}</h2>
                                @endif
                                @if ($text = $slide->tr('description'))
                                    <p class="hero-text">{{ $text }}</p>
                                @endif
                                @if (($label = $slide->tr('button_text')) && $slide->button_link)
                                    <a href="{{ site_link($slide->button_link) }}" class="btn-site hero-btn"
                                       style="--btn-bg: {{ $slide->button_color ?: 'var(--brand)' }}">{{ $label }}</a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if ($sliders->count() > 1)
                <div class="container hero-controls">
                    <div class="hero-counter">
                        <span class="current">01</span>
                        <span class="hero-progress"><span></span></span>
                        <span class="total">{{ str_pad($sliders->count(), 2, '0', STR_PAD_LEFT) }}</span>
                    </div>
                    <x-site.slider-nav id="heroSwiper" light />
                </div>
            @endif
        </div>
    </section>
@endif
