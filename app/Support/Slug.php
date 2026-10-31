<?php

namespace App\Support;

use Illuminate\Support\Str;

class Slug
{
    /** Unique slug for the model, built from the given text (English title preferred). */
    public static function unique(string $model, ?string $text, ?int $ignoreId = null): string
    {
        $base = Str::slug((string) $text) ?: Str::lower(Str::random(6));
        $slug = $base;
        $i = 2;

        while ($model::where('slug', $slug)->when($ignoreId, fn ($q) => $q->whereKeyNot($ignoreId))->exists()) {
            $slug = $base.'-'.$i++;
        }

        return $slug;
    }
}
