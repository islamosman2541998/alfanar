<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

/**
 * Stores {"ar": "...", "en": "..."} as readable (unescaped) JSON so MySQL
 * searches and exports keep the Arabic text intact.
 */
class Translatable implements CastsAttributes
{
    public function get(Model $model, string $key, mixed $value, array $attributes): array
    {
        if ($value === null || $value === '') {
            return [];
        }

        $decoded = json_decode($value, true);

        return is_array($decoded) ? $decoded : [config('app.locale') => $value];
    }

    public function set(Model $model, string $key, mixed $value, array $attributes): ?string
    {
        if ($value === null) {
            return null;
        }

        if (! is_array($value)) {
            $value = [app()->getLocale() => $value];
        }

        $value = array_map(fn ($v) => is_string($v) ? trim($v) : $v, $value);

        return json_encode($value, JSON_UNESCAPED_UNICODE);
    }
}
