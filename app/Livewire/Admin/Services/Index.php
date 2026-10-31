<?php

namespace App\Livewire\Admin\Services;

use App\Livewire\Admin\DataTable;
use App\Models\Service;
use Illuminate\Database\Eloquent\Model;
use Livewire\Attributes\Layout;

#[Layout('layouts.admin')]
class Index extends DataTable
{
    protected array $searchable = ['title', 'excerpt'];

    protected array $searchablePlain = ['slug'];

    protected array $sortable = ['id', 'sort_order', 'created_at', 'status', 'is_featured', 'projects_count'];

    protected function model(): string
    {
        return Service::class;
    }

    protected function exportName(): string
    {
        return 'services';
    }

    protected function exportRow(Model $row): array
    {
        return [
            '#' => $row->id,
            __('Title').' (AR)' => $row->title['ar'] ?? '',
            __('Title').' (EN)' => $row->title['en'] ?? '',
            __('Summary').' (AR)' => $row->excerpt['ar'] ?? '',
            __('Summary').' (EN)' => $row->excerpt['en'] ?? '',
            __('Link') => $row->url(),
            __('Projects') => $row->projects_count,
            __('Featured') => $this->yesNo($row->is_featured),
            __('Status') => $this->statusLabel($row->status),
            __('Created at') => $row->created_at?->format('Y-m-d'),
        ];
    }

    protected function query(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::query()->withCount(['projects', 'gallery']);
    }

    public function render()
    {
        return view('livewire.admin.services.index', [
            'rows' => $this->query()->paginate($this->perPage),
        ])->title(__('Services'));
    }
}
