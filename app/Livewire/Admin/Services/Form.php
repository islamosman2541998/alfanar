<?php

namespace App\Livewire\Admin\Services;

use App\Livewire\Admin\ContentForm;
use App\Models\Service;
use Livewire\Attributes\Layout;

#[Layout('layouts.admin')]
class Form extends ContentForm
{
    public array $title = [];

    public array $excerpt = [];

    public array $description = [];

    public array $meta_title = [];

    public array $meta_description = [];

    public function mount(?Service $service = null): void
    {
        $this->hydrateFrom($service);
    }

    protected function model(): string
    {
        return Service::class;
    }

    protected function translatable(): array
    {
        return ['title', 'excerpt', 'description', 'meta_title', 'meta_description'];
    }

    protected function rules(): array
    {
        return [
            'title.ar' => ['required', 'string', 'max:190'],
            'title.en' => ['required', 'string', 'max:190'],
            'excerpt.*' => ['nullable', 'string', 'max:400'],
            'description.*' => ['nullable', 'string', 'max:20000'],
            'meta_title.*' => ['nullable', 'string', 'max:190'],
            'meta_description.*' => ['nullable', 'string', 'max:300'],
        ];
    }

    protected function folder(): string
    {
        return 'services';
    }

    protected function indexRoute(): string
    {
        return 'admin.services.index';
    }

    protected function editRoute(): string
    {
        return 'admin.services.edit';
    }

    public function render()
    {
        return view('livewire.admin.services.form')
            ->title($this->recordId ? __('Edit service') : __('Add service'));
    }
}
