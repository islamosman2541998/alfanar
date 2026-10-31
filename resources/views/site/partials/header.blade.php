@php
    use App\Support\Menu;

    $menu = Menu::tree('header');
    $other = is_rtl() ? 'en' : 'ar';
@endphp

<header @class(['site-header', 'is-transparent' => $transparent]) id="siteHeader">
    <div class="container header-inner">
        <a href="{{ route('home') }}" class="brand" aria-label="{{ setting('site_name') }}">
            @if ($logo = setting('logo'))
                <img src="{{ media_url($logo) }}" alt="{{ setting('site_name') }}" width="52" height="52">
            @endif
            <span class="brand-text">
                <strong>{{ setting('site_name') }}</strong>
                <small>{{ setting('site_tagline') }}</small>
            </span>
        </a>

        <nav class="main-nav" aria-label="{{ txt('nav.main') }}">
            <ul>
                @foreach ($menu as $node)
                    @php $url = Menu::url($node); @endphp
                    <li @class(['nav-item', 'has-children' => $node['children']])>
                        <a href="{{ $url ?? '#' }}" @class(['nav-link', 'active' => Menu::isActive($node)])
                           @if ($node['new_tab']) target="_blank" rel="noopener" @endif
                           @if (! $url) role="button" aria-haspopup="true" @endif>
                            {{ Menu::title($node) }}
                            @if ($node['children'])
                                <x-icon name="chevron-down" :size="10" class="caret" />
                            @endif
                        </a>
                        @if ($node['children'])
                            <ul class="dropdown-panel">
                                @foreach ($node['children'] as $child)
                                    <li>
                                        <a href="{{ Menu::url($child) ?? '#' }}" @class(['active' => Menu::isActive($child)])
                                           @if ($child['new_tab']) target="_blank" rel="noopener" @endif>{{ Menu::title($child) }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </li>
                @endforeach
            </ul>
        </nav>

        <div class="header-actions">
            <a href="{{ localized_url($other) }}" class="lang-switch" hreflang="{{ $other }}" lang="{{ $other }}">
                {{ $other === 'en' ? 'EN' : 'عربي' }}
            </a>
            <a href="{{ route('contact') }}" class="btn-site btn-site--sm d-none d-md-inline-flex">{{ txt('nav.cta') }}</a>
            <button type="button" class="menu-toggle" data-bs-toggle="offcanvas" data-bs-target="#mobileMenu" aria-controls="mobileMenu" aria-label="{{ txt('nav.open_menu') }}">
                <span></span><span></span><span></span>
            </button>
        </div>
    </div>
</header>

{{-- Mobile navigation --}}
<div class="offcanvas offcanvas-end mobile-menu" tabindex="-1" id="mobileMenu" aria-label="{{ txt('nav.main') }}">
    <div class="offcanvas-header">
        <a href="{{ route('home') }}" class="brand">
            @if ($logo = setting('logo'))
                <img src="{{ media_url($logo) }}" alt="" width="44" height="44" loading="lazy">
            @endif
            <span class="brand-text"><strong>{{ setting('site_name') }}</strong></span>
        </a>
        <button type="button" class="mobile-close" data-bs-dismiss="offcanvas" aria-label="{{ txt('nav.close_menu') }}">
            <x-icon name="x-lg" :size="18" />
        </button>
    </div>
    <div class="offcanvas-body">
        <ul class="mobile-nav">
            @foreach ($menu as $i => $node)
                @php $url = Menu::url($node); @endphp
                <li>
                    @if ($node['children'])
                        <button type="button" @class(['mobile-link', 'collapsed', 'active' => Menu::isActive($node)])
                                data-bs-toggle="collapse" data-bs-target="#mobileSub{{ $i }}" aria-expanded="false">
                            {{ Menu::title($node) }}
                            <x-icon name="plus-lg" :size="14" class="toggle-icon" />
                        </button>
                        <ul class="collapse mobile-sub" id="mobileSub{{ $i }}">
                            @if ($url)
                                <li><a href="{{ $url }}">{{ txt('nav.view_all') }}</a></li>
                            @endif
                            @foreach ($node['children'] as $child)
                                <li><a href="{{ Menu::url($child) ?? '#' }}" @class(['active' => Menu::isActive($child)])>{{ Menu::title($child) }}</a></li>
                            @endforeach
                        </ul>
                    @else
                        <a href="{{ $url ?? '#' }}" @class(['mobile-link', 'active' => Menu::isActive($node)])
                           @if ($node['new_tab']) target="_blank" rel="noopener" @endif>{{ Menu::title($node) }}</a>
                    @endif
                </li>
            @endforeach
        </ul>

        <div class="mobile-contact">
            <a href="{{ route('contact') }}" class="btn-site w-100">{{ txt('nav.cta') }}</a>
            @if ($phone = setting('phone'))
                <a href="tel:{{ preg_replace('/\s+/', '', $phone) }}" class="mobile-phone" dir="ltr"><x-icon name="telephone" :size="15" /> {{ $phone }}</a>
            @endif
            <a href="{{ localized_url($other) }}" class="mobile-lang">{{ $other === 'en' ? 'English' : 'العربية' }}</a>
        </div>
    </div>
</div>
