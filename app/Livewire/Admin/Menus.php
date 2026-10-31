<?php

namespace App\Livewire\Admin;

use App\Livewire\Admin\Concerns\WithToast;
use App\Models\MenuItem;
use App\Models\Page;
use App\Models\Project;
use App\Models\Service;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;

/**
 * Menu builder for the header and the two footer columns.
 * Top level items can hold children (dropdown in the header).
 */
#[Layout('layouts.admin')]
class Menus extends Component
{
    use WithToast;

    #[Url]
    public string $location = 'header';

    public array $form = [];

    public ?int $editingId = null;

    /** Bulk add: link several records under one parent at once. */
    public array $bulk = ['parent_id' => '', 'type' => 'service', 'ids' => []];

    public function mount(): void
    {
        abort_unless(in_array($this->location, MenuItem::LOCATIONS, true), 404);
        $this->form = $this->defaults();
    }

    protected function defaults(): array
    {
        return [
            'title' => ['ar' => '', 'en' => ''],
            'type' => 'route',
            'route' => 'home',
            'linkable_id' => '',
            'url' => '',
            'parent_id' => '',
            'new_tab' => false,
            'status' => true,
        ];
    }

    protected function linkables(): array
    {
        return [
            'page' => Page::ordered()->get(['id', 'title']),
            'service' => Service::ordered()->get(['id', 'title']),
            'project' => Project::ordered()->get(['id', 'title']),
        ];
    }

    public function setLocation(string $location): void
    {
        abort_unless(in_array($location, MenuItem::LOCATIONS, true), 404);
        $this->location = $location;
    }

    public function create(?int $parentId = null): void
    {
        $this->resetValidation();
        $this->editingId = null;
        $this->form = array_merge($this->defaults(), ['parent_id' => (string) ($parentId ?? '')]);
        $this->dispatch('modal', id: 'formModal', action: 'show');
    }

    public function edit(int $id): void
    {
        $this->resetValidation();
        $item = MenuItem::findOrFail($id);
        $this->editingId = $id;
        $this->form = [
            'title' => array_merge(['ar' => '', 'en' => ''], $item->title),
            'type' => $item->type,
            'route' => $item->route ?? 'home',
            'linkable_id' => (string) ($item->linkable_id ?? ''),
            'url' => $item->url ?? '',
            'parent_id' => (string) ($item->parent_id ?? ''),
            'new_tab' => $item->new_tab,
            'status' => $item->status,
        ];
        $this->dispatch('modal', id: 'formModal', action: 'show');
    }

    /** Fill the title automatically from the linked page / record. */
    public function updatedForm($value, string $key): void
    {
        if (! in_array($key, ['route', 'linkable_id', 'type'], true)) {
            return;
        }

        $title = match ($this->form['type']) {
            'route' => collect(['ar', 'en'])->mapWithKeys(fn ($locale) => [
                $locale => __(MenuItem::ROUTES[$this->form['route']] ?? '', [], $locale),
            ])->all(),
            'page', 'service', 'project' => filled($this->form['linkable_id'])
                ? optional($this->linkables()[$this->form['type']]->firstWhere('id', (int) $this->form['linkable_id']))->title
                : null,
            default => null,
        };

        if ($title && (blank($this->form['title']['ar']) || blank($this->form['title']['en']) || $key !== 'type')) {
            $this->form['title'] = array_merge(['ar' => '', 'en' => ''], $title);
        }
    }

    public function save(): void
    {
        $data = $this->validateWithToast([
            'form.title.ar' => ['required', 'string', 'max:80'],
            'form.title.en' => ['required', 'string', 'max:80'],
            'form.type' => ['required', Rule::in(MenuItem::TYPES)],
            'form.route' => ['required_if:form.type,route', 'nullable', Rule::in(array_keys(MenuItem::ROUTES))],
            'form.linkable_id' => ['required_if:form.type,page,service,project', 'nullable', 'integer'],
            'form.url' => ['required_if:form.type,url', 'nullable', 'string', 'max:255'],
            'form.parent_id' => ['nullable', Rule::exists('menu_items', 'id')->where('location', $this->location)->whereNull('parent_id')],
            'form.new_tab' => ['boolean'],
            'form.status' => ['boolean'],
        ])['form'];

        if ($this->editingId && (int) $data['parent_id'] === $this->editingId) {
            $data['parent_id'] = null;
        }

        $item = $this->editingId ? MenuItem::findOrFail($this->editingId) : new MenuItem(['location' => $this->location]);

        if ($item->exists && $item->children()->exists() && filled($data['parent_id'])) {
            $this->addError('form.parent_id', __('An item with sub items cannot be moved inside another item.'));

            return;
        }

        $item->fill([
            'title' => $data['title'],
            'type' => $data['type'],
            'route' => $data['type'] === 'route' ? $data['route'] : null,
            'linkable_id' => in_array($data['type'], ['page', 'service', 'project'], true) ? $data['linkable_id'] : null,
            'url' => $data['type'] === 'url' ? $data['url'] : null,
            'parent_id' => $data['parent_id'] ?: null,
            'new_tab' => $data['new_tab'],
            'status' => $data['status'],
        ]);

        if (! $item->exists) {
            $item->sort_order = (int) MenuItem::where('location', $this->location)->where('parent_id', $item->parent_id)->max('sort_order') + 1;
        }

        $item->save();

        $this->dispatch('modal', id: 'formModal', action: 'hide');
        $this->toast($this->editingId ? __('Saved successfully') : __('Created successfully'));
        $this->editingId = null;
    }

    public function openBulk(?int $parentId = null): void
    {
        $this->resetValidation();
        $this->bulk = ['parent_id' => (string) ($parentId ?? ''), 'type' => 'service', 'ids' => []];
        $this->dispatch('modal', id: 'bulkModal', action: 'show');
    }

    public function saveBulk(): void
    {
        $this->validateWithToast([
            'bulk.parent_id' => ['required', Rule::exists('menu_items', 'id')->where('location', $this->location)->whereNull('parent_id')],
            'bulk.type' => ['required', Rule::in(['page', 'service', 'project'])],
            'bulk.ids' => ['required', 'array', 'min:1'],
        ]);

        $records = $this->linkables()[$this->bulk['type']]->whereIn('id', array_map('intval', $this->bulk['ids']));
        $order = (int) MenuItem::where('parent_id', $this->bulk['parent_id'])->max('sort_order');

        foreach ($records as $record) {
            MenuItem::firstOrCreate(
                ['parent_id' => $this->bulk['parent_id'], 'type' => $this->bulk['type'], 'linkable_id' => $record->id],
                ['location' => $this->location, 'title' => $record->title, 'status' => true, 'sort_order' => ++$order],
            );
        }

        $this->dispatch('modal', id: 'bulkModal', action: 'hide');
        $this->toast(__(':count links added', ['count' => $records->count()]));
    }

    public function toggle(int $id): void
    {
        $item = MenuItem::findOrFail($id);
        $item->update(['status' => ! $item->status]);
        $this->toast(__('Updated successfully'));
    }

    public function delete(int $id): void
    {
        MenuItem::findOrFail($id)->delete();
        $this->toast(__('Deleted successfully'));
    }

    /** wire:sort handler; $group is "root" or the parent id of the target list. */
    public function reorder(int $id, int $position, string $group = 'root'): void
    {
        $item = MenuItem::findOrFail($id);
        $parentId = $group === 'root' ? null : (int) $group;

        if ($parentId && ($parentId === $item->id || $item->children()->exists())) {
            $this->toast(__('An item with sub items cannot be moved inside another item.'), 'warning');

            return;
        }

        DB::transaction(function () use ($item, $parentId, $position) {
            $siblings = MenuItem::where('location', $this->location)
                ->where('parent_id', $parentId)
                ->whereKeyNot($item->id)
                ->orderBy('sort_order')->orderBy('id')
                ->pluck('id')->all();

            array_splice($siblings, $position, 0, [$item->id]);

            foreach ($siblings as $index => $siblingId) {
                MenuItem::whereKey($siblingId)->update(['sort_order' => $index, 'parent_id' => $parentId]);
            }
        });

        \App\Support\Menu::flush();
        $this->toast(__('Order updated'));
    }

    public function render()
    {
        $items = MenuItem::where('location', $this->location)->orderBy('sort_order')->orderBy('id')->get();
        $linkables = $this->linkables();

        return view('livewire.admin.menus', [
            'roots' => $items->whereNull('parent_id'),
            'items' => $items,
            'linkables' => $linkables,
            'linkTitles' => collect($linkables)->map(fn ($rows) => $rows->mapWithKeys(fn ($row) => [$row->id => $row->tr('title')])),
        ])->title(__('Menus'));
    }
}
