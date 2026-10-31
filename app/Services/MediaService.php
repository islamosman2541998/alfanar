<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Stores uploaded images and videos on the public disk.
 *
 * Images are re-encoded to high quality WebP (full size + card thumbnail),
 * videos are kept untouched and get a poster frame when ffmpeg is available.
 */
class MediaService
{
    public const IMAGE_EXTENSIONS = ['jpg', 'jpeg', 'png', 'webp'];

    public const VIDEO_EXTENSIONS = ['mp4', 'webm'];

    protected const MAX_WIDTH = 2400;

    protected const THUMB_WIDTH = 900;

    protected const QUALITY = 90;

    protected const THUMB_QUALITY = 80;

    /**
     * @return array{type: string, path: string, thumb: ?string, poster: ?string, size: int}
     */
    public function store(UploadedFile|string $file, string $dir): array
    {
        $source = $file instanceof UploadedFile ? $file->getRealPath() : $file;
        $extension = strtolower($file instanceof UploadedFile
            ? ($file->getClientOriginalExtension() ?: $file->extension())
            : pathinfo($file, PATHINFO_EXTENSION));

        $name = $dir.'/'.Str::lower((string) Str::ulid());

        return in_array($extension, self::VIDEO_EXTENSIONS, true)
            ? $this->storeVideo($source, $name, $extension)
            : $this->storeImage($source, $name, $extension);
    }

    /** Keeps the original file (logos, favicons, SVG) without re-encoding. */
    public function storeOriginal(UploadedFile $file, string $dir): string
    {
        return $file->storeAs($dir, Str::lower((string) Str::ulid()).'.'.strtolower($file->getClientOriginalExtension()), 'public');
    }

    public function delete(?array $media): void
    {
        $paths = array_filter([
            $media['path'] ?? null,
            $media['thumb'] ?? null,
            $media['poster'] ?? null,
        ]);

        if ($paths) {
            Storage::disk('public')->delete($paths);
        }
    }

    protected function storeImage(string $source, string $name, string $extension): array
    {
        $image = $this->load($source, $extension);

        if (! $image) {
            $path = $name.'.'.$extension;
            Storage::disk('public')->put($path, file_get_contents($source));

            return ['type' => 'image', 'path' => $path, 'thumb' => null, 'poster' => null, 'size' => filesize($source)];
        }

        $full = $this->encode($this->resize($image, self::MAX_WIDTH), self::QUALITY);
        $thumb = $this->encode($this->resize($image, self::THUMB_WIDTH), self::THUMB_QUALITY);
        imagedestroy($image);

        Storage::disk('public')->put($name.'.webp', $full);
        Storage::disk('public')->put($name.'-thumb.webp', $thumb);

        return ['type' => 'image', 'path' => $name.'.webp', 'thumb' => $name.'-thumb.webp', 'poster' => null, 'size' => strlen($full)];
    }

    protected function storeVideo(string $source, string $name, string $extension): array
    {
        $path = $name.'.'.$extension;
        $stream = fopen($source, 'r');
        Storage::disk('public')->writeStream($path, $stream);
        is_resource($stream) && fclose($stream);

        return ['type' => 'video', 'path' => $path, 'thumb' => null, 'poster' => $this->poster($source, $name), 'size' => filesize($source)];
    }

    /** Grabs a frame with ffmpeg (FFMPEG_PATH in .env) to use as the video poster. */
    protected function poster(string $source, string $name): ?string
    {
        $ffmpeg = config('services.ffmpeg.path');

        if (! $ffmpeg || ! function_exists('exec')) {
            return null;
        }

        $tmp = tempnam(sys_get_temp_dir(), 'poster').'.jpg';
        exec(sprintf('%s -v error -y -ss 1 -i %s -frames:v 1 %s', escapeshellarg($ffmpeg), escapeshellarg($source), escapeshellarg($tmp)), $output, $code);

        if ($code !== 0 || ! is_file($tmp) || ! ($image = $this->load($tmp, 'jpg'))) {
            @unlink($tmp);

            return null;
        }

        Storage::disk('public')->put($name.'-poster.webp', $this->encode($this->resize($image, self::MAX_WIDTH), self::THUMB_QUALITY + 5));
        imagedestroy($image);
        @unlink($tmp);

        return $name.'-poster.webp';
    }

    protected function load(string $source, string $extension): ?\GdImage
    {
        if (! in_array($extension, self::IMAGE_EXTENSIONS, true) || ! function_exists('imagewebp')) {
            return null;
        }

        $image = @imagecreatefromstring(file_get_contents($source));

        if (! $image) {
            return null;
        }

        if (in_array($extension, ['jpg', 'jpeg'], true) && function_exists('exif_read_data')) {
            $orientation = @exif_read_data($source)['Orientation'] ?? 1;
            $image = match ($orientation) {
                3 => imagerotate($image, 180, 0),
                6 => imagerotate($image, -90, 0),
                8 => imagerotate($image, 90, 0),
                default => $image,
            };
        }

        imagepalettetotruecolor($image);
        imagealphablending($image, true);
        imagesavealpha($image, true);

        return $image;
    }

    protected function resize(\GdImage $image, int $maxWidth): \GdImage
    {
        $width = imagesx($image);
        $height = imagesy($image);

        if ($width <= $maxWidth) {
            return $image;
        }

        $newHeight = (int) round($height * $maxWidth / $width);
        $resized = imagecreatetruecolor($maxWidth, $newHeight);
        imagealphablending($resized, false);
        imagesavealpha($resized, true);
        imagecopyresampled($resized, $image, 0, 0, 0, 0, $maxWidth, $newHeight, $width, $height);

        return $resized;
    }

    protected function encode(\GdImage $image, int $quality): string
    {
        ob_start();
        imagewebp($image, null, $quality);

        return (string) ob_get_clean();
    }
}
