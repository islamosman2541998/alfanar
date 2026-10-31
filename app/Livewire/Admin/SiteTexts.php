<?php

namespace App\Livewire\Admin;

use App\Models\SiteText;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;

/** Every text used on the website, editable inline in both languages. */
#[Layout('layouts.admin')]
class SiteTexts extends DataTable
{
    #[Url(except: '')]
    public string $group = '';

    public string $sortField = 'key';

    /** Values of the rows on the current page: [id => ['ar' => .., 'en' => ..]] */
    public array $values = [];

    protected array $sortable = ['key', 'group'];

    protected function model(): string
    {
        return SiteText::class;
    }

    protected function query(): Builder
    {
        return SiteText::query()
            ->when(filled($this->search), fn ($q) => $q->where(fn ($q) => $q
                ->where('key', 'like', "%{$this->search}%")
                ->orWhere('value->ar', 'like', "%{$this->search}%")
                ->orWhere('value->en', 'like', "%{$this->search}%")))
            ->when($this->group !== '', fn ($q) => $q->where('group', $this->group))
            ->orderBy($this->sortField, $this->sortDirection);
    }

    protected function resetFilterExtras(): void
    {
        $this->group = '';
    }

    /** Saves a single value as soon as the field loses focus. */
    public function updatedValues($value, string $key): void
    {
        [$id, $locale] = explode('.', $key);

        abort_unless(in_array($locale, ['ar', 'en'], true), 403);

        $text = SiteText::findOrFail($id);
        $text->value = array_merge($text->value, [$locale => trim((string) $value)]);
        $text->save();

        $this->toast(__('Saved successfully'));
    }

    protected function exportName(): string
    {
        return 'site-texts';
    }

    protected function exportRow(Model $row): array
    {
        return [
            __('Key') => $row->key,
            __('Group') => $row->group,
            __('Arabic') => $row->value['ar'] ?? '',
            __('English') => $row->value['en'] ?? '',
        ];
    }

    public function render()
    {
        $rows = $this->query()->paginate($this->perPage);
        $this->values = $rows->mapWithKeys(fn ($row) => [$row->id => array_merge(['ar' => '', 'en' => ''], $row->value)])->all();

        return view('livewire.admin.site-texts', [
            'rows' => $rows,
            'groups' => SiteText::query()->distinct()->orderBy('group')->pluck('group'),
        ])->title(__('Site texts'));
    }
}
