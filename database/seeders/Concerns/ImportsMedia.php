<?php

namespace Database\Seeders\Concerns;

use App\Services\MediaService;

/**
 * Reads the company photos / videos folder (SEED_MEDIA_PATH) for the initial content.
 * Images are addressed by their index in the de-duplicated, name-sorted list.
 */
trait ImportsMedia
{
    protected ?array $images = null;

    protected function mediaPath(): ?string
    {
        $path = env('SEED_MEDIA_PATH', base_path('../alfanar media'));

        return is_dir($path) ? rtrim($path, '/\\') : null;
    }

    protected function images(): array
    {
        if ($this->images !== null) {
            return $this->images;
        }

        $this->images = [];
        $seen = [];

        foreach (glob($this->mediaPath().'/*.jpeg') ?: [] as $file) {
            $hash = md5_file($file);
            if (! isset($seen[$hash])) {
                $seen[$hash] = true;
                $this->images[] = $file;
            }
        }

        return $this->images;
    }

    protected function image(int $index, string $dir): ?array
    {
        $file = $this->images()[$index] ?? null;

        return $file ? app(MediaService::class)->store($file, $dir) : null;
    }

    protected function video(string $time, string $dir): ?array
    {
        $file = $this->mediaPath()."/WhatsApp Video 2026-10-30 at {$time}.mp4";

        return is_file($file) ? app(MediaService::class)->store($file, $dir) : null;
    }

    /** Attach images (indexes) and videos (times) to a model gallery. */
    protected function gallery($model, string $dir, array $images, array $videos = []): void
    {
        $order = 0;

        foreach ($videos as $time) {
            if ($media = $this->video($time, $dir)) {
                $model->gallery()->create($media + ['sort_order' => $order++]);
            }
        }

        foreach ($images as $index) {
            if ($media = $this->image($index, $dir)) {
                $model->gallery()->create($media + ['sort_order' => $order++]);
            }
        }
    }
}
