@php
    $unread = \App\Models\Message::where('is_read', false)->count();
    $groups = [
        null => [
            ['route' => 'admin.dashboard', 'icon' => 'bi-grid', 'label' => __('Dashboard')],
        ],
        __('Content') => [
            ['route' => 'admin.sliders.index', 'icon' => 'bi-images', 'label' => __('Slider'), 'match' => 'admin.sliders.*'],
            ['route' => 'admin.services.index', 'icon' => 'bi-tools', 'label' => __('Services'), 'match' => 'admin.services.*'],
            ['route' => 'admin.projects.index', 'icon' => 'bi-building', 'label' => __('Projects'), 'match' => 'admin.projects.*'],
            ['route' => 'admin.pages.index', 'icon' => 'bi-file-earmark-text', 'label' => __('Pages'), 'match' => 'admin.pages.*'],
        ],
        __('Sections') => [
            ['route' => 'admin.testimonials', 'icon' => 'bi-chat-quote', 'label' => __('Testimonials')],
            ['route' => 'admin.stats', 'icon' => 'bi-123', 'label' => __('Statistics')],
            ['route' => 'admin.features', 'icon' => 'bi-check2-square', 'label' => __('Why us')],
            ['route' => 'admin.faqs', 'icon' => 'bi-question-circle', 'label' => __('FAQ')],
        ],
        __('Inbox') => [
            ['route' => 'admin.messages', 'icon' => 'bi-envelope', 'label' => __('Messages'), 'badge' => $unread],
        ],
        __('Website') => [
            ['route' => 'admin.menus', 'icon' => 'bi-list-nested', 'label' => __('Menus')],
            ['route' => 'admin.texts', 'icon' => 'bi-translate', 'label' => __('Site texts')],
            ['route' => 'admin.settings', 'icon' => 'bi-gear', 'label' => __('Settings')],
            ['route' => 'admin.users', 'icon' => 'bi-people', 'label' => __('Admins')],
        ],
    ];
@endphp
<aside class="sidebar">
    <a href="{{ route('admin.dashboard') }}" class="sidebar-brand">
        @if ($logo = setting('logo'))
            <img src="{{ media_url($logo) }}" alt="">
        @endif
        <strong>{{ setting('site_name', config('app.name')) }}</strong>
    </a>
    <nav class="sidebar-nav">
        @foreach ($groups as $group => $links)
            @if ($group)
                <div class="sidebar-title">{{ $group }}</div>
            @endif
            @foreach ($links as $link)
                <a href="{{ route($link['route']) }}" @class(['sidebar-link', 'active' => request()->routeIs($link['match'] ?? $link['route'])])>
                    <i class="bi {{ $link['icon'] }}"></i>
                    <span>{{ $link['label'] }}</span>
                    @if (! empty($link['badge']))
                        <span class="badge rounded-pill">{{ $link['badge'] }}</span>
                    @endif
                </a>
            @endforeach
        @endforeach
    </nav>
</aside>
