@if ($testimonials->isNotEmpty())
    <section class="section section--stone testimonials-section">
        <div class="container">
            <x-site.section-head :eyebrow="txt('home.testimonials.eyebrow')" :title="txt('home.testimonials.title')" :text="txt('home.testimonials.text')">
                <x-site.slider-nav id="testimonialsSwiper" />
            </x-site.section-head>

            <div class="cards-slider" data-reveal>
                <div class="swiper cards-swiper" id="testimonialsSwiper" data-cards="testimonials">
                    <div class="swiper-wrapper">
                        @foreach ($testimonials as $testimonial)
                            <div class="swiper-slide">
                                <x-site.testimonial-card :testimonial="$testimonial" />
                            </div>
                        @endforeach
                    </div>
                </div>
                <x-site.slider-nav id="testimonialsSwiper" side />
            </div>
        </div>
    </section>
@endif
