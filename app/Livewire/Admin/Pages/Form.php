<?php

namespace App\Livewire\Admin\Pages;

use App\Livewire\Admin\ContentForm;
use App\Models\Page;
use Livewire\Attributes\Layout;

#[Layout('layouts.admin')]
class Form extends ContentForm
{
    public array $title = [];

    public array $description = [];

    public array $meta_title = [];

    public array $meta_description = [];

    public function mount(?Page $page = null): void
    {
        $this->hydrateFrom($page);
    }

    protected function model(): string
    {
        return Page::class;
    }

    protected function translatable(): array
    {
        return ['title', 'description', 'meta_title', 'meta_description'];
    }

    protected function rules(): array
    {
        return [
            'title.ar' => ['required', 'string', 'max:190'],
            'title.en' => ['required', 'string', 'max:190'],
            'description.*' => ['nullable', 'string', 'max:30000'],
            'meta_title.*' => ['nullable', 'string', 'max:190'],
            'meta_description.*' => ['nullable', 'string', 'max:300'],
        ];
    }

    protected function folder(): string
    {
        return 'pages';
    }

    protected function indexRoute(): string
    {
        return 'admin.pages.index';
    }

    protected function editRoute(): string
    {
        return 'admin.pages.edit';
    }

    public function render()
    {
        return view('livewire.admin.pages.form')
            ->title($this->recordId ? __('Edit page') : __('Add page'));
    }
}
