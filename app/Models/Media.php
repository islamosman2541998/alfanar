<?php

namespace App\Models;

use App\Services\MediaService;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    protected $fillable = ['type', 'path', 'thumb', 'poster', 'size', 'sort_order'];

    protected static function booted(): void
    {
        static::deleted(fn (Media $media) => app(MediaService::class)->delete($media->only(['path', 'thumb', 'poster'])));
    }

    public function mediable()
    {
        return $this->morphTo();
    }

    public function isVideo(): bool
    {
        return $this->type === 'video';
    }

    public function url(): ?string
    {
        return media_url($this->path);
    }

    public function thumbUrl(): ?string
    {
        return media_url($this->thumb ?: $this->poster ?: ($this->isVideo() ? null : $this->path));
    }
}
