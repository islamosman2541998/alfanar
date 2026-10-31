@extends('layouts.site')

@section('content')
    @include('site.partials.page-hero', ['title' => txt('about.title'), 'subtitle' => txt('about.subtitle')])

    @include('site.sections.about', ['withLink' => false])

    @include('site.sections.stats')

    @if ($features->isNotEmpty())
        <section class="section">
            <div class="container">
                <x-site.section-head :eyebrow="txt('about.why.eyebrow')" :title="txt('about.why.title')" :text="txt('about.why.text')" />
                <div class="features-grid">
                    @foreach ($features as $feature)
                        <div class="feature" data-reveal>
                            <span class="card-index">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                            <h3>{{ $feature->tr('title') }}</h3>
                            @if ($text = $feature->tr('description'))
                                <p>{{ $text }}</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @include('site.sections.testimonials')
    @include('site.partials.cta')
@endsection
