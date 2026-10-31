<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;

#[Layout('layouts.admin')]
class Users extends ModalCrud
{
    public string $sortField = 'created_at';

    public string $sortDirection = 'desc';

    protected array $searchable = [];

    protected array $searchablePlain = ['name', 'email'];

    protected array $sortable = ['id', 'name', 'email', 'created_at', 'is_active'];

    protected array $toggleable = ['is_active'];

    protected function model(): string
    {
        return User::class;
    }

    protected function query(): Builder
    {
        return User::query()
            ->when(filled($this->search), fn ($q) => $q->where(fn ($q) => $q
                ->where('name', 'like', "%{$this->search}%")
                ->orWhere('email', 'like', "%{$this->search}%")))
            ->when($this->status !== '', fn ($q) => $q->where('is_active', (bool) $this->status))
            ->orderBy($this->sortField, $this->sortDirection);
    }

    protected function defaults(): array
    {
        return ['name' => '', 'email' => '', 'password' => '', 'is_active' => true];
    }

    protected function fillForm(Model $record): array
    {
        return ['name' => $record->name, 'email' => $record->email, 'password' => '', 'is_active' => $record->is_active];
    }

    protected function formRules(): array
    {
        return [
            'form.name' => ['required', 'string', 'max:120'],
            'form.email' => ['required', 'email', 'max:190', Rule::unique('users', 'email')->ignore($this->editingId)],
            'form.password' => [$this->editingId ? 'nullable' : 'required', 'string', 'min:8'],
            'form.is_active' => ['boolean'],
        ];
    }

    protected function beforeSave(array $data, Model $record): array
    {
        if (blank($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = Hash::make($data['password']);
        }

        if ($record->is(auth()->user())) {
            $data['is_active'] = true;
        }

        return $data;
    }

    public function toggle(int $id, string $field): void
    {
        if ($id === auth()->id()) {
            $this->toast(__('You cannot deactivate your own account.'), 'warning');

            return;
        }

        parent::toggle($id, $field);
    }

    public function delete(int $id): void
    {
        if ($id === auth()->id() || User::count() <= 1) {
            $this->toast(__('You cannot delete your own account.'), 'warning');

            return;
        }

        parent::delete($id);
    }

    public function deleteSelected(): void
    {
        $this->selected = array_values(array_diff($this->selected, [auth()->id(), (string) auth()->id()]));
        parent::deleteSelected();
    }

    protected function exportName(): string
    {
        return 'admins';
    }

    protected function exportRow(Model $row): array
    {
        return [
            '#' => $row->id,
            __('Name') => $row->name,
            __('Email') => $row->email,
            __('Status') => $this->statusLabel($row->is_active),
            __('Created at') => $row->created_at?->format('Y-m-d'),
        ];
    }

    public function render()
    {
        return view('livewire.admin.users', [
            'rows' => $this->query()->paginate($this->perPage),
        ])->title(__('Admins'));
    }
}
