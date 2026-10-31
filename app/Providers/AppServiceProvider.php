<?php

namespace App\Providers;

use App\Http\Middleware\SetAdminLocale;
use App\Http\Middleware\SetLocale;
use App\Support\Seo;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->scoped(Seo::class);
    }

    public function boot(): void
    {
        Paginator::useBootstrapFive();

        // Livewire requests keep the language of the page they came from.
        Livewire::addPersistentMiddleware([SetLocale::class, SetAdminLocale::class]);
    }
}
