<?php

namespace App\Models\Concerns;

use App\Casts\Translatable;

/**
 * Models list their bilingual columns in `$translatable`.
 * Read the active-locale value with `$model->tr('title')`.
 */
trait HasTranslations
{
    public function initializeHasTranslations(): void
    {
        $this->mergeCasts(array_fill_keys($this->translatable, Translatable::class));
    }

    public function tr(string $field, ?string $locale = null): string
    {
        $values = $this->{$field} ?: [];
        $locale ??= app()->getLocale();

        return (string) ($values[$locale] ?? '') !== ''
            ? (string) $values[$locale]
            : (string) collect($values)->first(fn ($v) => filled($v), '');
    }

    /** Search scope across both languages of the given translatable columns. */
    public function scopeSearchTranslated($query, ?string $term, array $fields)
    {
        if (blank($term)) {
            return $query;
        }

        return $query->where(function ($q) use ($term, $fields) {
            foreach ($fields as $field) {
                foreach (['ar', 'en'] as $locale) {
                    $q->orWhere("{$field}->{$locale}", 'like', "%{$term}%");
                }
            }
        });
    }
}
