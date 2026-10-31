<?php

namespace App\Models\Concerns;

use App\Models\Media;

trait HasGallery
{
    protected static function bootHasGallery(): void
    {
        static::deleting(fn ($model) => $model->gallery->each->delete());
    }

    public function gallery()
    {
        return $this->morphMany(Media::class, 'mediable')->orderBy('sort_order')->orderBy('id');
    }
}
