<?php

namespace App\Livewire\Admin;

use App\Livewire\Admin\Concerns\WithMedia;
use App\Livewire\Admin\Concerns\WithToast;
use App\Support\Slug;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

/**
 * Shared create / edit behaviour for content with translatable fields,
 * a cover (image or video), optional gallery, publishing flags and SEO.
 */
abstract class ContentForm extends Component
{
    use WithMedia, WithToast;

    public ?int $recordId = null;

    public bool $status = true;

    public bool $is_featured = false;

    public int $sort_order = 0;

    public string $slug = '';

    abstract protected function model(): string;

    /** Translatable properties of the form. */
    abstract protected function translatable(): array;

    /** Plain (non translatable) properties saved as-is. */
    protected function plain(): array
    {
        return ['status', 'is_featured', 'sort_order'];
    }

    abstract protected function rules(): array;

    abstract protected function folder(): string;

    abstract protected function indexRoute(): string;

    abstract protected function editRoute(): string;

    protected bool $hasSlug = true;

    protected bool $hasGallery = true;

    protected bool $coverRequired = false;

    protected function hydrateFrom(?Model $record): void
    {
        foreach ($this->translatable() as $field) {
            $this->{$field} = array_merge(['ar' => '', 'en' => ''], $record?->{$field} ?? []);
        }

        if (! $record?->exists) {
            $this->sort_order = (int) ($this->model())::max('sort_order') + 1;

            return;
        }

        $this->recordId = $record->getKey();

        foreach ($this->plain() as $field) {
            $this->{$field} = $record->{$field} ?? $this->{$field};
        }

        if ($this->hasSlug) {
            $this->slug = $record->slug;
        }

        $this->loadMedia($record);
    }

    protected function baseRules(): array
    {
        return array_merge([
            'status' => ['boolean'],
            'is_featured' => ['boolean'],
            'sort_order' => ['integer', 'min:0'],
            'slug' => $this->hasSlug ? ['nullable', 'string', 'max:190', 'regex:/^[a-z0-9-]+$/'] : [],
        ], $this->mediaRules($this->coverRequired));
    }

    public function save()
    {
        $this->validateWithToast(array_merge($this->baseRules(), $this->rules()));

        $model = $this->model();
        $record = $this->recordId ? $model::findOrFail($this->recordId) : new $model;
        $creating = ! $record->exists;

        DB::transaction(function () use ($record) {
            foreach ($this->translatable() as $field) {
                $record->{$field} = $this->{$field};
            }

            foreach ($this->plain() as $field) {
                $record->{$field} = $this->{$field} === '' ? null : $this->{$field};
            }

            if ($this->hasSlug) {
                $record->slug = Slug::unique($record::class, $this->slug ?: ($this->title['en'] ?: $this->title['ar']), $record->getKey());
            }

            $record->cover = $this->resolveCover($record, $this->folder());
            $record->save();

            if ($this->hasGallery) {
                $this->syncGallery($record, $this->folder().'/gallery');
            }
        });

        $this->cleanupReplacedCover();

        if ($creating) {
            $this->flashToast(__('Created successfully'));

            return $this->redirectRoute($this->editRoute(), $record);
        }

        $this->hydrateFrom($record->fresh());
        $this->toast(__('Saved successfully'));
    }
}
