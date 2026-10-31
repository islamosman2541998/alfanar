@extends('layouts.site')

@section('content')
    @include('site.partials.page-hero', ['title' => txt('services.title'), 'subtitle' => txt('services.subtitle')])

    <section class="section">
        <div class="container">
            <div class="row g-4">
                @forelse ($services as $service)
                    <div class="col-md-6 col-lg-4" data-reveal>
                        <x-site.service-card :service="$service" :index="$loop->index" />
                    </div>
                @empty
                    <p class="empty-text">{{ txt('general.empty') }}</p>
                @endforelse
            </div>
        </div>
    </section>

    @include('site.partials.cta')
@endsection
