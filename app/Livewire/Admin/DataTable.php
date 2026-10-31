<?php

namespace App\Livewire\Admin;

use App\Livewire\Admin\Concerns\WithToast;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\SimpleExcel\SimpleExcelWriter;

/**
 * Base for every dashboard listing: search, filters, sorting,
 * pagination, inline toggles, (bulk) delete and Excel export.
 */
abstract class DataTable extends Component
{
    use WithPagination, WithToast;

    protected string $paginationTheme = 'bootstrap';

    #[Url(except: '')]
    public string $search = '';

    #[Url(except: '')]
    public string $status = '';

    #[Url(except: '')]
    public string $featured = '';

    public int $perPage = 15;

    public string $sortField = 'sort_order';

    public string $sortDirection = 'asc';

    public array $selected = [];

    /** Model class managed by the table. */
    abstract protected function model(): string;

    /** Row values for the Excel file, keyed by column heading. */
    abstract protected function exportRow(Model $row): array;

    abstract protected function exportName(): string;

    /** Translatable columns searched in both languages. */
    protected array $searchable = ['title'];

    /** Plain columns searched with LIKE. */
    protected array $searchablePlain = [];

    protected array $sortable = ['id', 'sort_order', 'created_at', 'status'];

    protected array $toggleable = ['status', 'is_featured'];

    protected function applyFilters(Builder $query): Builder
    {
        return $query;
    }

    protected function query(): Builder
    {
        $query = ($this->model())::query()
            ->when(filled($this->search), function (Builder $q) {
                $q->where(function (Builder $q) {
                    if ($this->searchable) {
                        $q->searchTranslated($this->search, $this->searchable);
                    }
                    foreach ($this->searchablePlain as $column) {
                        $q->orWhere($column, 'like', "%{$this->search}%");
                    }
                });
            })
            ->when($this->status !== '', fn (Builder $q) => $q->where('status', (bool) $this->status))
            ->when($this->featured !== '', fn (Builder $q) => $q->where('is_featured', (bool) $this->featured));

        return $this->applyFilters($query)
            ->orderBy($this->sortField, $this->sortDirection)
            ->orderByDesc('id');
    }

    /** Properties that change the result set (page is reset when they change). */
    protected array $filters = ['search', 'status', 'featured', 'perPage', 'service', 'read', 'from', 'to', 'group'];

    public function updated(string $property): void
    {
        if (in_array($property, $this->filters, true)) {
            $this->resetPage();
            $this->selected = [];
        }
    }

    public function sortBy(string $field): void
    {
        abort_unless(in_array($field, $this->sortable, true), 403);

        $this->sortDirection = $this->sortField === $field && $this->sortDirection === 'asc' ? 'desc' : 'asc';
        $this->sortField = $field;
    }

    public function resetFilters(): void
    {
        $this->reset('search', 'status', 'featured', 'selected');
        $this->resetFilterExtras();
        $this->resetPage();
    }

    protected function resetFilterExtras(): void {}

    public function toggle(int $id, string $field): void
    {
        abort_unless(in_array($field, $this->toggleable, true), 403);

        $record = ($this->model())::findOrFail($id);
        $record->update([$field => ! $record->{$field}]);

        $this->toast(__('Updated successfully'));
    }

    public function delete(int $id): void
    {
        ($this->model())::findOrFail($id)->delete();
        $this->selected = array_values(array_diff($this->selected, [(string) $id, $id]));

        $this->toast(__('Deleted successfully'));
    }

    public function deleteSelected(): void
    {
        if (! $this->selected) {
            $this->toast(__('Select at least one row'), 'warning');

            return;
        }

        ($this->model())::whereKey($this->selected)->get()->each->delete();
        $count = count($this->selected);
        $this->selected = [];

        $this->toast(__(':count rows deleted', ['count' => $count]));
    }

    public function export()
    {
        $path = storage_path('app/'.$this->exportName().'-'.now()->format('Y-m-d-His').'.xlsx');
        $writer = SimpleExcelWriter::create($path);

        $this->query()->lazy(200)->each(fn (Model $row) => $writer->addRow($this->exportRow($row)));
        $writer->close();

        return response()->download($path)->deleteFileAfterSend();
    }

    protected function yesNo(bool $value): string
    {
        return $value ? __('Yes') : __('No');
    }

    protected function statusLabel(bool $value): string
    {
        return $value ? __('Active') : __('Hidden');
    }
}
