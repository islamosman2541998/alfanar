<?php

namespace App\Livewire\Admin;

use App\Livewire\Admin\Concerns\WithToast;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class Profile extends Component
{
    use WithToast;

    public string $name = '';

    public string $email = '';

    public string $current_password = '';

    public string $password = '';

    public string $password_confirmation = '';

    public function mount(): void
    {
        $this->name = auth()->user()->name;
        $this->email = auth()->user()->email;
    }

    public function saveProfile(): void
    {
        $data = $this->validateWithToast([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:190', Rule::unique('users', 'email')->ignore(auth()->id())],
        ]);

        auth()->user()->update($data);
        $this->toast(__('Saved successfully'));
    }

    public function savePassword(): void
    {
        $this->validateWithToast([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        auth()->user()->update(['password' => $this->password]);
        $this->reset('current_password', 'password', 'password_confirmation');
        $this->toast(__('Password changed successfully'));
    }

    public function render()
    {
        return view('livewire.admin.profile')->title(__('Profile'));
    }
}
