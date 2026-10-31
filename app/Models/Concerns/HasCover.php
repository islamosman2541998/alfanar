<?php

namespace App\Models\Concerns;

use App\Services\MediaService;

/**
 * Main media of a record stored in the `cover` JSON column:
 * {type: image|video, path, thumb, poster}
 */
trait HasCover
{
    public function initializeHasCover(): void
    {
        $this->mergeCasts(['cover' => 'array']);
    }

    protected static function bootHasCover(): void
    {
        static::deleted(fn ($model) => app(MediaService::class)->delete($model->cover));
    }

    public function hasCover(): bool
    {
        return ! empty($this->cover['path']);
    }

    public function coverIsVideo(): bool
    {
        return ($this->cover['type'] ?? null) === 'video';
    }

    public function coverUrl(): ?string
    {
        return media_url($this->cover['path'] ?? null);
    }

    public function coverThumbUrl(): ?string
    {
        $media = $this->cover ?? [];

        return media_url($media['thumb'] ?? $media['poster'] ?? ($this->coverIsVideo() ? null : ($media['path'] ?? null)));
    }
}
