<?php

namespace App\Support;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

class Settings
{
    protected const CACHE_KEY = 'site.settings';

    protected static ?array $loaded = null;

    public static function all(): array
    {
        return static::$loaded ??= Cache::rememberForever(static::CACHE_KEY, function () {
            if (! Schema::hasTable('settings')) {
                return [];
            }

            return Setting::query()->pluck('value', 'key')
                ->map(fn ($value) => static::decode($value))
                ->all();
        });
    }

    /** Raw value: translatable settings come back as ['ar' => .., 'en' => ..]. */
    public static function raw(string $key, mixed $default = null): mixed
    {
        return static::all()[$key] ?? $default;
    }

    /** Value for the active locale (falls back to the other language). */
    public static function get(string $key, mixed $default = null): mixed
    {
        $value = static::raw($key);

        if (is_array($value) && (array_key_exists('ar', $value) || array_key_exists('en', $value))) {
            $value = filled($value[app()->getLocale()] ?? null)
                ? $value[app()->getLocale()]
                : collect($value)->first(fn ($v) => filled($v));
        }

        return filled($value) ? $value : $default;
    }

    public static function set(array $values): void
    {
        foreach ($values as $key => $value) {
            Setting::updateOrCreate(['key' => $key], [
                'value' => is_array($value) ? json_encode($value, JSON_UNESCAPED_UNICODE) : $value,
            ]);
        }

        static::flush();
    }

    public static function flush(): void
    {
        static::$loaded = null;
        Cache::forget(static::CACHE_KEY);
    }

    protected static function decode(?string $value): mixed
    {
        if ($value !== null && str_starts_with($value, '{')) {
            $decoded = json_decode($value, true);

            return is_array($decoded) ? $decoded : $value;
        }

        return $value;
    }
}
