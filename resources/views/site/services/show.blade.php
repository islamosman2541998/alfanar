@extends('layouts.site')

@section('content')
    @include('site.partials.page-hero', [
        'title' => $service->tr('title'),
        'subtitle' => $service->tr('excerpt'),
        'image' => ! $service->coverIsVideo() ? $service->coverUrl() : null,
    ])

    <section class="section">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-8">
                    @if ($service->hasCover())
                        <div class="detail-media" data-reveal>
                            <x-site.media :media="$service->cover" video="controls" :alt="$service->tr('title')" sizes="(min-width: 992px) 66vw, 100vw" />
                        </div>
                    @endif

                    @if ($description = $service->tr('description'))
                        <div class="prose prose--lg" data-reveal>{!! paragraphs($description) !!}</div>
                    @endif

                    @if ($service->gallery->isNotEmpty())
                        <h2 class="block-title">{{ txt('services.gallery') }}</h2>
                        @include('site.partials.gallery', ['items' => $service->gallery, 'group' => 'service-'.$service->id, 'label' => $service->tr('title')])
                    @endif
                </div>

                <aside class="col-lg-4">
                    <div class="side-sticky">
                        @if ($others->isNotEmpty())
                            <div class="side-box">
                                <h3 class="side-title">{{ txt('services.others') }}</h3>
                                <ul class="side-links">
                                    @foreach ($others as $other)
                                        <li><a href="{{ $other->url() }}">{{ $other->tr('title') }}</a></li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div class="side-box side-box--dark">
                            <h3 class="side-title">{{ txt('cta.title') }}</h3>
                            <p>{{ txt('cta.text') }}</p>
                            <a href="{{ route('contact') }}" class="btn-site w-100">{{ txt('buttons.start_project') }}</a>
                            @if ($phone = setting('phone'))
                                <a href="tel:{{ preg_replace('/\s+/', '', $phone) }}" class="side-phone" dir="ltr"><x-icon name="telephone" :size="15" /> {{ $phone }}</a>
                            @endif
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </section>

    @include('site.sections.projects', [
        'projects' => $projects,
        'eyebrow' => $service->tr('title'),
        'title' => txt('services.related_projects'),
        'text' => null,
    ])

    <div class="section--stone">
        @include('site.sections.contact', ['serviceId' => $service->id])
    </div>
@endsection
