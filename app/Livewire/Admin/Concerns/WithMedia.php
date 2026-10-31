<?php

namespace App\Livewire\Admin\Concerns;

use App\Models\Media;
use App\Services\MediaService;
use Illuminate\Database\Eloquent\Model;
use Livewire\WithFileUploads;

/**
 * Cover (image or video) and gallery handling for content forms.
 * Changes are applied only when the form is saved.
 */
trait WithMedia
{
    use WithFileUploads;

    /** Existing cover: {type, path, thumb, poster} */
    public ?array $cover = null;

    public $coverFile = null;

    /** Existing gallery items shown in the form (in display order). */
    public array $galleryItems = [];

    public array $galleryUploads = [];

    public array $removedGallery = [];

    protected ?array $replacedCover = null;

    protected function mediaRules(bool $coverRequired = false): array
    {
        $mimes = 'mimes:'.implode(',', array_merge(MediaService::IMAGE_EXTENSIONS, MediaService::VIDEO_EXTENSIONS));

        return [
            'coverFile' => [($coverRequired && ! $this->cover) ? 'required' : 'nullable', 'file', $mimes, 'max:204800'],
            'galleryUploads.*' => ['file', $mimes, 'max:204800'],
        ];
    }

    public function updatedCoverFile(): void
    {
        $this->validateOnly('coverFile', $this->mediaRules());
    }

    public function updatedGalleryUploads(): void
    {
        $this->validateOnly('galleryUploads.*', $this->mediaRules());
    }

    public function removeCover(): void
    {
        $this->coverFile = null;
        $this->cover = null;
    }

    public function removeGalleryUpload(int $index): void
    {
        array_splice($this->galleryUploads, $index, 1);
    }

    public function removeGalleryItem(int $id): void
    {
        $this->removedGallery[] = $id;
        $this->galleryItems = array_values(array_filter($this->galleryItems, fn ($item) => $item['id'] !== $id));
    }

    /** wire:sort handler */
    public function sortGallery(int $id, int $position): void
    {
        $items = collect($this->galleryItems);
        $moved = $items->firstWhere('id', $id);

        if (! $moved) {
            return;
        }

        $items = $items->reject(fn ($item) => $item['id'] === $id)->values()->all();
        array_splice($items, $position, 0, [$moved]);
        $this->galleryItems = $items;
    }

    protected function loadMedia(Model $model): void
    {
        $this->cover = $model->cover;

        if (method_exists($model, 'gallery')) {
            $this->galleryItems = $model->gallery->map(fn (Media $media) => [
                'id' => $media->id,
                'type' => $media->type,
                'url' => $media->url(),
                'thumb' => $media->thumbUrl(),
            ])->all();
        }
    }

    /** Returns the cover value to save (new upload, kept or removed). */
    protected function resolveCover(Model $model, string $dir): ?array
    {
        $original = $model->exists ? $model->getOriginal('cover') : null;

        if ($this->coverFile) {
            $this->replacedCover = $original;

            return app(MediaService::class)->store($this->coverFile, $dir);
        }

        if (! $this->cover && $original) {
            $this->replacedCover = $original;
        }

        return $this->cover;
    }

    protected function syncGallery(Model $model, string $dir): void
    {
        Media::whereIn('id', $this->removedGallery)
            ->where('mediable_type', $model->getMorphClass())
            ->where('mediable_id', $model->getKey())
            ->get()->each->delete();

        foreach ($this->galleryItems as $position => $item) {
            Media::whereKey($item['id'])->update(['sort_order' => $position]);
        }

        $position = count($this->galleryItems);

        foreach ($this->galleryUploads as $upload) {
            $model->gallery()->create(app(MediaService::class)->store($upload, $dir) + ['sort_order' => $position++]);
        }

        $this->reset('galleryUploads', 'removedGallery');
    }

    /** Old cover files are removed only after a successful save. */
    protected function cleanupReplacedCover(): void
    {
        if ($this->replacedCover) {
            app(MediaService::class)->delete($this->replacedCover);
            $this->replacedCover = null;
        }

        $this->coverFile = null;
    }
}
