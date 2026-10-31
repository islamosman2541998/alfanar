@extends('layouts.site')

@section('content')
    @include('site.partials.page-hero', ['title' => txt('projects.title'), 'subtitle' => txt('projects.subtitle')])

    <section class="section">
        <div class="container">
            @if ($services->count() > 1)
                <nav class="filter-bar" aria-label="{{ txt('projects.filter') }}">
                    <a href="{{ route('projects.index') }}" @class(['filter-chip', 'active' => ! $current])>{{ txt('projects.all') }}</a>
                    @foreach ($services as $service)
                        <a href="{{ route('projects.index', ['service' => $service->slug]) }}" @class(['filter-chip', 'active' => $current?->is($service)])>{{ $service->tr('title') }}</a>
                    @endforeach
                </nav>
            @endif

            <div class="row g-4">
                @forelse ($projects as $project)
                    <div class="col-sm-6 col-lg-4" data-reveal>
                        <x-site.project-card :project="$project" />
                    </div>
                @empty
                    <p class="empty-text">{{ txt('general.empty') }}</p>
                @endforelse
            </div>

            @if ($projects->hasPages())
                <div class="site-pagination">{{ $projects->onEachSide(1)->links() }}</div>
            @endif
        </div>
    </section>

    @include('site.partials.cta')
@endsection
