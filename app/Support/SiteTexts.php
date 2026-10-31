<?php

namespace App\Support;

use App\Models\SiteText;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

class SiteTexts
{
    protected const CACHE_KEY = 'site.texts';

    protected static ?array $loaded = null;

    public static function all(): array
    {
        return static::$loaded ??= Cache::rememberForever(static::CACHE_KEY, function () {
            if (! Schema::hasTable('site_texts')) {
                return [];
            }

            return SiteText::query()->get(['key', 'value'])->pluck('value', 'key')->all();
        });
    }

    public static function get(string $key, array $replace = []): string
    {
        $values = static::all()[$key] ?? [];
        $text = $values[app()->getLocale()] ?? '';
        $text = filled($text) ? $text : collect($values)->first(fn ($v) => filled($v), '');

        foreach ($replace as $search => $value) {
            $text = str_replace(':'.$search, $value, $text);
        }

        return (string) $text;
    }

    public static function flush(): void
    {
        static::$loaded = null;
        Cache::forget(static::CACHE_KEY);
    }
}
