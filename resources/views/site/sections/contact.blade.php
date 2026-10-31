{{-- Contact info + form: home page section and contact page --}}
<section class="section contact-section">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-5">
                <div class="contact-info" data-reveal>
                    <span class="eyebrow">{{ txt('home.contact.eyebrow') }}</span>
                    <h2 class="section-title">{{ txt('home.contact.title') }}</h2>
                    <p class="section-text">{{ txt('home.contact.text') }}</p>

                    <ul class="contact-list">
                        @if ($phone = setting('phone'))
                            <li>
                                <span class="contact-icon"><x-icon name="telephone" :size="16" /></span>
                                <span><small>{{ txt('contact.phone') }}</small>
                                    <a href="tel:{{ preg_replace('/\s+/', '', $phone) }}" dir="ltr">{{ $phone }}</a>
                                    @if ($phone2 = setting('phone_2'))
                                        <a href="tel:{{ preg_replace('/\s+/', '', $phone2) }}" dir="ltr">{{ $phone2 }}</a>
                                    @endif
                                </span>
                            </li>
                        @endif
                        @if ($wa = whatsapp_url())
                            <li>
                                <span class="contact-icon"><x-icon name="whatsapp" :size="16" /></span>
                                <span><small>{{ txt('contact.whatsapp') }}</small><a href="{{ $wa }}" target="_blank" rel="noopener">{{ txt('contact.whatsapp_cta') }}</a></span>
                            </li>
                        @endif
                        @if ($email = setting('email'))
                            <li>
                                <span class="contact-icon"><x-icon name="envelope" :size="16" /></span>
                                <span><small>{{ txt('contact.email') }}</small><a href="mailto:{{ $email }}">{{ $email }}</a></span>
                            </li>
                        @endif
                        @if ($address = setting('address'))
                            <li>
                                <span class="contact-icon"><x-icon name="geo-alt" :size="16" /></span>
                                <span><small>{{ txt('contact.address') }}</small>{{ $address }}</span>
                            </li>
                        @endif
                        @if ($hours = setting('working_hours'))
                            <li>
                                <span class="contact-icon"><x-icon name="clock" :size="16" /></span>
                                <span><small>{{ txt('contact.hours') }}</small>{{ $hours }}</span>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="contact-card" data-reveal>
                    <h3 class="contact-card-title">{{ txt('contact.form.title') }}</h3>
                    <livewire:site.contact-form :service="$serviceId ?? null" />
                </div>
            </div>
        </div>
    </div>
</section>
