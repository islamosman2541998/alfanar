<?php

namespace App\Livewire\Admin\Concerns;

use Illuminate\Validation\ValidationException;

/**
 * One way to notify the user from any component:
 * - toast()      for the current page (browser event handled by toastr)
 * - flashToast() before a redirect (rendered once by the layout)
 */
trait WithToast
{
    protected function toast(string $message, string $type = 'success'): void
    {
        $this->dispatch('toast', type: $type, message: $message);
    }

    protected function flashToast(string $message, string $type = 'success'): void
    {
        session()->flash('toast', ['type' => $type, 'message' => $message]);
    }

    /** validate() that also tells the user something needs fixing. */
    protected function validateWithToast(array $rules, array $attributes = []): array
    {
        try {
            return $this->validate($rules, [], $attributes);
        } catch (ValidationException $e) {
            $this->toast(__('Please review the highlighted fields.'), 'error');

            throw $e;
        }
    }
}
