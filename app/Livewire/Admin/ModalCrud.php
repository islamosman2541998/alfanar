<?php

namespace App\Livewire\Admin;

use Illuminate\Database\Eloquent\Model;

/**
 * Listing + create/edit in a Bootstrap modal for small records
 * (testimonials, statistics, features, FAQ, admins).
 */
abstract class ModalCrud extends DataTable
{
    public array $form = [];

    public ?int $editingId = null;

    /** Empty form values. */
    abstract protected function defaults(): array;

    abstract protected function formRules(): array;

    protected function fillForm(Model $record): array
    {
        return collect($this->defaults())
            ->map(function ($default, $key) use ($record) {
                $value = $record->{$key};

                return is_array($default) ? array_merge($default, $value ?? []) : ($value ?? $default);
            })
            ->all();
    }

    /** Hook to adjust data before saving (uploads, hashing...). */
    protected function beforeSave(array $data, Model $record): array
    {
        return $data;
    }

    public function create(): void
    {
        $this->resetValidation();
        $this->editingId = null;
        $this->form = $this->defaults();

        if (array_key_exists('sort_order', $this->form)) {
            $this->form['sort_order'] = (int) ($this->model())::max('sort_order') + 1;
        }

        $this->dispatch('modal', id: 'formModal', action: 'show');
    }

    public function edit(int $id): void
    {
        $this->resetValidation();
        $this->editingId = $id;
        $this->form = $this->fillForm(($this->model())::findOrFail($id));
        $this->dispatch('modal', id: 'formModal', action: 'show');
    }

    public function save(): void
    {
        $this->validateWithToast($this->formRules());

        $model = $this->model();
        $record = $this->editingId ? $model::findOrFail($this->editingId) : new $model;

        $record->fill($this->beforeSave($this->form, $record))->save();

        $this->dispatch('modal', id: 'formModal', action: 'hide');
        $this->toast($this->editingId ? __('Saved successfully') : __('Created successfully'));
        $this->editingId = null;
    }
}
