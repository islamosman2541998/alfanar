<?php

namespace App\Livewire\Admin\Pages;

use App\Livewire\Admin\DataTable;
use App\Models\Page;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Livewire\Attributes\Layout;

#[Layout('layouts.admin')]
class Index extends DataTable
{
    protected array $searchable = ['title', 'description'];

    protected array $searchablePlain = ['slug'];

    protected array $sortable = ['id', 'sort_order', 'created_at', 'status', 'is_featured'];

    protected function model(): string
    {
        return Page::class;
    }

    protected function applyFilters(Builder $query): Builder
    {
        return $query->withCount('gallery');
    }

    protected function exportName(): string
    {
        return 'pages';
    }

    protected function exportRow(Model $row): array
    {
        return [
            '#' => $row->id,
            __('Title').' (AR)' => $row->title['ar'] ?? '',
            __('Title').' (EN)' => $row->title['en'] ?? '',
            __('Link') => $row->url(),
            __('Media files') => $row->gallery_count,
            __('Featured') => $this->yesNo($row->is_featured),
            __('Status') => $this->statusLabel($row->status),
            __('Created at') => $row->created_at?->format('Y-m-d'),
        ];
    }

    public function render()
    {
        return view('livewire.admin.pages.index', [
            'rows' => $this->query()->paginate($this->perPage),
        ])->title(__('Pages'));
    }
}
