<?php

namespace App\Livewire\Admin\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.auth')]
class Login extends Component
{
    public string $email = '';

    public string $password = '';

    public bool $remember = true;

    public function login()
    {
        $this->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $key = Str::lower($this->email).'|'.request()->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            throw ValidationException::withMessages([
                'email' => __('Too many attempts. Try again in :seconds seconds.', ['seconds' => RateLimiter::availableIn($key)]),
            ]);
        }

        if (! Auth::attempt(['email' => $this->email, 'password' => $this->password, 'is_active' => true], $this->remember)) {
            RateLimiter::hit($key);

            throw ValidationException::withMessages(['email' => __('These credentials do not match our records.')]);
        }

        RateLimiter::clear($key);
        session()->regenerate();
        session()->flash('toast', ['type' => 'success', 'message' => __('Welcome back, :name', ['name' => Auth::user()->name])]);

        return $this->redirectIntended(route('admin.dashboard'));
    }

    public function render()
    {
        return view('livewire.admin.auth.login')->title(__('Login'));
    }
}
