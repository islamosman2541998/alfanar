<?php

namespace App\Livewire\Admin;

use App\Models\Faq;
use Illuminate\Database\Eloquent\Model;
use Livewire\Attributes\Layout;

#[Layout('layouts.admin')]
class Faqs extends ModalCrud
{
    protected array $searchable = ['question', 'answer'];

    protected function model(): string
    {
        return Faq::class;
    }

    protected function defaults(): array
    {
        return ['question' => ['ar' => '', 'en' => ''], 'answer' => ['ar' => '', 'en' => ''], 'status' => true, 'sort_order' => 0];
    }

    protected function formRules(): array
    {
        return [
            'form.question.ar' => ['required', 'string', 'max:255'],
            'form.question.en' => ['required', 'string', 'max:255'],
            'form.answer.ar' => ['required', 'string', 'max:3000'],
            'form.answer.en' => ['required', 'string', 'max:3000'],
            'form.status' => ['boolean'],
            'form.sort_order' => ['integer', 'min:0'],
        ];
    }

    protected function exportName(): string
    {
        return 'faq';
    }

    protected function exportRow(Model $row): array
    {
        return [
            '#' => $row->id,
            __('Question').' (AR)' => $row->question['ar'] ?? '',
            __('Question').' (EN)' => $row->question['en'] ?? '',
            __('Answer').' (AR)' => $row->answer['ar'] ?? '',
            __('Answer').' (EN)' => $row->answer['en'] ?? '',
            __('Status') => $this->statusLabel($row->status),
        ];
    }

    public function render()
    {
        return view('livewire.admin.faqs', [
            'rows' => $this->query()->paginate($this->perPage),
        ])->title(__('FAQ'));
    }
}
