@extends('layouts.site')

@section('content')
    @include('site.partials.page-hero', ['title' => txt('faq.title'), 'subtitle' => txt('faq.subtitle')])

    <section class="section">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-8">
                    <div class="faq-search">
                        <x-icon name="search" :size="16" />
                        <input type="search" id="faqSearch" placeholder="{{ txt('faq.search') }}" aria-label="{{ txt('faq.search') }}">
                    </div>

                    <div class="faq-list" id="faqList">
                        @foreach ($faqs as $faq)
                            <div class="faq-item" data-reveal>
                                <button class="faq-question collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq{{ $faq->id }}" aria-expanded="false" aria-controls="faq{{ $faq->id }}">
                                    <span>{{ $faq->tr('question') }}</span>
                                    <x-icon name="plus-lg" :size="14" class="toggle-icon" />
                                </button>
                                <div id="faq{{ $faq->id }}" class="collapse" data-bs-parent="#faqList">
                                    <div class="faq-answer">{!! paragraphs($faq->tr('answer')) !!}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <p class="empty-text d-none" id="faqEmpty">{{ txt('faq.no_results') }}</p>
                </div>

                <aside class="col-lg-4">
                    <div class="side-sticky">
                        <div class="side-box side-box--dark">
                            <h3 class="side-title">{{ txt('faq.ask_title') }}</h3>
                            <p>{{ txt('faq.ask_text') }}</p>
                            <a href="{{ route('contact') }}" class="btn-site w-100">{{ txt('nav.cta') }}</a>
                            @if ($wa = whatsapp_url())
                                <a href="{{ $wa }}" target="_blank" rel="noopener" class="side-phone"><x-icon name="whatsapp" :size="15" /> {{ txt('contact.whatsapp_cta') }}</a>
                            @endif
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </section>
@endsection

@push('head')
    @if ($faqs->isNotEmpty())
        {!! \App\Support\Seo::jsonLd([
                '@type' => 'FAQPage',
                'mainEntity' => $faqs->map(fn ($faq) => [
                    '@type' => 'Question',
                    'name' => $faq->tr('question'),
                    'acceptedAnswer' => ['@type' => 'Answer', 'text' => $faq->tr('answer')],
                ])->all(),
            ]) !!}
    @endif
@endpush
