<section class="cta-band">
    <div class="container cta-inner" data-reveal>
        <div>
            <h2>{{ txt('cta.title') }}</h2>
            <p>{{ txt('cta.text') }}</p>
        </div>
        <div class="cta-actions">
            <a href="{{ route('contact') }}" class="btn-site">{{ txt('buttons.start_project') }}</a>
            @if ($wa = whatsapp_url())
                <a href="{{ $wa }}" class="btn-site btn-site--outline-light" target="_blank" rel="noopener">
                    <x-icon name="whatsapp" :size="16" /> {{ txt('contact.whatsapp') }}
                </a>
            @endif
        </div>
    </div>
</section>
