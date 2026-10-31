<?php

namespace App\Livewire\Admin;

use App\Models\Stat;
use Illuminate\Database\Eloquent\Model;
use Livewire\Attributes\Layout;

#[Layout('layouts.admin')]
class Stats extends ModalCrud
{
    protected array $searchable = ['label'];

    protected array $sortable = ['id', 'sort_order', 'status', 'value'];

    protected function model(): string
    {
        return Stat::class;
    }

    protected function defaults(): array
    {
        return ['label' => ['ar' => '', 'en' => ''], 'value' => 0, 'suffix' => '+', 'status' => true, 'sort_order' => 0];
    }

    protected function formRules(): array
    {
        return [
            'form.label.ar' => ['required', 'string', 'max:80'],
            'form.label.en' => ['required', 'string', 'max:80'],
            'form.value' => ['required', 'integer', 'min:0'],
            'form.suffix' => ['nullable', 'string', 'max:10'],
            'form.status' => ['boolean'],
            'form.sort_order' => ['integer', 'min:0'],
        ];
    }

    protected function exportName(): string
    {
        return 'statistics';
    }

    protected function exportRow(Model $row): array
    {
        return [
            '#' => $row->id,
            __('Label').' (AR)' => $row->label['ar'] ?? '',
            __('Label').' (EN)' => $row->label['en'] ?? '',
            __('Number') => $row->value.$row->suffix,
            __('Status') => $this->statusLabel($row->status),
        ];
    }

    public function render()
    {
        return view('livewire.admin.stats', [
            'rows' => $this->query()->paginate($this->perPage),
        ])->title(__('Statistics'));
    }
}
