@extends('layouts.site')

@section('content')
    @include('site.partials.page-hero', ['title' => txt('contact.title'), 'subtitle' => txt('contact.subtitle')])

    @include('site.sections.contact')

    @if ($map = setting('map_embed'))
        <section class="map-wrap">
            <iframe src="{{ $map }}" title="{{ txt('contact.address') }}" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>
        </section>
    @endif
@endsection
