{{-- About intro: used on the home page and the about page --}}
@php
    $experience = $stats->first();
@endphp
<section @class(['section about-section', $class ?? null])>
    <div class="container">
        <div class="row g-5 align-items-center">
            <div class="col-lg-6 order-lg-2">
                <div class="about-text" data-reveal>
                    <span class="eyebrow">{{ txt('home.about.eyebrow') }}</span>
                    <h2 class="section-title">{{ txt('home.about.title') }}</h2>
                    <div class="prose">{!! paragraphs(txt('home.about.text')) !!}</div>

                    @if ($features->isNotEmpty())
                        <ul class="check-list">
                            @foreach ($features->take(4) as $feature)
                                <li>{{ $feature->tr('title') }}</li>
                            @endforeach
                        </ul>
                    @endif

                    <div class="about-actions">
                        @if ($withLink ?? true)
                            <a href="{{ route('about') }}" class="btn-site">{{ txt('buttons.about') }}</a>
                        @else
                            <a href="{{ route('contact') }}" class="btn-site">{{ txt('buttons.start_project') }}</a>
                        @endif
                        @if ($phone = setting('phone'))
                            <a href="tel:{{ preg_replace('/\s+/', '', $phone) }}" class="about-call">
                                <span class="about-call-icon"><x-icon name="telephone" :size="16" /></span>
                                <span><small>{{ txt('home.about.call') }}</small><strong dir="ltr">{{ $phone }}</strong></span>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="about-media" data-reveal>
                    @if ($image = setting('about_image'))
                        <img src="{{ media_url($image) }}" alt="{{ txt('home.about.title') }}" class="about-img-main" loading="lazy" decoding="async">
                    @endif
                    @if ($image2 = setting('about_image_2'))
                        <img src="{{ media_url($image2) }}" alt="" class="about-img-small" loading="lazy" decoding="async">
                    @endif
                    @if ($experience)
                        <div class="about-badge">
                            <strong dir="ltr">{{ $experience->value }}{{ $experience->suffix }}</strong>
                            <span>{{ $experience->tr('label') }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
