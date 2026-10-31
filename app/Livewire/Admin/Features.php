<?php

namespace App\Livewire\Admin;

use App\Models\Feature;
use Illuminate\Database\Eloquent\Model;
use Livewire\Attributes\Layout;

#[Layout('layouts.admin')]
class Features extends ModalCrud
{
    protected array $searchable = ['title', 'description'];

    protected function model(): string
    {
        return Feature::class;
    }

    protected function defaults(): array
    {
        return ['title' => ['ar' => '', 'en' => ''], 'description' => ['ar' => '', 'en' => ''], 'status' => true, 'sort_order' => 0];
    }

    protected function formRules(): array
    {
        return [
            'form.title.ar' => ['required', 'string', 'max:120'],
            'form.title.en' => ['required', 'string', 'max:120'],
            'form.description.*' => ['nullable', 'string', 'max:500'],
            'form.status' => ['boolean'],
            'form.sort_order' => ['integer', 'min:0'],
        ];
    }

    protected function exportName(): string
    {
        return 'why-us';
    }

    protected function exportRow(Model $row): array
    {
        return [
            '#' => $row->id,
            __('Title').' (AR)' => $row->title['ar'] ?? '',
            __('Title').' (EN)' => $row->title['en'] ?? '',
            __('Description').' (AR)' => $row->description['ar'] ?? '',
            __('Description').' (EN)' => $row->description['en'] ?? '',
            __('Status') => $this->statusLabel($row->status),
        ];
    }

    public function render()
    {
        return view('livewire.admin.features', [
            'rows' => $this->query()->paginate($this->perPage),
        ])->title(__('Why us'));
    }
}
