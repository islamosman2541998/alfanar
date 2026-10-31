<?php

namespace App\Livewire\Admin;

use App\Models\Message;
use App\Models\Service;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;

#[Layout('layouts.admin')]
class Messages extends DataTable
{
    public string $sortField = 'created_at';

    public string $sortDirection = 'desc';

    #[Url(except: '')]
    public string $read = '';

    #[Url(except: '')]
    public string $service = '';

    #[Url(except: '')]
    public string $from = '';

    #[Url(except: '')]
    public string $to = '';

    public ?Message $viewing = null;

    protected array $searchable = [];

    protected array $sortable = ['id', 'name', 'created_at', 'is_read'];

    protected array $toggleable = ['is_read'];

    public function mount(): void
    {
        if ($id = request()->integer('open')) {
            $this->show($id);
        }
    }

    protected function model(): string
    {
        return Message::class;
    }

    protected function query(): Builder
    {
        return Message::query()->with('service')
            ->when(filled($this->search), fn ($q) => $q->where(fn ($q) => $q
                ->where('name', 'like', "%{$this->search}%")
                ->orWhere('phone', 'like', "%{$this->search}%")
                ->orWhere('email', 'like', "%{$this->search}%")
                ->orWhere('message', 'like', "%{$this->search}%")))
            ->when($this->read !== '', fn ($q) => $q->where('is_read', (bool) $this->read))
            ->when($this->service !== '', fn ($q) => $q->where('service_id', $this->service))
            ->when($this->from, fn ($q) => $q->whereDate('created_at', '>=', $this->from))
            ->when($this->to, fn ($q) => $q->whereDate('created_at', '<=', $this->to))
            ->orderBy($this->sortField, $this->sortDirection);
    }

    protected function resetFilterExtras(): void
    {
        $this->reset('read', 'service', 'from', 'to');
    }

    public function show(int $id): void
    {
        $this->viewing = Message::with('service')->findOrFail($id);

        if (! $this->viewing->is_read) {
            $this->viewing->update(['is_read' => true]);
        }

        $this->dispatch('modal', id: 'messageModal', action: 'show');
    }

    public function markAllRead(): void
    {
        Message::where('is_read', false)->update(['is_read' => true]);
        $this->toast(__('All messages marked as read'));
    }

    protected function exportName(): string
    {
        return 'messages';
    }

    protected function exportRow(Model $row): array
    {
        return [
            '#' => $row->id,
            __('Name') => $row->name,
            __('Phone') => $row->phone,
            __('Email') => $row->email,
            __('Service') => $row->service?->tr('title'),
            __('Message') => $row->message,
            __('Language') => $row->locale,
            __('Read') => $this->yesNo($row->is_read),
            __('Date') => $row->created_at?->format('Y-m-d H:i'),
        ];
    }

    public function render()
    {
        return view('livewire.admin.messages', [
            'rows' => $this->query()->paginate($this->perPage),
            'services' => Service::ordered()->get(['id', 'title']),
        ])->title(__('Messages'));
    }
}
