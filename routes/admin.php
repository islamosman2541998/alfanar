<?php

use App\Livewire\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::livewire('login', Admin\Auth\Login::class)->name('login');
});

Route::get('locale/{locale}', function (Request $request, string $locale) {
    abort_unless(array_key_exists($locale, locales()), 404);
    $request->session()->put('admin_locale', $locale);

    return back();
})->name('locale');

Route::middleware('auth')->group(function () {
    Route::post('logout', function (Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    })->name('logout');

    Route::livewire('/', Admin\Dashboard::class)->name('dashboard');

    Route::livewire('sliders', Admin\Sliders\Index::class)->name('sliders.index');
    Route::livewire('sliders/create', Admin\Sliders\Form::class)->name('sliders.create');
    Route::livewire('sliders/{slider}/edit', Admin\Sliders\Form::class)->name('sliders.edit');

    Route::livewire('services', Admin\Services\Index::class)->name('services.index');
    Route::livewire('services/create', Admin\Services\Form::class)->name('services.create');
    Route::livewire('services/{service}/edit', Admin\Services\Form::class)->name('services.edit');

    Route::livewire('projects', Admin\Projects\Index::class)->name('projects.index');
    Route::livewire('projects/create', Admin\Projects\Form::class)->name('projects.create');
    Route::livewire('projects/{project}/edit', Admin\Projects\Form::class)->name('projects.edit');

    Route::livewire('pages', Admin\Pages\Index::class)->name('pages.index');
    Route::livewire('pages/create', Admin\Pages\Form::class)->name('pages.create');
    Route::livewire('pages/{page}/edit', Admin\Pages\Form::class)->name('pages.edit');

    Route::livewire('testimonials', Admin\Testimonials::class)->name('testimonials');
    Route::livewire('stats', Admin\Stats::class)->name('stats');
    Route::livewire('features', Admin\Features::class)->name('features');
    Route::livewire('faqs', Admin\Faqs::class)->name('faqs');
    Route::livewire('messages', Admin\Messages::class)->name('messages');
    Route::livewire('menus', Admin\Menus::class)->name('menus');
    Route::livewire('texts', Admin\SiteTexts::class)->name('texts');
    Route::livewire('settings/{tab?}', Admin\Settings::class)->name('settings');
    Route::livewire('users', Admin\Users::class)->name('users');
    Route::livewire('profile', Admin\Profile::class)->name('profile');
});
