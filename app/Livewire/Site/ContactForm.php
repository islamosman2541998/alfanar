<?php

namespace App\Livewire\Site;

use App\Models\Message;
use App\Models\Service;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class ContactForm extends Component
{
    public string $name = '';

    public string $phone = '';

    public string $email = '';

    public string $service_id = '';

    public string $message = '';

    /** Honeypot field: real visitors never fill it. */
    public string $website = '';

    public function mount(?int $service = null): void
    {
        $this->service_id = (string) ($service ?? '');
    }

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:120'],
            'phone' => ['required', 'string', 'regex:/^[0-9+\s()-]{8,20}$/'],
            'email' => ['nullable', 'email', 'max:190'],
            'service_id' => ['nullable', 'exists:services,id'],
            'message' => ['required', 'string', 'min:5', 'max:3000'],
        ];
    }

    protected function validationAttributes(): array
    {
        return [
            'name' => txt('contact.form.name'),
            'phone' => txt('contact.form.phone'),
            'email' => txt('contact.form.email'),
            'service_id' => txt('contact.form.service'),
            'message' => txt('contact.form.message'),
        ];
    }

    public function submit(): void
    {
        if (filled($this->website)) {
            return;
        }

        $key = 'contact:'.request()->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $this->dispatch('toast', type: 'warning', message: txt('contact.form.too_many'));

            return;
        }

        try {
            $data = $this->validate();
        } catch (ValidationException $e) {
            $this->dispatch('toast', type: 'error', message: txt('contact.form.invalid'));

            throw $e;
        }

        RateLimiter::hit($key, 600);

        $message = Message::create($data + [
            'service_id' => $data['service_id'] ?: null,
            'locale' => app()->getLocale(),
            'ip' => request()->ip(),
        ]);

        $this->notifyByEmail($message);

        $this->reset('name', 'phone', 'email', 'message');
        $this->dispatch('toast', type: 'success', message: txt('contact.form.success'));
        $this->dispatch('lead');
    }

    protected function notifyByEmail(Message $message): void
    {
        if (! ($to = setting('notify_email'))) {
            return;
        }

        try {
            Mail::raw(
                "{$message->name}\n{$message->phone}\n{$message->email}\n\n{$message->message}",
                fn ($mail) => $mail->to($to)->subject(setting('site_name').' - '.__('New message', [], 'ar'))
            );
        } catch (\Throwable $e) {
            Log::warning('Contact notification failed: '.$e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.site.contact-form', [
            'services' => Service::active()->ordered()->get(['id', 'title']),
        ]);
    }
}
