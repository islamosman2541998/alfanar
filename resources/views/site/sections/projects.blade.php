@if ($projects->isNotEmpty())
    <section class="section projects-section">
        <div class="container">
            <x-site.section-head :eyebrow="$eyebrow ?? txt('home.projects.eyebrow')" :title="$title ?? txt('home.projects.title')" :text="$text ?? txt('home.projects.text')">
                <a href="{{ route('projects.index') }}" class="link-line d-none d-md-inline-block">{{ txt('buttons.all_projects') }}</a>
                <x-site.slider-nav :id="$sliderId ?? 'projectsSwiper'" />
            </x-site.section-head>

            <div class="cards-slider" data-reveal>
                <div class="swiper cards-swiper" id="{{ $sliderId ?? 'projectsSwiper' }}" data-cards="projects">
                    <div class="swiper-wrapper">
                        @foreach ($projects as $project)
                            <div class="swiper-slide">
                                <x-site.project-card :project="$project" />
                            </div>
                        @endforeach
                    </div>
                </div>
                <x-site.slider-nav :id="$sliderId ?? 'projectsSwiper'" side />
            </div>

            <div class="text-center mt-4 d-md-none">
                <a href="{{ route('projects.index') }}" class="link-line">{{ txt('buttons.all_projects') }}</a>
            </div>
        </div>
    </section>
@endif
