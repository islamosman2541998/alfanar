<?php

namespace App\Livewire\Admin\Projects;

use App\Livewire\Admin\ContentForm;
use App\Models\Project;
use App\Models\Service;
use Livewire\Attributes\Layout;

#[Layout('layouts.admin')]
class Form extends ContentForm
{
    public array $title = [];

    public array $excerpt = [];

    public array $description = [];

    public array $location = [];

    public array $meta_title = [];

    public array $meta_description = [];

    public ?string $service_id = null;

    public ?string $year = null;

    public function mount(?Project $project = null): void
    {
        $this->hydrateFrom($project);
    }

    protected function model(): string
    {
        return Project::class;
    }

    protected function translatable(): array
    {
        return ['title', 'excerpt', 'description', 'location', 'meta_title', 'meta_description'];
    }

    protected function plain(): array
    {
        return ['status', 'is_featured', 'sort_order', 'service_id', 'year'];
    }

    protected function rules(): array
    {
        return [
            'title.ar' => ['required', 'string', 'max:190'],
            'title.en' => ['required', 'string', 'max:190'],
            'excerpt.*' => ['nullable', 'string', 'max:400'],
            'description.*' => ['nullable', 'string', 'max:20000'],
            'location.*' => ['nullable', 'string', 'max:120'],
            'service_id' => ['nullable', 'exists:services,id'],
            'year' => ['nullable', 'string', 'max:10'],
            'meta_title.*' => ['nullable', 'string', 'max:190'],
            'meta_description.*' => ['nullable', 'string', 'max:300'],
        ];
    }

    protected function folder(): string
    {
        return 'projects';
    }

    protected function indexRoute(): string
    {
        return 'admin.projects.index';
    }

    protected function editRoute(): string
    {
        return 'admin.projects.edit';
    }

    public function render()
    {
        return view('livewire.admin.projects.form', [
            'services' => Service::ordered()->get(['id', 'title']),
        ])->title($this->recordId ? __('Edit project') : __('Add project'));
    }
}
