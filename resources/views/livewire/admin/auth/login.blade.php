<div class="login-page">
    <div class="login-visual" style="background-image: url('{{ media_url(setting('login_image')) }}')">
        <div class="caption">
            <span></span>
            <h2>{{ setting('login_title', setting('site_name')) }}</h2>
            <p>{{ setting('login_subtitle') }}</p>
        </div>
    </div>

    <div class="login-form position-relative">
        <a href="{{ route('admin.locale', is_rtl() ? 'en' : 'ar') }}" class="login-lang">{{ is_rtl() ? 'English' : 'العربية' }}</a>

        <form class="login-box" wire:submit="login">
            @if ($logo = setting('logo'))
                <img src="{{ media_url($logo) }}" alt="{{ setting('site_name') }}" class="logo">
            @endif
            <h1>{{ __('Sign in to dashboard') }}</h1>
            <p>{{ __('Enter your email and password to continue.') }}</p>

            <div class="mb-3">
                <label class="form-label" for="email">{{ __('Email') }}</label>
                <input type="email" id="email" dir="ltr" autocomplete="username" autofocus
                       class="form-control @error('email') is-invalid @enderror" wire:model="email">
                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3" x-data="{ show: false }">
                <label class="form-label" for="password">{{ __('Password') }}</label>
                <div class="input-group">
                    <input :type="show ? 'text' : 'password'" id="password" dir="ltr" autocomplete="current-password"
                           class="form-control @error('password') is-invalid @enderror" wire:model="password">
                    <button type="button" class="btn btn-light border" x-on:click="show = !show" tabindex="-1">
                        <i class="bi" :class="show ? 'bi-eye-slash' : 'bi-eye'"></i>
                    </button>
                </div>
                @error('password') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
            </div>

            <div class="form-check mb-4">
                <input class="form-check-input" type="checkbox" id="remember" wire:model="remember">
                <label class="form-check-label" for="remember">{{ __('Remember me') }}</label>
            </div>

            <button type="submit" class="btn btn-brand w-100" wire:loading.attr="disabled">
                <span wire:loading class="spinner-border spinner-border-sm"></span>
                {{ __('Sign in') }}
            </button>
        </form>
    </div>
</div>
