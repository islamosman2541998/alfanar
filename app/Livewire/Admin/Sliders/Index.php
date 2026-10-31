<?php

namespace App\Livewire\Admin\Sliders;

use App\Livewire\Admin\DataTable;
use App\Models\Slider;
use Illuminate\Database\Eloquent\Model;
use Livewire\Attributes\Layout;

#[Layout('layouts.admin')]
class Index extends DataTable
{
    protected function model(): string
    {
        return Slider::class;
    }

    protected function exportName(): string
    {
        return 'slider';
    }

    protected function exportRow(Model $row): array
    {
        return [
            '#' => $row->id,
            __('Title').' (AR)' => $row->title['ar'] ?? '',
            __('Title').' (EN)' => $row->title['en'] ?? '',
            __('Media') => $row->coverIsVideo() ? __('Video') : __('Image'),
            __('Button link') => $row->button_link,
            __('Status') => $this->statusLabel($row->status),
            __('Display order') => $row->sort_order,
        ];
    }

    public function render()
    {
        return view('livewire.admin.sliders.index', [
            'rows' => $this->query()->paginate($this->perPage),
        ])->title(__('Slider'));
    }
}
