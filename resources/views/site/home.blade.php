@extends('layouts.site')

@section('content')
    @include('site.sections.hero')

    @if (setting('home_about', '1'))
        @include('site.sections.about')
    @endif

    @include('site.sections.services')

    @if (setting('home_stats', '1'))
        @include('site.sections.stats')
    @endif

    @include('site.sections.projects')
    @include('site.sections.testimonials')

    @if (setting('home_contact', '1'))
        <div class="section--stone">
            @include('site.sections.contact')
        </div>
    @endif
@endsection
