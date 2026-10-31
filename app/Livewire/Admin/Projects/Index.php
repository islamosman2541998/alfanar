<?php

namespace App\Livewire\Admin\Projects;

use App\Livewire\Admin\DataTable;
use App\Models\Project;
use App\Models\Service;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;

#[Layout('layouts.admin')]
class Index extends DataTable
{
    #[Url(except: '')]
    public string $service = '';

    protected array $searchable = ['title', 'location'];

    protected array $searchablePlain = ['slug', 'year'];

    protected array $sortable = ['id', 'sort_order', 'created_at', 'status', 'is_featured', 'year'];

    protected function model(): string
    {
        return Project::class;
    }

    protected function applyFilters(Builder $query): Builder
    {
        return $query->with('service')->withCount('gallery')
            ->when($this->service !== '', fn ($q) => $q->where('service_id', $this->service));
    }

    protected function resetFilterExtras(): void
    {
        $this->service = '';
    }

    protected function exportName(): string
    {
        return 'projects';
    }

    protected function exportRow(Model $row): array
    {
        return [
            '#' => $row->id,
            __('Title').' (AR)' => $row->title['ar'] ?? '',
            __('Title').' (EN)' => $row->title['en'] ?? '',
            __('Service') => $row->service?->tr('title'),
            __('Location') => $row->tr('location'),
            __('Year') => $row->year,
            __('Media files') => $row->gallery_count,
            __('Link') => $row->url(),
            __('Featured') => $this->yesNo($row->is_featured),
            __('Status') => $this->statusLabel($row->status),
            __('Created at') => $row->created_at?->format('Y-m-d'),
        ];
    }

    public function render()
    {
        return view('livewire.admin.projects.index', [
            'rows' => $this->query()->paginate($this->perPage),
            'services' => Service::ordered()->get(['id', 'title']),
        ])->title(__('Projects'));
    }
}
