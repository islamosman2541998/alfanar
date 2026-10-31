@php
    use App\Support\Menu;

    $columns = [
        ['title' => txt('footer.links_1'), 'items' => Menu::tree('footer_1')],
        ['title' => txt('footer.links_2'), 'items' => Menu::tree('footer_2')],
    ];
@endphp

<footer class="site-footer">
    <div class="container">
        <div class="row g-4 g-lg-5 footer-top">
            <div class="col-lg-4">
                <a href="{{ route('home') }}" class="brand brand--footer">
                    @if ($logo = setting('logo'))
                        <img src="{{ media_url($logo) }}" alt="{{ setting('site_name') }}" width="60" height="60" loading="lazy">
                    @endif
                    <span class="brand-text"><strong>{{ setting('site_name') }}</strong><small>{{ setting('site_tagline') }}</small></span>
                </a>
                <p class="footer-about">{{ setting('footer_about') }}</p>
                @include('site.partials.social')
            </div>

            @foreach ($columns as $column)
                @if ($column['items'])
                    <div class="col-6 col-lg-2">
                        <h3 class="footer-title">{{ $column['title'] }}</h3>
                        <ul class="footer-links">
                            @foreach ($column['items'] as $node)
                                @if ($url = Menu::url($node))
                                    <li><a href="{{ $url }}" @if ($node['new_tab']) target="_blank" rel="noopener" @endif>{{ Menu::title($node) }}</a></li>
                                @endif
                                @foreach ($node['children'] as $child)
                                    <li><a href="{{ Menu::url($child) ?? '#' }}">{{ Menu::title($child) }}</a></li>
                                @endforeach
                            @endforeach
                        </ul>
                    </div>
                @endif
            @endforeach

            <div class="col-lg-4">
                <h3 class="footer-title">{{ txt('footer.contact') }}</h3>
                <ul class="footer-contact">
                    @if ($phone = setting('phone'))
                        <li><x-icon name="telephone" :size="15" /><a href="tel:{{ preg_replace('/\s+/', '', $phone) }}" dir="ltr">{{ $phone }}</a></li>
                    @endif
                    @if ($email = setting('email'))
                        <li><x-icon name="envelope" :size="15" /><a href="mailto:{{ $email }}">{{ $email }}</a></li>
                    @endif
                    @if ($address = setting('address'))
                        <li><x-icon name="geo-alt" :size="15" /><span>{{ $address }}</span></li>
                    @endif
                    @if ($hours = setting('working_hours'))
                        <li><x-icon name="clock" :size="15" /><span>{{ $hours }}</span></li>
                    @endif
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            <span>{{ str_replace(':year', now()->year, (string) setting('copyright', '')) }}</span>
            <a href="{{ localized_url(is_rtl() ? 'en' : 'ar') }}">{{ is_rtl() ? 'English' : 'العربية' }}</a>
        </div>
    </div>
</footer>
