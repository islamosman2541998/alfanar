@props(['project'])

<article class="project-card">
    <a href="{{ $project->url() }}" class="project-card-media">
        <x-site.media :media="$project->cover" thumb video="poster" :alt="$project->tr('title')" />
        @if ($project->coverIsVideo())
            <span class="media-badge"><x-icon name="play-fill" :size="12" /></span>
        @endif
    </a>
    <div class="project-card-body">
        @if ($project->service)
            <span class="card-meta">{{ $project->service->tr('title') }}</span>
        @endif
        <h3 class="card-title"><a href="{{ $project->url() }}">{{ $project->tr('title') }}</a></h3>
        @if ($location = $project->tr('location'))
            <span class="card-location">{{ $location }}</span>
        @endif
    </div>
</article>
