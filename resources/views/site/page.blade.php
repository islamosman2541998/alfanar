@extends('layouts.site')

@section('content')
    @include('site.partials.page-hero', [
        'title' => $page->tr('title'),
        'image' => ! $page->coverIsVideo() ? $page->coverUrl() : null,
    ])

    <section class="section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10 col-xl-9">
                    @if ($page->coverIsVideo())
                        <div class="detail-media" data-reveal>
                            <x-site.media :media="$page->cover" video="controls" :alt="$page->tr('title')" />
                        </div>
                    @endif

                    @if ($description = $page->tr('description'))
                        <div class="prose prose--lg" data-reveal>{!! paragraphs($description) !!}</div>
                    @endif

                    @if ($page->gallery->isNotEmpty())
                        <h2 class="block-title">{{ txt('pages.gallery') }}</h2>
                        @include('site.partials.gallery', ['items' => $page->gallery, 'group' => 'page-'.$page->id, 'label' => $page->tr('title')])
                    @endif
                </div>
            </div>
        </div>
    </section>

    @include('site.partials.cta')
@endsection
