<?php

namespace App\Livewire\Admin\Sliders;

use App\Livewire\Admin\ContentForm;
use App\Models\Slider;
use Livewire\Attributes\Layout;

#[Layout('layouts.admin')]
class Form extends ContentForm
{
    public array $title = [];

    public array $description = [];

    public array $button_text = [];

    public ?string $button_link = null;

    public ?string $button_color = '#e3a01b';

    protected bool $hasSlug = false;

    protected bool $hasGallery = false;

    protected bool $coverRequired = true;

    public function mount(?Slider $slider = null): void
    {
        $this->hydrateFrom($slider);
    }

    protected function model(): string
    {
        return Slider::class;
    }

    protected function translatable(): array
    {
        return ['title', 'description', 'button_text'];
    }

    protected function plain(): array
    {
        return ['status', 'sort_order', 'button_link', 'button_color'];
    }

    protected function rules(): array
    {
        return [
            'title.ar' => ['required', 'string', 'max:190'],
            'title.en' => ['required', 'string', 'max:190'],
            'description.*' => ['nullable', 'string', 'max:500'],
            'button_text.*' => ['nullable', 'string', 'max:60'],
            'button_link' => ['nullable', 'string', 'max:255'],
            'button_color' => ['nullable', 'regex:/^#[0-9a-fA-F]{6}$/'],
        ];
    }

    protected function folder(): string
    {
        return 'sliders';
    }

    protected function indexRoute(): string
    {
        return 'admin.sliders.index';
    }

    protected function editRoute(): string
    {
        return 'admin.sliders.edit';
    }

    public function render()
    {
        return view('livewire.admin.sliders.form')
            ->title($this->recordId ? __('Edit slide') : __('Add slide'));
    }
}
