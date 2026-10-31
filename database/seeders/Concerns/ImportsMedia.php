<?php

namespace Database\Seeders\Concerns;

use App\Services\MediaService;

/**
 * Reads the company photos / videos folder (SEED_MEDIA_PATH) for the initial content.
 * Images are addressed by their index in the de-duplicated, name-sorted list.
 *
 * Only real site photos and videos are used: 3D renders, advertising posters
 * and photos with text written on them are excluded (see EXCLUDED).
 */
trait ImportsMedia
{
    /** Image indexes that are not real work photos (3D renders, ad posters, text overlays, screenshots). */
    protected const EXCLUDED = [
        // 3D renders
        26, 49, 59, 70, 82, 87, 101, 111, 124, 134, 140, 149, 153, 164, 165, 169, 173, 178, 180, 181, 184, 188, 189, 196,
        // Advertising posters
        100, 104, 106, 107, 110, 112, 113, 116, 118, 119, 121, 126, 127, 128, 129, 131, 132, 136, 138, 141, 142, 146, 148,
        152, 160, 176, 182, 185, 187, 193, 194, 197,
        // Text overlays, collages and screenshots
        60, 125, 143, 147, 154, 159, 172, 183,
    ];

    /** Videos recorded sideways (stored landscape without rotation info): turned upright on import. */
    protected const SIDEWAYS_VIDEOS = ['3.57.12', '3.57.16', '3.57.20', '3.57.29', '3.57.36', '3.57.42', '3.58.21', '3.58.26'];

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
        $file = in_array($index, self::EXCLUDED, true) ? null : ($this->images()[$index] ?? null);

        return $file ? app(MediaService::class)->store($file, $dir) : null;
    }

    protected function video(string $time, string $dir): ?array
    {
        $file = $this->mediaPath()."/WhatsApp Video 2026-10-30 at {$time} PM.mp4";

        if (! is_file($file)) {
            return null;
        }

        if (! in_array($time, self::SIDEWAYS_VIDEOS, true)) {
            return app(MediaService::class)->store($file, $dir);
        }

        if (! ($upright = $this->upright($file))) {
            return null;
        }

        $media = app(MediaService::class)->store($upright, $dir);
        @unlink($upright);

        return $media;
    }

    /** Re-encodes a sideways video upright (visually lossless, streaming friendly). */
    protected function upright(string $file): ?string
    {
        $ffmpeg = config('services.ffmpeg.path');
        $tmp = tempnam(sys_get_temp_dir(), 'video').'.mp4';

        exec(sprintf(
            '%s -v error -y -i %s -vf transpose=1 -c:v libx264 -crf 18 -preset slow -pix_fmt yuv420p -c:a copy -movflags +faststart %s',
            escapeshellarg((string) $ffmpeg), escapeshellarg($file), escapeshellarg($tmp)
        ), $output, $code);

        return $code === 0 && is_file($tmp) ? $tmp : null;
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

    /** Cover: a video when given, otherwise a real photo. */
    protected function cover(?string $video, int $image, string $dir): ?array
    {
        return ($video ? $this->video($video, $dir) : null) ?: $this->image($image, $dir);
    }
}
