<?php

namespace App\Livewire\Admin;

use App\Models\Testimonial;
use App\Services\MediaService;
use Illuminate\Database\Eloquent\Model;
use Livewire\Attributes\Layout;
use Livewire\WithFileUploads;

#[Layout('layouts.admin')]
class Testimonials extends ModalCrud
{
    use WithFileUploads;

    public $photo = null;

    protected array $searchable = ['name', 'content', 'position'];

    protected array $sortable = ['id', 'sort_order', 'created_at', 'status', 'rating'];

    protected function model(): string
    {
        return Testimonial::class;
    }

    protected function defaults(): array
    {
        return [
            'name' => ['ar' => '', 'en' => ''],
            'position' => ['ar' => '', 'en' => ''],
            'content' => ['ar' => '', 'en' => ''],
            'rating' => 5,
            'photo' => null,
            'status' => true,
            'sort_order' => 0,
        ];
    }

    protected function formRules(): array
    {
        return [
            'form.name.ar' => ['required', 'string', 'max:120'],
            'form.name.en' => ['required', 'string', 'max:120'],
            'form.position.*' => ['nullable', 'string', 'max:120'],
            'form.content.ar' => ['required', 'string', 'max:1000'],
            'form.content.en' => ['required', 'string', 'max:1000'],
            'form.rating' => ['required', 'integer', 'between:1,5'],
            'form.status' => ['boolean'],
            'form.sort_order' => ['integer', 'min:0'],
            'photo' => ['nullable', 'image', 'max:5120'],
        ];
    }

    public function create(): void
    {
        $this->photo = null;
        parent::create();
    }

    public function edit(int $id): void
    {
        $this->photo = null;
        parent::edit($id);
    }

    public function removePhoto(): void
    {
        $this->photo = null;
        $this->form['photo'] = null;
    }

    protected function beforeSave(array $data, Model $record): array
    {
        $media = app(MediaService::class);

        if ($this->photo) {
            // Avatars only need the small version.
            $stored = $media->store($this->photo, 'testimonials');
            $data['photo'] = $stored['thumb'] ?: $stored['path'];

            if ($stored['thumb']) {
                $media->delete(['path' => $stored['path']]);
            }
        }

        if ($record->photo && $record->photo !== $data['photo']) {
            $media->delete(['path' => $record->photo]);
        }

        $this->photo = null;

        return $data;
    }

    protected function exportName(): string
    {
        return 'testimonials';
    }

    protected function exportRow(Model $row): array
    {
        return [
            '#' => $row->id,
            __('Name').' (AR)' => $row->name['ar'] ?? '',
            __('Name').' (EN)' => $row->name['en'] ?? '',
            __('Position') => $row->tr('position'),
            __('Review').' (AR)' => $row->content['ar'] ?? '',
            __('Review').' (EN)' => $row->content['en'] ?? '',
            __('Rating') => $row->rating,
            __('Status') => $this->statusLabel($row->status),
        ];
    }

    public function render()
    {
        return view('livewire.admin.testimonials', [
            'rows' => $this->query()->paginate($this->perPage),
        ])->title(__('Testimonials'));
    }
}
