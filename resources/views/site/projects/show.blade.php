@extends('layouts.site')

@section('content')
    @include('site.partials.page-hero', [
        'title' => $project->tr('title'),
        'subtitle' => $project->tr('excerpt'),
        'image' => ! $project->coverIsVideo() ? $project->coverUrl() : null,
    ])

    <section class="section">
        <div class="container">
            <dl class="project-meta" data-reveal>
                @if ($project->service)
                    <div><dt>{{ txt('projects.service') }}</dt><dd><a href="{{ $project->service->url() }}">{{ $project->service->tr('title') }}</a></dd></div>
                @endif
                @if ($location = $project->tr('location'))
                    <div><dt>{{ txt('projects.location') }}</dt><dd>{{ $location }}</dd></div>
                @endif
                @if ($project->year)
                    <div><dt>{{ txt('projects.year') }}</dt><dd>{{ $project->year }}</dd></div>
                @endif
                <div><dt>{{ txt('projects.media') }}</dt><dd>{{ $project->gallery->count() }}</dd></div>
            </dl>

            <div class="row g-5">
                <div @class(['col-lg-8' => $project->tr('description'), 'col-12' => ! $project->tr('description')])>
                    @if ($project->hasCover())
                        <div class="detail-media" data-reveal>
                            <x-site.media :media="$project->cover" video="controls" :alt="$project->tr('title')" sizes="(min-width: 992px) 66vw, 100vw" />
                        </div>
                    @endif
                </div>
                @if ($description = $project->tr('description'))
                    <div class="col-lg-4">
                        <div class="prose" data-reveal>{!! paragraphs($description) !!}</div>
                        <a href="{{ route('contact') }}" class="btn-site mt-3">{{ txt('buttons.similar_project') }}</a>
                    </div>
                @endif
            </div>

            @if ($project->gallery->isNotEmpty())
                <h2 class="block-title">{{ txt('projects.gallery') }}</h2>
                @include('site.partials.gallery', ['items' => $project->gallery, 'group' => 'project-'.$project->id, 'label' => $project->tr('title')])
            @endif
        </div>
    </section>

    <div class="section--stone">
        @include('site.sections.projects', [
            'projects' => $related,
            'eyebrow' => txt('projects.title'),
            'title' => txt('projects.related'),
            'text' => null,
        ])
    </div>

    @include('site.partials.cta')
@endsection
