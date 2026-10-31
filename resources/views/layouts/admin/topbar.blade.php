<header class="topbar">
    <button type="button" class="btn btn-light btn-icon d-lg-none" data-sidebar-toggle aria-label="{{ __('Menu') }}">
        <i class="bi bi-list"></i>
    </button>

    <div class="ms-auto d-flex align-items-center gap-2">
        <a href="{{ route('admin.locale', is_rtl() ? 'en' : 'ar') }}" class="btn btn-light btn-sm">
            {{ is_rtl() ? 'English' : 'العربية' }}
        </a>
        <a href="{{ url('/') }}" target="_blank" class="btn btn-light btn-sm d-none d-sm-inline-flex align-items-center gap-1">
            <i class="bi bi-box-arrow-up-right"></i> {{ __('View site') }}
        </a>

        <div class="dropdown">
            <button class="btn p-0 border-0 topbar-user" data-bs-toggle="dropdown" aria-expanded="false">
                <span class="avatar">{{ mb_substr(auth()->user()->name, 0, 1) }}</span>
                <span class="d-none d-md-inline fw-semibold">{{ auth()->user()->name }}</span>
                <i class="bi bi-chevron-down small text-muted"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="{{ route('admin.profile') }}"><i class="bi bi-person me-2"></i>{{ __('Profile') }}</a></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button class="dropdown-item text-danger"><i class="bi bi-box-arrow-left me-2"></i>{{ __('Logout') }}</button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</header>
