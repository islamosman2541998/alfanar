@if ($services->isNotEmpty())
    <section class="section section--stone services-section">
        <div class="container">
            <x-site.section-head :eyebrow="txt('home.services.eyebrow')" :title="txt('home.services.title')" :text="txt('home.services.text')">
                <a href="{{ route('services.index') }}" class="link-line d-none d-md-inline-block">{{ txt('buttons.all_services') }}</a>
                <x-site.slider-nav id="servicesSwiper" />
            </x-site.section-head>

            <div class="cards-slider" data-reveal>
                <div class="swiper cards-swiper" id="servicesSwiper" data-cards="services">
                    <div class="swiper-wrapper">
                        @foreach ($services as $service)
                            <div class="swiper-slide">
                                <x-site.service-card :service="$service" :index="$loop->index" />
                            </div>
                        @endforeach
                    </div>
                </div>
                <x-site.slider-nav id="servicesSwiper" side />
            </div>

            <div class="text-center mt-4 d-md-none">
                <a href="{{ route('services.index') }}" class="link-line">{{ txt('buttons.all_services') }}</a>
            </div>
        </div>
    </section>
@endif
