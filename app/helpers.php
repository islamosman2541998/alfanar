<?php

use App\Support\Settings;
use App\Support\SiteTexts;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

if (! function_exists('setting')) {
    /** Setting value for the active locale. */
    function setting(string $key, mixed $default = null): mixed
    {
        return Settings::get($key, $default);
    }
}

if (! function_exists('txt')) {
    /** Front-end text managed from the dashboard (Site texts). */
    function txt(string $key, array $replace = []): string
    {
        return SiteTexts::get($key, $replace);
    }
}

if (! function_exists('media_url')) {
    function media_url(?string $path): ?string
    {
        if (blank($path)) {
            return null;
        }

        return str_starts_with($path, 'http') ? $path : Storage::disk('public')->url($path);
    }
}

if (! function_exists('is_rtl')) {
    function is_rtl(?string $locale = null): bool
    {
        return ($locale ?? app()->getLocale()) === 'ar';
    }
}

if (! function_exists('locales')) {
    function locales(): array
    {
        return ['ar' => 'العربية', 'en' => 'English'];
    }
}

if (! function_exists('seo')) {
    function seo(): \App\Support\Seo
    {
        return app(\App\Support\Seo::class);
    }
}

if (! function_exists('site_link')) {
    /** Links entered in the dashboard: internal paths get the current language prefix. */
    function site_link(?string $link): ?string
    {
        if (blank($link) || preg_match('#^(https?:|mailto:|tel:|\#)#', $link)) {
            return $link;
        }

        $path = ltrim($link, '/');

        if (! preg_match('#^(ar|en)(/|$)#', $path)) {
            $path = app()->getLocale().'/'.$path;
        }

        return url($path);
    }
}

if (! function_exists('paragraphs')) {
    /** Plain text from the dashboard as escaped HTML paragraphs. */
    function paragraphs(?string $text): string
    {
        return collect(preg_split('/\R{2,}/u', trim((string) $text)))
            ->filter(fn ($p) => trim($p) !== '')
            ->map(fn ($p) => '<p>'.nl2br(e(trim($p))).'</p>')
            ->implode('');
    }
}

if (! function_exists('whatsapp_url')) {
    function whatsapp_url(?string $text = null): ?string
    {
        $number = preg_replace('/\D/', '', (string) setting('whatsapp'));

        return $number ? 'https://wa.me/'.$number.($text ? '?text='.rawurlencode($text) : '') : null;
    }
}

if (! function_exists('localized_url')) {
    /** Current page URL in another language. */
    function localized_url(string $locale): string
    {
        $route = request()->route();

        if (! $route || ! $route->getName() || ! Route::has($route->getName())) {
            return url($locale);
        }

        $parameters = collect($route->parameters())
            ->map(fn ($value) => $value instanceof \Illuminate\Database\Eloquent\Model ? $value->getRouteKey() : $value)
            ->put('locale', $locale)
            ->all();

        $query = request()->getQueryString();

        return route($route->getName(), $parameters).($query ? '?'.$query : '');
    }
}
